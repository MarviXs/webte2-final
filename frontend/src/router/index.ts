import { useAuthStore } from '@/stores/auth-store'
import { createRouter, createWebHistory } from 'vue-router'
const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/layouts/MainLayout.vue'),
      children: [
        {
          path: '',
          name: 'vote-code-enter',
          component: () => import('@/views/VoteCodeEnterView.vue')
        },
        {
          path: 'questions',
          name: 'questions',
          component: () => import('@/views/QuestionListView.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'questions/create',
          name: 'questions-create',
          component: () => import('@/views/QuestionCreateView.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: 'questions/:id/edit',
          name: 'questions-edit',
          component: () => import('@/views/QuestionEditView.vue'),
          meta: { requiresAuth: true }
        },
      ]
    },
    {
      path: '/login',
      component: () => import('@/views/auth/LoginView.vue')
    },
    {
      path: '/register',
      component: () => import('@/views/auth/RegisterView.vue')
    }
  ]
})

router.beforeEach((to) => {
  const requiresAuth = to.matched.some((r) => r.meta.requiresAuth)

  const authStore = useAuthStore()

  if (requiresAuth && !authStore.isAuthenticated) {
    return '/login'
  }
})

export default router
