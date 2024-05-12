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
          path: '/guide',
          component: () => import('@/views/GuideView.vue')
        },
        {
          path: '/account',
          component: () => import('@/views/AccountView.vue')
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
        {
          path: 'questions/:code(\\w{5})/results',
          name: 'vote-results',
          component: () => import('@/views/VoteLatestResultsView.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: ':code(\\w{5})/history',
          name: 'question-closures',
          component: () => import('@/views/VoteHistoryView.vue'),
          meta: { requiresAuth: true }
        },
        {
          path: ':code(\\w{5})/results',
          name: 'vote-public-results',
          component: () => import('@/views/VotePublicLatestResultsView.vue')
        },
        {
          path: ':code(\\w{5})/archive/:closure_id',
          name: 'vote-archive-results',
          component: () => import('@/views/VoteArchivedResultsView.vue')
        },
        {
          path: ':code(\\w{5})',
          name: 'vote-question',
          component: () => import('@/views/VoteQuestionView.vue')
        },
        {
          path: 'users',
          name: 'users',
          component: () => import('@/views/UserListView.vue')
        },
        {
          path: 'users/:id/edit',
          name: 'users-edit',
          component: () => import('@/views/UserEditView.vue')
        },
        {
          path: 'users/create',
          name: 'users-create',
          component: () => import('@/views/UserCreateView.vue')
        }
      ]
    },
    {
      path: '/login',
      component: () => import('@/views/auth/LoginView.vue')
    },
    {
      path: '/register',
      component: () => import('@/views/auth/RegisterView.vue')
    },
    {
      path: '/change-password',
      component: () => import('@/views/auth/ChangePasswordView.vue'),
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
