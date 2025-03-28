import { useAuthStore } from '@/stores/auth'  // Importando a store
import { useRouter } from 'vue-router'       // Importando o router
import axios from 'axios'

const api = axios.create({
  baseURL: `${import.meta.env.VITE_APP_URL_BASE_SERVICES}`,
  headers: {
    'Content-Type': 'application/json',
  },
})

// Interceptador para adicionar o token JWT
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Interceptador para capturar erros de resposta
api.interceptors.response.use(
  response => response,  // Retorna a resposta normalmente
  (error) => {
    // Acesse a store de autenticação e o router
    const authStore = useAuthStore()
    const router = useRouter()

    // Verifique se o erro de resposta é relacionado à autenticação
    if (error.response && error.response.data.message === 'Não autenticado.') {
      console.log('Token expirado ou não autenticado, redirecionando para o login...')
      authStore.logout()  // Faça logout
      router.push('/')  // Redireciona para a página de login
    }

    // Retorna o erro para que ele possa ser tratado onde a API foi chamada
    return Promise.reject(error)
  }
)

export default api
