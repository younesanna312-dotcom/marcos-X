import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
import { getAuth, onAuthStateChanged, signInWithEmailAndPassword, createUserWithEmailAndPassword, signOut } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-auth.js";
import { getFirestore, collection, getDocs, query, orderBy, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-firestore.js";
import { firebaseConfig } from "./firebase-config.js";

const app=initializeApp(firebaseConfig), auth=getAuth(app), db=getFirestore(app);
const $=s=>document.querySelector(s); const money=n=>`${Number(n||0).toLocaleString("fr-MA")} DH`;
let products=[],cart=JSON.parse(localStorage.getItem("babour_cart")||"[]"),category="الكل",currentUser=null;

async function loadProducts(){
  try{
    const snap=await getDocs(query(collection(db,"products"),orderBy("createdAt","desc")));
    products=snap.docs.map(d=>({id:d.id,...d.data()}));
    renderCats(); renderProducts();
  }catch(e){
    console.error(e); $("#productsGrid").innerHTML='<div class="loading">تعذر تحميل المنتجات. تأكد من إعداد Firebase وقاعدة Firestore.</div>';
  }
}
function renderCats(){
  const cats=["الكل",...new Set(products.map(p=>p.category).filter(Boolean))];
  $("#categories").innerHTML=cats.map(c=>`<button class="${c===category?"active":""}" data-cat="${c}">${c}</button>`).join("");
  document.querySelectorAll("[data-cat]").forEach(b=>b.onclick=()=>{category=b.dataset.cat;renderCats();renderProducts()});
}
function renderProducts(){
 const list=category==="الكل"?products:products.filter(p=>p.category===category);
 $("#productsGrid").innerHTML=list.length?list.map(p=>`<article class="card"><div class="pic" style="background-image:url('${p.image||"assets/placeholder.svg"}')">${p.badge?`<span class="tag">${p.badge}</span>`:""}</div><div class="info"><h3>${esc(p.name)}</h3><p>${esc(p.category||"عام")}</p><div class="price"><strong>${money(p.price)}</strong><button class="add" data-add="${p.id}">أضف للسلة</button></div></div></article>`).join(""):'<div class="loading">لا توجد منتجات حاليًا.</div>';
 document.querySelectorAll("[data-add]").forEach(b=>b.onclick=()=>add(b.dataset.add));
}
function esc(v){return String(v??"").replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"}[m]))}
function add(id){const x=cart.find(a=>a.id===id);if(x)x.qty++;else cart.push({id,qty:1});save();toast("تمت إضافة المنتج للسلة");openCart()}
function save(){localStorage.setItem("babour_cart",JSON.stringify(cart));renderCart()}
function renderCart(){
 let total=0,count=0;
 $("#cartItems").innerHTML=cart.length?cart.map(x=>{const p=products.find(a=>a.id===x.id);if(!p)return "";total+=Number(p.price||0)*x.qty;count+=x.qty;return `<div class="cart-item"><img src="${p.image||"assets/placeholder.svg"}"><div><h4>${esc(p.name)}</h4><small>${money(p.price)} × ${x.qty}</small><div class="qty"><button data-q="${p.id}" data-d="-1">−</button>${x.qty}<button data-q="${p.id}" data-d="1">+</button></div></div></div>`}).join(""):'<div class="loading">السلة فارغة 🛒</div>';
 $("#total").textContent=money(total);$("#cartCount").textContent=count;
 document.querySelectorAll("[data-q]").forEach(b=>b.onclick=()=>{let x=cart.find(a=>a.id===b.dataset.q);x.qty+=Number(b.dataset.d);if(x.qty<1)cart=cart.filter(a=>a.id!==b.dataset.q);save()});
}
function openCart(){$("#cartPanel").classList.add("open");$("#shade").classList.add("open")}
function closeCart(){$("#cartPanel").classList.remove("open");$("#shade").classList.remove("open")}
$("#cartBtn").onclick=openCart;$("#closeCart").onclick=closeCart;$("#shade").onclick=closeCart;

function loginUI(){
 $("#modalContent").innerHTML=`<div class="form"><h2>تسجيل الدخول</h2><p style="color:#8d96a0">ادخل إلى حسابك لإرسال الطلبات.</p><input id="email" type="email" placeholder="البريد الإلكتروني"><input id="pass" type="password" placeholder="كلمة المرور"><button class="btn main" id="doLogin">دخول</button><button class="switch" id="register">إنشاء حساب جديد</button><p id="authError" style="color:#ff6875"></p></div>`;
 $("#doLogin").onclick=async()=>{try{await signInWithEmailAndPassword(auth,$("#email").value,$("#pass").value);closeModal();toast("تم تسجيل الدخول")}catch(e){$("#authError").textContent=authError(e)}};
 $("#register").onclick=registerUI;
 openModal();
}
function registerUI(){
 $("#modalContent").innerHTML=`<div class="form"><h2>إنشاء حساب</h2><input id="email" type="email" placeholder="البريد الإلكتروني"><input id="pass" type="password" minlength="6" placeholder="كلمة المرور (6 أحرف على الأقل)"><button class="btn main" id="doRegister">إنشاء الحساب</button><button class="switch" id="backLogin">لدي حساب بالفعل</button><p id="authError" style="color:#ff6875"></p></div>`;
 $("#doRegister").onclick=async()=>{try{await createUserWithEmailAndPassword(auth,$("#email").value,$("#pass").value);closeModal();toast("تم إنشاء الحساب")}catch(e){$("#authError").textContent=authError(e)}};
 $("#backLogin").onclick=loginUI;
}
function authError(e){return e.code==="auth/invalid-credential"?"البريد أو كلمة المرور غير صحيحة":e.code==="auth/email-already-in-use"?"البريد مستخدم مسبقًا":"حدث خطأ، تحقق من البيانات."}
function openModal(){$("#modal").classList.remove("hidden")}function closeModal(){$("#modal").classList.add("hidden")}
$("#closeModal").onclick=closeModal;$("#loginBtn").onclick=()=>currentUser?logout():loginUI();
async function logout(){await signOut(auth);toast("تم تسجيل الخروج")}
onAuthStateChanged(auth,u=>{currentUser=u;$("#loginBtn").textContent=u?"تسجيل الخروج":"تسجيل الدخول"});

$("#checkout").onclick=async()=>{
 if(!cart.length)return toast("السلة فارغة");
 if(!currentUser)return loginUI();
 const items=cart.map(x=>{const p=products.find(a=>a.id===x.id);return {productId:p.id,name:p.name,price:Number(p.price),qty:x.qty,image:p.image||""}});
 const total=items.reduce((s,x)=>s+x.price*x.qty,0);
 try{await addDoc(collection(db,"orders"),{userId:currentUser.uid,email:currentUser.email,items,total,status:"pending",createdAt:serverTimestamp()});cart=[];save();closeCart();toast("تم إرسال الطلب بنجاح")}catch(e){console.error(e);toast("تعذر إرسال الطلب")};
};
function toast(s){$("#toast").textContent=s;$("#toast").classList.add("show");setTimeout(()=>$("#toast").classList.remove("show"),2200)}
$("#year").textContent=new Date().getFullYear();
renderCart();loadProducts();
