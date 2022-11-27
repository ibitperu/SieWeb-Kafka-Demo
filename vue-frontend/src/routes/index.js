import { createRouter, createWebHistory } from 'vue-router'
// 1. Define route components.
// These can be imported from other files
import SimpleLayout from '../layouts/SimpleLayout.vue';
// import Home from '../pages/Home.vue';
import Home from '../pages/Home.vue';
import Websocket from '../pages/Websocket.vue';
import Login from '../pages/Login.vue';
// const Home = { template: '<div>Home</div>' }
const About = { template: '<div>About</div>' }

// 2. Define some routes
// Each route should map to a component.
// We'll talk about nested routes later.
const routes = [
    {
        path: '/', component: SimpleLayout,
        children: [
            {
                path: '',
                name: 'home',
                component: Home,
            },
            {
                path: 'websocket',
                name: 'websocket',
                component: Websocket,
            }
        ]
    },
    { path: '/login', name: 'Login', component: Login },
]

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    const sesion = localStorage.getItem('sesion');
    const isAuthenticated = sesion ? true : false;
    if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
    else next()
})

export default router;