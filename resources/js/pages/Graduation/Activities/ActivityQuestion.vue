<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../../../components/layout/PageHeader.vue'

const props = defineProps({
  questions: {
    type: Array,
    required: true,
  },
  activityTitle: {
    type: String,
    default: 'Atividade'
  },
  idSalaVirtualOfertaAproveitamento: {
    type: [ String, Number ]
  },
  idSalaVirtual: {
    type: [ String, Number ]
  },
  typeExam : {
    type: String
  }
})

// Estado
const currentQuestionIndex = ref(0)
const selectedAnswers = ref({})
const isSubmitting = ref(false)
const menuOpen = ref(false)

// Computadas
const totalQuestions = computed(() => props.questions.length)
const currentQuestion = computed(() => props.questions[currentQuestionIndex.value] || null)
const currentQuestionNumber = computed(() => currentQuestionIndex.value + 1)
const selectedAlternative = computed({
  get: () => selectedAnswers.value[currentQuestionIndex.value] ?? null,
  set: (value) => {
    selectedAnswers.value[currentQuestionIndex.value] = value
  }
})

const alternativeLetters = computed(() => {
  const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']
  return letters
})

const progressPercent = computed(() => {
  if (totalQuestions.value === 0) return 0
  return Math.round((currentQuestionNumber.value / totalQuestions.value) * 100)
})

const answeredCount = computed(() => {
  return Object.keys(selectedAnswers.value).length
})

// Métodos
const handleSelectAlternative = (alternativeIndex) => {
    selectedAlternative.value = alternativeIndex
}

const handleNextQuestion = (question, index) => {
    router.post(`/selected_alternative/${question.alternativas[index].idQuestaoAlternativa}`, { question })
    if (currentQuestionIndex.value < totalQuestions.value - 1) {
        currentQuestionIndex.value++
        window.scrollTo({ top: 0, behavior: 'smooth' })
    }
}

const handlePreviousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
}


