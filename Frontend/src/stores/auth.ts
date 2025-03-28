// src/stores/auth.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/lib/axios'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('auth_token'))
  const isAuthenticated = computed(() => !!token.value)
  const profile = ref<any>(null)

  const login = async (email: string, password: string) => {
    try {
      const { data } = await api.post('/login', { email, password })
      token.value = data.token
      localStorage.setItem('auth_token', data.token)
      // me();
      return true
    } catch (err) {
      console.error('Erro ao logar:', err)
      return false
    }
  }

  const register = async (name:string, email: string, password: string) => {
    try {
      const { data } = await api.post('/register', { name, email, password })
      token.value = data.token
      localStorage.setItem('auth_token', data.token)
      // me();
      return true
    } catch (err) {
      console.error('Erro ao registrar usuário:', err)
      return false
    }
  }

  const logout = () => {
    token.value = null
    localStorage.removeItem('auth_token')
  }

  const me = async () => {
    try {
      const { data } = await api.get('/me')
      profile.value = data
    } catch (err) {
      console.error('Erro ao buscar usuário:', err)
      return null
    }
  }
  
  return {
    token,
    isAuthenticated,
    profile,
    login,
    register,
    logout,
    me
  }
})
