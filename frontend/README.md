# TaskFlow - Front-end (Next.js)

Este é o front-end do nosso Gerenciador de Tarefas Acadêmicas, construído como parte da entrega da disciplina de Desenvolvimento de Sistemas Web. 
Ele utiliza Next.js (React) e Tailwind CSS para fornecer uma interface moderna, rápida e responsiva.

Nesta etapa, os dados são simulados (mocks) e não há conexão com um back-end real.

## Pré-requisitos

Você precisará do [Node.js](https://nodejs.org/pt-br) instalado na sua máquina.

## Como rodar o projeto

1. Abra o terminal nesta pasta (`frontend`).
2. Instale as dependências executando:
   ```bash
   npm install
   ```
3. Inicie o servidor de desenvolvimento:
   ```bash
   npm run dev
   ```
4. Abra o navegador e acesse [http://localhost:3000](http://localhost:3000).

## Credenciais para Teste (Mock)

Como o sistema usa dados simulados, você pode usar as seguintes credenciais para testar o login sem precisar se cadastrar:

- **E-mail:** `admin@admin.com`
- **Senha:** `admin123`

## Funcionalidades Implementadas

- **Autenticação Simulada:** Login e Cadastro com validações simples em tela.
- **Dashboard (Kanban):** Visualização das tarefas divididas por status (Pendente, Em Andamento, Concluída) com design premium.
- **Operações:** Criação, edição, exclusão e alteração de status da tarefa com reflexo imediato na interface (sem recarregar a página).
- **Responsividade:** Todas as telas e o menu lateral (Sidebar) adaptam-se para uso em dispositivos móveis.

## Tecnologias

- [Next.js](https://nextjs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Lucide Icons](https://lucide.dev/)
