<?php
class Usuario {
    public static function autenticar($email, $senha) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    public static function emailExiste($email, $ignorarId = null) {
        $db = Database::getConnection();
        $sql = "SELECT id FROM usuario WHERE email = :email";
        $params = [':email' => $email];
        if ($ignorarId) {
            $sql .= " AND id != :id";
            $params[':id'] = $ignorarId;
        }
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() !== false;
    }

    public static function cadastrar($nome, $email, $senha, $perfil = 'ALUNO') {
        $db = Database::getConnection();
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO usuario (nome, email, senha, perfil) VALUES (:nome, :email, :senha, :perfil)");
        return $stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $hash, ':perfil' => $perfil]);
    }

    public static function atualizar($id, $nome, $email, $senha = null, $perfil = null) {
        $db = Database::getConnection();
        $sql = "UPDATE usuario SET nome = :nome, email = :email";
        $params = [':nome' => $nome, ':email' => $email, ':id' => $id];
        
        if (!empty($senha)) {
            $sql .= ", senha = :senha";
            $params[':senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
        if (!empty($perfil)) {
            $sql .= ", perfil = :perfil";
            $params[':perfil'] = $perfil;
        }
        
        $sql .= " WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function listar() {
        $db = Database::getConnection();
        return $db->query("SELECT id, nome, email, perfil FROM usuario")->fetchAll();
    }

    public static function buscarPorId($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, nome, email, perfil FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public static function excluir($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
