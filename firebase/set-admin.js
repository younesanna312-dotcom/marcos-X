// Bootstrap an admin account.
// Run this ONCE on your own computer/server with Firebase Admin SDK.
// NEVER put a service-account JSON file or this code in GitHub Pages.
//
// 1) Create a Firebase service account and download its private key.
// 2) Install: npm i firebase-admin
// 3) Save this as set-admin.js outside the public website.
// 4) Replace USER_UID with the Firebase Authentication UID.
// 5) Run: node set-admin.js
//
// const admin = require("firebase-admin");
// const serviceAccount = require("./serviceAccountKey.json");
// admin.initializeApp({credential: admin.credential.cert(serviceAccount)});
// admin.auth().setCustomUserClaims("USER_UID", {admin:true})
//   .then(()=>console.log("Admin enabled"));
