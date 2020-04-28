importScripts("https://www.gstatic.com/firebasejs/7.12.0/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/7.12.0/firebase-messaging.js");
// Your web app's Firebase configuration
var firebaseConfig = {
    apiKey: "AIzaSyCmIr87Ihp8nXtHrKWZyeH1GcvFrHxmtJw",
    authDomain: "alnahr-3a32e.firebaseapp.com",
    databaseURL: "https://alnahr-3a32e.firebaseio.com",
    projectId: "alnahr-3a32e",
    storageBucket: "alnahr-3a32e.appspot.com",
    messagingSenderId: "410160983978",
    appId: "1:410160983978:web:22a64081a20724ec9185d3",
    measurementId: "G-QYSFSMTB8R"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload){
    title = "Hello world";
    const options = {
        body : payload.body.status
    }
    return self.registration.showNotification(title,options)
});
