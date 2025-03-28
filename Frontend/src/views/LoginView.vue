<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid';

// Estado para controlar o formulário ativo: login ou cadastro
const isLogin = ref(true);

// Estados do formulário de login
const email = ref('');
const password = ref('');
const showPassword = ref(false);
const emailError = ref('');
const passwordError = ref('');

// Estados do formulário de cadastro (exemplo: nome, email, senha)
const nome = ref('');
const emailCadastro = ref('');
const senhaCadastro = ref('');
const showSenhaCadastro = ref(false);
const nomeError = ref('');
const emailCadastroError = ref('');
const senhaCadastroError = ref('');

const auth = useAuthStore();
const router = useRouter();

const handleLogin = async () => {
  emailError.value = '';
  passwordError.value = '';

  if (!email.value) {
    emailError.value = 'Por favor, insira seu e-mail.';
  } else if (!/\S+@\S+\.\S+/.test(email.value)) {
    emailError.value = 'E-mail inválido.';
  }

  if (!password.value) {
    passwordError.value = 'Por favor, insira sua senha.';
  }

  if (emailError.value || passwordError.value) {
    return;
  }

  const success = await auth.login(email.value, password.value);
  if (!success) {
    passwordError.value = 'E-mail ou senha incorretos.';
  } else {
    router.push('/pedidos');
  }
};

const handleCadastro = async () => {
  // Limpeza dos erros
  nomeError.value = '';
  emailCadastroError.value = '';
  senhaCadastroError.value = '';

  // Validações básicas
  if (!nome.value) {
    nomeError.value = 'Por favor, insira seu nome.';
  }
  if (!emailCadastro.value) {
    emailCadastroError.value = 'Por favor, insira seu e-mail.';
  } else if (!/\S+@\S+\.\S+/.test(emailCadastro.value)) {
    emailCadastroError.value = 'E-mail inválido.';
  }
  if (!senhaCadastro.value) {
    senhaCadastroError.value = 'Por favor, insira sua senha.';
  }

  if (nomeError.value || emailCadastroError.value || senhaCadastroError.value) {
    return;
  }

  const cadastroRealizado = await auth.register(nome.value, emailCadastro.value,senhaCadastro.value);

  if (cadastroRealizado) {
    router.push('/pedidos');
    isLogin.value = true;
  } else {
    // Tratar erros de cadastro
  }
};

const toggleForm = () => {
  isLogin.value = !isLogin.value;
};
</script>

<template>
  <div class="flex h-screen">
    <!-- Lado esquerdo (imagem) -->
    <div class="hidden md:flex md:w-8/9 overflow-hidden">
      <img src="../assets/login-v4.jpg" alt="Onfly"
        class="w-full h-[1150px] object-cover -translate-y-16 transform scale-110" />
    </div>

    <!-- Lado direito (formulário) TODO Componentizar -->
    <div class="flex flex-col justify-center items-center w-full md:w-4/5 p-8 bg-white">
      <div class="w-full max-w-md">
        <img src="../assets/onfly-logo.png" alt="Onfly" class="w-[300px] mx-auto mb-6" />

        <!-- Formulário de Login -->
        <div v-if="isLogin" class="space-y-4">
          <div>
            <input v-model="email" type="email" placeholder="Qual o seu e-mail?"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
            <p v-if="emailError" class="text-red-500 text-sm mt-1">{{ emailError }}</p>
          </div>

          <div class="relative">
            <input v-model="password" :type="showPassword ? 'text' : 'password'" placeholder="Agora sua senha"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 pr-12" />
            <button type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-500 hover:text-gray-700"
              @click="showPassword = !showPassword">
              <EyeIcon v-if="showPassword" class="w-5 h-5" />
              <EyeSlashIcon v-else class="w-5 h-5" />
            </button>
            <p v-if="passwordError" class="text-red-500 text-sm mt-1">{{ passwordError }}</p>
          </div>

          <a href="#" class="text-sm text-blue-500 hover:underline block text-right">
            Esqueceu sua senha?
          </a>

          <button @click="handleLogin" class="w-full bg-blue-500 text-white p-3 rounded-lg hover:bg-blue-600">
            Vamos!
          </button>

          <!-- Link para alternar para cadastro -->
          <p class="mt-4 text-center">
            <a href="#" class="text-blue-500 underline" @click.prevent="toggleForm">
              Criar uma conta
            </a>
          </p>
        </div>

        <!-- Formulário de Cadastro TODO Componentizar -->
        <div v-else class="space-y-4">
          <div>
            <input v-model="nome" type="text" placeholder="Seu nome completo"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
            <p v-if="nomeError" class="text-red-500 text-sm mt-1">{{ nomeError }}</p>
          </div>

          <div>
            <input v-model="emailCadastro" type="email" placeholder="Seu e-mail"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500" />
            <p v-if="emailCadastroError" class="text-red-500 text-sm mt-1">{{ emailCadastroError }}</p>
          </div>

          <div class="relative">
            <input v-model="senhaCadastro" :type="showSenhaCadastro ? 'text' : 'password'" placeholder="Crie sua senha"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 pr-12" />
            <button type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-500 hover:text-gray-700"
              @click="showSenhaCadastro = !showSenhaCadastro">
              <EyeIcon v-if="showSenhaCadastro" class="w-5 h-5" />
              <EyeSlashIcon v-else class="w-5 h-5" />
            </button>
            <p v-if="senhaCadastroError" class="text-red-500 text-sm mt-1">{{ senhaCadastroError }}</p>
          </div>

          <button @click="handleCadastro" class="w-full bg-green-500 text-white p-3 rounded-lg hover:bg-green-600">
            Cadastrar
          </button>

          <!-- Link para voltar ao login -->
          <p class="mt-4 text-center">
            <a href="#" class="text-blue-500 underline" @click.prevent="toggleForm">
              Já tenho uma conta
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
