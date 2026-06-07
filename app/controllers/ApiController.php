<?php
class ApiController {
    
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    private function getJsonInput() {
        return json_decode(file_get_contents('php://input'), true);
    }

    public function login() {
        $data = $this->getJsonInput();
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            $this->jsonResponse(['error' => 'Email e senha são obrigatórios'], 400);
        }

        $usuario = Usuario::autenticar($email, $senha);
        if ($usuario) {
            unset($usuario['senha']);
            $this->jsonResponse($usuario);
        } else {
            $this->jsonResponse(['error' => 'Credenciais inválidas'], 401);
        }
    }

    public function register() {
        $data = $this->getJsonInput();
        $nome = $data['nome'] ?? '';
        $email = $data['email'] ?? '';
        $senha = $data['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            $this->jsonResponse(['error' => 'Preencha todos os campos'], 400);
        }

        if (Usuario::emailExiste($email)) {
            $this->jsonResponse(['error' => 'E-mail já está em uso'], 400);
        }

        if (Usuario::cadastrar($nome, $email, $senha, 'ALUNO')) {
            // Buscar o usuario recém criado para retornar o ID
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id, nome, email, perfil FROM usuario WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['error' => 'Erro ao cadastrar usuário'], 500);
        }
    }

    public function getTasks() {
        $usuario_id = $_GET['usuario_id'] ?? null;
        if (!$usuario_id) {
            $this->jsonResponse(['error' => 'usuario_id não fornecido'], 400);
        }

        $tarefas = Tarefa::listarPorUsuario($usuario_id);
        
        // Mapear os campos status_nome e prioridade_nome para o front-end
        $result = array_map(function($t) {
            $t['status'] = $t['status_nome'];
            $t['prioridade'] = $t['prioridade_nome'];
            unset($t['status_nome'], $t['prioridade_nome']);
            return $t;
        }, $tarefas);

        $this->jsonResponse($result);
    }

    private function buscarIdPorNome($tabela, $nome) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id FROM {$tabela} WHERE nome = :nome LIMIT 1");
        $stmt->execute([':nome' => $nome]);
        $row = $stmt->fetch();
        return $row ? $row['id'] : null;
    }

    public function createTask() {
        $data = $this->getJsonInput();
        $titulo = $data['titulo'] ?? '';
        $descricao = $data['descricao'] ?? '';
        $disciplina = $data['disciplina'] ?? '';
        $data_entrega = $data['data_entrega'] ?? '';
        $usuario_id = $data['usuario_id'] ?? null;
        $prioridade_nome = $data['prioridade'] ?? 'Baixa';
        $status_nome = $data['status'] ?? 'Pendente';

        if (empty($titulo) || !$usuario_id) {
            $this->jsonResponse(['error' => 'Título e usuario_id são obrigatórios'], 400);
        }

        $prioridade_id = $this->buscarIdPorNome('prioridade', $prioridade_nome) ?: 1;
        $status_id = $this->buscarIdPorNome('status', $status_nome) ?: 1;

        // Se a data de entrega for vazia, definimos null
        if (empty($data_entrega)) $data_entrega = null;

        if (Tarefa::cadastrar($titulo, $descricao, $disciplina, $data_entrega, $usuario_id, $prioridade_id, $status_id)) {
            $this->jsonResponse(['message' => 'Tarefa criada com sucesso']);
        } else {
            $this->jsonResponse(['error' => 'Erro ao criar tarefa'], 500);
        }
    }

    public function updateTask() {
        $id = $_GET['id'] ?? null;
        $data = $this->getJsonInput();
        $usuario_id = $data['usuario_id'] ?? null;

        if (!$id || !$usuario_id) {
            $this->jsonResponse(['error' => 'id da tarefa e usuario_id são obrigatórios'], 400);
        }

        // Buscar dados antigos para preencher o que não foi enviado
        $tarefaAntiga = Tarefa::buscarPorId($id, $usuario_id);
        if (!$tarefaAntiga) {
            $this->jsonResponse(['error' => 'Tarefa não encontrada'], 404);
        }

        $titulo = $data['titulo'] ?? $tarefaAntiga['titulo'];
        $descricao = isset($data['descricao']) ? $data['descricao'] : $tarefaAntiga['descricao'];
        $disciplina = isset($data['disciplina']) ? $data['disciplina'] : $tarefaAntiga['disciplina'];
        $data_entrega = isset($data['data_entrega']) ? $data['data_entrega'] : $tarefaAntiga['data_entrega'];
        
        $prioridade_id = $tarefaAntiga['prioridade_id'];
        if (isset($data['prioridade'])) {
            $prioridade_id = $this->buscarIdPorNome('prioridade', $data['prioridade']) ?: $prioridade_id;
        }

        $status_id = $tarefaAntiga['status_id'];
        if (isset($data['status'])) {
            $status_id = $this->buscarIdPorNome('status', $data['status']) ?: $status_id;
        }

        if (empty($data_entrega)) $data_entrega = null;

        if (Tarefa::atualizar($id, $titulo, $descricao, $disciplina, $data_entrega, $prioridade_id, $status_id, $usuario_id)) {
            $this->jsonResponse(['message' => 'Tarefa atualizada com sucesso']);
        } else {
            $this->jsonResponse(['error' => 'Erro ao atualizar tarefa'], 500);
        }
    }

    public function deleteTask() {
        $id = $_GET['id'] ?? null;
        $usuario_id = $_GET['usuario_id'] ?? null;

        if (!$id || !$usuario_id) {
            $this->jsonResponse(['error' => 'id e usuario_id são obrigatórios'], 400);
        }

        if (Tarefa::excluir($id, $usuario_id)) {
            $this->jsonResponse(['message' => 'Tarefa deletada']);
        } else {
            $this->jsonResponse(['error' => 'Erro ao deletar tarefa'], 500);
        }
    }
}
