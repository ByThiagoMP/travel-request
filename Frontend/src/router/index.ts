import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Criação do router
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/pedidos',
      name: 'pedidos',
      component: () => import('../views/DashboardView.vue'),
      meta: { requiresAuth: true }, // Protege essa rota
    },
    {
      path: '/',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
    },
  ],
})

// Guard de navegação para verificar autenticação
router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()  // Acesse a store de autenticação
  // Verifique se a rota exige autenticação e se o usuário não está autenticado
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next('/')  // Redireciona para a página de login se não estiver autenticado
  } else {
    next()  // Caso contrário, permite a navegação
    await auth.me();
  }
})

export default router
