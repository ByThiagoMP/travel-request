<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { usePedidosStore } from '../stores/pedidos'
import { useAuthStore } from '../stores/auth'
import type {PedidoForm, BuscarPedidosParams } from '../interfaces/Pedido'
import { useSnackbarStore } from '../composables/snackbarStore'
import dayjs from 'dayjs'

const snackbar = useSnackbarStore()
const pedidosStore = usePedidosStore()
const authStore = useAuthStore()

const editingPedidoId = ref<number | null>(null)
const pedidoForm = ref<PedidoForm>({
  destination: '',
  departure_date: '',
  return_date: '',
})

const filtrosAplicados = ref(false)
const showFilterDropdown = ref(false)
const isLoading = ref(false)

const destinoError = ref('');
const returnDateError = ref('');
const departureDateError = ref('');

const filtros = ref<BuscarPedidosParams>({
  departure_date: '',
  return_date: '',
  destination: '',
  status_id: '',
  page: 1
})

// Ao montar o componente, buscar pedidos (página 1, sem filtros)
onMounted(async () => {
  isLoading.value = true
  await pedidosStore.buscarPedidos({ page: 1 })
  carregarStatus()
  isLoading.value = false
})

// Abre/fecha dropdown de filtros
const toggleFilterDropdown = () => {
  showFilterDropdown.value = !showFilterDropdown.value
}

// Fecha o dropdown quando o mouse sair da área
const closeFilterDropdown = () => {
  showFilterDropdown.value = false
}

const carregarStatus = async () => {
  try {
    await pedidosStore.listaPedidosStatus();

  } catch (error) {
    console.error('Erro ao carregar os status:', error);
  }
}

// Aplica os filtros e faz a requisição
const aplicarFiltros = async (nextPage: number) => {
  isLoading.value = true
  filtrosAplicados.value = true;

  const params: any = { page: nextPage }

  if (filtros.value.departure_date) {
    params.departure_date = filtros.value.departure_date
  }
  if (filtros.value.return_date) {
    params.return_date = filtros.value.return_date
  }
  if (filtros.value.destination) {
    params.destination = filtros.value.destination
  }
  if (filtros.value.status_id) {
    params.status_id = filtros.value.status_id
  }

  await pedidosStore.buscarPedidos(params)

  showFilterDropdown.value = false
  isLoading.value = false
}


const limparFiltros = async () => {
  isLoading.value = true
  filtros.value.departure_date = '';
  filtros.value.return_date = '';
  filtros.value.destination = '';
  filtros.value.status_id = '';


  await pedidosStore.buscarPedidos({ page: 1 })
  filtrosAplicados.value = false;
  isLoading.value = false;
  // Aqui você pode também resetar os dados filtrados
}

const statusClass = (status: string) => {
  switch (status) {
    case 'cancelado': return 'bg-red-500'
    case 'aprovado': return 'bg-green-500'
    case 'solicitado': return 'bg-yellow-500'
    default: return 'bg-gray-400'
  }
}

// Exemplo de atualização de status
const handleUpdateStatus = async (id: number, novoStatus: number) => {
  try {
    isLoading.value = true
    await pedidosStore.atualizarStatus(id, novoStatus)
    await pedidosStore.buscarPedidos({ page: 1 })
    snackbar.show('Status atualizado com sucesso!', 'success', 3000)
  } catch (error) {
    const message = (error as any)?.response?.data?.error || 'Ocorreu um erro inesperado';
    snackbar.show(message, 'warning', 3000)
  } finally {
    isLoading.value = false
  }
}

// Função para submeter o formulário (pode ser ajustada para enviar dados)
const submitForm = async () => {
  try {
    if (validaForm()) {
      isLoading.value = true
      await pedidosStore.criarPedido(pedidoForm.value)
      await pedidosStore.buscarPedidos({ page: 1 })
      snackbar.show('Pedido criado com sucesso!', 'success', 3000)
      closeModal()
    }  
  } catch (error) {
    snackbar.show('Ocorreu um erro ao criar seu pedido, favor contate um administrador', 'error', 3000)
  } finally {
    isLoading.value = false
  }
}

const validaForm = () => {
  let isValid = true;
  destinoError.value = '';
  if (pedidoForm.value.destination == '') {
    destinoError.value = 'Por favor, insira o destino.';
    isValid =  false;
  }
  departureDateError.value = '';
  if (pedidoForm.value.departure_date == '') {
    departureDateError.value = 'Por favor, insira a data de partida.';
    isValid =  false;
  }
  returnDateError.value = '';
  if (pedidoForm.value.return_date == '') {
    returnDateError.value = 'Por favor, insira a data de retorno.';
    isValid = false;
  }

  return isValid;
}

// Ativa o modo de edição para um pedido específico
const startEditing = (pedido: any) => {
  editingPedidoId.value = pedido.id
  pedidoForm.value = { ...pedido }
}

