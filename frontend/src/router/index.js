import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/', 
      name: 'home', 
      component: () => import('../views/HomeView.vue')
    },
    {
    path: '/register',
    name: 'register',
    component: () => import('../views/RegistrationView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue')
    },
    {
      name: 'login',
      path: '/login',
      component: () => import('../views/LoginView.vue')
    }
  ],
})

export default router
