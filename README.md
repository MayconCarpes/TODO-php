# Gerenciador de Tarefas Acadêmicas - Full Stack

## Descrição do Projeto
Este é um sistema completo e integrado de Gerenciamento de Tarefas Acadêmicas. O projeto evoluiu para uma arquitetura moderna Full Stack, conectando uma interface gráfica rica e reativa no Front-end com um servidor Back-end conteinerizado operando com persistência real em banco de dados.

A aplicação opera de **ponta a ponta**, onde o Front-end consome dinamicamente a API RESTful do PHP para operações de CRUD (Criar, Ler, Atualizar, Excluir) de usuários e tarefas.

## Tecnologias Utilizadas
- **Front-end:** Next.js (React), Tailwind CSS e Lucide Icons.
- **Back-end API:** PHP 8.2 (MVC) com PDO.
- **Banco de Dados:** MySQL 8.0.
- **Infraestrutura:** Docker e Docker Compose.

## Estrutura Simplificada
```text
projeto/
├── app/                  # API e Lógica de negócio no Back-end (PHP)
├── database/             # Scripts de criação (init.sql) do MySQL
├── frontend/             # Interface do usuário (SPA construída em Next.js)
├── docker-compose.yml    # Orquestração do Back-end, Banco e phpMyAdmin
└── README.md             # Esta documentação
```

## Como Executar o Sistema

Para subir o projeto localmente, você deve levantar o servidor Back-end e, em seguida, iniciar o Front-end.

### 1. Iniciar o Back-end e o Banco de Dados (Docker)
Na raiz do projeto (onde está este arquivo), execute o comando do Docker para inicializar o PHP e o MySQL:
```bash
docker-compose up -d
```
> O Banco de Dados já será criado e populado automaticamente!

### 2. Iniciar o Front-end (Next.js)
Abra um novo terminal, acesse a pasta do Front-end e inicie o servidor React:
```bash
cd frontend
npm install   # (Apenas na primeira vez)
npm run dev
```

### 3. Acessar
- **Aplicação (Front-end):** [http://localhost:3000](http://localhost:3000)
- **Painel do Banco (phpMyAdmin):** [http://localhost:8081](http://localhost:8081)


*(Para o painel do phpMyAdmin, você pode acessar usando usuário `root` e senha `root`).*
