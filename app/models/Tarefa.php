<?php
class Tarefa {
    public static function listarPorUsuario($usuario_id, $filtros = []) {
        $db = Database::getConnection();
        $sql = "SELECT t.*, s.nome as status_nome, p.nome as prioridade_nome 
                FROM tarefa t
                JOIN status s ON t.status_id = s.id
                JOIN prioridade p ON t.prioridade_id = p.id
                WHERE t.usuario_id = :usuario_id";
        
        $params = [':usuario_id' => $usuario_id];

        if (!empty($filtros['status_id'])) {
            $sql .= " AND t.status_id = :status_id";
            $params[':status_id'] = $filtros['status_id'];
        }
        if (!empty($filtros['prioridade_id'])) {
            $sql .= " AND t.prioridade_id = :prioridade_id";
            $params[':prioridade_id'] = $filtros['prioridade_id'];
        }
        
        $sql .= " ORDER BY t.data_entrega ASC, t.prioridade_id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function buscarPorId($id, $usuario_id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tarefa WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
        return $stmt->fetch();
    }

    public static function cadastrar($titulo, $descricao, $disciplina, $data_entrega, $usuario_id, $prioridade_id, $status_id) {
        $db = Database::getConnection();
        $sql = "INSERT INTO tarefa (titulo, descricao, disciplina, data_entrega, usuario_id, prioridade_id, status_id) 
                VALUES (:titulo, :descricao, :disciplina, :data_entrega, :usuario_id, :prioridade_id, :status_id)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':disciplina' => $disciplina,
            ':data_entrega' => $data_entrega,
            ':usuario_id' => $usuario_id,
            ':prioridade_id' => $prioridade_id,
            ':status_id' => $status_id
        ]);
    }

    public static function atualizar($id, $titulo, $descricao, $disciplina, $data_entrega, $prioridade_id, $status_id, $usuario_id) {
        $db = Database::getConnection();
        $sql = "UPDATE tarefa SET 
                titulo = :titulo, 
                descricao = :descricao, 
                disciplina = :disciplina, 
                data_entrega = :data_entrega, 
                prioridade_id = :prioridade_id, 
                status_id = :status_id
                WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':disciplina' => $disciplina,
            ':data_entrega' => $data_entrega,
            ':prioridade_id' => $prioridade_id,
            ':status_id' => $status_id,
            ':id' => $id,
            ':usuario_id' => $usuario_id
        ]);
    }

    public static function excluir($id, $usuario_id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM tarefa WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    }

    public static function concluir($id, $usuario_id) {
        $db = Database::getConnection();
        // O id do status "Concluída" costuma ser 3, ou pegamos pelo nome
        $stmt = $db->prepare("UPDATE tarefa SET status_id = (SELECT id FROM status WHERE nome = 'Concluída' LIMIT 1) WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    }
}
