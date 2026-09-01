import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/', name: 'root', redirect: () => {
        return localStorage.getItem('token') ? '/profile' : '/register';
      }
    },
    {
    path: '/register',
    name: 'register',
    component: () => import('../Registration/RegistrationView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../NotFoundView.vue')
    }
  ],
})

export default router
