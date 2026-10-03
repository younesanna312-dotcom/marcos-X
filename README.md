# BABOUR SHOP — Firebase Edition

نسخة قوية لـ GitHub Pages + Firebase:
- Firebase Authentication: تسجيل/دخول بالبريد وكلمة المرور.
- Firestore: المنتجات والطلبات.
- Firebase Storage: صور المنتجات.
- Admin Dashboard: إضافة/حذف المنتجات، رفع الصور، مشاهدة الطلبات وتغيير حالتها.
- قواعد Firestore وStorage مقفلة بحيث لا يستطيع المستخدم العادي تعديل المنتجات أو الطلبات.

## 1) إنشاء Firebase

في Firebase Console:
1. أنشئ Project.
2. أضف Web App.
3. فعّل Authentication > Sign-in method > Email/Password.
4. أنشئ Firestore Database.
5. أنشئ Storage.
6. انسخ إعدادات Web App.

انسخ:
`assets/firebase-config.example.js`
إلى:
`assets/firebase-config.js`

ثم ضع قيم مشروعك.

> لا تضع Service Account JSON أو Private Key داخل الموقع.

## 2) قواعد Firebase

انسخ محتوى:
`firebase/firestore.rules`
إلى Firestore Rules.

وانسخ:
`firebase/storage.rules`
إلى Storage Rules.

## 3) إنشاء أول Admin

المشروع يستخدم Custom Claim اسمها `admin=true`، وهذا أكثر أمانًا من جعل صفحة Admin مفتوحة.

التعليمات والكود موجودان في:
`firebase/set-admin.js`

يجب تشغيل كود Admin SDK على جهازك/خادمك الخاص، وليس داخل GitHub Pages.

بعد إعطاء الحساب claim، سجّل خروجًا ثم ادخل من جديد.

## 4) GitHub Pages

1. أنشئ Repository جديدًا.
2. ارفع كل الملفات.
3. يجب أن يكون `index.html` في root.
4. Settings > Pages > Deploy from branch.
5. Branch: main / root.

الرابط:
https://USERNAME.github.io/REPOSITORY/

## 5) صور المنتجات

من لوحة Admin:
- اكتب بيانات المنتج.
- اختر صورة من الكمبيوتر.
- يتم رفعها إلى Firebase Storage.
- يحفظ رابط الصورة في Firestore.

## 6) الطلبات

عندما يكون المستخدم مسجلًا:
- يضيف المنتجات إلى السلة.
- يرسل الطلب.
- الطلب يحفظ في Firestore.
- Admin يرى الطلبات ويستطيع تغييرها إلى completed.

## مهم جدًا

Firebase Web config يمكن وضعه في موقع Frontend، لكن الأمان الحقيقي يأتي من Firestore/Storage Security Rules.

لا ترفع أبدًا:
- serviceAccountKey.json
- private keys
- Admin SDK credentials

## الملفات

index.html
assets/app.js
assets/style.css
assets/firebase-config.example.js
admin/index.html
admin/admin.js
firebase/firestore.rules
firebase/storage.rules
firebase/set-admin.js
