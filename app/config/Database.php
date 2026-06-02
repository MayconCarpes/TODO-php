<?php
class Database {
    private static $pdo = null;

    public static function getConnection() {
        if (self::$pdo === null) {
            $host = getenv('MYSQL_HOST') ?: 'db';
            $db   = getenv('MYSQL_DATABASE') ?: 'tarefas_db';
            $user = getenv('MYSQL_USER') ?: 'tarefas_user';
            $pass = getenv('MYSQL_PASSWORD') ?: 'senha123';
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>
