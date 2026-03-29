CREATE TABLE IF NOT EXISTS status (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS prioridade (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS usuario (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nome TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    senha TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS tarefa (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titulo TEXT NOT NULL,
    descricao TEXT,
    disciplina TEXT,
    data_criacao TEXT NOT NULL DEFAULT (DATE('now')),
    data_entrega TEXT,
    usuario_id INTEGER NOT NULL,
    prioridade_id INTEGER NOT NULL,
    status_id INTEGER NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE,
    FOREIGN KEY (prioridade_id) REFERENCES prioridade(id),
    FOREIGN KEY (status_id) REFERENCES status(id)
);

INSERT INTO status (nome) VALUES
    ('Pendente'),
    ('Em andamento'),
    ('Concluída');

INSERT INTO prioridade (nome) VALUES
    ('Baixa'),
    ('Média'),
    ('Alta'),
    ('Urgente');
