import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/axios'
import type { Pedido, Status, BuscarPedidosParams, PedidoForm } from '@/interfaces/Pedido'

export const usePedidosStore = defineStore('pedidos', () => {
  // Estado reativo para armazenar os pedidos e as informações de paginação
  const pedidos = ref<Pedido[]>([])
  const status = ref<Status[]>([])
  const currentPage = ref(1)
  const totalPages = ref(1)
  const perPage = ref(10)
  const total = ref(0)

  /**
   * Busca os pedidos da API, podendo receber parâmetros de página e filtros.
   * @param {BuscarPedidosParams} params - Parâmetros de busca, incluindo paginação e filtros.
   */
  const buscarPedidos = async (params: BuscarPedidosParams = {}) => {
    try {

      const {
        page = 1,
        departure_date = null,
        return_date = null,
        destination = null,
        status_id = null
      } = params

      const { data } = await api.get('/pedidos', {
        params: { page, departure_date, return_date, destination, status_id }
      })

      pedidos.value = data.data
      currentPage.value = data.current_page
      totalPages.value = data.last_page
      perPage.value = data.per_page
      total.value = data.total
    } catch (error) {
      console.error('Erro ao buscar pedidos:', error)
    }
  }

  const listaPedidosStatus = async () => {
    try {
      const { data } = await api.get(`/pedidos/travel-order-status`)
      status.value = data
    } catch (error) {
      console.error('Erro ao listar os status:', error)
    }
  }

  const atualizarStatus = async (id: number, novoStatus: number) => {
    try {
      await api.patch(`/pedidos/${id}/status`, { status: novoStatus })
    } catch (error) {
      console.error('Erro ao atualizar status:', error)
      throw error
    }
  }

  const criarPedido = async (pedido: PedidoForm) => {
    try {
      const response = await api.post(`/pedidos`, pedido)
      return response.data
    } catch (error) {
      console.error('Erro ao criar pedido:', error)
      throw error
    }
  }

  const atualizarPedido = async (id: number, pedidoAtualizado: PedidoForm) => {
    try {
      const response = await api.put(`/pedidos/${id}`, pedidoAtualizado)

      const index = pedidos.value.findIndex(p => p.id === id)
      if (index !== -1) {
        pedidos.value[index] = response.data
      }

      return response.data
    } catch (error) {
      console.error('Erro ao atualizar pedido:', error)
      throw error 
    }
  }

  return {
    pedidos,
    status,
    currentPage,
    totalPages,
    perPage,
    total,
    buscarPedidos,
    criarPedido,
    atualizarStatus,
    atualizarPedido,
    listaPedidosStatus
  }
})
