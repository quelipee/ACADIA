<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../components/layout/PageHeader.vue'

const { user } = defineProps({
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

// Iniciais do nome para avatar fallback
const userInitial = computed(() => {
  return user?.nome?.charAt(0).toUpperCase() || 'U'
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
        <page-header
        title="Perfil"
        subtitle="Suas informações pessoais"/>

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
