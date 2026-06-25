# Gerenciador de Tarefas Acadêmicas - Full Stack

## Descrição do Projeto
Este é um sistema completo e integrado de Gerenciamento de Tarefas Acadêmicas. O projeto atende integralmente aos requisitos da disciplina, implementando uma arquitetura **Full Stack moderna com Next.js** (tanto Front-end quanto Back-end via Route Handlers), conectando a interface gráfica rica com uma base de dados relacional **SQLite** gerenciada nativamente pelo **Prisma ORM**.

A aplicação opera de **ponta a ponta**, consumindo dinamicamente as APIs protegidas e construídas dentro do próprio Next.js para as operações de CRUD (Criar, Ler, Atualizar, Excluir) de usuários e tarefas. Conta ainda com segurança na criptografia de senhas usando `bcryptjs` (evitando injeção e falhas de segurança básicas) e perfis distintos (Aluno e Administrador).

## Tecnologias Utilizadas
- **Front-end:** Next.js (React 18), Tailwind CSS e Lucide Icons (SPA fluida e responsiva).
- **Back-end API:** Next.js Route Handlers (Serverless API Functions).
- **Banco de Dados:** SQLite local (`dev.db`).
- **ORM:** Prisma ORM.

## Estrutura Simplificada
```text
projeto/
├── frontend/             # Raiz do projeto
│   ├── prisma/           # Configuração do ORM e Schema do banco (schema.prisma)
│   ├── src/app/api/      # Back-end: Endpoints protegidos (Login, CRUD e Relatórios)
│   ├── src/app/          # Front-end: Telas, painéis e páginas visuais
│   └── dev.db            # Banco de Dados gerado automaticamente
└── README.md             # Esta documentação técnica
```

## Como Instalar, Configurar e Executar o Sistema

Para subir o projeto localmente, basta garantir que você possui o [Node.js](https://nodejs.org) instalado (versão 18+ recomendada) e seguir os passos abaixo na raiz do projeto (`frontend`):

### 1. Instalar as Dependências
Abra o seu terminal na pasta `frontend` e rode o comando:
```bash
cd frontend
npm install
```

### 2. Configurar e Criar o Banco de Dados
Ainda no terminal, execute o comando do Prisma para sincronizar os modelos de dados e gerar o arquivo de banco local (`dev.db`):
```bash
npx prisma db push
```

### 3. Iniciar o Servidor (Front-end e Back-end integrados)
Inicie o ambiente de desenvolvimento local com:
```bash
npm run dev
```

### 4. Acessar o Sistema
Abra no seu navegador: [http://localhost:3000](http://localhost:3000)

**Dicas de Teste:**
1. Clique em "Cadastrar" para criar um novo usuário com perfil `ALUNO`.
2. Para testar o **Painel de Administrador (Relatórios)**, crie uma conta cujo perfil seja salvo como `ADMIN` no banco de dados. *(Se preferir testar as funcionalidades exclusivas, crie o usuário e altere diretamente a coluna perfil no arquivo SQLite)*.
