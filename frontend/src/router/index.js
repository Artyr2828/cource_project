import { createRouter, createWebHistory } from 'vue-router'
import api from '../services/api.js'
import { errorMessages } from 'vue/compiler-sfc'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'main',
      redirect: '/login'
    },
    {
      path: '/profile', 
      name: 'profile', 
      component: () => import('../views/ProfileView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/positions',
      name: 'positions',
      component: () => import('../views/PositionView.vue'),
      meta: {requiresAuth: false}
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('../views/RegistrationView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('../views/NotFoundView.vue')
    }
  ]
})

router.beforeEach(async (to, from) => {
  if (!to.meta.requiresAuth) {
    return true
  }

  if (to.name === "profile"){
    try {
      await api.get('/api/profile/me');
      return true;
    } catch (error) {
       return { 
        name: 'login',
        query: {error: "Access to the profile page is prohibited for unauthorized users"}
      }
  }
}

  if (to.name === "dashboard"){
    console.log("Здесь должен получить данные");
}

})

export default router