import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'

import Categorias from './views/Categorias.vue'
import Productos from './views/Productos.vue'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/categorias' },
        { path: '/categorias', component: Categorias },
        { path: '/productos', component: Productos },
    ]
})

const app = createApp(App)
app.use(router)
app.mount('#app')