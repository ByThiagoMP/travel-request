<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { Status } from '../interfaces/Pedido'
import { usePedidosStore } from '../stores/pedidos'

const emit = defineEmits(['aplicar'])

const pedidosStore = usePedidosStore()
const statusList = ref<Status[]>([])

const filtros = ref({
  departure_date: '',
  return_date: '',
  destination: '',
  status_id: ''
})

onMounted(async () => {
  const response = await pedidosStore.listaPedidosStatus()
  if (Array.isArray(response)) {
    statusList.value = response
  }
})

const aplicarFiltros = () => {
  emit('aplicar', { ...filtros.value })
}
</script>

<template>
  <div class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 shadow-md rounded-md p-4 z-50">
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicial</label>
      <input type="date" v-model="filtros.departure_date" class="input-filter" />
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Data Final</label>
      <input type="date" v-model="filtros.return_date" class="input-filter" />
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
      <input type="text" v-model="filtros.destination" placeholder="Ex: São Paulo" class="input-filter" />
    </div>

    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
      <select v-model="filtros.status_id" class="input-filter">
        <option value="" disabled>Selecione um status</option>
        <option v-for="s in statusList" :key="s.id" :value="s.id">
          {{ s.name }}
        </option>
      </select>
    </div>

    <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded" @click="aplicarFiltros">
      Aplicar
    </button>
  </div>
</template>

<style scoped>
.input-filter {
  @apply block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500;
}
</style>
