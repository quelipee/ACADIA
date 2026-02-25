<script setup>
import { ref, computed } from 'vue'
import PageHeader from '../../components/layout/PageHeader.vue';
import { router } from '@inertiajs/vue3';


const { attempts } = defineProps({
  attempts: {
    type: Array,
    default: () => []
  },
})

const selectedAttempt = ref(null)
const searchQuery = ref('')
const filterStatus = ref('todos')


// Filtrar tentativas
const filteredAttempts = computed(() => {
  let filtered = attempts

  // Filtro por busca (número da tentativa)
  if (searchQuery.value) {
    filtered = filtered.filter(attempt =>
      attempt.tentativa.toString().includes(searchQuery.value)
    )
  }

  // Filtro por status
  if (filterStatus.value !== 'todos') {
    filtered = filtered.filter(attempt =>
      attempt.status.toLowerCase() === filterStatus.value.toLowerCase()
    )
  }

  return filtered.sort((a, b) => b.tentativa - a.tentativa) // Ordenar por tentativa (decrescente)
})

// Cores por status
const getStatusColor = (status) => {
  const statusMap = {
    'Finalizada': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
    'Pendente': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30' },
    'Em Progresso': { bg: 'bg-blue-500/20', text: 'text-blue-400', border: 'border-blue-500/30' },
    'Cancelada': { bg: 'bg-red-500/20', text: 'text-red-400', border: 'border-red-500/30' }
  }
  return statusMap[status] || statusMap['Pendente']
}

// Cores por nota
const getNoteColor = (nota) => {
  if (nota >= 90) return 'text-green-400'
  if (nota >= 70) return 'text-yellow-400'
  if (nota >= 50) return 'text-orange-400'
  return 'text-red-400'
}

// Status badge
const getStatusBadge = (status) => {
  const badgeMap = {
    'Finalizada': '✓',
    'Pendente': '⏱',
    'Em Progresso': '⟳',
    'Cancelada': '✗'
  }
  return badgeMap[status] || '•'
}

// Abrir detalhes
const openAttemptDetails = (attempt) => {
  selectedAttempt.value = attempt
}

const closeAttemptDetails = () => {
  selectedAttempt.value = null
}

// Copiar para clipboard
const copyToClipboard = (text, label) => {
  navigator.clipboard.writeText(text)
  console.log(`✅ ${label} copiado!`)
}

// Status únicos para filtro
const uniqueStatuses = computed(() => {
  const statuses = attempts.map(a => a.status)
  return [...new Set(statuses)]
})

// Cálculo de média de notas
const averageNote = computed(() => {
  if (attempts.length === 0) return 0
  const sum = attempts.reduce((acc, attempt) => acc + attempt.nota, 0)
  return (sum / attempts.length).toFixed(1)
})

// Melhor tentativa
const bestAttempt = computed(() => {
  if (attempts.length === 0) return null
  return attempts.reduce((prev, current) =>
    (prev.nota > current.nota) ? prev : current
  )
})

// Pior tentativa
const worstAttempt = computed(() => {
  if (attempts.length === 0) return null
  return attempts.reduce((prev, current) =>
    (prev.nota < current.nota) ? prev : current
  )
})

