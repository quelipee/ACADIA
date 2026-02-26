<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import PageHeader from '../../components/layout/PageHeader.vue'

const { activities, idSalaVirtualOfertaAproveitamento } = defineProps({
  activities: {
    type: Array,
    default: () => []
  },
  idSalaVirtualOfertaAproveitamento: {
    type: [Number, String],
    required: true
  }
})

// Estado
const loading = ref(false)
const selectedActivity = ref(null)
const searchQuery = ref('')
const activeTab = ref('em-andamento') // 'em-andamento' ou 'concluidas'
const modalStep = ref('details')
const useAI = ref(null)
const selectedAI = ref(null)

// Abas
const tabs = [
  { id: 'em-andamento', label: 'Em Andamento', icon: '⟳' },
  { id: 'concluidas', label: 'Concluídas', icon: '✓' }
]

// AI Providers
const aiProviders = [
  {
    id: 'openai',
    name: 'ChatGPT',
    label: 'OpenAI',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M22.282 9.821a5.985 5.985 0 0 0-.516-4.91 6.046 6.046 0 0 0-6.51-2.9A6.065 6.065 0 0 0 4.981 4.18a5.985 5.985 0 0 0-3.998 2.9 6.046 6.046 0 0 0 .743 7.097 5.98 5.98 0 0 0 .51 4.911 6.051 6.051 0 0 0 6.515 2.9A5.985 5.985 0 0 0 13.26 24a6.056 6.056 0 0 0 5.772-4.206 5.99 5.99 0 0 0 3.997-2.9 6.056 6.056 0 0 0-.747-7.073zM13.26 22.43a4.476 4.476 0 0 1-2.876-1.04l.141-.081 4.779-2.758a.795.795 0 0 0 .392-.681v-6.737l2.02 1.168a.071.071 0 0 1 .038.052v5.583a4.504 4.504 0 0 1-4.494 4.494zM3.6 18.304a4.47 4.47 0 0 1-.535-3.014l.142.085 4.783 2.759a.771.771 0 0 0 .78 0l5.843-3.369v2.332a.08.08 0 0 1-.033.062L9.74 19.95a4.5 4.5 0 0 1-6.14-1.646zM2.34 7.896a4.485 4.485 0 0 1 2.366-1.973V11.6a.766.766 0 0 0 .388.676l5.815 3.355-2.02 1.168a.076.076 0 0 1-.071 0L4.05 13.484A4.501 4.501 0 0 1 2.34 7.896zm16.597 3.855l-5.843-3.372L15.11 7.21a.076.076 0 0 1 .071 0l4.775 2.758a4.504 4.504 0 0 1-.67 8.127v-5.678a.79.79 0 0 0-.389-.666zm2.01-3.023l-.141-.085-4.774-2.782a.776.776 0 0 0-.785 0L9.409 9.23V6.897a.066.066 0 0 1 .028-.061l4.767-2.752a4.5 4.5 0 0 1 6.693 4.66zm-12.64 4.135l-2.02-1.164a.08.08 0 0 1-.038-.057V6.075a4.5 4.5 0 0 1 7.375-3.453l-.142.08L8.704 5.46a.795.795 0 0 0-.393.681zm1.097-2.365l2.602-1.5 2.607 1.5v2.999l-2.597 1.5-2.607-1.5z"/></svg>`,
    color: '#10a37f',
    gradient: 'from-[#10a37f]/20 to-[#10a37f]/5',
    border: 'border-[#10a37f]/40',
  },
  {
    id: 'gemini',
    name: 'Gemini',
    label: 'Google',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M12 24A14.304 14.304 0 0 0 0 12 14.304 14.304 0 0 0 12 0a14.305 14.305 0 0 0 12 12 14.305 14.305 0 0 0-12 12" style="fill:#4285f4"/></svg>`,
    color: '#4285f4',
    gradient: 'from-[#4285f4]/20 to-[#4285f4]/5',
    border: 'border-[#4285f4]/40',
  },
  {
    id: 'claude',
    name: 'Claude',
    label: 'Anthropic',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M4.709 15.955l4.72-8.045 1.924 1.124-4.72 8.045zm-1.705.5l1.924-1.124 4.72 8.045-1.924 1.124zm8.999-9.169l1.924-1.124 4.72 8.045-1.924 1.124zm1.705-.5L9.988 15.831l-1.924-1.124 4.72-8.045zM12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0z"/></svg>`,
    color: '#cc785c',
    gradient: 'from-[#cc785c]/20 to-[#cc785c]/5',
    border: 'border-[#cc785c]/40',
  },
  {
    id: 'grok',
    name: 'Grok',
    label: 'xAI',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>`,
    color: '#e8eaf0',
    gradient: 'from-white/10 to-white/5',
    border: 'border-white/20',
  },
  {
    id: 'llama',
    name: 'Llama',
    label: 'Meta',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M6.597 2.938c-.286.37-.42.862-.444 1.496-.053 1.38.533 3.101 1.555 4.69l.26.397c.243-.26.503-.496.777-.707C8.013 7.84 7.61 6.1 7.647 4.793c.015-.526.108-.942.284-1.22a.63.63 0 0 1 .112-.135C7.38 3.21 6.962 3.2 6.597 2.938zm1.878-.336c-.08.072-.154.156-.218.252-.254.39-.366.91-.38 1.53-.037 1.48.48 3.393 1.561 5.048a6.044 6.044 0 0 1 1.388-.48c-1.059-1.65-1.6-3.569-1.562-5.066.01-.384.063-.706.163-.964a6.014 6.014 0 0 0-.952-.32zM4.49 4.185c.071 1.398.63 3.11 1.67 4.698a7.586 7.586 0 0 1 .777-.743c-1.007-1.534-1.558-3.228-1.625-4.71a5.97 5.97 0 0 0-.822.755zm16.014 5.824c-.68-1.618-1.846-2.689-3.109-3.282-.734 1.505-1.755 2.588-2.95 3.302 1.54.586 2.81 1.706 3.664 3.458a6.043 6.043 0 0 0 2.395-3.478zm-5.037-3.745c.008.011.017.021.025.033.97.69 1.78 1.647 2.315 2.958a6.044 6.044 0 0 0 1.03-2.394 5.99 5.99 0 0 0-3.37-.597zM12.59 2.04a5.976 5.976 0 0 0-2.432.522c.08.167.146.348.195.543.083.328.11.694.1 1.085-.02.756-.2 1.634-.523 2.493a6.046 6.046 0 0 1 1.55-.022c.286-.79.449-1.61.467-2.344.01-.393-.014-.74-.082-1.022a1.54 1.54 0 0 0-.106-.296L12.59 2.04zm-3.26.922a5.96 5.96 0 0 0-1.613.861c.046.015.09.033.133.053.44.2.806.543 1.09 1.015.129.214.24.455.333.718.304-.83.478-1.672.497-2.393.003-.1.001-.197-.004-.291a4.065 4.065 0 0 0-.436.037zM6.03 3.313a5.98 5.98 0 0 0-.966 1.452c.054 1.395.57 3.023 1.558 4.549.228-.264.471-.51.729-.737-1.004-1.534-1.527-3.229-1.32-5.264zm11.734 8.83c-.87-1.928-2.289-3.078-3.995-3.536a7.58 7.58 0 0 1-1.43.617c2.115.342 3.779 1.552 4.636 3.617a5.97 5.97 0 0 0 .789-.698zm-6.5-2.686a6.054 6.054 0 0 1-1.551.022c-1.087 1.735-2.773 3.037-4.73 3.737a6.002 6.002 0 0 0 5.4 3.752 6.017 6.017 0 0 0 2.428-.508c-1.178-1.96-1.62-4.334-.888-6.347a7.576 7.576 0 0 1-.66-.656zm-2.2.228a6.046 6.046 0 0 1-1.03 2.394c.04.049.076.1.113.15a6.007 6.007 0 0 0 2.346 1.875c-.644-1.523-.86-2.98-.8-4.193a7.567 7.567 0 0 1-.63-.226zm-2.088-1.03a7.58 7.58 0 0 1-.729.737c-.248 1.659.001 3.658.942 5.372.198-.12.39-.25.573-.388a5.97 5.97 0 0 1-.628-1.134c-.736-1.9-.653-3.36-.158-4.587zm10.09 2.447c-.844-1.882-2.349-2.966-4.19-3.333a9.22 9.22 0 0 1-.462.53c1.93.28 3.535 1.396 4.432 3.455.08-.213.155-.43.22-.652z"/></svg>`,
    color: '#0064e0',
    gradient: 'from-[#0064e0]/20 to-[#0064e0]/5',
    border: 'border-[#0064e0]/40',
  },
  {
    id: 'deepseek',
    name: 'DeepSeek',
    label: 'DeepSeek',
    icon: `<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M23.748 4.482c-.26-.12-.51.048-.668.203-.12.12-.239.192-.43.239-.144.024-.287.048-.43.048-.765-.024-1.75-.979-2.826-2.01C18.25 1.848 17.08.916 15.767.4c-1.411-.55-3.042-.55-4.69.694C9.622 2.13 8.93 3.659 8.5 5.092c-.287.144-.55.31-.79.478-.072-.084-.12-.168-.144-.264-.072-.31-.24-.55-.47-.694a.761.761 0 0 0-.612-.072c-.19.072-.334.24-.406.454l-.024.107a4.377 4.377 0 0 0-.144 1.34c.024.597.167 1.195.43 1.816-.692 1.17-1.1 2.506-1.1 3.986 0 1.887.62 3.612 1.648 5.024-.108.072-.192.144-.287.216-.43.31-.812.693-1.123 1.171a4.305 4.305 0 0 0-.621 1.578c-.12.55-.12 1.147 0 1.72a3.551 3.551 0 0 0 .621 1.34c.31.43.73.812 1.218 1.075a3.6 3.6 0 0 0 1.67.406c.548 0 1.075-.12 1.55-.358.43-.216.812-.526 1.123-.908l.095-.12c.43.162.88.287 1.338.382.62.12 1.267.192 1.91.192 1.075 0 2.1-.215 3.066-.597a9.001 9.001 0 0 0 2.601-1.72c.38-.358.716-.765 1.003-1.195.287-.43.526-.884.692-1.362.38-1.075.43-2.246.12-3.35a5.98 5.98 0 0 0-.79-1.697l.012.048c.215.55.35 1.123.406 1.72.12 1.243-.072 2.53-.597 3.707a8.167 8.167 0 0 1-2.054 2.84 8.476 8.476 0 0 1-3.01 1.745c-.55.18-1.123.287-1.72.334a7.95 7.95 0 0 1-1.768-.12 8.14 8.14 0 0 1-1.648-.502c.144-.191.263-.406.35-.62.19-.527.19-1.1 0-1.625a2.474 2.474 0 0 0-.813-1.147 5.8 5.8 0 0 1-.693-.693 6.085 6.085 0 0 1-.955-2.054 6.276 6.276 0 0 1-.12-2.317c.12-.788.406-1.553.836-2.245.43-.693.979-1.29 1.648-1.768.094-.048.167-.12.215-.215.263-.358.55-.621.86-.86.31-.215.633-.358.98-.43.31-.048.597-.024.87.096.095.048.19.12.263.191l.072.096c.024.024.024.072.048.096.024.19.096.406.263.526.19.12.406.144.598.048.191-.096.31-.287.31-.502l-.024-.12V7.01c-.024-.19.024-.43.144-.62.12-.192.311-.335.526-.406.19-.048.406-.048.597.024.19.096.358.24.454.43.12.24.12.502 0 .741-.072.168-.168.312-.287.454l-.455.478c-.31.334-.621.669-.86 1.051a3.66 3.66 0 0 0-.525 1.339c-.072.478-.048.98.072 1.459.12.478.334.932.62 1.338.263.382.597.716.98.98.382.26.812.453 1.266.55.454.095.908.095 1.362 0 .43-.096.836-.287 1.194-.55.36-.263.669-.598.908-.98.263-.382.43-.813.502-1.267.024-.214.024-.43 0-.645a3.39 3.39 0 0 0-.12-.62 4.214 4.214 0 0 0-.215-.573 4.527 4.527 0 0 0-.334-.55 2.35 2.35 0 0 1-.31-.812 2.327 2.327 0 0 1 .048-.884c.072-.287.215-.55.406-.789.19-.24.43-.43.693-.573.263-.12.55-.192.836-.192.311 0 .597.072.86.216.262.144.502.334.692.573.192.24.334.502.43.789.12.31.167.62.144.932 0 .215-.048.43-.12.645.072.024.168.024.24 0a.671.671 0 0 0 .334-.215c.096-.12.144-.263.144-.406 0-.144-.048-.287-.12-.406a.669.669 0 0 0-.31-.263.671.671 0 0 0-.406-.072.762.762 0 0 0-.383.168l-.048.048c.024-.048.072-.072.12-.12.168-.12.358-.168.55-.144.191.024.382.12.526.263.144.144.24.334.263.526a.766.766 0 0 1-.12.597c-.12.168-.287.287-.478.334a.753.753 0 0 1-.574-.048.753.753 0 0 1-.358-.43c.167.12.358.168.55.12.191-.048.358-.168.43-.334.071-.167.047-.382-.072-.526a.571.571 0 0 0-.47-.216.569.569 0 0 0-.455.24.572.572 0 0 0-.096.502c.048.167.167.31.31.382.167.072.358.072.526.024.192-.048.358-.168.478-.31.12-.167.192-.358.168-.55a.961.961 0 0 0-.334-.669.96.96 0 0 0-.717-.24.96.96 0 0 0-.668.358.959.959 0 0 0-.192.741c.048.263.192.502.406.669.216.144.478.216.74.192.263-.024.503-.12.693-.287.19-.168.31-.406.334-.645.048-.263 0-.526-.12-.765a1.718 1.718 0 0 0-.526-.597 1.747 1.747 0 0 0-.765-.287 1.77 1.77 0 0 0-.836.096c-.263.096-.502.24-.693.43a2.01 2.01 0 0 0-.454.668 2.035 2.035 0 0 0-.12.812c.024.287.12.55.263.789.168.263.382.478.645.645.263.168.55.263.86.287.31.024.62-.024.908-.144.287-.12.55-.31.741-.55.19-.24.31-.526.334-.836.024-.31-.024-.621-.144-.908a2.26 2.26 0 0 0-.502-.74 2.344 2.344 0 0 0-.764-.48 2.37 2.37 0 0 0-.908-.12c-.311.024-.598.12-.86.287-.264.168-.479.406-.622.669-.143.287-.19.597-.167.908.048.31.167.597.358.836.19.24.454.43.74.526.287.096.598.12.908.048.31-.072.598-.215.836-.43.24-.216.406-.478.502-.765.072-.287.072-.598-.024-.884a1.962 1.962 0 0 0-.454-.764 1.932 1.932 0 0 0-.741-.478 1.95 1.95 0 0 0-.884-.072c-.31.048-.597.168-.836.358-.239.19-.43.43-.55.716-.12.287-.144.597-.096.884.072.31.215.573.43.789.215.215.478.358.765.43.287.072.598.048.884-.048.287-.096.55-.263.764-.478.215-.215.358-.478.43-.765.072-.31.048-.62-.024-.908a2.365 2.365 0 0 0-.406-.788 2.354 2.354 0 0 0-.669-.597 2.38 2.38 0 0 0-.836-.287z"/></svg>`,
    color: '#5b6af0',
    gradient: 'from-[#5b6af0]/20 to-[#5b6af0]/5',
    border: 'border-[#5b6af0]/40',
  },
]

