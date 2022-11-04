import { createApp } from 'vue'
import axios from 'axios';
import { initializeApp } from "firebase/app";
import Swal from "sweetalert2";
import { getMessaging, isSupported, getToken, onMessage } from "firebase/messaging";
import router from './routes'
import '../node_modules/bootstrap/dist/css/bootstrap.css'
import App from './App.vue'

var toastMixin = Swal.mixin({
    toast: true,
    icon: 'success',
    title: 'General Title',
    animation: false,
    position: 'top-right',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: false,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

const sesionUsuario = localStorage.getItem('sesion') || null;
const supported = await isSupported().catch(() => false)

if (sesionUsuario && Notification && supported) {
    const firebaseConfig = {
        apiKey: "AIzaSyDwy7FGA-Nci9ACLgL-xr38_p_QKCMB9Zo",
        authDomain: "sie-web-demo.firebaseapp.com",
        projectId: "sie-web-demo",
        storageBucket: "sie-web-demo.appspot.com",
        messagingSenderId: "929879698385",
        appId: "1:929879698385:web:9dbf2ff21c8803b55b4ef0",
        measurementId: "G-CDNC1C1907"
    };

    const sw = await window.navigator.serviceWorker.register('/firebase-messaging-sw.js')
    const appFirebase = initializeApp(firebaseConfig);
    const messaging = getMessaging(appFirebase);

    Notification.requestPermission()
        .then(function () {
            console.log("Notification permission granted.");

            getToken(messaging, {
                serviceWorkerRegistration: sw
            })
                .then(function (currentToken) {
                    if (currentToken) {
                        console.log(currentToken);
                        sendTokenToServer(currentToken); //save in database
                    }
                })
                .catch(function (err) {
                    console.log("An error occurred while retrieving token. ", err);
                });
        })
        .catch(function (err) {
            console.log("Unable to get permission to notify.", err);
        });

    // onTokenRefresh(function () {
    //     getToken(messaging, {
    //         serviceWorkerRegistration: sw
    //     })
    //         .then(function (refreshedToken) {
    //             sendTokenToServer(refreshedToken);
    //         })
    //         .catch(function (err) {
    //             console.log(err);
    //         });
    // });

    onMessage(messaging, function (payload) {
        // console.log(payload);
        //receive notification here when this page is active. working fine
        toastMixin.fire({
            animation: false,
            title: payload.notification.title,
            html: `La descarga de su archivo ya está disponible: ${payload.notification.body}`,
            // title: payload.notification.title
          });
    });
}

function sendTokenToServer(currentToken) {
    console.log(currentToken);
    const usuario = JSON.parse(sesionUsuario);
    axios.post('http://localhost:9000/api/users/device', {
        tokenDispositivo: currentToken,
        usuarioId: usuario.ID,
    })
        .then(function (response) {
            console.log(response);
        })
        .catch(function (reason) {
            console.log(reason);
        });
}


// 5. Create and mount the root instance.
const app = createApp(App)
// Make sure to _use_ the router instance to make the
// whole app router-aware.
app.use(router)
// app.config.globalProperties.$appFirebase = appFirebase
app.mount('#app')