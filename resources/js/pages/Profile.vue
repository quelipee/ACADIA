<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  user: {
    type: Object,
    required: true,
    default: () => ({
      idUsuario: null,
      nome: '',
      ru: '',
      login: '',
      email: '',
      imagem: '',
      ultimoLogin: '',
      cpf: '',
      dataNascimento: ''
    })
  }
})

const menuOpen = ref(false)
const editMode = ref(false)

// Iniciais do nome para avatar fallback
const userInitial = computed(() => {
  return props.user?.nome?.charAt(0).toUpperCase() || 'U'
})

// Formatar data
const formatDate = (dateString) => {
  if (!dateString) return 'Não informado'
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    })
  } catch {
    return dateString
  }
}

// Formatar último login
const formatLastLogin = (dateString) => {
  if (!dateString) return 'Nunca'
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch {
    return dateString
  }
}

// Mascarar CPF
const maskCpf = (cpf) => {
  if (!cpf) return 'Não informado'
  return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
}

// Logout
const handleLogout = () => {
  console.log('🔓 Logout realizado')
  router.post(route('logout'))
  menuOpen.value = false
}

// Fechar menu
const closeMenu = () => {
  menuOpen.value = false
}
</script>

<template>
  <div class="relative min-h-screen bg-[#0a0c10] font-sans overflow-x-hidden">

    <!-- Orbs decorativas -->
    <div class="fixed w-[500px] h-[500px] bg-[radial-gradient(circle,#1a4a42,transparent)]
                rounded-full blur-[80px] opacity-25 -top-[200px] -left-[200px] pointer-events-none"></div>

    <div class="fixed w-[400px] h-[400px] bg-[radial-gradient(circle,#0d2d4a,transparent)]
                rounded-full blur-[80px] opacity-25 -bottom-[150px] -right-[150px] pointer-events-none"></div>

    <div class="fixed w-[300px] h-[300px] bg-[radial-gradient(circle,#2a1a4a,transparent)]
                rounded-full blur-[80px] opacity-25 top-1/2 right-[10%] pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto p-6">

      <!-- HEADER -->
      <header class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

          <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-14 h-14
                        bg-[#63cab71f] border border-[#63cab740]
                        rounded-2xl">
              <span class="text-2xl text-[#63cab7]">⬡</span>
            </div>

            <div>
              <h1 class="text-2xl font-extrabold text-[#e8eaf0]">Perfil</h1>
              <p class="text-sm text-gray-500">Suas informações pessoais</p>
            </div>
          </div>

          <!-- USER MENU COM LOGOUT -->
          <div class="flex items-center gap-3 relative">
            <div class="flex items-center justify-center w-11 h-11
                        bg-gradient-to-br from-[#63cab7] to-[#4ab3a0]
                        text-[#0a1a17] rounded-xl font-bold text-sm">
              {{ userInitial }}
            </div>

            <!-- Botão menu -->
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

                <!-- Voltar Dashboard -->
                <a href="/"
                   @click.prevent="router.get('/dashboard')"
                   class="flex items-center gap-3 px-4 py-3
                          text-gray-400 hover:bg-white/5 hover:text-[#63cab7]
                          transition-colors duration-200
                          border-b border-white/10">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                    <path d="M21 3v5h-5"/>
                    <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                    <path d="M3 21v-5h5"/>
                  </svg>
                  <span class="text-sm font-medium">Dashboard</span>
                </a>

                <!-- Divisor -->
                <div class="h-px bg-white/10"></div>

                <!-- Logout -->
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

      <!-- CONTEÚDO PRINCIPAL -->
      <main class="grid gap-6">

        <!-- CARD: AVATAR E INFORMAÇÕES PRINCIPAIS -->
        <section class="bg-[#111318] border border-white/10 rounded-2xl p-8
                       shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                       hover:border-[#63cab780]
                       hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)]
                       transition-all duration-300">

          <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">

            <!-- AVATAR -->
            <div class="flex flex-col items-center gap-4">
              <!-- Imagem do usuário ou fallback -->
              <div v-if="user.imagem" class="relative">
                <img
                  :src="user.imagem"
                  :alt="user.nome"
                  class="w-32 h-32 rounded-2xl object-cover
                         border-2 border-[#63cab7] shadow-lg">
              </div>
              <div v-else
                   class="flex items-center justify-center w-32 h-32
                          bg-gradient-to-br from-[#63cab7] to-[#4ab3a0]
                          text-white rounded-2xl font-bold text-5xl
                          border-2 border-[#63cab7] shadow-lg">
                {{ userInitial }}
              </div>

              <!-- Status -->
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-green-400 font-medium">Ativo</span>
              </div>
            </div>

            <!-- INFORMAÇÕES PRINCIPAIS -->
            <div class="flex-1 w-full">
              <!-- Nome -->
              <h2 class="text-3xl font-bold text-[#e8eaf0] mb-2 break-words">
                {{ user.nome || 'Usuário' }}
              </h2>

              <!-- Email -->
              <p class="text-[#63cab7] text-base font-medium mb-6 break-words">
                {{ user.email || 'Não informado' }}
              </p>

              <!-- Grid de infos rápidas -->
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- RU -->
                <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-1">RU</p>
                  <p class="text-sm text-[#e8eaf0] font-bold">{{ user.ru || '—' }}</p>
                </div>

                <!-- Login -->
                <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Login</p>
                  <p class="text-sm text-[#e8eaf0] font-bold">{{ user.login || '—' }}</p>
                </div>

                <!-- ID Usuário -->
                <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID</p>
                  <p class="text-sm text-[#e8eaf0] font-bold">{{ user.idUsuario || '—' }}</p>
                </div>

                <!-- Último Login -->
                <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Último Acesso</p>
                  <p class="text-sm text-[#63cab7] font-bold">{{ formatLastLogin(user.ultimoLogin) }}</p>
                </div>
              </div>
            </div>

          </div>
        </section>

        <!-- CARD: INFORMAÇÕES PESSOAIS -->
        <section class="bg-[#111318] border border-white/10 rounded-2xl p-8
                       shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                       hover:border-[#63cab780] transition-all duration-300">

          <h3 class="text-xl font-bold text-[#e8eaf0] mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-[#63cab7]">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
            Informações Pessoais
          </h3>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- CPF -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                CPF
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0] font-mono text-base">
                  {{ maskCpf(user.cpf) }}
                </p>
              </div>
            </div>

            <!-- Data de Nascimento -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Data de Nascimento
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0] font-medium">
                  {{ formatDate(user.dataNascimento) }}
                </p>
              </div>
            </div>
          </div>
        </section>

        <!-- CARD: INFORMAÇÕES DE CONTA -->
        <section class="bg-[#111318] border border-white/10 rounded-2xl p-8
                       shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                       hover:border-[#63cab780] transition-all duration-300">

          <h3 class="text-xl font-bold text-[#e8eaf0] mb-6 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-[#63cab7]">
              <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
              <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Informações da Conta
          </h3>

          <div class="space-y-4">
            <!-- Email -->
            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-lg">
              <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Email</p>
                <p class="text-[#e8eaf0] break-all">{{ user.email || 'Não informado' }}</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                   viewBox="0 0 24 24" fill="none" stroke="#63cab7" stroke-width="2"
                   class="flex-shrink-0">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
              </svg>
            </div>

            <!-- Login -->
            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-lg">
              <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Login</p>
                <p class="text-[#e8eaf0] font-mono">{{ user.login || 'Não informado' }}</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                   viewBox="0 0 24 24" fill="none" stroke="#63cab7" stroke-width="2"
                   class="flex-shrink-0">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </div>

            <!-- RU -->
            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-lg">
              <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Registro do Usuário (RU)</p>
                <p class="text-[#e8eaf0] font-bold text-lg">{{ user.ru || 'Não informado' }}</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                   viewBox="0 0 24 24" fill="none" stroke="#63cab7" stroke-width="2"
                   class="flex-shrink-0">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
              </svg>
            </div>

            <!-- ID Usuário -->
            <div class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-lg">
              <div>
                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID do Usuário</p>
                <p class="text-[#e8eaf0] font-mono">{{ user.idUsuario || 'Não informado' }}</p>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                   viewBox="0 0 24 24" fill="none" stroke="#63cab7" stroke-width="2"
                   class="flex-shrink-0">
                <path d="M11 4a7 7 0 0 0 7 7h.01v-7"/>
                <circle cx="12" cy="12" r="9"/>
              </svg>
            </div>
          </div>
        </section>

      </main>

    </div>
  </div>
</template>

<style scoped>
/* Animação do status */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
