CREATE DATABASE IF NOT EXISTS `tarefas_db`;
USE `tarefas_db`;

CREATE TABLE IF NOT EXISTS status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS prioridade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    perfil VARCHAR(50) NOT NULL DEFAULT 'ALUNO'
);

CREATE TABLE IF NOT EXISTS tarefa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    disciplina VARCHAR(255),
    data_criacao DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_entrega DATETIME,
    usuario_id INT NOT NULL,
    prioridade_id INT NOT NULL,
    status_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (prioridade_id) REFERENCES prioridade(id),
    FOREIGN KEY (status_id) REFERENCES status(id)
);

INSERT INTO status (nome) VALUES ('Pendente'), ('Em andamento'), ('Concluída');
INSERT INTO prioridade (nome) VALUES ('Baixa'), ('Média'), ('Alta'), ('Urgente');

-- Senha padrão do admin é admin123 hash bcrypt
INSERT INTO usuario (nome, email, senha, perfil) 
VALUES ('Administrador do Sistema', 'admin@admin.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'ADMIN');
