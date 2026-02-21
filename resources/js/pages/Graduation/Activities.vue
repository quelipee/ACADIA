<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../../components/layout/PageHeader.vue';

const { activities } = defineProps({
  activities: {
    type: Array,
    default: () => []
  },
})

const menuOpen = ref(false)
const selectedActivity = ref(null)
const searchQuery = ref('')
const filterStatus = ref('todos')

// Filtrar atividades
const filteredActivities = computed(() => {
  let filtered = activities

  // Filtro por busca
  if (searchQuery.value) {
    filtered = filtered.filter(activity =>
      activity.nameActivities.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  // Filtro por status
  if (filterStatus.value !== 'todos') {
    filtered = filtered.filter(activity =>
      activity.status.toLowerCase() === filterStatus.value.toLowerCase()
    )
  }

  return filtered
})

// Cores por status
const getStatusColor = (status) => {
  const statusMap = {
    'Finalizada': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
    'Pendente': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30' },
    'Em Progresso': { bg: 'bg-blue-500/20', text: 'text-blue-400', border: 'border-blue-500/30' },
    'Não Iniciada': { bg: 'bg-gray-500/20', text: 'text-gray-400', border: 'border-gray-500/30' }
  }
  return statusMap[status] || statusMap['Não Iniciada']
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
    'Não Iniciada': '◯'
  }
  return badgeMap[status] || '•'
}

// Abrir detalhes
const openActivityDetails = (activity) => {
  selectedActivity.value = activity
}

const closeActivityDetails = () => {
  selectedActivity.value = null
}

// Copiar para clipboard
const copyToClipboard = (text, label) => {
  navigator.clipboard.writeText(text)
  console.log(`✅ ${label} copiado!`)
}

// Status únicos para filtro
const uniqueStatuses = computed(() => {
  const statuses = activities.map(a => a.status)
  return [...new Set(statuses)]
})

const Activityattempts  = (data) => {
    router.get(`/activity_attempts/${data.cID}`);
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
    title="Atividades"
    subtitle="Suas atividades e avaliações"/>

      <!-- CONTROLES: BUSCA E FILTROS -->
      <section class="mb-8 flex flex-col md:flex-row gap-4 items-stretch">
        <!-- Search -->
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar atividades..."
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
            {{ filteredActivities.length }} {{ filteredActivities.length === 1 ? 'atividade' : 'atividades' }}
          </p>
        </div>
      </section>

      <!-- LISTA VAZIA -->
      <div v-if="activities.length === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             class="mx-auto text-gray-500 mb-4 opacity-50">
          <path d="M9 11l3 3L22 4"/>
          <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-gray-400 text-lg">Nenhuma atividade encontrada</p>
      </div>

      <!-- GRID DE ATIVIDADES -->
      <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="activity in filteredActivities" :key="activity.id"
             @click="openActivityDetails(activity)"
             class="bg-[#111318] border border-white/10 rounded-2xl p-6
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                    hover:border-[#63cab780]
                    hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)]
                    transition-all duration-300 cursor-pointer
                    hover:-translate-y-1">

          <!-- Header: Status e Nota -->
          <div class="flex items-start justify-between mb-4">
            <span :class="`inline-flex items-center gap-2 rounded-md text-xs font-semibold px-3 py-1 ${getStatusColor(activity.status).bg} ${getStatusColor(activity.status).text} border ${getStatusColor(activity.status).border}`">
              <span>{{ getStatusBadge(activity.status) }}</span>
              {{ activity.status }}
            </span>
            <span :class="`text-lg font-bold ${getNoteColor(activity.nota)}`">
              {{ activity.nota }}
            </span>
          </div>

          <!-- Nome da atividade -->
          <h3 class="text-base font-bold text-[#e8eaf0] mb-3 line-clamp-2 min-h-[2.5rem]">
            {{ activity.nameActivities }}
          </h3>

          <!-- Progresso -->
          <div class="mb-4 pb-4 border-b border-white/10">
            <div class="flex justify-between items-center mb-2">
              <span class="text-xs text-gray-500 font-semibold uppercase">Tentativas</span>
              <span class="text-sm font-bold text-[#63cab7]">{{ activity.tentativa }}/{{ activity.tentativaTotal }}</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
              <div
                class="bg-[#63cab7] h-2 rounded-full transition-all duration-300"
                :style="{ width: `${(activity.tentativa / activity.tentativaTotal) * 100}%` }">
              </div>
            </div>
          </div>

          <!-- Informações rápidas -->
          <div class="space-y-2 text-xs">
            <div class="flex justify-between">
              <span class="text-gray-500">Tipo:</span>
              <span class="text-[#e8eaf0] font-semibold">{{ activity.nomeClassificacaoTipo }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Ação:</span>
              <span class="text-[#63cab7] font-semibold">{{ activity.acao }}</span>
            </div>
            <div v-if="activity.questoesGeradas" class="flex justify-between">
              <span class="text-gray-500">Questões:</span>
              <span class="text-green-400 font-semibold">Geradas</span>
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
      <div v-if="selectedActivity"
           @click="closeActivityDetails"
           class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50
                  flex items-center justify-center p-6">

        <div @click.stop
             class="bg-[#111318] border border-white/10 rounded-2xl
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_25px_50px_rgba(0,0,0,0.5)]
                    w-full max-w-2xl max-h-[90vh] overflow-y-auto">

          <!-- Header -->
          <div class="sticky top-0 bg-[#111318] border-b border-white/10 flex items-center justify-between p-6">
            <h2 class="text-2xl font-bold text-[#e8eaf0]">Detalhes da Atividade</h2>
            <button
              @click="closeActivityDetails"
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
                <div :class="`inline-flex items-center gap-2 rounded-md text-sm font-bold px-3 py-2 ${getStatusColor(selectedActivity.status).bg} ${getStatusColor(selectedActivity.status).text} border ${getStatusColor(selectedActivity.status).border}`">
                  {{ getStatusBadge(selectedActivity.status) }} {{ selectedActivity.status }}
                </div>
              </div>
              <div class="bg-white/5 border rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Nota Final</p>
                <p :class="`text-3xl font-bold ${getNoteColor(selectedActivity.nota)}`">
                  {{ selectedActivity.nota }}
                </p>
              </div>
            </div>

            <!-- Nome da atividade -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Nome da Atividade
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0] text-base leading-relaxed">
                  {{ selectedActivity.nameActivities }}
                </p>
              </div>
            </div>

            <!-- Disciplina -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Disciplina
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0]">
                  {{ selectedActivity.nameDisciplina }}
                </p>
              </div>
            </div>

            <!-- Tentativas -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Progresso de Tentativas
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                  <p class="text-[#e8eaf0] font-bold">{{ selectedActivity.tentativa }} de {{ selectedActivity.tentativaTotal }}</p>
                  <p class="text-[#63cab7] font-bold">{{ Math.round((selectedActivity.tentativa / selectedActivity.tentativaTotal) * 100) }}%</p>
                </div>
                <div class="w-full bg-white/10 rounded-full h-3">
                  <div
                    class="bg-[#63cab7] h-3 rounded-full transition-all duration-300"
                    :style="{ width: `${(selectedActivity.tentativa / selectedActivity.tentativaTotal) * 100}%` }">
                  </div>
                </div>
              </div>
            </div>

            <!-- Grid de IDs -->
            <div class="grid grid-cols-2 gap-4">
              <!-- ID da Atividade -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID da Atividade
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedActivity.id }}</p>
                  <button
                    @click="copyToClipboard(selectedActivity.id, 'ID')"
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
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedActivity.idAvaliacao }}</p>
                  <button
                    @click="copyToClipboard(selectedActivity.idAvaliacao, 'ID Avaliação')"
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

            <!-- Grid de salas -->
            <div class="grid grid-cols-2 gap-4">
              <!-- Sala Virtual -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  Sala Virtual
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedActivity.idSalaVirtual }}</p>
                  <button
                    @click="copyToClipboard(selectedActivity.idSalaVirtual, 'Sala Virtual')"
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
                  <p class="text-[#e8eaf0] font-mono text-sm">{{ selectedActivity.idUsuario }}</p>
                  <button
                    @click="copyToClipboard(selectedActivity.idUsuario, 'ID Usuário')"
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

            <!-- Código -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Código da Atividade
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                <p class="text-[#63cab7] font-mono text-sm break-all">{{ selectedActivity.cID }}</p>
                <button
                  @click="copyToClipboard(selectedActivity.cID, 'Código')"
                  class="opacity-0 group-hover:opacity-100 text-[#63cab7] transition-all duration-200 flex-shrink-0 ml-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Informações adicionais -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Tipo</p>
                <p class="text-[#e8eaf0] font-bold">{{ selectedActivity.nomeClassificacaoTipo }}</p>
              </div>

              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Ação</p>
                <p class="text-[#63cab7] font-bold">{{ selectedActivity.acao }}</p>
              </div>

              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Questões</p>
                <p :class="selectedActivity.questoesGeradas ? 'text-green-400 font-bold' : 'text-gray-400'">
                  {{ selectedActivity.questoesGeradas ? 'Geradas' : 'Não geradas' }}
                </p>
              </div>

              <div v-if="selectedActivity.cIdAvaliacaoVinculada" class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Avaliação Vinculada</p>
                <p class="text-[#e8eaf0] font-mono text-xs break-all">{{ selectedActivity.cIdAvaliacaoVinculada }}</p>
              </div>
            </div>

          </div>

          <!-- Footer -->
          <div class="sticky bottom-0 bg-[#111318] border-t border-white/10 flex gap-4 p-6">
            <button
              @click="closeActivityDetails"
              class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-white/10
                     text-[#e8eaf0] hover:bg-white/10 font-bold
                     transition-colors duration-200">
              Fechar
            </button>
            <button
            @click="Activityattempts(selectedActivity)"
              class="flex-1 px-4 py-3 rounded-lg bg-[#63cab7] text-[#0a1a17]
                     hover:bg-[#5ab5a8] font-bold
                     transition-colors duration-200">
              Acessar Tentativas
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
