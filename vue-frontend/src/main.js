import { createApp } from 'vue'
import '../node_modules/bootstrap/dist/css/bootstrap.css'
import router from './routes'
import App from './App.vue'


// 5. Create and mount the root instance.
const app = createApp(App)
// Make sure to _use_ the router instance to make the
// whole app router-aware.
app.use(router)

app.mount('#app')