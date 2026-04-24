<?php
$isLoggedIn = isset($_SESSION['usuario_id']);
$isAdmin = isset($_SESSION['usuario_perfil']) && $_SESSION['usuario_perfil'] === 'ADMIN';
$userName = $_SESSION['usuario_nome'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gerenciador de Tarefas MVC' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark-glass {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-blue-50 min-h-screen text-gray-800 antialiased flex flex-col">

    <?php if ($isLoggedIn): ?>
    <nav class="glass sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        <span class="ml-2 font-bold text-xl tracking-tight text-gray-900">TaskSys</span>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="index.php?c=tarefa&a=index" class="border-transparent text-gray-500 hover:border-blue-500 hover:text-blue-700 transition inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">Minhas Tarefas</a>
                        
                        <?php if ($isAdmin): ?>
                        <a href="index.php?c=admin&a=index" class="border-transparent text-gray-500 hover:border-purple-500 hover:text-purple-700 transition inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            Administração
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm font-medium text-gray-600">Olá, <?= htmlspecialchars($userName) ?> <span class="text-xs bg-<?= $isAdmin ? 'purple' : 'gray' ?>-100 text-<?= $isAdmin ? 'purple' : 'gray' ?>-800 px-2 py-0.5 rounded-full ml-1"><?= $isAdmin ? 'Admin' : 'Aluno' ?></span></span>
                    <a href="index.php?c=auth&a=logout" class="text-sm border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 font-medium px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        Sair
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">&copy; <?= date('Y') ?> Gerenciador de Tarefas MVC - Trabalho de Desenvolvimento Web.</p>
        </div>
    </footer>
</body>
</html>
