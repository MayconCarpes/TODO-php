<?php
class AuthController extends Controller {
    public function index() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (!empty($email) && !empty($senha)) {
                $usuario = Usuario::autenticar($email, $senha);
                if ($usuario) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_perfil'] = $usuario['perfil'];

                    if ($usuario['perfil'] === 'ADMIN') {
                        $this->redirect('index.php?c=admin&a=index');
                    } else {
                        $this->redirect('index.php?c=tarefa&a=index');
                    }
                } else {
                    $this->render('auth/login', ['erro' => 'E-mail ou senha inválidos.']);
                    return;
                }
            } else {
                $this->render('auth/login', ['erro' => 'Preencha todos os campos.']);
                return;
            }
        }
        $this->render('auth/login');
    }

    public function cadastro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');

            if (!empty($nome) && !empty($email) && !empty($senha)) {
                if (Usuario::emailExiste($email)) {
                    $this->render('auth/cadastro', ['erro' => 'Este e-mail já está cadastrado.']);
                    return;
                }

                if (Usuario::cadastrar($nome, $email, $senha)) {
                    $this->render('auth/cadastro', ['sucesso' => 'Cadastro realizado com sucesso! Você já pode fazer login.']);
                    return;
                } else {
                    $this->render('auth/cadastro', ['erro' => 'Erro ao cadastrar usuário.']);
                    return;
                }
            } else {
                $this->render('auth/cadastro', ['erro' => 'Preencha todos os campos.']);
                return;
            }
        }
        $this->render('auth/cadastro');
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('index.php');
    }
}