// Salva as alterações feitas em um pedido
const saveEdit = async () => {
  try {
    if (editingPedidoId.value !== null) {
      await pedidosStore.atualizarPedido(editingPedidoId.value, pedidoForm.value)
      snackbar.show('Pedido editado com sucesso!', 'success', 3000)
    } else {
      snackbar.show('Não foi possivel achar pedido', 'error', 3000)
    }
    editingPedidoId.value = null
  } catch (error) {
    console.error('Erro ao salvar edição:', error)
    snackbar.show('Ocorreu um erro ao editar seu pedido, favor contate um administrador', 'error', 3000)
  }
}

// Cancela a edição e volta ao estado original
const cancelEdit = () => {
  editingPedidoId.value = null
}

// Estado para controlar a visibilidade da modal
const isModalOpen = ref(false)

// Função para abrir a modal
const openModal = () => {
  pedidoForm.value = { destination: '', departure_date: '', return_date: '' }
  isModalOpen.value = true
}

// Função para fechar a modal
const closeModal = () => {
  isModalOpen.value = false
  // Limpar campos ao fechar a modal
  pedidoForm.value = { destination: '', departure_date: '', return_date: '' }
}
</script>

<template>
  <div v-if="authStore.isAuthenticated" class="p-4 relative">
    <div class="flex items-center justify-between mb-4">
      <div class="relative w-full max-w-sm">
        <p class="font-bold text-blue-500">Pedidos de Viagem</p>
      </div>
      <div class=" flex items-center space-x-4">
        <!-- Botão Novo Pedido TODO Componentizar-->
        <div class="relative">
          <button
            class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md focus:outline-none"
            @click="openModal">
            <i class="pi pi-plus mr-2"></i>
            Novo Pedido
          </button>
        </div>

        <div v-if="isModalOpen" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
          <div class="bg-white p-6 rounded-md w-96">
            <h3 class="text-xl font-semibold mb-4">Criar Novo Pedido</h3>

            <!-- Formulário para Novo Pedido   -->
            <form @submit.prevent="submitForm">
              <div class="mb-4">
                <label for="campo1" class="block text-sm font-medium text-gray-700">
                  Destino <span class="text-red-500">*</span>
                </label>
                <input id="campo1" type="text" v-model="pedidoForm.destination" :class="{
                  'border-red-500': destinoError,
                  'border-gray-300': !destinoError
                }" class="mt-1 block w-full px-3 py-2 border rounded-md" />
                <p v-if="destinoError" class="text-red-500 text-sm mt-1">{{ destinoError }}</p>
              </div>

              <div class="mb-4">
                <label for="campo2" class="block text-sm font-medium text-gray-700">Data de ida
                  <span class="text-red-500">*</span>
                </label>
                <input id="campo2" type="date" v-model="pedidoForm.departure_date" :class="{
                  'border-red-500': departureDateError,
                  'border-gray-300': !departureDateError
                }" class="mt-1 block w-full px-3 py-2 border rounded-md" />
                <p v-if="departureDateError" class="text-red-500 text-sm mt-1">{{ departureDateError }}</p>
              </div>

              <div class="mb-4">
                <label for="campo3" class="block text-sm font-medium text-gray-700">Data de retorno
                  Data de retorno <span class="text-red-500">*</span>
                </label>
                <input id="campo3" type="date" v-model="pedidoForm.return_date" :class="{
                  'border-red-500': returnDateError,
                  'border-gray-300': !returnDateError
                }" class="mt-1 block w-full px-3 py-2 border rounded-md" />
                <p v-if="returnDateError" class="text-red-500 text-sm mt-1">{{ returnDateError }}</p>
              </div>
              <div class="flex justify-end space-x-2">
                <button type="button" @click="closeModal"
                  class="inline-flex items-center px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded">
                  Cancelar
                </button>
                <button type="submit"
                  class="inline-flex items-center px-3 py-1 mr-2 text-white bg-green-500 hover:bg-green-600 rounded">
                  Criar Pedido
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Botão Filtros TODO Componentizar-->
        <div class="relative">
          <button v-if="!filtrosAplicados"
            class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-md focus:outline-none"
            @click.stop="toggleFilterDropdown">
            <i class="pi pi-filter mr-2"></i>
            Filtros
          </button>

          <button v-else
            class="flex items-center bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md focus:outline-none"
            @click.stop="limparFiltros">
            <i class="pi pi-filter-slash mr-2"></i>
            Limpar Filtros
          </button>

          <!-- Dropdown de filtros -->
          <div v-if="showFilterDropdown" @mouseleave="closeFilterDropdown"
            class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 shadow-md rounded-md p-4 z-50">

            <!-- Data Inicial -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
              <input type="date" v-model="filtros.departure_date"
                class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <!-- Data Final -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
              <input type="date" v-model="filtros.return_date"
                class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <!-- Destino -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
              <input type="text" placeholder="Ex: São Paulo" v-model="filtros.destination"
                class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <!-- Status -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select v-model="filtros.status_id"
                class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="" disabled>Selecione um status</option>
                <option v-for="s in pedidosStore.status" :key="s.id" :value="s.id">
                  {{ s.name }}
                </option>
              </select>
            </div>

            <!-- Botão aplicar -->
            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded focus:outline-none"
              @click.stop="aplicarFiltros(1)">
              Aplicar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabela de Pedidos TODO Componentizar -->
    <div class="overflow-x-auto border border-gray-200 rounded-lg">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destino</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Partida</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Retorno</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <!-- Linha de loading -->
          <tr v-if="isLoading">
            <td colspan="7" class="px-4 py-3 text-gray-700 text-center">
              <i class="pi pi-spin pi-spinner" style="font-size: 2rem"></i>
            </td>
          </tr>
          <!-- Mensagem caso não tenha pedidos -->
          <tr v-else-if="pedidosStore.pedidos?.length === 0">
            <td colspan="7" class="px-4 py-3 text-gray-700 text-center">
              Não há solicitações de viagem!
            </td>
          </tr>

          <!-- Lista de pedidos -->
          <tr v-else v-for="pedido in pedidosStore.pedidos" :key="pedido.id" class="hover:bg-gray-100">
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
              {{ pedido.id }}
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
              {{ pedido.user.name }}
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
              <template v-if="editingPedidoId === pedido.id">
                <input v-model="pedidoForm.destination" class="w-full p-2 border rounded" />
              </template>
              <template v-else>
                {{ pedido.destination }}
              </template>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
              <template v-if="editingPedidoId === pedido.id">
                <input type="date" v-model="pedidoForm.departure_date" class="w-full p-2 border rounded" />
              </template>
              <template v-else>
                {{ dayjs(pedido.departure_date).format('DD/MM/YYYY') }}
              </template>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
              <template v-if="editingPedidoId === pedido.id">
                <input type="date" v-model="pedidoForm.return_date" class="w-full p-2 border rounded" />
              </template>
              <template v-else>
                {{ dayjs(pedido.return_date).format('DD/MM/YYYY') }}
              </template>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm">
              <span
                :class="['inline-flex items-center px-2 py-1 rounded text-xs font-medium text-white', statusClass(pedido.status.name)]">
                {{ pedido.status.name }}
              </span>
            </td>
            <td class="px-4 py-3 whitespace-nowrap text-sm">
              <button v-if="editingPedidoId != pedido.id && authStore.profile.is_admin"
                class="inline-flex items-center px-3 py-1 mr-2 text-white bg-green-500 hover:bg-green-600 rounded"
                @click="handleUpdateStatus(pedido.id, 2)" title="Aprovar">
                <i class="pi pi-check"></i>
              </button>
              <button v-if="editingPedidoId != pedido.id && authStore.profile.is_admin"
                class="inline-flex items-center px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded"
                @click="handleUpdateStatus(pedido.id, 3)" title="Cancelar">
                <i class="pi pi-times"></i>
              </button>
              <button v-if="editingPedidoId === pedido.id" @click="saveEdit"
                class="inline-flex items-center px-3 py-1 mr-2 text-white bg-green-500 hover:bg-green-600 rounded">
                Salvar
              </button>
              <button v-if="editingPedidoId === pedido.id" @click="cancelEdit"
                class="inline-flex items-center px-3 py-1 text-white bg-red-500 hover:bg-red-600 rounded">
                Cancelar
              </button>
              <button v-else @click="startEditing(pedido)" class="inline-flex items-center px-3 py-1 ml-4 rounded"
                :class="{
                  'text-gray-400 cursor-not-allowed': pedido.status.name === 'aprovado' || pedido.status.name === 'cancelado',
                  'text-black hover:bg-gray-300': !(pedido.status.name === 'aprovado' || pedido.status.name === 'cancelado')
                }" title="Editar" :disabled="pedido.status.name === 'aprovado' || pedido.status.name === 'cancelado'">
                <i class="pi pi-pencil"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginação -->
    <div v-if="pedidosStore.totalPages > 1" class="flex items-center justify-center mt-4 space-x-2">
      <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="pedidosStore.currentPage === 1" @click="aplicarFiltros(pedidosStore.currentPage - 1)">
        Anterior
      </button>
      <span class="text-sm text-gray-700">
        Página {{ pedidosStore.currentPage }} de {{ pedidosStore.totalPages }}
      </span>
      <button class="px-3 py-1 bg-gray-200 text-gray-600 rounded disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="pedidosStore.currentPage === pedidosStore.totalPages" @click="aplicarFiltros(pedidosStore.currentPage + 1)">
        Próxima
      </button>
    </div>
  </div>
</template>