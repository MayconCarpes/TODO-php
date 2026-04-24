<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        ob_start();
        require_once __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();
        
        // Se for uma view de erro isolado ou algo parecido, podemos verificar,
        // mas vamos abraçar todos com o layout.
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function checkAuth() {
        if (!isset($_SESSION['usuario_id'])) {
            $this->redirect('index.php');
        }
    }

    protected function checkAdmin() {
        $this->checkAuth();
        if ($_SESSION['usuario_perfil'] !== 'ADMIN') {
            die("<div style='padding:20px; color:red; font-family:sans-serif;'>Acesso negado. Apenas administradores podem acessar esta área. <a href='index.php'>Voltar</a></div>");
        }
    }
}
