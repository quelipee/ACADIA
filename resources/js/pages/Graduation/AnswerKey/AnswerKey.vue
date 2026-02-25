<script setup>
import { computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../../../components/layout/PageHeader.vue'

const props = defineProps({
  questoes: {
    type: Array,
    required: true,
  },
  activityTitle: {
    type: String,
    default: 'Gabarito da Atividade'
  },
  score: {
    type: [Number, String],
    default: null
  }
})

const calculatedScore = computed(() => {
  return props.questoes.reduce((acc, questao) => {
    // Garante que o valor seja um número antes de somar
    const nota = parseFloat(questao.notaQuestao) || 0
    return acc + nota
  }, 0)
})

const alternativeLetters = computed(() => {
  const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J']
  return letters
})

const totalQuestions = computed(() => props.questoes.length)

// Contar acertos baseado em notaQuestao > 0
const totalCorrect = computed(() => {
  let count = 0
  props.questoes.forEach(questao => {
    if (questao.notaQuestao > 0) {
      count++
    }
  })
  return count
})

const scorePercentage = computed(() => {
  if (totalQuestions.value === 0) return 0
  return Math.round((totalCorrect.value / totalQuestions.value) * 100)
})

// Funções auxiliares
const getAlternativaTexto = (alternativa) => {
  if (!alternativa) return ''
  const attrs = alternativa.questaoAlternativaAtributos || []
  for (let attr of attrs) {
    if (attr.nomeAtributo === 'texto') {
      return attr.valor
    }
  }
  return ''
}

const getAlternativaCorreta = (questao) => {
    if (!questao) return null
    const alternativas = questao.alternativas || []
    for (let alt of alternativas) {
        const attrs = alt.questaoAlternativaAtributos || []
        for (let attr of attrs) {
        if (attr.nomeAtributo === 'correta' && attr.valor === 'true') {
            return alt.id
        }
        }
    }
    return null
}

const getComentarioAlternativa = (alternativa) => {
  if (!alternativa) return null
  const attrs = alternativa.questaoAlternativaAtributos || []
  for (let attr of attrs) {
    if (attr.nomeAtributo === 'comentário') {
      return attr.valor
    }
  }
  return null
}
// 9225496
const getAlternativaIndex = (questao, alternativaId) => {
    if (!alternativaId) return -1
    const alternativas = questao.alternativas || []
    return alternativas.findIndex(alt => alt.id === alternativaId)
}

const sanitizeHTML = (html) => {
  if (!html) return ''
  try {
    return DOMPurify.sanitize(html, {
      ALLOWED_TAGS: ['p', 'br', 'strong', 'em', 'u', 'b', 'i', 'table', 'tr', 'td', 'th', 'span', 'div', 'h1', 'h2', 'h3'],
      ALLOWED_ATTR: ['style', 'class']
    })
  } catch (e) {
    return html
  }
}

const isCorrect = (questao) => {
  if (!questao) return false
  return questao.notaQuestao > 0
}

const goBackToActivities = () => {
  router.get('/activities')
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
        :subtitle="`${totalQuestions} questões`"/>

        <div class="top-0 z-50 mb-8 grid grid-cols-3 gap-4 py-4 backdrop-blur-sm">
          <div class="bg-[#111318] border border-white/10 rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Acertos</p>
            <p class="text-2xl font-bold text-green-400">{{ totalCorrect }}/{{ totalQuestions }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ scorePercentage }}%</p>
          </div>

          <div class="bg-[#111318] border border-white/10 rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Erros</p>
            <p class="text-2xl font-bold text-red-400">{{ totalQuestions - totalCorrect }}</p>
            <p class="text-sm text-gray-400 mt-1">{{ 100 - scorePercentage }}%</p>
          </div>

          <div class="bg-[#111318] border border-white/10 rounded-xl p-4">
            <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Sua Nota</p>
            <p v-if="calculatedScore" class="text-2xl font-bold text-[#63cab7]">{{ score || calculatedScore }}</p>
            <p v-else class="text-2xl font-bold text-gray-400">—</p>
            <p class="text-sm text-gray-400 mt-1">Desempenho</p>
          </div>
        </div>

        <!-- QUESTÕES - TODAS VISÍVEIS -->
        <div class="space-y-12">
          <div v-for="(questao, questionIndex) in questoes" :key="questao.id" class="scroll-mt-32">
            <!-- Número da questão -->
            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-white/10">
              <div class="flex items-center justify-center w-12 h-12 rounded-lg text-lg font-bold text-white"
                   :class="isCorrect(questao) ? 'bg-green-500' : 'bg-red-500'">
                {{ isCorrect(questao) ? '✓' : '✗' }}
              </div>
              <div>
                <h3 class="text-2xl font-bold text-[#e8eaf0]">Questão {{ questionIndex + 1 }}</h3>
                <p class="text-sm text-gray-400">
                  {{ isCorrect(questao) ? '✓ Acertou' : '✗ Errou' }}
                  <span v-if="isCorrect(questao)" class="text-green-400">
                    (+{{ questao.notaQuestao }} ponto{{ questao.notaQuestao > 1 ? 's' : '' }})
                  </span>
                  <span v-else class="text-red-400">
                    ({{ questao.notaQuestao }}/{{ questao.peso || 10 }} pontos)
                  </span>
                </p>
              </div>
            </div>

            <!-- Card da questão -->
            <section class="mb-8">
              <div class="bg-[#111318] border border-white/10 rounded-2xl p-8
                          shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]">

                <!-- Comando -->
                <div v-if="questao.comando" class="mb-6 p-4 bg-white/5 border border-white/10 rounded-lg">
                  <p class="text-[#63cab7] text-sm font-semibold"
                     v-html="sanitizeHTML(questao.comando)">
                  </p>
                </div>

                <!-- Texto da questão -->
                <div class="mb-8 text-[#e8eaf0] text-lg leading-relaxed prose prose-invert max-w-none"
                     v-html="sanitizeHTML(questao.questao)">
                </div>

                <!-- Informações da questão -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6 border-t border-white/10">
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">ID Questão</p>
                    <p class="text-[#63cab7] font-mono font-bold text-sm">{{ questao.idQuestao }}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Tentativa</p>
                    <p class="text-[#e8eaf0] font-mono font-bold text-sm">{{ questao.tentativa }}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Ordem</p>
                    <p class="text-[#e8eaf0] font-mono font-bold text-sm">{{ questao.ordem }}</p>
                  </div>
                  <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Taxa Acerto</p>
                    <p class="text-[#e8eaf0] font-mono font-bold text-sm">{{ questao.percentualAcerto }}%</p>
                  </div>
                </div>

                <!-- Data de correção -->
                <div v-if="questao.dataCorrecao" class="mt-4 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                  <p class="text-xs text-blue-400">
                    📅 Corrigido em: {{ new Date(questao.dataCorrecao).toLocaleString('pt-BR') }}
                  </p>
                </div>

                <!-- Comentário de correção -->
                <div v-if="questao.comentarioCorrecao" class="mt-4 p-4 bg-purple-500/10 border border-purple-500/30 rounded-lg">
                  <p class="text-xs text-purple-400 uppercase font-bold mb-2">Comentário da Correção:</p>
                  <div class="text-purple-300 text-sm"
                       v-html="sanitizeHTML(questao.comentarioCorrecao)">
                  </div>
                </div>

              </div>
            </section>

            <!-- ALTERNATIVAS -->
            <section class="mb-12">
              <h4 class="text-lg font-bold text-[#e8eaf0] mb-4 uppercase tracking-wider">
                Alternativas
              </h4>

              <div class="space-y-3">
                <div
                  v-for="(alternativa, altIndex) in questao.alternativas"
                  :key="alternativa.id"
                  :class="`w-full px-6 py-4 rounded-lg border-2 transition-all duration-200
                    ${
                      alternativa.id === getAlternativaCorreta(questao)
                        ? 'bg-green-500/20 border-green-500/50'
                        : getAlternativaIndex(questao, questao.idQuestaoAlternativa) === altIndex && !isCorrect(questao)
                        ? 'bg-red-500/20 border-red-500/50'
                        : 'bg-[#111318] border-white/10'
                    }`">

                  <div class="flex items-start gap-4">
                    <!-- Letra -->
                    <div class="flex-shrink-0 w-8 h-8 rounded-md flex items-center justify-center font-bold text-white"
                         :class="{
                           'bg-green-500': alternativa.id === getAlternativaCorreta(questao),
                           'bg-red-500': getAlternativaIndex(questao, questao.idQuestaoAlternativa) === altIndex && !isCorrect(questao),
                           'bg-[#63cab7]': alternativa.id !== getAlternativaCorreta(questao) && getAlternativaIndex(questao, questao.id) !== altIndex
                         }">
                         <p v-html="sanitizeHTML(alternativeLetters[altIndex])"/>
                    </div>

                    <!-- Conteúdo -->
                    <div class="flex-1">
                      <p class="font-semibold text-base leading-relaxed"
                         :class="alternativa.id === getAlternativaCorreta(questao) ? 'text-green-400' : getAlternativaIndex(questao, questao.idQuestaoAlternativa) === altIndex && !isCorrect(questao) ? 'text-red-400' : 'text-[#e8eaf0]'">
                         <p v-html="sanitizeHTML(getAlternativaTexto(alternativa))"/>
                      </p>
                    </div>

                    <!-- Indicadores -->
                    <div class="flex-shrink-0 flex items-center gap-2">
                      <!-- Resposta correta -->
                      <div v-if="alternativa.id === getAlternativaCorreta(questao)" class="flex items-center gap-1 text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <span class="text-xs font-bold">Correta</span>
                      </div>

                      <!-- Sua resposta (errada) -->
                      <div v-else-if="getAlternativaIndex(questao, questao.idQuestaoAlternativa) === altIndex && !isCorrect(questao)" class="flex items-center gap-1 text-red-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <circle cx="12" cy="12" r="10"/>
                          <line x1="15" y1="9" x2="9" y2="15"/>
                          <line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                        <span class="text-xs font-bold">Sua resposta</span>
                      </div>
                    </div>
                  </div>

                  <!-- Comentário da alternativa -->
                  <div v-if="getComentarioAlternativa(alternativa)" class="mt-3 ml-12 p-3 bg-white/5 border border-white/10 rounded text-sm text-gray-300"
                       v-html="sanitizeHTML(getComentarioAlternativa(alternativa))">
                  </div>
                </div>
              </div>

              <!-- Resumo da questão -->
              <div v-if="isCorrect(questao)"
                   class="mt-6 px-4 py-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                <p class="text-green-400 text-sm font-semibold">
                  ✓ Você acertou! A resposta correta era {{ alternativeLetters[getAlternativaIndex(questao, getAlternativaCorreta(questao))] }}
                </p>
              </div>

              <div v-else
                   class="mt-6 px-4 py-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                <p class="text-red-400 text-sm font-semibold">
                  ✗ Você errou. Respondeu {{ getAlternativaIndex(questao, questao.idQuestaoAlternativa) >= 0 ? alternativeLetters[getAlternativaIndex(questao, questao.idQuestaoAlternativa)] : 'nada' }}, mas a correta era {{ alternativeLetters[getAlternativaIndex(questao, getAlternativaCorreta(questao))] }}
                </p>
              </div>
            </section>

            <!-- Divisor entre questões -->
            <div v-if="questionIndex < questoes.length - 1" class="my-12 border-t-2 border-dashed border-white/20"></div>
          </div>
        </div>

        <!-- BOTÃO FINAL -->
        <div class="mt-16 flex justify-center">
          <button
            @click="goBackToActivities"
            class="px-8 py-4 rounded-lg bg-gradient-to-r from-[#63cab7] to-[#5ab5a8] text-[#0a1a17] font-bold hover:shadow-lg transition-all duration-200 text-lg">
            <div class="flex items-center justify-center gap-2">
              ✓ Voltar para Atividades
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                   viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="9 18 15 12 9 6"/>
              </svg>
            </div>
          </button>
        </div>

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

:deep(table) {
  width: 100%;
  border-collapse: collapse;
  margin: 1rem 0;
}

:deep(table tr) {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

:deep(table td) {
  padding: 1rem;
  text-align: left;
  color: #e8eaf0;
}

:deep(table tr:last-child) {
  border-bottom: none;
}

:deep(p) {
  margin: 0.5rem 0;
  line-height: 1.6;
}

:deep(strong) {
  color: #63cab7;
  font-weight: 600;
}
</style>
