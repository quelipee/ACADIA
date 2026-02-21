<script setup>
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const { title, subtitle } = defineProps({
  title: {
    type: String,
    default: 'Dashboard'
  },
  subtitle: {
    type: String,
    default: 'Suas informações'
  },
})

const menuOpen = ref(false)
const page = usePage()

const userInitial = computed(() => {
  return page.props.auth.user.nome?.charAt(0).toUpperCase() || 'U'
})

// Logout
const handleLogout = () => {
  router.post('/logout')
  menuOpen.value = false
}

// Navegar para perfil
const openProfile = () => {
  router.get('/profile')
  menuOpen.value = false
}

// Navegar para dashboard
const openDashboard = () => {
  router.get('/dashboard')
  menuOpen.value = false
}

// Fechar menu ao clicar fora
const closeMenu = () => {
  menuOpen.value = false
}
</script>

<template>
  <header class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

      <!-- Logo e Títulos -->
      <div class="flex items-center gap-4">
        <div class="flex items-center justify-center w-14 h-14
                    bg-[#63cab71f] border border-[#63cab740]
                    rounded-2xl">
          <span class="text-2xl text-[#63cab7]">⬡</span>
        </div>

        <div>
          <h1 class="text-2xl font-extrabold text-[#e8eaf0]">{{ title }}</h1>
          <p class="text-sm text-gray-500">{{ subtitle }}</p>
        </div>
      </div>

      <!-- USER MENU COM LOGOUT -->
      <div class="flex items-center gap-3 relative">
        <!-- Avatar com iniciais -->
        <div class="flex items-center justify-center w-11 h-11
                    bg-gradient-to-br from-[#63cab7] to-[#4ab3a0]
                    text-[#0a1a17] rounded-xl font-bold text-sm">
          {{ userInitial }}
        </div>

        <!-- Botão do menu (3 pontos) -->
        <button
          @click="menuOpen = !menuOpen"
          class="flex p-2 rounded-lg bg-white/5 border border-white/10
                 text-gray-400 hover:bg-white/10 hover:text-[#63cab7]
                 transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="1"/>
            <circle cx="19" cy="12" r="1"/>
            <circle cx="5" cy="12" r="1"/>
          </svg>
        </button>

        <!-- DROPDOWN MENU -->
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 transform translate-y-[-8px]"
          enter-to-class="opacity-100 transform translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-from-class="opacity-100 transform translate-y-0"
          leave-to-class="opacity-0 transform translate-y-[-8px]">

          <div v-show="menuOpen"
               @click.self="closeMenu"
               class="absolute top-full right-0 mt-2 w-48
                      bg-[#16191e] border border-white/10 rounded-xl
                      shadow-[0_16px_32px_rgba(0,0,0,0.3)]
                      overflow-hidden z-20">

            <!-- Item: Dashboard -->
            <a href="#"
               @click.prevent="openDashboard"
               class="flex items-center gap-3 px-4 py-3
                      text-gray-400 hover:bg-white/5 hover:text-[#63cab7]
                      transition-colors duration-200
                      border-b border-white/10">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
              <span class="text-sm font-medium">Dashboard</span>
            </a>

            <!-- Item: Perfil -->
            <a href="#"
               @click.prevent="openProfile"
               class="flex items-center gap-3 px-4 py-3
                      text-gray-400 hover:bg-white/5 hover:text-[#63cab7]
                      transition-colors duration-200
                      border-b border-white/10">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
              <span class="text-sm font-medium">Perfil</span>
            </a>

            <!-- Divisor -->
            <div class="h-px bg-white/10"></div>

            <!-- Item: Logout -->
            <button
              @click="handleLogout"
              class="w-full flex items-center gap-3 px-4 py-3
                     text-red-400 hover:bg-red-500/10
                     transition-colors duration-200
                     font-medium text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
              </svg>
              <span>Sair</span>
            </button>

          </div>
        </transition>
      </div>

    </div>
  </header>
</template>

<style scoped>
/* Sem estilos adicionais necessários - tudo é inline com Tailwind */
</style>
