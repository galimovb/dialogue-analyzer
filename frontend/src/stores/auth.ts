import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/axios'

export interface User {
  id: number
  name: string
  email: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isReady = ref(false)
  const isLoading = ref(false)

  // Проверить текущую сессию.
  async function fetchUser(): Promise<void> {
    try {
      const { data } = await api.get<User>('/api/user')
      user.value = data
    } catch (e) {
      console.error(e)
      user.value = null
    }
  }

  // Однократная инициализация при старте приложения.
  async function init(): Promise<void> {
    if (isReady.value) return
    try {
      await fetchUser()
    } finally {
      isReady.value = true
    }
  }

  async function login(email: string, password: string): Promise<void> {
    if (isLoading.value) return
    isLoading.value = true
    try {
      const { data } = await api.post<User>('/api/login', { email, password })
      user.value = data
    } catch (e) {
      console.error(e)
      throw e
    } finally {
      isLoading.value = false
    }
  }

  async function logout(): Promise<void> {
    if (isLoading.value) return
    isLoading.value = true
    try {
      await api.post('/api/logout')
      user.value = null
    } catch (e) {
      console.error(e)
    } finally {
      isLoading.value = false
    }
  }

  return { user, isReady, isLoading, init, fetchUser, login, logout }
})
