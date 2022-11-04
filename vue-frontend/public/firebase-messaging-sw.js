importScripts("https://www.gstatic.com/firebasejs/8.3.3/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.3/firebase-messaging.js");
// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyDwy7FGA-Nci9ACLgL-xr38_p_QKCMB9Zo",
    authDomain: "sie-web-demo.firebaseapp.com",
    projectId: "sie-web-demo",
    storageBucket: "sie-web-demo.appspot.com",
    messagingSenderId: "929879698385",
    appId: "1:929879698385:web:9dbf2ff21c8803b55b4ef0",
    measurementId: "G-CDNC1C1907"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

var messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log(payload)
    //debugger;
    const promiseChain = clients
        .matchAll({
            type: "window",
            includeUncontrolled: true,
        })
        .then((windowClients) => {
            for (let i = 0; i < windowClients.length; i++) {
                const windowClient = windowClients[i];
                windowClient.postMessage(payload);
            }
        })
        .then((response) => {
            console.log(response);
            return registration.showNotification("New Message");
        });
    return promiseChain;
});

console.log(self);

self.addEventListener('push', function (event) {
    var data = event.data.json();

    const title = data.notification.title;
    // data.Data.actions = data.Actions;
    // const options = {
    //     body: data.Message,
        // data: data.Data
    // };
    event.waitUntil(self.registration.showNotification(title, data.notification));
});

self.addEventListener("notificationclick", function (event) {
    console.log("notification received: ", event);
});

self.addEventListener('notificationclose', function (event) { 
    console.log("notification close: ", event);
});
