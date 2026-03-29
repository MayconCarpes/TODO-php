<?php
session_start();
require 'db.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!empty($nome) && !empty($email) && !empty($senha)) {
        $stmt_check = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
        $stmt_check->execute([':email' => $email]);
        
        if ($stmt_check->fetch()) {
            $erro = "Este e-mail já está cadastrado.";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)");
            
            if ($stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $hash])) {
                $sucesso = "Cadastro realizado com sucesso! Você já pode fazer login.";
            } else {
                $erro = "Erro ao cadastrar usuário.";
            }
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Gerenciador de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Criar Nova Conta</h2>
            <p class="text-gray-500 mt-1 text-sm">Organize suas atividades acadêmicas</p>
        </div>
        
        <?php if ($erro): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($erro) ?></span>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($sucesso) ?></span>
            </div>
        <?php endif; ?>

        <form action="cadastro.php" method="POST">
            <div class="mb-4">
                <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome Completo</label>
                <input type="text" name="nome" id="nome" required 
                       class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-mail Universitário ou Pessoal</label>
                <input type="email" name="email" id="email" required 
                       class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
            </div>
            
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                <input type="password" name="senha" id="senha" required minlength="6"
                       class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-300">
                    Cadastrar
                </button>
            </div>
        </form>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">Já tem uma conta? <a href="index.php" class="text-blue-600 hover:text-blue-800 font-semibold transition duration-200">Faça login</a></p>
        </div>
    </div>

</body>
</html>