// Computadas - Separar por status
const emAndamento = computed(() => {
    const nowDate = new Date()
    return activities.filter(activity => {
        const isActive = activity.status !== 'Finalizada' && activity.status !== 'Concluída'
        const status = new Date(activity.dataInicio) <= nowDate && new Date(activity.dataFim) >= nowDate
        const tryActivity = activity.tentativa <= activity.tentativaTotal
        return tryActivity && status && isActive && activity.nameActivities.toLowerCase().includes(searchQuery.value.toLowerCase())
  })
})

const concluidas = computed(() => {
  return activities.filter(activity => {
    const isCompleted = activity.status === 'Finalizada' || activity.status === 'Concluída'
    return isCompleted && activity.nameActivities.toLowerCase().includes(searchQuery.value.toLowerCase())
  })
})

const exibindo = computed(() => {
  return activeTab.value === 'em-andamento' ? emAndamento.value : concluidas.value
})

// Cores e badges
const getStatusColor = (status) => {
  const statusMap = {
    'Finalizada': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
    'Concluída': { bg: 'bg-green-500/20', text: 'text-green-400', border: 'border-green-500/30' },
    'Pendente': { bg: 'bg-yellow-500/20', text: 'text-yellow-400', border: 'border-yellow-500/30' },
    'Em Progresso': { bg: 'bg-blue-500/20', text: 'text-blue-400', border: 'border-blue-500/30' },
    'Aguardando início': { bg: 'bg-purple-500/20', text: 'text-purple-400', border: 'border-purple-500/30' },
    'Não Iniciada': { bg: 'bg-gray-500/20', text: 'text-gray-400', border: 'border-gray-500/30' }
  }
  return statusMap[status] || statusMap['Não Iniciada']
}

