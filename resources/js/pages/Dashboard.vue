<script setup>
import { ref, computed } from 'vue'

const { graduation } = defineProps({
  graduation: {
    type: Array,
    default: () => []
  }
})

const menuOpen = ref(false)

// Pegar o primeiro curso como principal (se existir)
const mainGraduation = computed(() => {
  return graduation.length > 0 ? graduation[0] : null
})

const userInitial = computed(() => {
  return mainGraduation.value?.nome?.charAt(0).toUpperCase() || 'U'
})
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
              <h1 class="text-2xl font-extrabold text-[#e8eaf0]">Dashboard</h1>
              <p class="text-sm text-gray-500">Suas informações acadêmicas</p>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-11 h-11
                        bg-gradient-to-br from-[#63cab7] to-[#4ab3a0]
                        text-[#0a1a17] rounded-xl font-bold">
              {{ userInitial }}
            </div>

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
          </div>

        </div>
      </header>

      <!-- Se não tiver dados -->
      <div v-if="!mainGraduation" class="text-center py-12">
        <p class="text-gray-400">Nenhuma graduação encontrada</p>
      </div>

      <!-- Se tiver dados -->
      <div v-else>
        <!-- LISTA DE TODOS OS CURSOS (com v-for) -->
        <section v-if="graduation.length > 1" class="mt-12">
          <h2 class="text-2xl font-bold text-[#e8eaf0] mb-6">
            Meus Cursos ({{ graduation.length }})
          </h2>

          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- V-FOR: iterar sobre cada graduação -->
            <div v-for="(course, index) in graduation" :key="index"
                 class="bg-[#111318] border border-white/10 rounded-2xl p-6
                        shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)]
                        hover:border-[#63cab780]
                        hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)]
                        transition-all duration-300 cursor-pointer
                        hover:-translate-y-1">

              <!-- Badge de tipo -->
              <div class="mb-4 flex justify-between items-start">
                <span class="bg-[#63cab71f] text-[#63cab7] px-3 py-1
                             rounded-md text-xs font-semibold">
                  {{ course.nomeCursoNivel }}
                </span>
                <span v-if="index === 0" class="bg-green-500/20 text-green-400 px-2 py-1
                                                 rounded-md text-xs font-semibold">
                  Principal
                </span>
              </div>

              <!-- Nome do curso -->
              <h3 class="text-base font-bold text-[#e8eaf0] mb-4 line-clamp-2">
                {{ course.nome }}
              </h3>

              <!-- Detalhes -->
              <div class="flex flex-col gap-2 text-sm">
                <div class="flex justify-between">
                  <span class="text-gray-500">ID Curso:</span>
                  <span class="text-[#63cab7] font-semibold">{{ course.idCurso }}</span>
                </div>

                <div class="flex justify-between">
                  <span class="text-gray-500">ID Usuário:</span>
                  <span class="text-[#e8eaf0]">{{ course.idUsuarioCurso }}</span>
                </div>

                <div class="grid grid-cols-[auto_1fr] gap-x-4 items-start">
                    <div class="text-gray-500">Turma:</div>
                    <div class="text-[#e8eaf0] text-right max-w-full truncate" :title="course.turma">
                        {{ course.turma }}
                    </div>
                </div>



                <div class="pt-2 border-t border-white/10">
                  <span class="text-gray-500 text-xs">Código:</span>
                  <p class="text-[#e8eaf0] text-xs break-all mt-1">
                    {{ course.cIdCurso }}
                  </p>
                </div>
              </div>

            </div>
          </div>
        </section>

      </div>

    </div>
  </div>
</template>
