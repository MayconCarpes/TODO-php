# Roteiro de Apresentação: Gerenciador de Tarefas Acadêmicas

Este documento foi criado para guiar você durante a sua apresentação. Siga os tópicos abaixo para explicar ao professor e à turma como a arquitetura do projeto evoluiu e como cada peça funciona.

---

## 1. Introdução e Propósito do Projeto
**O que falar:** 
"Olá, pessoal! Nosso projeto é um **Gerenciador de Tarefas Acadêmicas**. O objetivo dele é permitir que alunos organizem seus estudos, adicionando tarefas, definindo prazos e acompanhando o progresso. A grande sacada do nosso projeto é que ele não é apenas um front-end bonitinho; ele é uma aplicação **Full Stack real**, com banco de dados, segurança de senhas e painel de administração."

## 2. A Nova Arquitetura (A "Virada de Chave")
**O que falar:**
"No escopo original, nós cogitamos usar PHP com Docker. Porém, para tornar o projeto mais moderno, rápido e unificado, decidimos migrar tudo para **Next.js**.
- **O Front-end** foi construído com Next.js (React) e Tailwind CSS, garantindo uma interface extremamente fluida (Single Page Application) e responsiva.
- **O Back-end** não está em um servidor separado. Nós construímos as APIs dentro do próprio Next.js usando o recurso de *Route Handlers*. Isso significa que o servidor e o cliente moram no mesmo ecossistema, o que facilita muito o deploy e a manutenção."

## 3. O Banco de Dados (Prisma + SQLite)
**O que falar:**
"Para armazenar os dados de verdade (sem usar mocks), nós adotamos o **SQLite**.
Para conversar com esse banco de dados sem precisar escrever SQL na mão, nós utilizamos o **Prisma ORM**. 
Isso traz duas vantagens enormes:
1. **Segurança:** O Prisma previne automaticamente ataques de *SQL Injection*.
2. **Tipagem:** Nós temos garantia de que os dados (como Nome, E-mail, Status da Tarefa) estão sempre no formato correto."

*(Mostre rapidamente o arquivo `schema.prisma` se quiser ganhar pontos extras)*

## 4. Segurança e Senhas (Criptografia)
**O que falar:**
"Uma preocupação que tivemos foi não salvar senhas em texto puro no banco de dados. Para isso, utilizamos a biblioteca `bcryptjs`. Quando um usuário se cadastra, a senha dele passa por um *hash* e é salva de forma ilegível. Quando ele faz o login, o sistema compara os hashes. Se o banco vazar, as senhas continuam seguras."

## 5. Demonstração Prática: O CRUD do Aluno
**O que mostrar na tela:**
1. Crie uma conta nova (mostre a validação).
2. Faça o login.
3. No Dashboard, crie uma tarefa (Preencha Título, Descrição, Disciplina, Data).
4. Mostre a tarefa aparecendo na lista.
5. Edite a tarefa (Mude o status de Pendente para Em Andamento).
6. Exclua uma tarefa.

**O que falar:**
"Como vocês podem ver, todas as operações de CRUD estão funcionando perfeitamente, sendo salvas e lidas do nosso banco de dados em tempo real."

## 6. O "Superpoder": O Painel de Administração
**O que mostrar na tela:**
1. Faça o *logoff* da conta do aluno.
2. Faça login com a conta de administrador (`admin@admin.com` / `123`).
3. Mostre o botão "Painel Admin" que acabou de aparecer (explique que ele estava oculto para alunos comuns).
4. Entre no Painel e mostre as 3 abas.

**O que falar:**
"Nós fomos além e criamos um controle de permissões rígido. O sistema identifica quem é `ALUNO` e quem é `ADMIN`. 
O Administrador tem acesso a um painel exclusivo com três superpoderes:
1. **Visão Geral (Relatórios):** Um resumo de quantas pessoas usam o sistema e como está o andamento das tarefas globais.
2. **Gerenciamento de Usuários:** Aqui o Admin faz o CRUD de pessoas. Ele pode editar perfis ou até excluir uma conta do banco de dados.
3. **Controle de Tarefas Globais:** O Admin consegue ver as tarefas de **todos os alunos** e tem permissão para alterar os status ou apagá-las caso necessário."

---

## 7. Encerramento
**O que falar:**
"Concluindo, nós entregamos um produto finalizado que atende a todos os requisitos solicitados: Back-end blindado, Front-end moderno, Banco de Dados real, segurança e diferentes níveis de permissões. É um sistema pronto para ir para o mundo real!"
