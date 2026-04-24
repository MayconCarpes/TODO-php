<?php
class Status {
    public static function listar() {
        $db = Database::getConnection();
        return $db->query("SELECT * FROM status")->fetchAll();
    }

    public static function buscarPorId($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM status WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function cadastrar($nome) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO status (nome) VALUES (:nome)");
        return $stmt->execute([':nome' => $nome]);
    }

    public static function atualizar($id, $nome) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE status SET nome = :nome WHERE id = :id");
        return $stmt->execute([':nome' => $nome, ':id' => $id]);
    }

    public static function excluir($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM status WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
