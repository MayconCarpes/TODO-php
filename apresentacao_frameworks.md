# Slide 1: Migração para Frameworks - Sistema de Controle de Tarefas
- **Disciplina/Projeto:** Desenvolvimento de Sistemas Web
- **Integrantes do Grupo:** Maykon, Cauã Fortes, Gabriel Marcelini, Guilherme Leoni
- **Data da Entrega:** 08/05/2026

---

# Slide 2: A Escolha da Estratégia
**Opção Escolhida:** Framework Full Stack
**Framework Selecionado:** Next.js (Baseado em React)

**Por que um Framework Full Stack?**
- Permite construir tanto a interface (Front-end) quanto a lógica de servidor e banco de dados (Back-end) em um único projeto unificado.
- Unificação da linguagem: usaremos **JavaScript/TypeScript** de ponta a ponta, eliminando a troca de contexto entre PHP e JS.
- O ecossistema React/Next.js é, atualmente, a stack tecnológica mais requisitada no mercado de trabalho de desenvolvimento web.

---

# Slide 3: Por que o Next.js? (Justificativa)
- **Performance Extrema:** Renderização híbrida (SSR - Server Side Rendering e Server Components) entrega as páginas muito mais rápido do que a abordagem tradicional.
- **Ecossistema e Mercado:** É o framework React mais poderoso e adotado do mundo, com documentação rica e mantido pela Vercel.
- **Roteamento Inteligente:** O sistema de rotas (App Router) funciona de forma automatizada baseando-se na organização das pastas, bem mais avançado que nosso roteamento manual no PHP.
- **Experiência de Uso (UX):** Transições entre páginas e ações ocorrem de forma fluida e sem recarregar a página (comportamento de Single Page Application).

---

# Slide 4: Principais Melhorias em Relação ao PHP "Puro"
- **Adeus ao PHP Estrutural:** Deixamos para trás o uso de páginas monolíticas e includes para adotar uma **Componentização** real. Criaremos componentes React isolados e reutilizáveis (ex: botão, card de tarefa).
- **Sem Recarregamento de Página:** Ao adicionar ou mudar o status de uma tarefa, o sistema atualizará a interface instantaneamente sem piscar a tela.
- **Banco de Dados Moderno:** Em vez de queries SQL manuais via `PDO`, passaremos a usar o **Prisma ORM** para nos comunicarmos com o banco de forma orientada a objetos.
- **Estado Dinâmico:** Gerenciamento de estado complexo na interface torna-se natural, coisa que seria muito difícil usando apenas PHP e HTML puro.

---

# Slide 5: Recursos Específicos que Serão Utilizados (Backend & Banco)
- **Server Actions e Route Handlers (APIs):** Irão substituir os nossos antigos `Controllers` do PHP. Toda a lógica para criar tarefas ou salvar dados ocorrerá no lado do servidor com segurança total.
- **Prisma ORM:** Vai substituir o nosso antigo arquivo estático `database.sql`. O Prisma fará as **Migrations** (geração automática das tabelas) e facilitará absurdamente as consultas ao banco de dados SQLite.
- **Auth.js (NextAuth):** Biblioteca que utilizaremos para implementar a autenticação. Substituirá nosso sistema frágil de `$_SESSION` manual por um fluxo de login robusto para Alunos e Admin.

---

# Slide 6: Recursos Específicos que Serão Utilizados (Frontend)
- **React Server Components (RSC):** Usaremos para renderizar as listagens pesadas de tarefas diretamente no servidor, entregando HTML puro ao usuário e poupando uso de internet/processamento.
- **Tailwind CSS:** Continuaremos utilizando o Tailwind para estilizar, pois ele tem integração nativa, automática e altamente otimizada com o Next.js.
- **Hooks do React (`useState`, `useEffect`):** Usaremos para criar interações ricas no lado do cliente, como modais de adição de tarefas, filtros de busca instantânea ou botões de mudança de status (A Fazer -> Concluído).

---

# Slide 7: Conclusão
A adoção do **Next.js** transformará nosso Gerenciador de Tarefas:
- Saímos de uma aplicação tradicional (clicar e esperar a página toda carregar) para uma aplicação web **moderna, rápida e reativa**.
- Ganharemos produtividade com o uso de **componentes reutilizáveis** e integração com o banco via **Prisma**.
- Garantimos que o grupo adote **tecnologias líderes absolutas no mercado atual**, valorizando o currículo de todos.
