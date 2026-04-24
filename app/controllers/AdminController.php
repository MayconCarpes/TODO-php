<?php
class AdminController extends Controller {
    public function __construct() {
        $this->checkAdmin();
    }

    public function index() {
        $this->render('admin/index');
    }

    // --- CRUD USUÁRIOS ---
    public function usuarios() {
        $usuarios = Usuario::listar();
        $this->render('admin/usuarios/index', ['usuarios' => $usuarios]);
    }
    
    public function usuario_form() {
        $id = $_GET['id'] ?? null;
        $usuario = null;
        $erro = '';

        if ($id) {
            $usuario = Usuario::buscarPorId($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $perfil = trim($_POST['perfil'] ?? 'ALUNO');

            if (Usuario::emailExiste($email, $id)) {
                $erro = 'E-mail já está em uso por outro usuário.';
            } else {
                if ($id) {
                    Usuario::atualizar($id, $nome, $email, $senha, $perfil);
                } else {
                    Usuario::cadastrar($nome, $email, $senha, $perfil);
                }
                $this->redirect('index.php?c=admin&a=usuarios');
            }
        }

        $this->render('admin/usuarios/form', ['usuario' => $usuario, 'erro' => $erro]);
    }

    public function usuario_excluir() {
        $id = $_GET['id'] ?? null;
        if ($id && $id != $_SESSION['usuario_id']) { // Não pode excluir a si mesmo
            Usuario::excluir($id);
        }
        $this->redirect('index.php?c=admin&a=usuarios');
    }

    // --- CRUD STATUS ---
    public function status() {
        $status = Status::listar();
        $this->render('admin/status/index', ['status' => $status]);
    }

    public function status_form() {
        $id = $_GET['id'] ?? null;
        $status = null;

        if ($id) {
            $status = Status::buscarPorId($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            if ($id) {
                Status::atualizar($id, $nome);
            } else {
                Status::cadastrar($nome);
            }
            $this->redirect('index.php?c=admin&a=status');
        }

        $this->render('admin/status/form', ['status' => $status]);
    }

    public function status_excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Status::excluir($id);
        }
        $this->redirect('index.php?c=admin&a=status');
    }

    // --- CRUD PRIORIDADES ---
    public function prioridades() {
        $prioridades = Prioridade::listar();
        $this->render('admin/prioridades/index', ['prioridades' => $prioridades]);
    }

    public function prioridade_form() {
        $id = $_GET['id'] ?? null;
        $prioridade = null;

        if ($id) {
            $prioridade = Prioridade::buscarPorId($id);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            if ($id) {
                Prioridade::atualizar($id, $nome);
            } else {
                Prioridade::cadastrar($nome);
            }
            $this->redirect('index.php?c=admin&a=prioridades');
        }

        $this->render('admin/prioridades/form', ['prioridade' => $prioridade]);
    }

    public function prioridade_excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Prioridade::excluir($id);
        }
        $this->redirect('index.php?c=admin&a=prioridades');
    }
}