const getNoteColor = (nota) => {
  if (!nota) return 'text-gray-400'
  if (nota >= 90) return 'text-green-400'
  if (nota >= 70) return 'text-yellow-400'
  if (nota >= 50) return 'text-orange-400'
  return 'text-red-400'
}

const getStatusBadge = (status) => {
  const badgeMap = {
    'Finalizada': '✓',
    'Concluída': '✓',
    'Pendente': '⏱',
    'Em Progresso': '⟳',
    'Aguardando início': '◯',
    'Não Iniciada': '◯'
  }
  return badgeMap[status] || '•'
}

// Métodos
const openActivityDetails = (activity) => {
  selectedActivity.value = activity
  modalStep.value = 'details'
  useAI.value = null
  selectedAI.value = null
}

const closeActivityDetails = () => {
  selectedActivity.value = null
  modalStep.value = 'details'
  useAI.value = null
  selectedAI.value = null
}

const goToAiChoice = () => {
  modalStep.value = 'ai-choice'
}

const chooseWithAI = () => {
  useAI.value = true
  modalStep.value = 'ai-select'
}

const chooseWithoutAI = () => {
  useAI.value = false
  confirmAccess()
}

const confirmAccess = () => {
  if (!selectedActivity.value) return

  if (!useAI.value) {
    router.get(`/answer_activity/${selectedActivity.value.id}`, {
      data: selectedActivity.value,
      idSalaVirtualOfertaAproveitamento
    })
    return
  }

  if (useAI.value && selectedAI.value) {
    loading.value = true

    router.post(`/answer_activity/${selectedAI.value.name}/${selectedActivity.value.id}`, {
      ai: selectedAI.value.id,
      data: selectedActivity.value,
      idSalaVirtualOfertaAproveitamento
    }, {
      preserveState: false,
      onSuccess: () => {
        closeActivityDetails()
      },
      onFinish: () => {
        loading.value = false
      }
    })
  }
}

