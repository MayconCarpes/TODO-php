<?php
session_start();

// Configurações de CORS globais
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Responder preflight OPTIONS imediatamente
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

spl_autoload_register(function ($class_name) {
    if (file_exists(__DIR__ . '/controllers/' . $class_name . '.php')) {
        require_once __DIR__ . '/controllers/' . $class_name . '.php';
    } elseif (file_exists(__DIR__ . '/models/' . $class_name . '.php')) {
        require_once __DIR__ . '/models/' . $class_name . '.php';
    } elseif (file_exists(__DIR__ . '/config/' . $class_name . '.php')) {
        require_once __DIR__ . '/config/' . $class_name . '.php';
    }
});

// A rota será ex: ?c=tarefa&a=index
$controllerName = isset($_GET['c']) ? ucfirst($_GET['c']) . 'Controller' : 'AuthController';
$action = isset($_GET['a']) ? $_GET['a'] : 'index';

// Se usuário está logado e tenta a página index do login, vai pro dashboard
if (isset($_SESSION['usuario_id']) && $controllerName === 'AuthController' && $action === 'index') {
    if ($_SESSION['usuario_perfil'] === 'ADMIN') {
        $controllerName = 'AdminController';
    } else {
        $controllerName = 'TarefaController';
    }
}

if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Erro: Ação '$action' não encontrada em '$controllerName'!");
    }
} else {
    die("Erro: Controlador '$controllerName' não encontrado!");
}
