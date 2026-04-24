<?php
class TarefaController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $filtros = [
            'status_id' => $_GET['status_id'] ?? '',
            'prioridade_id' => $_GET['prioridade_id'] ?? ''
        ];
        
        $tarefas = Tarefa::listarPorUsuario($_SESSION['usuario_id'], $filtros);
        $status_list = Status::listar();
        $prioridades = Prioridade::listar();
        
        $this->render('tarefas/index', [
            'tarefas' => $tarefas,
            'status_list' => $status_list,
            'prioridades' => $prioridades,
            'filtros' => $filtros
        ]);
    }

    public function form() {
        $id = $_GET['id'] ?? null;
        $tarefa = null;
        
        if ($id) {
            $tarefa = Tarefa::buscarPorId($id, $_SESSION['usuario_id']);
            if (!$tarefa) {
                die("Tarefa não encontrada.");
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $descricao = $_POST['descricao'] ?? '';
            $disciplina = $_POST['disciplina'] ?? '';
            $data_entrega = $_POST['data_entrega'] ?? '';
            $prioridade_id = $_POST['prioridade_id'] ?? '';
            $status_id = $_POST['status_id'] ?? '';

            if ($id) {
                Tarefa::atualizar($id, $titulo, $descricao, $disciplina, $data_entrega, $prioridade_id, $status_id, $_SESSION['usuario_id']);
            } else {
                Tarefa::cadastrar($titulo, $descricao, $disciplina, $data_entrega, $_SESSION['usuario_id'], $prioridade_id, $status_id);
            }
            $this->redirect('index.php?c=tarefa&a=index');
        }

        $status_list = Status::listar();
        $prioridades = Prioridade::listar();
        
        $this->render('tarefas/form', [
            'tarefa' => $tarefa,
            'status_list' => $status_list,
            'prioridades' => $prioridades
        ]);
    }

    public function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Tarefa::excluir($id, $_SESSION['usuario_id']);
        }
        $this->redirect('index.php?c=tarefa&a=index');
    }

    public function concluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Tarefa::concluir($id, $_SESSION['usuario_id']);
        }
        $this->redirect('index.php?c=tarefa&a=index');
    }
}
