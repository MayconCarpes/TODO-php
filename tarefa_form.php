<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];
$id = $_GET['id'] ?? null;
$erro = '';

$tarefa = [
    'titulo' => '',
    'descricao' => '',
    'disciplina' => '',
    'data_entrega' => '',
    'prioridade_id' => 1,
    'status_id' => 1
];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM tarefa WHERE id = :id AND usuario_id = :usuario_id");
    $stmt->execute([':id' => $id, ':usuario_id' => $usuario_id]);
    $tarefa_encontrada = $stmt->fetch();
    
    if (!$tarefa_encontrada) {
        header("Location: dashboard.php");
        exit;
    }
    $tarefa = $tarefa_encontrada;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $disciplina = trim($_POST['disciplina']);
    $data_entrega = empty($_POST['data_entrega']) ? null : $_POST['data_entrega'];
    $prioridade_id = $_POST['prioridade_id'];
    $status_id = $_POST['status_id'];

    if (empty($titulo) || empty($prioridade_id) || empty($status_id)) {
        $erro = "Os campos Título, Prioridade e Status são obrigatórios.";
    } else {
        if ($id) {
            $stmt = $pdo->prepare("
                UPDATE tarefa SET 
                    titulo = :titulo, descricao = :descricao, disciplina = :disciplina,
                    data_entrega = :data_entrega, prioridade_id = :prioridade_id, status_id = :status_id
                WHERE id = :id AND usuario_id = :usuario_id
            ");
            $sucesso = $stmt->execute([
                ':titulo' => $titulo, ':descricao' => $descricao, ':disciplina' => $disciplina,
                ':data_entrega' => $data_entrega, ':prioridade_id' => $prioridade_id, ':status_id' => $status_id,
                ':id' => $id, ':usuario_id' => $usuario_id
            ]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO tarefa (titulo, descricao, disciplina, data_entrega, usuario_id, prioridade_id, status_id)
                VALUES (:titulo, :descricao, :disciplina, :data_entrega, :usuario_id, :prioridade_id, :status_id)
            ");
            $sucesso = $stmt->execute([
                ':titulo' => $titulo, ':descricao' => $descricao, ':disciplina' => $disciplina,
                ':data_entrega' => $data_entrega, ':usuario_id' => $usuario_id, 
                ':prioridade_id' => $prioridade_id, ':status_id' => $status_id
            ]);
        }
        
        if ($sucesso) {
            header("Location: dashboard.php");
            exit;
        } else {
            $erro = "Erro ao salvar a tarefa no banco de dados.";
        }
    }
    
    $tarefa = [
        'titulo' => $titulo, 'descricao' => $descricao, 'disciplina' => $disciplina,
        'data_entrega' => $data_entrega, 'prioridade_id' => $prioridade_id, 'status_id' => $status_id
    ];
}

$status_list = $pdo->query("SELECT * FROM status")->fetchAll();
$prioridade_list = $pdo->query("SELECT * FROM prioridade")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editar Tarefa' : 'Nova Tarefa' ?> - Gerenciador</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <nav class="bg-blue-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="dashboard.php" class="text-white font-bold text-xl flex items-center gap-2 hover:text-blue-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-8">
        
        <div class="bg-white p-8 rounded-lg shadow-md border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-b pb-4">
                <?php if ($id): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                        <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                    </svg>
                    Editar Tarefa
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                    </svg>
                    Adicionar Nova Tarefa
                <?php endif; ?>
            </h1>

            <?php if ($erro): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($erro) ?></span>
                </div>
            <?php endif; ?>

            <form action="tarefa_form.php<?= $id ? '?id=' . $id : '' ?>" method="POST">
                
                <div class="mb-4">
                    <label for="titulo" class="block text-gray-700 text-sm font-bold mb-2">Título da Tarefa *</label>
                    <input type="text" name="titulo" id="titulo" required value="<?= htmlspecialchars($tarefa['titulo']) ?>" placeholder="Ex: Estudar Cálculo Diferencial"
                           class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="disciplina" class="block text-gray-700 text-sm font-bold mb-2">Disciplina (Opcional)</label>
                        <input type="text" name="disciplina" id="disciplina" value="<?= htmlspecialchars($tarefa['disciplina']) ?>" placeholder="Ex: Matemática"
                               class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>
                    <div>
                        <label for="data_entrega" class="block text-gray-700 text-sm font-bold mb-2">Data de Entrega (Opcional)</label>
                        <input type="date" name="data_entrega" id="data_entrega" value="<?= htmlspecialchars($tarefa['data_entrega']) ?>"
                               class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                    </div>
                </div>

                <div class="mb-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="prioridade_id" class="block text-gray-700 text-sm font-bold mb-2">Prioridade *</label>
                        <select name="prioridade_id" id="prioridade_id" required class="shadow border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <?php foreach($prioridade_list as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= $tarefa['prioridade_id'] == $p['id'] ? 'selected' : '' ?>><?= $p['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="status_id" class="block text-gray-700 text-sm font-bold mb-2">Status *</label>
                        <select name="status_id" id="status_id" required class="shadow border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <?php foreach($status_list as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= $tarefa['status_id'] == $s['id'] ? 'selected' : '' ?>><?= $s['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição (Opcional)</label>
                    <textarea name="descricao" id="descricao" rows="4" placeholder="Detalhes da tarefa..."
                              class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200"><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="dashboard.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded transition duration-200 focus:outline-none">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded shadow focus:outline-none transition duration-200">
                        <?= $id ? 'Salvar Alterações' : 'Criar Tarefa' ?>
                    </button>
                </div>

            </form>
        </div>

    </main>

</body>
</html>