const Activityattempts = (data) => {
  router.get(`/activity_attempts/${data.id}`, { data })
}

const stepBack = () => {
  if (modalStep.value === 'ai-select') modalStep.value = 'ai-choice'
  else if (modalStep.value === 'ai-choice') modalStep.value = 'details'
}

const isDisabled = computed(() => {
    if (!selectedActivity.value) return true

    const terminouTentativas = selectedActivity.value.tentativa >= selectedActivity.value.tentativaTotal

    const naoEstaAguardando = selectedActivity.value.status != `Aguardando início`

    const dataFim = new Date(selectedActivity.value.dataFim)
    const agora = new Date()

    const atividadeEncerrada = dataFim < agora

    return terminouTentativas && naoEstaAguardando || atividadeEncerrada
})
</script>

<template>
  <div class="relative min-h-screen bg-[#0a0c10] font-sans overflow-x-hidden">

    <!-- Orbs decorativas -->
    <div class="fixed w-[500px] h-[500px] bg-[radial-gradient(circle,#1a4a42,transparent)] rounded-full blur-[80px] opacity-25 -top-[200px] -left-[200px] pointer-events-none"></div>
    <div class="fixed w-[400px] h-[400px] bg-[radial-gradient(circle,#0d2d4a,transparent)] rounded-full blur-[80px] opacity-25 -bottom-[150px] -right-[150px] pointer-events-none"></div>
    <div class="fixed w-[300px] h-[300px] bg-[radial-gradient(circle,#2a1a4a,transparent)] rounded-full blur-[80px] opacity-25 top-1/2 right-[10%] pointer-events-none"></div>

    <div class="relative z-10 max-w-6xl mx-auto p-6">

      <page-header title="Atividades" subtitle="Suas atividades e avaliações"/>

      <!-- ABAS -->
      <div class="mb-8">
        <div class="flex items-center gap-2 border-b border-white/10">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'flex items-center gap-2 px-6 py-3 font-semibold text-sm uppercase tracking-wider transition-all duration-200',
              activeTab === tab.id
                ? 'text-[#63cab7] border-b-2 border-[#63cab7]'
                : 'text-gray-500 hover:text-gray-400'
            ]">
            <span>{{ tab.icon }}</span>
            <span>{{ tab.label }}</span>
            <span class="ml-2 text-xs bg-white/10 px-2 py-1 rounded-full">
              {{ activeTab === tab.id ? (tab.id === 'em-andamento' ? emAndamento.length : concluidas.length) : (tab.id === 'em-andamento' ? emAndamento.length : concluidas.length) }}
            </span>
          </button>
        </div>
      </div>

      <!-- BUSCA -->
      <section class="mb-8">
        <div class="flex-1 relative max-w-md">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar atividades..."
            class="w-full px-4 py-3 pl-10 rounded-lg bg-white/5 border border-white/10 text-[#e8eaf0] placeholder-gray-500 focus:border-[#63cab7] focus:outline-none focus:bg-white/10 transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">
            <circle cx="11" cy="11" r="8"/>
            <path d="m21 21-4.35-4.35"/>
          </svg>
        </div>
      </section>

      <!-- VAZIO -->
      <div v-if="exibindo.length === 0" class="text-center py-12">
        <p class="text-gray-400 text-lg">
          {{ activeTab === 'em-andamento' ? 'Nenhuma atividade em andamento' : 'Nenhuma atividade concluída' }}
        </p>
      </div>

      <!-- GRID DE ATIVIDADES -->
      <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <div v-for="activity in exibindo" :key="activity.id"
             @click="openActivityDetails(activity)"
             class="bg-[#111318] border border-white/10 rounded-2xl p-6 shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_16px_48px_rgba(0,0,0,0.3)] hover:border-[#63cab780] hover:shadow-[0_0_0_1px_rgba(99,202,183,0.2),0_24px_64px_rgba(99,202,183,0.08)] transition-all duration-300 cursor-pointer hover:-translate-y-1">
          <div class="flex items-start justify-between mb-4">
            <span :class="`inline-flex items-center gap-2 rounded-md text-xs font-semibold px-3 py-1 ${getStatusColor(activity.status).bg} ${getStatusColor(activity.status).text} border ${getStatusColor(activity.status).border}`">
              <span>{{ getStatusBadge(activity.status) }}</span>
              {{ activity.status }}
            </span>
            <span v-if="activity.nota" :class="`text-lg font-bold ${getNoteColor(activity.nota)}`">{{ activity.nota }}</span>
            <span v-else class="text-lg font-bold text-gray-500">—</span>
          </div>

          <h3 class="text-base font-bold text-[#e8eaf0] mb-3 line-clamp-2 min-h-[2.5rem]">{{ activity.nameActivities }}</h3>

          <div class="mb-4 pb-4 border-b border-white/10">
            <div class="flex justify-between items-center mb-2">
              <span class="text-xs text-gray-500 font-semibold uppercase">Tentativas</span>
              <span class="text-sm font-bold text-[#63cab7]">{{ activity.tentativa }}/{{ activity.tentativaTotal }}</span>
            </div>
            <div class="w-full bg-white/10 rounded-full h-2">
              <div class="bg-[#63cab7] h-2 rounded-full transition-all duration-300" :style="{ width: `${(activity.tentativa / activity.tentativaTotal) * 100}%` }"></div>
            </div>
          </div>

          <div class="space-y-2 text-xs">
            <div class="flex justify-between">
              <span class="text-gray-500">Tipo:</span>
              <span class="text-[#e8eaf0] font-semibold">{{ activity.nomeClassificacaoTipo }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Ação:</span>
              <span class="text-[#63cab7] font-semibold">{{ activity.acao }}</span>
            </div>
          </div>

          <div class="mt-4 flex justify-end">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-[#63cab7]">
              <polyline points="9 18 15 12 9 6"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL -->
    <transition name="modal">
      <div v-if="selectedActivity"
           @click="closeActivityDetails"
           class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-6">

        <div @click.stop class="relative bg-[#111318] border border-white/10 rounded-2xl shadow-[0_0_0_1px_rgba(255,255,255,0.04),0_25px_50px_rgba(0,0,0,0.5)] w-full max-w-2xl max-h-[90vh] overflow-y-auto">

          <!-- Loading -->
          <div v-if="loading"
               class="absolute inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 rounded-2xl">
            <div class="flex flex-col items-center gap-4">
              <div class="w-10 h-10 border-4 border-[#63cab7]/30 border-t-[#63cab7] rounded-full animate-spin"></div>
              <p class="text-[#63cab7] font-semibold">{{ selectedAI?.name }} Resolvendo atividade...</p>
            </div>
          </div>

          <!-- Header -->
          <div class="sticky top-0 bg-[#111318] border-b border-white/10 flex items-center justify-between p-6 z-10">
            <div class="flex items-center gap-3">
              <button
                v-if="modalStep !== 'details'"
                @click="stepBack"
                class="p-2 text-gray-400 hover:text-[#63cab7] hover:bg-white/10 rounded-lg transition-colors duration-200 -ml-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <polyline points="15 18 9 12 15 6"/>
                </svg>
              </button>
              <div>
                <h2 class="text-xl font-bold text-[#e8eaf0] leading-tight">
                  <span v-if="modalStep === 'details'">Detalhes da Atividade</span>
                  <span v-else-if="modalStep === 'ai-choice'">Acessar Atividade</span>
                  <span v-else-if="modalStep === 'ai-select'">Selecionar IA</span>
                </h2>
                <div class="flex items-center gap-1.5 mt-1">
                  <span :class="['w-1.5 h-1.5 rounded-full transition-all duration-300', modalStep === 'details' ? 'bg-[#63cab7] w-4' : 'bg-white/20']"></span>
                  <span :class="['w-1.5 h-1.5 rounded-full transition-all duration-300', modalStep === 'ai-choice' ? 'bg-[#63cab7] w-4' : 'bg-white/20']"></span>
                  <span :class="['w-1.5 h-1.5 rounded-full transition-all duration-300', modalStep === 'ai-select' ? 'bg-[#63cab7] w-4' : 'bg-white/20']"></span>
                </div>
              </div>
            </div>
            <button @click="closeActivityDetails" class="p-2 text-gray-400 hover:text-[#63cab7] hover:bg-white/10 rounded-lg transition-colors duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/>
                <line x1="6" y1="6" x2="18" y2="18"/>
              </svg>
            </button>
          </div>

          <!-- Conteúdo do Modal (igual ao original, mantendo os steps) -->
          <transition name="step">
            <div v-if="modalStep === 'details'" key="details" class="p-6 space-y-6">
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 border rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Status</p>
                  <div :class="`inline-flex items-center gap-2 rounded-md text-sm font-bold px-3 py-2 ${getStatusColor(selectedActivity.status).bg} ${getStatusColor(selectedActivity.status).text} border ${getStatusColor(selectedActivity.status).border}`">
                    {{ getStatusBadge(selectedActivity.status) }} {{ selectedActivity.status }}
                  </div>
                </div>
                <div class="bg-white/5 border rounded-lg p-4">
                  <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Nota Final</p>
                  <p v-if="selectedActivity.nota" :class="`text-3xl font-bold ${getNoteColor(selectedActivity.nota)}`">{{ selectedActivity.nota }}</p>
                  <p v-else class="text-3xl font-bold text-gray-500">—</p>
                </div>
              </div>

              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">Nome da Atividade</label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                  <p class="text-[#e8eaf0] text-base leading-relaxed">{{ selectedActivity.nameActivities }}</p>
                </div>
              </div>

              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">Disciplina</label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                  <p class="text-[#e8eaf0]">{{ selectedActivity.nameDisciplina }}</p>
                </div>
              </div>

              <div>
                <label class="text-xs text-gray-500 uppercase font-semibold tracking-wider block mb-2">Progresso de Tentativas</label>
                <div class="bg-white/5 border border-white/10 rounded-lg p-4">
                  <div class="flex justify-between items-center mb-3">
                    <p class="text-[#e8eaf0] font-bold">{{ selectedActivity.tentativa }} de {{ selectedActivity.tentativaTotal }}</p>
                    <p class="text-[#63cab7] font-bold">{{ Math.round((selectedActivity.tentativa / selectedActivity.tentativaTotal) * 100) }}%</p>
                  </div>
                  <div class="w-full bg-white/10 rounded-full h-3">
                    <div class="bg-[#63cab7] h-3 rounded-full" :style="{ width: `${(selectedActivity.tentativa / selectedActivity.tentativaTotal) * 100}%` }"></div>
                  </div>
                </div>
              </div>
<!-- LEMBRAR QUE NAO PODE ACESSAR A ATIVIDADE SE A DATA DE TERMINO DELA FOR MENOR QUE A DATA DE HOJE -->
              <div class="sticky bottom-0 bg-[#111318] border-t border-white/10 flex gap-3 p-6">

                <button @click="closeActivityDetails" class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-[#e8eaf0] hover:bg-white/10 font-bold transition-colors duration-200">
                  Fechar
                </button>

                <button
                    @click="goToAiChoice"
                    :disabled="isDisabled"
                    :class="[
                        'flex-1 px-4 py-3 rounded-lg font-bold transition-colors duration-200',
                        isDisabled
                        ? 'border border-white/10 text-gray-500 bg-white/5 cursor-not-allowed opacity-50'
                        : 'border border-[#63cab7]/50 text-[#63cab7] hover:bg-[#63cab7]/10'
                    ]">
                    Acessar Atividade
                </button>

                <button @click="Activityattempts(selectedActivity)" class="flex-1 px-4 py-3 rounded-lg bg-[#63cab7] text-[#0a1a17] hover:bg-[#5ab5a8] font-bold transition-colors duration-200">
                  Ver Tentativas
                </button>

              </div>
            </div>
          </transition>

          <!-- STEPS AI (igual ao original) -->
          <transition name="step">
            <div v-if="modalStep === 'ai-choice'" key="ai-choice" class="p-6 space-y-6">
              <!-- Conteúdo igual ao original -->
              <div class="text-center py-2">
                <h3 class="text-xl font-bold text-[#e8eaf0] mb-2">Como deseja realizar esta atividade?</h3>
                <p class="text-gray-400 text-sm">Você pode fazer de forma independente ou com ajuda de IA</p>
              </div>

              <div class="grid grid-cols-1 gap-4">
                <button @click="chooseWithAI" class="group relative w-full text-left rounded-xl border border-white/10 bg-white/5 hover:border-[#63cab7]/60 hover:bg-[#63cab7]/5 transition-all duration-200 overflow-hidden p-5">
                  <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#63cab7]/25 to-[#63cab7]/10 border border-[#63cab7]/30 flex items-center justify-center">
                      <span>🤖</span>
                    </div>
                    <div class="flex-1">
                      <p class="text-[#e8eaf0] font-bold text-base">Realizar com IA</p>
                      <p class="text-gray-400 text-sm mt-0.5">Escolha um modelo de IA para auxiliar</p>
                    </div>
                  </div>
                </button>

                <button @click="chooseWithoutAI" class="group relative w-full text-left rounded-xl border border-white/10 bg-white/5 hover:border-white/25 hover:bg-white/8 transition-all duration-200 p-5">
                  <div class="relative flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center">
                      <span>👤</span>
                    </div>
                    <div class="flex-1">
                      <p class="text-[#e8eaf0] font-bold text-base">Realizar sem IA</p>
                      <p class="text-gray-400 text-sm mt-0.5">Acesse de forma independente</p>
                    </div>
                  </div>
                </button>
              </div>

              <div class="sticky bottom-0 bg-[#111318] border-t border-white/10 p-6">
                <button @click="closeActivityDetails" class="w-full px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-[#e8eaf0] hover:bg-white/10 font-bold">
                  Cancelar
                </button>
              </div>
            </div>
          </transition>

          <!-- AI SELECT STEP (igual ao original) -->
          <transition name="step">
            <div v-if="modalStep === 'ai-select'" key="ai-select">
              <div class="p-6 space-y-5">

                <div class="text-center pb-2">
                  <h3 class="text-lg font-bold text-[#e8eaf0]">Selecione o modelo de IA</h3>
                  <p class="text-gray-400 text-sm mt-1">O modelo escolhido irá auxiliar você durante a atividade</p>
                </div>

                <!-- Grid de IAs -->
                <div class="grid grid-cols-2 gap-3">
                  <button
                    v-for="ai in aiProviders"
                    :key="ai.id"
                    @click="selectedAI = ai"
                    :class="[
                      'group relative text-left rounded-xl border transition-all duration-200 overflow-hidden p-4',
                      selectedAI?.id === ai.id
                        ? `bg-gradient-to-br ${ai.gradient} ${ai.border} shadow-lg`
                        : `bg-white/5 border-white/10 hover:${ai.border} hover:bg-white/8`
                    ]">
                    <!-- Selected indicator -->
                    <div v-if="selectedAI?.id === ai.id" class="absolute top-3 right-3 w-5 h-5 rounded-full flex items-center justify-center" :style="{ backgroundColor: ai.color }">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                        <polyline points="20 6 9 17 4 12"/>
                      </svg>
                    </div>

                    <div class="flex flex-col gap-3">
                      <!-- Icon -->
                      <div
                        class="w-11 h-11 rounded-lg flex items-center justify-center transition-transform duration-200 group-hover:scale-105"
                        :style="{ backgroundColor: ai.color + '22', border: `1px solid ${ai.color}44` }">
                        <div :style="{ color: ai.color }" v-html="ai.icon"></div>
                      </div>
                      <!-- Info -->
                      <div>
                        <p class="text-[#e8eaf0] font-bold text-sm">{{ ai.name }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">{{ ai.label }}</p>
                      </div>
                    </div>
                  </button>
                </div>

                <!-- Preview da seleção -->
                <transition name="fade">
                  <div v-if="selectedAI" class="rounded-xl border p-4 flex items-center gap-3" :style="{ backgroundColor: selectedAI.color + '11', borderColor: selectedAI.color + '44' }">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :style="{ backgroundColor: selectedAI.color + '22' }">
                      <div :style="{ color: selectedAI.color, transform: 'scale(0.75)' }" v-html="selectedAI.icon"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-sm font-semibold" :style="{ color: selectedAI.color }">{{ selectedAI.name }} selecionado</p>
                      <p class="text-gray-400 text-xs truncate">Pronto para auxiliar em "{{ selectedActivity.nameActivities }}"</p>
                    </div>
                  </div>
                </transition>

              </div>

              <!-- Footer step AI select -->
              <div class="sticky bottom-0 bg-[#111318] border-t border-white/10 flex gap-3 p-6">
                <button @click="stepBack" class="flex-1 px-4 py-3 rounded-lg bg-white/5 border border-white/10 text-[#e8eaf0] hover:bg-white/10 font-bold transition-colors duration-200">
                  Voltar
                </button>
                <button
                  @click="confirmAccess"
                  :disabled="!selectedAI || loading"
                  :class="[
                    'flex-1 px-4 py-3 rounded-lg font-bold transition-all duration-200',
                    selectedAI && !loading
                      ? 'bg-[#63cab7] text-[#0a1a17] hover:bg-[#5ab5a8]'
                      : 'bg-white/5 border border-white/10 text-gray-500 cursor-not-allowed'
                  ]">
                  Confirmar e Acessar
                </button>
              </div>
            </div>
          </transition>

        </div>
      </div>
    </transition>

  </div>
</template>

<style scoped>
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

.step-enter-active, .step-leave-active {
  transition: all 0.2s ease;
}
.step-enter-from {
  opacity: 0;
  transform: translateX(16px);
}
.step-leave-to {
  opacity: 0;
  transform: translateX(-16px);
}
</style>
