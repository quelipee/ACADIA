<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../../components/layout/PageHeader.vue';

const { subjects } = defineProps({
  subjects: {
    type: Array,
    default: () => []
  },
})

const selectedSubject = ref(null)
const searchQuery = ref('')
const selectedActivityType = ref('Objetiva')
const activeTab = ref('em-andamento') // 'em-andamento' ou 'concluidas'


// Disciplinas em andamento
const subjectsInProgress = computed(() => {
  return subjects.filter(subject => !subject.statusConcluido)
})

// Disciplinas concluídas
const subjectsCompleted = computed(() => {
  return subjects.filter(subject => subject.statusConcluido)
})

// Filtrar matérias por busca (baseado na aba ativa)
const filteredSubjects = computed(() => {
  const sourceSubjects = activeTab.value === 'em-andamento' ? subjectsInProgress.value : subjectsCompleted.value

  if (!searchQuery.value) return sourceSubjects

  return sourceSubjects.filter(subject =>
    subject.nome.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Lógica para modal/detalhes
const openSubjectDetails = (subject) => {
  selectedSubject.value = subject
  selectedActivityType.value = 'Objetiva' // Reset ao abrir
}

const closeSubjectDetails = () => {
  selectedSubject.value = null
}

const open_activies = (data, type) => {
    router.get(`/activities/${data.id}/${data.idSalaVirtualOfertaAproveitamento}/${type}`)
}

// Copiar para clipboard
const copyToClipboard = (text, label) => {
  navigator.clipboard.writeText(text)
  console.log(`✅ ${label} copiado!`)
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
        title="Matérias"
        subtitle="Suas disciplinas acadêmicas"/>

      <!-- ABAS: EM ANDAMENTO E CONCLUÍDAS -->
      <section class="mb-8">
        <div class="flex gap-2 border-b border-white/10">
          <!-- Aba: Em Andamento -->
          <button
            @click="activeTab = 'em-andamento'"
            :class="`px-6 py-4 font-bold transition-all duration-200 border-b-2 text-sm ${
              activeTab === 'em-andamento'
                ? 'border-[#63cab7] text-[#63cab7]'
                : 'border-transparent text-gray-400 hover:text-[#e8eaf0]'
            }`">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 6v6l4 2"/>
              </svg>
              Em Andamento
              <span class="ml-2 px-2 py-1 bg-white/10 rounded-full text-xs">
                {{ subjectsInProgress.length }}
              </span>
            </div>
          </button>

          <!-- Aba: Concluídas -->
          <button
            @click="activeTab = 'concluidas'"
            :class="`px-6 py-4 font-bold transition-all duration-200 border-b-2 text-sm ${
              activeTab === 'concluidas'
                ? 'border-[#63cab7] text-[#63cab7]'
                : 'border-transparent text-gray-400 hover:text-[#e8eaf0]'
            }`">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              Concluídas
              <span class="ml-2 px-2 py-1 bg-white/10 rounded-full text-xs">
                {{ subjectsCompleted.length }}
              </span>
            </div>
          </button>
        </div>
      </section>

      <!-- CONTROLES: BUSCA E FILTROS -->
      <section class="mb-8 flex flex-col md:flex-row gap-4 items-stretch md:items-center">
        <!-- Search -->
        <div class="flex-1 relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar matérias..."
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

        <!-- Total de matérias -->
        <div class="px-4 py-3 rounded-lg bg-[#63cab71f] border border-[#63cab740]">
          <p class="text-sm font-semibold text-[#63cab7]">
            {{ filteredSubjects.length }} {{ filteredSubjects.length === 1 ? 'matéria' : 'matérias' }}
          </p>
        </div>
      </section>

      <!-- LISTA VAZIA -->
      <div v-if="filteredSubjects.length === 0" class="text-center py-12">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             class="mx-auto text-gray-500 mb-4 opacity-50">
          <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
          <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
        </svg>
        <p class="text-gray-400 text-lg">
          {{ activeTab === 'em-andamento' ? 'Nenhuma matéria em andamento' : 'Nenhuma matéria concluída' }}
        </p>
      </div>

      <!-- GRID DE MATÉRIAS -->
      <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="subject in filteredSubjects" :key="subject.id"
             @click="openSubjectDetails(subject)"
             class="bg-[#111318] border border-white/10 rounded-2xl p-6
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                    hover:border-[#63cab780]
                    hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)]
                    transition-all duration-300 cursor-pointer
                    hover:-translate-y-1 relative">

          <!-- Indicador de status (canto superior direito) -->
          <div v-if="subject.statusConcluido" class="absolute top-4 right-4">
            <span class="inline-flex items-center gap-1 bg-green-500/20 text-green-400 px-3 py-1 rounded-full text-xs font-bold">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              Concluída
            </span>
          </div>

          <!-- Badge -->
          <div class="mb-4">
            <span class="inline-block bg-[#63cab71f] text-[#63cab7] px-3 py-1
                         rounded-md text-xs font-semibold">
              Disciplina
            </span>
          </div>

          <!-- Nome da matéria -->
          <h3 class="text-base font-bold text-[#e8eaf0] mb-4 line-clamp-3 min-h-[3.5rem]">
            {{ subject.nome }}
          </h3>

          <!-- IDs em grid compacto -->
          <div class="space-y-2 mb-4 pb-4 border-b border-white/10">
            <div class="flex justify-between items-center">
              <span class="text-xs text-gray-500 uppercase font-semibold">ID</span>
              <span class="text-sm font-mono text-[#63cab7] font-bold">{{ subject.id }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-gray-500 uppercase font-semibold">ID Curso</span>
              <span class="text-sm font-mono text-[#e8eaf0]">{{ subject.idCurso }}</span>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-gray-500 uppercase font-semibold">Sala Virtual</span>
              <span class="text-sm font-mono text-[#e8eaf0]">{{ subject.idSalaVirtualOfertaAtual }}</span>
            </div>
          </div>

          <!-- CID (código) -->
          <div class="text-xs">
            <p class="text-gray-500 uppercase font-semibold mb-1">Código</p>
            <p class="text-[#63cab7] font-mono break-all text-xs">
              {{ subject.cId }}
            </p>
          </div>

          <!-- Arrow indicator -->
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
      <div v-if="selectedSubject"
           @click="closeSubjectDetails"
           class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50
                  flex items-center justify-center p-6">

        <div @click.stop
             class="bg-[#111318] border border-white/10 rounded-2xl
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_25px_50px_rgba(0,0,0,0.5)]
                    w-full max-w-lg">

          <!-- Header do modal -->
          <div class="flex items-center justify-between p-6 border-b border-white/10">
            <div>
              <h2 class="text-2xl font-bold text-[#e8eaf0]">Detalhes da Matéria</h2>
              <p v-if="selectedSubject.statusConcluido" class="text-sm text-green-400 font-semibold mt-1">
                ✓ Disciplina Concluída
              </p>
            </div>
            <button
              @click="closeSubjectDetails"
              class="p-2 text-gray-400 hover:text-[#63cab7] hover:bg-white/10
                     rounded-lg transition-colors duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Conteúdo do modal -->
          <div class="p-6 space-y-6">

            <!-- Nome -->
            <div>
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                Nome da Matéria
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                <p class="text-[#e8eaf0] text-base">
                  {{ selectedSubject.nome }}
                </p>
              </div>
            </div>

            <!-- IDs -->
            <div class="grid grid-cols-2 gap-4">
              <!-- ID -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono font-bold">
                    {{ selectedSubject.id }}
                  </p>
                  <button
                    @click="copyToClipboard(selectedSubject.id, 'ID')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] hover:text-[#e8eaf0]
                           transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- ID Curso -->
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  ID Curso
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono font-bold">
                    {{ selectedSubject.idCurso }}
                  </p>
                  <button
                    @click="copyToClipboard(selectedSubject.idCurso, 'ID Curso')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] hover:text-[#e8eaf0]
                           transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Sala Virtual -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  Sala Virtual Atual
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm font-bold">
                    {{ selectedSubject.idSalaVirtualOfertaAtual }}
                  </p>
                  <button
                    @click="copyToClipboard(selectedSubject.idSalaVirtualOfertaAtual, 'Sala Virtual Atual')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] hover:text-[#e8eaf0]
                           transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                      <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                  </button>
                </div>
              </div>

              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">
                  Sala Virtual Aproveitamento
                </label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                  <p class="text-[#e8eaf0] font-mono text-sm font-bold">
                    {{ selectedSubject.idSalaVirtualOfertaAproveitamento }}
                  </p>
                  <button
                    @click="copyToClipboard(selectedSubject.idSalaVirtualOfertaAproveitamento, 'Sala Virtual Aproveitamento')"
                    class="opacity-0 group-hover:opacity-100 text-[#63cab7] hover:text-[#e8eaf0]
                           transition-all duration-200">
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
                Código da Matéria
              </label>
              <div class="bg-white/5 border border-white/10 rounded-lg p-4 flex items-center justify-between group">
                <p class="text-[#63cab7] font-mono text-sm break-all">
                  {{ selectedSubject.cId }}
                </p>
                <button
                  @click="copyToClipboard(selectedSubject.cId, 'Código')"
                  class="opacity-0 group-hover:opacity-100 text-[#63cab7] hover:text-[#e8eaf0]
                         transition-all duration-200 flex-shrink-0 ml-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                  </svg>
                </button>
              </div>
            </div>

            <!-- Seleção de Tipo de Atividade (apenas se NÃO concluída) -->
            <div v-if="!selectedSubject.statusConcluido">
              <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-3">
                Tipo de Atividade
              </label>
              <div class="grid grid-cols-3 gap-3">
                <!-- Objetiva -->
                <button
                  @click="selectedActivityType = 'Objetiva'"
                  :class="`px-4 py-3 rounded-lg border-2 font-bold transition-all duration-200 ${
                    selectedActivityType === 'Objetiva'
                      ? 'bg-[#63cab7] border-[#63cab7] text-[#0a1a17]'
                      : 'bg-white/5 border-white/10 text-[#e8eaf0] hover:border-[#63cab7] hover:bg-white/10'
                  }`">
                  APOL
                </button>

                <!-- Discursiva -->
                <button
                  @click="selectedActivityType = 'Discursiva'"
                  :class="`px-4 py-3 rounded-lg border-2 font-bold transition-all duration-200 ${
                    selectedActivityType === 'Discursiva'
                      ? 'bg-[#63cab7] border-[#63cab7] text-[#0a1a17]'
                      : 'bg-white/5 border-white/10 text-[#e8eaf0] hover:border-[#63cab7] hover:bg-white/10'
                  }`">
                  PROVAS
                </button>

                <!-- Mista -->
                <button
                  @click="selectedActivityType = 'Mista'"
                  :class="`px-4 py-3 rounded-lg border-2 font-bold transition-all duration-200 ${
                    selectedActivityType === 'Mista'
                      ? 'bg-[#63cab7] border-[#63cab7] text-[#0a1a17]'
                      : 'bg-white/5 border-white/10 text-[#e8eaf0] hover:border-[#63cab7] hover:bg-white/10'
                  }`">
                  MISTA
                </button>
              </div>

              <!-- Feedback do tipo selecionado -->
              <div class="mt-3 px-4 py-2 bg-[#63cab71f] border border-[#63cab740] rounded-lg">
                <p class="text-xs text-[#63cab7] font-semibold">
                  Selecionado: <span class="font-bold">{{ selectedActivityType }}</span>
                </p>
              </div>
            </div>

            <!-- Mensagem para concluídas -->
            <div v-else class="px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-lg">
              <p class="text-sm text-green-400 font-semibold">
                ✓ Esta disciplina foi concluída com sucesso!
              </p>
            </div>

          </div>

          <!-- Footer -->
          <div class="flex gap-4 p-6 border-t border-white/10">
            <button
              @click="closeSubjectDetails"
              class="flex-1 px-4 py-2 rounded-lg bg-white/5 border border-white/10
                     text-[#e8eaf0] hover:bg-white/10
                     transition-colors duration-200 font-medium">
              Fechar
            </button>
            <button
              v-if="!selectedSubject.statusConcluido"
              @click="open_activies(selectedSubject, selectedActivityType)"
              class="flex-1 px-4 py-2 rounded-lg bg-[#63cab7] text-[#0a1a17]
                     hover:bg-[#5ab5a8]
                     transition-colors duration-200 font-bold">
              Acessar Sala
            </button>
            <button
              v-else
              disabled
              class="flex-1 px-4 py-2 rounded-lg bg-green-600 text-white
                     cursor-not-allowed opacity-70
                     transition-colors duration-200 font-bold">
              Concluída ✓
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
