import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/LoginView.vue'),
      meta: { guest: true },
    },
    {
      path: '/',
      name: 'dialogues',
      component: () => import('@/views/DialoguesView.vue'),
      meta: { auth: true },
    },
  ],
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  await auth.init()

  if (to.meta.auth && !auth.user) {
    return { name: 'login' }
  }

  if (to.meta.guest && auth.user) {
    return { name: 'dialogues' }
  }
})

export default router
