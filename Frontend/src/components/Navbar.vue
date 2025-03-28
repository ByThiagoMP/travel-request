

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { useNotificationsStore } from '@/stores/notifications';


const authStore = useAuthStore();
const route = useRoute();

const notificationsStore = useNotificationsStore();

const isAuthenticated = computed(() => authStore.isAuthenticated);
const profile = computed(() => authStore.profile);

// Estado do menu do usuário
const isUserMenuOpen = ref(false);
const isNotificationsMenuOpen = ref(false);
const unreadNotifications = ref(0);

const routesSemNavbar = ['/',];

const showNavbar = computed(() => {
  return authStore.isAuthenticated && !routesSemNavbar.includes(route.path);
});

// Função para alternar o estado do menu do usuário
const toggleUserMenu = () => {
  isUserMenuOpen.value = !isUserMenuOpen.value;
  isNotificationsMenuOpen.value = false;
};

const toggleNotificationsMenu = () => {
  isNotificationsMenuOpen.value = !isNotificationsMenuOpen.value;
  isUserMenuOpen.value = false;
};

const logout = () => {
  authStore.logout();
  isUserMenuOpen.value = false;
};

const readNotification = async (id: number) => {
  await notificationsStore.notificationsRead(id);
  await notificationsStore.listaNotifications();
  countNotifications();
};

const countNotifications =() => {
  unreadNotifications.value = notificationsStore.notifications.length;
}

onMounted(async () => {
  if (isAuthenticated.value) {
    await notificationsStore.listaNotifications();
    countNotifications();
  }
});

</script>

<template>
  <nav v-if="showNavbar" class="bg-white shadow-md p-4">
    <div class="max-w-screen-xl mx-auto flex justify-between items-center">
      <!-- Logo à esquerda -->
      <a href="#" class="flex items-center space-x-3">
        <img src="../assets/onfly-logo.png" class="max-h-16" alt="Logo" />
        <span class="text-2xl font-semibold text-gray-800"></span>
      </a>

      <!-- Menus no centro -->
      <div class="hidden md:flex space-x-8">
        <a href="/" class="font-bold text-gray-500 hover:text-blue-500">Home</a>
        <a href="#" class="font-bold text-gray-500 hover:text-blue-500">Metricas</a>
        <a href="#" class="font-bold text-gray-500 hover:text-blue-500">Serviços</a>
        <a href="#" class="font-bold text-gray-500 hover:text-blue-500">Notas</a>
        <a href="#" class="font-bold text-gray-500 hover:text-blue-500">Listas</a>
      </div>

      <!-- Notificação e Ícone de usuário à direita -->
      <div class="flex items-center space-x-4">
        <!-- Notificação -->
        <div class="relative">
          <button @click="toggleNotificationsMenu"
            class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-gray-300 text-gray-800">
            <!-- Ícone maior -->
            <i class="pi pi-bell text-2xl"></i>
            <!-- Indicador de notificações não lidas -->
            <span v-if="unreadNotifications > 0"
              class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
              {{ unreadNotifications }}
            </span>
          </button>
          <!-- Menu de notificações -->
          <div v-show="isNotificationsMenuOpen"
            class="absolute right-0 top-full mt-2 bg-white shadow-lg rounded-lg w-80 border divide-y divide-gray-100 z-10">
            <!-- Se não houver notificações -->
            <div v-if="notificationsStore.notifications?.length === 0" class="px-4 py-3 text-gray-700 text-center">
              Você está em dia!
            </div>

            <!-- Caso contrário, lista as notificações -->
            <ul v-else class="max-h-72 overflow-y-auto">
              <li v-for="notification in notificationsStore.notifications" :key="notification.id"
                class="px-4 py-3 text-gray-700 hover:bg-gray-100 flex justify-between items-center">
                {{ notification.data?.messagem }}
                <!-- Botão para marcar como lida -->
                <button class="inline-flex items-center px-3 py-1 text-white hover:bg-gray-300 rounded"
                  @click="readNotification(notification.id)" title="Marcar como lida">
                  <i class="pi pi-check cursor-pointer text-sm text-green-500"></i>
                </button>
              </li>
            </ul>

          </div>
        </div>


        <!-- Ícone de usuário -->
        <div class="relative">
          <button type="button"
            class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-gray-300 text-gray-800"
            @click="toggleUserMenu">
            <i class="pi pi-user text-xl"></i>
          </button>

          <!-- Menu dropdown do usuário -->
          <div v-show="isUserMenuOpen"
            class="z-50 absolute right-0 top-full mt-2 bg-white shadow-lg rounded-lg w-48 border divide-y divide-gray-100 z-10">
            <div class="px-4 py-3">
              <!-- Exibindo nome e email do usuário -->
              <span class="block text-sm text-gray-900">{{ profile?.name }}</span>
              <span class="block text-sm text-gray-500 truncate">{{ profile?.email }}</span>
            </div>
            <ul>
              <li>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100"
                  @click.prevent="logout">
                  <i class="pi pi-user-edit text-xl"></i>
                  <span>Perfil</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100"
                  @click.prevent="logout">
                  <i class="pi pi-cog text-xl"></i>
                  <span>Configurações</span>
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100"
                  @click.prevent="logout">
                  <i class="pi pi-sign-out text-xl"></i>
                  <span>Sair</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>