const answerkey = (data) => {
    router.get(`/answer_key/${data.id}`, { data: data })
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

    <div class="relative z-10 max-w-6xl mx-auto p-6">

        <page-header
        title="Tentativas"
        subtitle="Suas tentativas"/>

            <!-- ESTATÍSTICAS -->
      <section class="mb-8 grid gap-4 md:grid-cols-4">
        <!-- Total de tentativas -->
        <div class="bg-[#111318] border border-white/10 rounded-lg p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Total</p>
              <p class="text-2xl font-bold text-[#e8eaf0]">{{ attempts.length }}</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-[#63cab7] opacity-50">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 6v6l4 2"/>
            </svg>
          </div>
        </div>

        <!-- Média de notas -->
        <div class="bg-[#111318] border border-white/10 rounded-lg p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Média</p>
              <p :class="`text-2xl font-bold ${getNoteColor(averageNote)}`">{{ averageNote }}</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-yellow-400 opacity-50">
              <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3"/>
              <line x1="12" y1="12" x2="20" y2="7.5"/>
              <line x1="12" y1="21" x2="12" y2="12"/>
              <line x1="4" y1="7.5" x2="12" y2="12"/>
            </svg>
          </div>
        </div>

        <!-- Melhor nota -->
        <div class="bg-[#111318] border border-white/10 rounded-lg p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Melhor</p>
              <p :class="`text-2xl font-bold ${getNoteColor(bestAttempt?.nota)}`">
                {{ bestAttempt?.nota || '—' }}
              </p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-green-400 opacity-50">
              <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
            </svg>
          </div>
        </div>

        <!-- Pior nota -->
        <div class="bg-[#111318] border border-white/10 rounded-lg p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Pior</p>
              <p :class="`text-2xl font-bold ${getNoteColor(worstAttempt?.nota)}`">
                {{ worstAttempt?.nota || '—' }}
              </p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-red-400 opacity-50">
              <path d="M11 22H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v9"/>
              <path d="M15 13v4"/>
              <path d="M15 17v2"/>
            </svg>
          </div>
        </div>
      </section>

      <!-- CONTROLES: BUSCA E FILTROS -->
      <section class="mb-8 flex flex-col md:flex-row gap-4 items-stretch">
        <!-- Search -->
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar por número da tentativa..."
            class="w-full px-4 py-3 pl-10 rounded-lg
                   bg-white/5 border border-white/10
                   text-[#e8eaf0] placeholder-gray-500
                   focus:border-[#63cab7] focus:outline-none focus:bg-white/10
                   transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
               class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
        </div>

        <!-- Filtro por Status -->
        <select
          v-model="filterStatus"
          class="px-4 py-3 rounded-lg bg-white/5 border border-white/10
                 text-[#e8eaf0] focus:border-[#63cab7] focus:outline-none
                 transition-all duration-200 min-w-[180px]">
          <option value="todos">Todos os Status</option>
          <option v-for="status in uniqueStatuses" :key="status" :value="status">
            {{ status }}
          </option>
        </select>

        <!-- Total -->
        <div class="px-4 py-3 rounded-lg bg-[#63cab71f] border border-[#63cab740]">
          <p class="text-sm font-semibold text-[#63cab7] whitespace-nowrap">
            {{ filteredAttempts.length }} {{ filteredAttempts.length === 1 ? 'tentativa' : 'tentativas' }}
          </p>
        </div>
      </section>

      <!-- LISTA VAZIA -->
      <div v-if="attempts.length === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             class="mx-auto text-gray-500 mb-4 opacity-50">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 6v6l4 2"/>
        </svg>
        <p class="text-gray-400 text-lg">Nenhuma tentativa encontrada</p>
      </div>

      <!-- GRID DE TENTATIVAS -->
      <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="attempt in filteredAttempts" :key="attempt.id"
             @click="openAttemptDetails(attempt)"
             class="bg-[#111318] border border-white/10 rounded-2xl p-6
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                    hover:border-[#63cab780]
                    hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)]
                    transition-all duration-300 cursor-pointer
                    hover:-translate-y-1">

          <!-- Header: Status e Nota -->
          <div class="flex items-start justify-between mb-4">
            <span :class="`inline-flex items-center gap-2 rounded-md text-xs font-semibold px-3 py-1 ${getStatusColor(attempt.status).bg} ${getStatusColor(attempt.status).text} border ${getStatusColor(attempt.status).border}`">
              <span>{{ getStatusBadge(attempt.status) }}</span>
              {{ attempt.status }}
            </span>
            <span :class="`text-lg font-bold ${getNoteColor(attempt.nota)}`">
              {{ attempt.nota }}
            </span>
          </div>

          <!-- Número da Tentativa -->
          <div class="mb-4">
            <p class="text-sm text-gray-500 uppercase font-semibold mb-1">Tentativa</p>
            <h3 class="text-2xl font-bold text-[#e8eaf0]">
              #{{ attempt.tentativa }} de {{ attempt.tentativaTotal }}
            </h3>
          </div>

          <!-- Progresso -->
          <div class="mb-4 pb-4 border-b border-white/10">
            <div class="w-full bg-white/10 rounded-full h-2">
              <div
                class="bg-[#63cab7] h-2 rounded-full transition-all duration-300"
                :style="{ width: `${(attempt.tentativa / attempt.tentativaTotal) * 100}%` }">
              </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
              {{ Math.round((attempt.tentativa / attempt.tentativaTotal) * 100) }}% completo
            </p>
          </div>

          <!-- IDs compactos -->
          <div class="space-y-2 text-xs">
            <div class="flex justify-between">
              <span class="text-gray-500">ID Avaliação:</span>
              <span class="text-[#63cab7] font-mono font-bold">{{ attempt.idAvaliacao }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">ID Usuário:</span>
              <span class="text-[#e8eaf0] font-mono">{{ attempt.idUsuario }}</span>
            </div>
          </div>

          <!-- Arrow -->
          <div class="mt-4 flex justify-end">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 class="text-[#63cab7]">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>
      </div>

    </div>

    <!-- MODAL DE DETALHES -->
    <transition name="modal">
      <div v-if="selectedAttempt"
           @click="closeAttemptDetails"
           class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50
                  flex items-center justify-center p-6">

        <div @click.stop
             class="bg-[#111318] border border-white/10 rounded-2xl
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_25px_50px_rgba(0,0,0,0.5)]
                    w-full max-w-2xl max-h-[90vh] overflow-y-auto">

          <!-- Header -->
          <div class="sticky top-0 bg-[#111318] border-b border-white/10 flex items-center justify-between p-6">
            <h2 class="text-2xl font-bold text-[#e8eaf0]">Detalhes da Tentativa</h2>
            <button
              @click="closeAttemptDetails"
              class="p-2 text-gray-400 hover:text-[#63cab7] hover:bg-white/10
                     rounded-lg transition-colors duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Conteúdo -->
          <div class="p-6 space-y-6">

            <!-- Status e Nota em destaque -->
            <div class="grid grid-cols-2 gap-4">
              <div class="bg-white/5 border rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Status</p>
                <div :class="`inline-flex items-center gap-2 rounded-md text-sm font-bold px-3 py-2 ${getStatusColor(selectedAttempt.status).bg} ${getStatusColor(selectedAttempt.status).text} border ${getStatusColor(selectedAttempt.status).border}`">
                  {{ getStatusBadge(selectedAttempt.status) }} {{ selectedAttempt.status }}
                </div>
              </div>
              <div class="bg-white/5 border rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Nota</p>
                <p :class="`text-3xl font-bold ${getNoteColor(selectedAttempt.nota)}`">
                  {{ selectedAttempt.nota }}
                </p>
              </div>
            </div>

            <!-- Tentativa -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Número da Tentativa
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0] text-2xl font-bold">
                  {{ selectedAttempt.tentativa }} / {{ selectedAttempt.tentativaTotal }}
                </p>
                <div class="mt-3 w-full bg-white/10 rounded-full h-3">
                  <div
                    class="bg-[#63cab7] h-3 rounded-full transition-all duration-300"
                    :style="{ width: `${(selectedAttempt.tentativa / selectedAttempt.tentativaTotal) * 100}%` }">
                  </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                  {{ Math.round((selectedAttempt.tentativa / selectedAttempt.tentativaTotal) * 100) }}% de progresso
                </p>
              </div>
            </div>

            <!-- IDs -->
            <div class="grid grid-cols-3 gap-4">
              <!-- ID -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID da Tentativa
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm break-all">{{ selectedAttempt.id }}</p>
                  <button
                    @click="copyToClipboard(selectedAttempt.id, 'ID')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- ID Avaliação -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID Avaliação
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedAttempt.idAvaliacao }}</p>
                  <button
                    @click="copyToClipboard(selectedAttempt.idAvaliacao, 'ID Avaliação')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- ID Usuário -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID Usuário
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedAttempt.idUsuario }}</p>
                  <button
                    @click="copyToClipboard(selectedAttempt.idUsuario, 'ID Usuário')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Resumo -->
            <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/10">
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Tentativa Atual</p>
                <p class="text-2xl font-bold text-[#63cab7]">{{ selectedAttempt.tentativa }}</p>
              </div>

              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Total de Tentativas</p>
                <p class="text-2xl font-bold text-[#e8eaf0]">{{ selectedAttempt.tentativaTotal }}</p>
              </div>

              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Nota Obtida</p>
                <p :class="`text-2xl font-bold ${getNoteColor(selectedAttempt.nota)}`">{{ selectedAttempt.nota }}</p>
              </div>
            </div>

          </div>

          <!-- Footer -->
          <div class="sticky bottom-0 bg-[#111318] border-t border-white/10 flex gap-4 p-6">
            <button
              @click="closeAttemptDetails"
              class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-white/10
                     text-[#e8eaf0] hover:bg-white/10 font-bold
                     transition-colors duration-200">
              Fechar
            </button>
            <button
            @click="answerkey(selectedAttempt)"
            :disabled="selectedAttempt.status === 'Aguardando início'"
            :class="[
                        'flex-1 px-4 py-3 rounded-lg font-bold transition-colors duration-200',
                        selectedAttempt.status === 'Aguardando início'
                        ? 'border border-white/10 text-gray-500 bg-white/5 cursor-not-allowed opacity-50'
                        : 'border border-[#63cab7]/50 text-[#63cab7] hover:bg-[#63cab7]/10'
                    ]">
              Ver Gabarito
            </button>
          </div>

        </div>
      </div>
    </transition>

  </div>
</template>

<style scoped>
/* Animações do modal */
.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
}
.modal-enter-active > div, .modal-leave-active > div {
  transition: all 0.3s ease;
}
.modal-enter-from > div, .modal-leave-to > div {
  transform: scale(0.95);
  opacity: 0;
}
</style>
