import {initializeApp} from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
import {getAuth,onAuthStateChanged,signOut} from "https://www.gstatic.com/firebasejs/12.1.0/firebase-auth.js";
import {getFirestore,collection,getDocs,doc,deleteDoc,updateDoc,serverTimestamp,query,orderBy} from "https://www.gstatic.com/firebasejs/12.1.0/firebase-firestore.js";
import {getStorage,ref,uploadBytes,getDownloadURL} from "https://www.gstatic.com/firebasejs/12.1.0/firebase-storage.js";
import {firebaseConfig} from "../assets/firebase-config.js";
const app=initializeApp(firebaseConfig),auth=getAuth(app),db=getFirestore(app),storage=getStorage(app),$=s=>document.querySelector(s);
const esc=v=>String(v??"").replace(/[&<>"']/g,m=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#039;"}[m]));
const money=n=>`${Number(n||0).toLocaleString("fr-MA")} DH`;
let products=[];
onAuthStateChanged(auth,async user=>{
 if(!user){location.href="../";return}
 const token=await user.getIdTokenResult(true);
 if(token.claims.admin!==true){$("#guard").innerHTML="⛔ هذا الحساب ليس لديه صلاحية Admin. أضف له صلاحية admin حسب README ثم أعد تسجيل الدخول.";return}
 $("#guard").style.display="none";$("#admin").style.display="block";loadProducts();loadOrders();
});
$("#logout").onclick=()=>signOut(auth);
async function loadProducts(){const s=await getDocs(query(collection(db,"products"),orderBy("createdAt","desc")));products=s.docs.map(d=>({id:d.id,...d.data()}));$("#products").innerHTML=products.map(p=>`<div class="admin-row"><span>${esc(p.name)} — ${money(p.price)}</span><button class="btn danger" data-del="${p.id}">حذف</button></div>`).join("")||"لا توجد منتجات";document.querySelectorAll("[data-del]").forEach(b=>b.onclick=()=>removeProduct(b.dataset.del))}
async function removeProduct(id){if(!confirm("حذف المنتج؟"))return;await deleteDoc(doc(db,"products",id));loadProducts()}
async function loadOrders(){const s=await getDocs(query(collection(db,"orders"),orderBy("createdAt","desc")));$("#orders").innerHTML=s.docs.map(d=>{let o=d.data();return `<div class="admin-row"><div><b>${esc(o.email||"")}</b><br><small>${esc(o.status||"pending")} — ${money(o.total)}</small></div><button class="btn" data-status="${d.id}">تم التجهيز</button></div>`}).join("")||"لا توجد طلبات";document.querySelectorAll("[data-status]").forEach(b=>b.onclick=()=>setStatus(b.dataset.status))}
async function setStatus(id){await updateDoc(doc(db,"orders",id),{status:"completed",updatedAt:serverTimestamp()});loadOrders()}
$("#addProduct").onclick=async()=>{
 const name=$("#name").value.trim(),category=$("#category").value.trim(),price=Number($("#price").value),badge=$("#badge").value.trim();if(!name||!category||!price)return $("#msg").textContent="أكمل الاسم والتصنيف والسعر";
 let image=$("#image").value.trim();
 try{const f=$("#file").files[0];if(f){const r=ref(storage,`products/${crypto.randomUUID()}-${f.name}`);await uploadBytes(r,f);image=await getDownloadURL(r)}
 await import("https://www.gstatic.com/firebasejs/12.1.0/firebase-firestore.js").then(async({addDoc,collection,serverTimestamp})=>addDoc(collection(db,"products"),{name,category,price,badge,image,createdAt:serverTimestamp()}));
 $("#msg").textContent="تمت إضافة المنتج";["name","category","price","badge","image"].forEach(x=>$("#"+x).value="");$("#file").value="";loadProducts()
 }catch(e){console.error(e);$("#msg").textContent="حدث خطأ: "+e.message}
};
