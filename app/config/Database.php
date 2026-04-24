<?php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $db_file = __DIR__ . '/../../database.sqlite';
            $is_first_run = !file_exists($db_file);

            try {
                self::$pdo = new PDO('sqlite:' . $db_file);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$pdo->exec('PRAGMA foreign_keys = ON;');

                if ($is_first_run) {
                    self::initDatabase();
                } else {
                    self::checkMigrations();
                }
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function checkMigrations() {
        // Verifica se a nova coluna 'perfil' existe (Migration para a nova versão MVC)
        $stmt = self::$pdo->query("PRAGMA table_info(usuario)");
        $hasPerfil = false;
        foreach ($stmt as $row) {
            if ($row['name'] === 'perfil') $hasPerfil = true;
        }

        if (!$hasPerfil) {
            self::$pdo->exec("ALTER TABLE usuario ADD COLUMN perfil TEXT NOT NULL DEFAULT 'ALUNO'");
            // Adiciona administrador padrão caso a tabela receba a atualização agora
            $senha_admin = password_hash('admin123', PASSWORD_DEFAULT);
            self::$pdo->exec("INSERT INTO usuario (nome, email, senha, perfil) VALUES ('Administrador Sistema', 'admin@admin.com', '$senha_admin', 'ADMIN')");
        }
    }

    private static function initDatabase() {
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
            senha TEXT NOT NULL,
            perfil TEXT NOT NULL DEFAULT 'ALUNO'
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
        self::$pdo->exec($sql);
        
        $senha_admin = password_hash('admin123', PASSWORD_DEFAULT);
        self::$pdo->exec("INSERT INTO usuario (nome, email, senha, perfil) VALUES ('Administrador do Sistema', 'admin@admin.com', '$senha_admin', 'ADMIN')");
    }
}
?>