const handleFinish = async (question, index) => {
    router.post(`/selected_alternative/${question.alternativas[index].idQuestaoAlternativa}`, { question })
  // Verificar se respondeu todas
  if (answeredCount.value !== totalQuestions.value) {
    alert('Por favor, responda todas as questões antes de finalizar.')
    return
  }

  isSubmitting.value = true

  router.post(`/activities/${question.id}/complete`, {
    question: question,
    idSalaVirtual: props.idSalaVirtual,
    idSalaVirtualOfertaAproveitamento: props.idSalaVirtualOfertaAproveitamento,
    typeExam: props.typeExam
  })
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
        :title="activityTitle"
        :subtitle="`Questão ${currentQuestionNumber} de ${totalQuestions}`"/>

        <div class="mt-6 space-y-3 mb-6">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Progresso Geral</span>
            <span class="text-sm font-bold text-[#63cab7]">{{ answeredCount }}/{{ totalQuestions }} respondidas</span>
          </div>
          <div class="w-full bg-white/10 rounded-full h-2.5">
            <div
              class="bg-[#63cab7] h-2.5 rounded-full transition-all duration-300"
              :style="{ width: `${progressPercent}%` }">
            </div>
          </div>
          <p class="text-xs text-gray-500">
            {{ progressPercent }}% completo
          </p>
        </div>


      <!-- QUESTÃO ATUAL -->
      <section class="mb-10" v-if="currentQuestion">
        <div class="bg-[#111318] border border-white/10 rounded-2xl p-8
                    shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]">

          <!-- Número da questão -->
          <div class="flex items-center gap-3 mb-6">
            <div class="flex items-center justify-center w-10 h-10
                        bg-[#63cab7] text-[#0a1a17] rounded-lg font-bold">
              {{ currentQuestionNumber }}
            </div>
            <span class="text-gray-500 text-sm uppercase font-semibold">Questão</span>
          </div>

          <!-- Texto da questão -->
          <div class="mb-8">
            <p class="text-[#e8eaf0] text-lg leading-relaxed font-medium">
              {{ currentQuestion.questao }}
            </p>
          </div>

          <!-- IDs compactos -->
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-white/10">
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Questão</p>
              <p class="text-[#63cab7] font-mono font-bold">{{ currentQuestion.idQuestao }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Avaliação</p>
              <p class="text-[#e8eaf0] font-mono font-bold">{{ currentQuestion.id }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Usuário</p>
              <p class="text-[#e8eaf0] font-mono font-bold">{{ currentQuestion.idAvaliacaoUsuario }}</p>
            </div>
            <div>
              <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Alternativa</p>
              <p class="text-[#e8eaf0] font-mono font-bold">{{ currentQuestion.idQuestaoAlternativa }}</p>
            </div>
          </div>

        </div>
      </section>

      <!-- ALTERNATIVAS -->
      <section class="mb-10" v-if="currentQuestion">
        <h2 class="text-lg font-bold text-[#e8eaf0] mb-4 uppercase tracking-wider">
          Selecione uma alternativa
        </h2>

        <div class="space-y-3">
          <button
            v-for="(alternativa, index) in currentQuestion.alternativas"
            :key="alternativa.id"
            @click="handleSelectAlternative(index)"
            :class="`w-full px-6 py-4 rounded-lg border-2 transition-all duration-200 text-left
              ${
                selectedAlternative === index
                  ? 'bg-[#63cab7] border-[#63cab7] text-[#0a1a17] font-bold'
                  : 'bg-[#111318] border-white/10 text-[#e8eaf0] hover:border-[#63cab7] hover:bg-white/5'
              }`">

            <div class="flex items-start gap-4">
              <!-- Letra -->
              <div class="flex-shrink-0 w-8 h-8 rounded-md flex items-center justify-center"
                   :class="{
                     'bg-white text-[#0a1a17]': selectedAlternative === index,
                     'bg-[#63cab71f] text-[#63cab7]': selectedAlternative !== index
                   }">
                <span class="font-bold">{{ alternativeLetters[index] }}</span>
              </div>

              <!-- Conteúdo -->
              <div class="flex-1">
                <p class="font-semibold text-base leading-relaxed">
                    {{ alternativa.valor }}
                </p>
              </div>

              <!-- Checkmark -->
              <div v-if="selectedAlternative === index" class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="20 6 9 17 4 12"/>
                </svg>
              </div>
            </div>
          </button>
        </div>

        <!-- Feedback -->
        <div v-if="selectedAlternative === null"
             class="mt-6 px-4 py-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
          <p class="text-yellow-400 text-sm font-semibold">
            ⚠️ Selecione uma alternativa para continuar
          </p>
        </div>

        <div v-else
             class="mt-6 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-lg">
          <p class="text-green-400 text-sm font-semibold">
            ✓ Alternativa {{ alternativeLetters[selectedAlternative] }} selecionada
          </p>
        </div>
      </section>

      <!-- BOTÕES DE NAVEGAÇÃO -->
      <section class="flex gap-4">
        <button
          @click="handlePreviousQuestion"
          :disabled="currentQuestionIndex === 0"
          :class="`flex-1 px-6 py-3 rounded-lg border border-white/10 font-bold
            transition-all duration-200
            ${
              currentQuestionIndex === 0
                ? 'bg-white/5 text-gray-500 cursor-not-allowed opacity-50'
                : 'bg-white/5 text-[#e8eaf0] hover:bg-white/10 hover:text-[#63cab7]'
            }`">
          <div class="flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
            Anterior
          </div>
        </button>

        <!-- Botão Próxima ou Finalizar -->
        <button
          v-if="currentQuestionNumber < totalQuestions"
          @click="handleNextQuestion(currentQuestion, selectedAlternative)"
          :disabled="selectedAlternative === null"
          :class="`flex-1 px-6 py-3 rounded-lg font-bold
            transition-all duration-200
            ${
              selectedAlternative === null
                ? 'bg-[#63cab7]/50 text-[#0a1a17]/50 cursor-not-allowed opacity-50'
                : 'bg-[#63cab7] text-[#0a1a17] hover:bg-[#5ab5a8]'
            }`">
          <div class="flex items-center justify-center gap-2">
            Próxima
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </button>

        <!-- Botão Finalizar (última questão) -->
        <button
          v-else
          @click="handleFinish(currentQuestion, selectedAlternative)"
          :disabled="isSubmitting || answeredCount !== totalQuestions"
          :class="`flex-1 px-6 py-3 rounded-lg font-bold
            transition-all duration-200
            ${
              isSubmitting || answeredCount !== totalQuestions
                ? 'bg-[#63cab7]/50 text-[#0a1a17]/50 cursor-not-allowed opacity-50'
                : 'bg-gradient-to-r from-[#63cab7] to-[#5ab5a8] text-[#0a1a17] hover:shadow-lg'
            }`">
          <div class="flex items-center justify-center gap-2">
            {{ isSubmitting ? 'Enviando...' : '✓ Finalizar' }}
            <svg v-if="!isSubmitting" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
        </button>
      </section>

    </div>
  </div>
</template>

<style scoped>
button {
  transition: all 0.3s ease;
}

button:not(:disabled):hover {
  transform: translateY(-2px);
}
</style>
