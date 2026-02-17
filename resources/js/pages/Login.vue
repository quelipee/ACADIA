<script setup>
import { ref, reactive, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  ru: '',
  password: ''
})

const focused = reactive({
  ru: false,
  password: false
})

const showPassword = ref(false)
const isLoading = ref(false)

const isFormEmpty = computed(() => {
  return form.ru.trim() === '' || form.password.trim() === ''
})

// Validação local (ANTES de enviar)
const validateField = (field) => {
  if (field === 'ru') {
    form.errors.ru = form.ru.trim() === '' ? 'O RU é obrigatório.' : ''
  }
  if (field === 'password') {
    form.errors.password = form.password.trim() === ''
      ? 'A senha é obrigatória.'
      : form.password.length < 6
        ? 'Mínimo 6 caracteres.' : ''
  }
}

const onBlur = (field) => {
  focused[field] = false
  validateField(field)
}

const validateForm = () => {
  validateField('ru')
  validateField('password')
  return !form.errors.ru && !form.errors.password
}

const handleLogin = () => {
  form.clearErrors()

  if (!validateForm()) return

  form.post('/login-faculdade', {
    onStart: () => isLoading.value = true,
    onFinish: () => isLoading.value = false,
    // Não precisa de onError agora - Inertia trata automaticamente!
    onSuccess: () => {
      // Opcional: fazer algo após sucesso
      console.log("Login realizado!")
    }
  })
}
</script>

<template>
    <div class="relative min-h-screen flex items-center justify-center bg-gray-900 font-sans px-6 overflow-hidden">
      <!-- Orbs de fundo -->
      <div class="absolute w-[500px] h-[500px] bg-gradient-radial from-green-900/70 to-transparent rounded-full top-[-200px] left-[-200px] blur-[80px] pointer-events-none"></div>
      <div class="absolute w-[400px] h-[400px] bg-gradient-radial from-blue-900/70 to-transparent rounded-full bottom-[-150px] right-[-150px] blur-[80px] pointer-events-none"></div>
      <div class="absolute w-[300px] h-[300px] bg-gradient-radial from-purple-900/70 to-transparent rounded-full top-1/2 left-[60%] transform -translate-x-1/2 -translate-y-1/2 blur-[80px] pointer-events-none"></div>

      <!-- Card principal -->
      <div class="relative z-10 bg-gray-800 border border-white/10 rounded-2xl p-12 max-w-md w-full shadow-[0_32px_80px_rgba(0,0,0,0.5)] animate-fade-in">

        <!-- Cabeçalho -->
        <div class="text-center mb-9">
          <div class="inline-flex items-center justify-center w-14 h-14 bg-green-400/10 border border-green-400/25 rounded-lg mb-5">
            <span class="text-green-400 text-2xl">⬡</span>
          </div>
          <h1 class="text-white font-extrabold text-2xl mb-1 font-display">Bem-vindo</h1>
          <p class="text-gray-400 text-sm font-light">Acesse sua conta para continuar</p>
        </div>

        <!-- Formulário -->
        <form @submit.prevent="handleLogin" class="flex flex-col gap-5">

          <!-- Campo RU -->
          <div :class="['flex flex-col gap-1', form.errors.ru ? 'text-red-400' : '', focused.ru ? 'text-green-400' : '']">
            <label for="ru" class="text-xs font-medium uppercase tracking-wide">{{ form.errors.ru ? 'Erro' : 'Registro do Usuário (RU)' }}</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <!-- Ícone usuário -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
              </span>
              <input id="ru" v-model="form.ru" type="text" placeholder="Digite seu RU"
                autocomplete="username"
                @focus="focused.ru = true"
                @blur="onBlur('ru')"
                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
              />
            </div>
            <transition name="fade">
              <span v-if="form.errors.ru" class="flex items-center gap-1 text-xs text-red-400 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor" stroke-width="2.5">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ form.errors.ru }}
              </span>
            </transition>
          </div>

          <!-- Campo Senha -->
          <div :class="['flex flex-col gap-1', form.errors.password ? 'text-red-400' : '', focused.password ? 'text-green-400' : '']">
            <label for="password" class="text-xs font-medium uppercase tracking-wide">{{ form.errors.password ? 'Erro' : 'Senha' }}</label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                <!-- Ícone cadeado -->
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                  <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
              </span>
              <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
                placeholder="Digite sua senha"
                autocomplete="current-password"
                @focus="focused.password = true"
                @blur="onBlur('password')"
                class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-10 pr-10 text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-green-400 transition"
              />
              <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-green-400 transition">
                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                  <circle cx="12" cy="12" r="3"/>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                  viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                  <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                  <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
              </button>
            </div>
            <transition name="fade">
              <span v-if="form.errors.password" class="flex items-center gap-1 text-xs text-red-400 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                  fill="none" stroke="currentColor" stroke-width="2.5">
                  <circle cx="12" cy="12" r="10"/>
                  <line x1="12" y1="8" x2="12" y2="12"/>
                  <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ form.errors.password }}
              </span>
            </transition>
          </div>

          <!-- Esqueci a senha -->
          <div class="flex justify-end -mt-2">
            <a href="#" @click.prevent class="text-gray-400 text-xs hover:text-green-400 transition">Esqueci minha senha</a>
          </div>

          <!-- Botão de login -->
          <button type="submit" :disabled="isFormEmpty || isLoading"
            :class="['w-full py-3 rounded-xl font-bold text-sm flex justify-center items-center gap-2 transition',
                    isLoading ? 'opacity-80 cursor-wait bg-gradient-to-r from-green-400 to-teal-500' : 'bg-gradient-to-r from-green-400 to-teal-500 hover:translate-y-[-1px] shadow-lg']">
            <span v-if="isLoading" class="w-4 h-4 border-2 border-gray-700 border-t-green-900 rounded-full animate-spin"></span>
            <span>{{ isLoading ? 'Autenticando...' : 'Entrar' }}</span>
            <span v-if="!isLoading">→</span>
          </button>

        </form>
      </div>
    </div>
  </template>
