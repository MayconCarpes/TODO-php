<?php
$db_file = __DIR__ . '/database.sqlite';
$is_first_run = !file_exists($db_file);

try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $pdo->exec('PRAGMA foreign_keys = ON;');

    if ($is_first_run) {
        $sql = <<<SQL
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

        INSERT INTO status (nome) VALUES ('Pendente'), ('Em andamento'), ('Concluída');
        INSERT INTO prioridade (nome) VALUES ('Baixa'), ('Média'), ('Alta'), ('Urgente');
SQL;
        $pdo->exec($sql);
    }
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
