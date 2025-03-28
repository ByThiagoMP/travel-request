// src/stores/auth.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/axios'

export const useNotificationsStore = defineStore('notifications', () => {

    const notifications = ref<any>(null)

    const notificationsRead = async (id: number) => {
        try {
            const { data } = await api.post(`/notifications/${id}/read`)
            notifications.value = data
        } catch (err) {
            console.error('Erro ao buscar usuário:', err)
            return null
        }
    }

    const listaNotifications = async () => {
        try {
            const { data } = await api.get('/notifications')
            notifications.value = data
        } catch (err) {
            console.error('Erro ao listar notificações:', err)
            return null
        }
    }


    return {
        notifications,
        notificationsRead,
        listaNotifications
    }
})
