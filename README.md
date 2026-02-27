# ACADIA 🎓

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-4FC08D?style=flat&logo=vue.js&logoColor=white)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net)

Plataforma inteligente para apoio acadêmico, capaz de responder atividades e provas da faculdade por meio de Inteligência Artificial.

## 📋 Sobre

ACADIA é uma solução inovadora que utiliza IA para auxiliar estudantes no processo de aprendizado acadêmico. A plataforma integra múltiplos provedores de IA (ChatGPT, Gemini, Claude, Grok, Llama, DeepSeek) para resolver questões objetivas e discursivas de forma inteligente e adaptada.

### 🎯 Objetivo

Fornecer uma plataforma web intuitiva e poderosa que permita aos alunos:
- 📝 Responder atividades com ou sem assistência de IA
- 🧪 Realizar provas com suporte inteligente
- 📊 Visualizar gabaritos e desempenho
- 🎓 Acompanhar progresso em disciplinas

## ✨ Features Principais

### 🤖 Inteligência Artificial
- **Multi-Provider:** Suporta ChatGPT, Gemini, Claude, Grok, Llama, DeepSeek
- **Resolução Automática:** Responde questões objetivas e discursivas
- **Análise de Conteúdo:** Processa tabelas, imagens e texto formatado
- **Feedback Inteligente:** Fornece explicações e comentários

### 📚 Gerenciamento Acadêmico
- **Disciplinas:** Visualize e gerencie suas matérias por status
- **Atividades:** Acesse APOLs, Provas e Atividades Mistas
- **Gabarito Interativo:** Revise respostas com navegação fluida
- **Histórico de Tentativas:** Acompanhe todas as suas submissões

### 🎨 Interface Moderna
- **Dark Theme:** Design elegante e confortável para os olhos
- **Responsivo:** Funciona em desktop, tablet e mobile
- **Componentes Intuitivos:** Modais, abas, filtros e buscas
- **Animações Suaves:** Transições e efeitos visuais refinados

### 📊 Dashboard Completo
- **Resumo de Desempenho:** Estatísticas de acertos e erros
- **Abas Organizadas:** Atividades em andamento vs concluídas
- **Filtros Avançados:** Busca por disciplina ou tipo de atividade
- **Status Real-time:** Indicadores de progresso

## 🛠️ Stack Técnico

### Backend
- **Framework:** Laravel 12+
- **Linguagem:** PHP 8.3+
- **API:** RESTful com JSON
- **Integrações IA:** OpenAI, Google Gemini, Anthropic Claude, xAI, Meta Llama, DeepSeek

### Frontend
- **Framework:** Vue.js 3
- **Build Tool:** Vite
- **CSS:** Tailwind CSS
- **HTTP Client:** Inertia.js
- **UI Components:** Custom + Lucide Icons
- **State Management:** Vue Composition API

### Ferramentas
- **Versionamento:** Git
- **Testing:** PHPUnit

## 🚀 Começando

### Pré-requisitos

- PHP 8.1+
- Composer
- Node.js 16+
- npm ou yarn
- Git

### Instalação

#### 1. Clone o Repositório
```bash
git clone https://github.com/quelipee/ACADIA.git
cd ACADIA
```

#### 2. Setup Backend
```bash
# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

```

#### 3. Setup Frontend
```bash
# Instalar dependências
npm install

# Executar servidor de desenvolvimento
npm run dev
```

#### 4. install 
```bash
# install inertiajs
composer require inertiajs/inertia-laravel   
```

#### 5. Configurar IAs
```bash
# Adicionar suas chaves de API no .env
OPENAI_API_KEY=your_key
GEMINI_API_KEY=your_key
# ... outras IAs
```

#### 6. Iniciar Aplicação
```bash
# Terminal 1: Backend
php artisan serve

# Terminal 2: Frontend (se não estiver em watch mode)
npm run build
```

Acesse a aplicação em `http://localhost:8000`

## 💻 Como Usar

### 1. Autenticação
```bash
# Faça login com suas credenciais
# A plataforma utiliza autenticação via api da faculdade uninter
```

### 2. Acessar Disciplinas
```
Menu Principal → Matérias
├── Em Andamento
└── Concluídas
```

### 3. Realizar Atividade
```
Matérias → Selecionar Disciplina → Tipo de Atividade
├── APOL (Atividade Objetiva)
├── PROVAS (Discursiva)
└── MISTA (Combinada)
```

### 4. Usar IA para Resolver
```
Durante a Atividade:
├── Com IA → Selecionar Modelo (ChatGPT, Claude, etc)
└── Sem IA → Responder manualmente
```

### 5. Ver Gabarito
```
Atividade Concluída → Gabarito
├── Visualizar todas as questões
├── Revisar respostas
└── Ver feedback da IA
```

## 🔌 API Endpoints Principais

### Autenticação
```http
POST   /login-faculdade         # Fazer login
POST   /logout                  # Fazer logout
GET    /profile                 # Dados do usuário
```

### Disciplinas
```http
GET    /api/subjects            # Listar disciplinas
GET    /api/subjects/{id}       # Detalhes da disciplina
```

### Atividades
```http
GET    /activities/{id}/{idSalaVirtual}/{type}          # Listar atividades
GET    /activity_attempts/{cId}                         # Lista de tentativa da atividade
POST   /activities/{activity}/complete                  # Submeter respostas
GET    /answer_key/{idTry}                              # Ver gabarito
```

### IA
```http
POST   /answer_activity/{ai}/{cID}          # Resolver questão com IA
```


## 🤝 Contribuindo

Contribuições são bem-vindas! Para contribuir:

1. **Fork** o projeto
2. **Crie uma branch** para sua feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. **Push** para a branch (`git push origin feature/AmazingFeature`)
5. **Abra um Pull Request**

## 📚 Documentação

Para documentação mais detalhada, consulte:
- [Guia de Instalação](./docs/INSTALL.md)
- [API Documentation](./docs/API.md)
- [Architecture Guide](./docs/ARCHITECTURE.md)
- [Contributing Guide](./CONTRIBUTING.md)

## 📞 Suporte

- **Issues:** [GitHub Issues](https://github.com/quelipee/ACADIA/issues)
- **Email:** felipemateusfks97@gmail.com

## 👨‍💻 Autor

**Quelipe** - [@quelipee](https://github.com/quelipee)

## 📈 Status do Projeto

```
Version: 1.0.0
Status: 🟢 Estável em Produção
Last Updated: 2026-02-27
```

---

<div align="center">

[⬆ Voltar ao Topo](#acadia-)

</div>
