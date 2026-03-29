<?php
session_start();
require 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

$filtro_status = $_GET['status'] ?? '';
$filtro_prioridade = $_GET['prioridade'] ?? '';
$filtro_disciplina = trim($_GET['disciplina'] ?? '');


$ordem = $_GET['ordem'] ?? 'data_asc';


$query = "
    SELECT t.*, 
           s.nome as status_nome, 
           p.nome as prioridade_nome 
    FROM tarefa t
    JOIN status s ON t.status_id = s.id
    JOIN prioridade p ON t.prioridade_id = p.id
    WHERE t.usuario_id = :usuario_id
";
$params = [':usuario_id' => $usuario_id];

if ($filtro_status !== '') {
    $query .= " AND t.status_id = :status_id";
    $params[':status_id'] = $filtro_status;
}

if ($filtro_prioridade !== '') {
    $query .= " AND t.prioridade_id = :prioridade_id";
    $params[':prioridade_id'] = $filtro_prioridade;
}

if ($filtro_disciplina !== '') {
    $query .= " AND t.disciplina LIKE :disciplina";
    $params[':disciplina'] = "%$filtro_disciplina%";
}

if ($ordem === 'data_asc') {
    $query .= " ORDER BY t.data_entrega ASC, t.id DESC";
} elseif ($ordem === 'data_desc') {
    $query .= " ORDER BY t.data_entrega DESC, t.id DESC";
} elseif ($ordem === 'prio_desc') {
    $query .= " ORDER BY t.prioridade_id DESC, t.data_entrega ASC";
} elseif ($ordem === 'prio_asc') {
    $query .= " ORDER BY t.prioridade_id ASC, t.data_entrega ASC";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tarefas = $stmt->fetchAll();

$status_list = $pdo->query("SELECT * FROM status")->fetchAll();
$prioridade_list = $pdo->query("SELECT * FROM prioridade")->fetchAll();

// Função helper para cores do Tailwind
function getCorPrioridade($id)
{
    switch ($id) {
        case 1:
            return 'text-green-700 bg-green-100 border-green-300 border-t-green-500'; // Baixa
        case 2:
            return 'text-yellow-700 bg-yellow-100 border-yellow-300 border-t-yellow-500'; // Média
        case 3:
            return 'text-orange-700 bg-orange-100 border-orange-300 border-t-orange-500'; // Alta
        case 4:
            return 'text-red-700 bg-red-100 border-red-300 border-t-red-500'; // Urgente
        default:
            return 'text-gray-700 bg-gray-100 border-gray-300 border-t-gray-500';
    }
}

function getCorStatus($id)
{
    switch ($id) {
        case 1:
            return 'bg-gray-200 text-gray-800'; // Pendente
        case 2:
            return 'bg-blue-200 text-blue-800'; // Em andamento
        case 3:
            return 'bg-green-200 text-green-800'; // Concluída
        default:
            return 'bg-gray-200 text-gray-800';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gerenciador de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    <nav class="bg-blue-600 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="text-white font-bold text-xl flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Tarefas
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-blue-100 text-sm">Olá,
                        <strong><?= htmlspecialchars($usuario_nome) ?></strong></span>
                    <a href="logout.php"
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-4 rounded text-sm transition duration-200">Sair</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Minhas Tarefas</h1>
            <a href="tarefa_form.php"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2 transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nova Tarefa
            </a>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border border-gray-100 mb-8">
            <form action="dashboard.php" method="GET"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="disciplina">Disciplina</label>
                    <input type="text" name="disciplina" id="disciplina"
                        value="<?= htmlspecialchars($filtro_disciplina) ?>" placeholder="Buscar..."
                        class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="status">Status</label>
                    <select name="status" id="status"
                        class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Todos</option>
                        <?php foreach ($status_list as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= $filtro_status == $s['id'] ? 'selected' : '' ?>>
                                <?= $s['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="prioridade">Prioridade</label>
                    <select name="prioridade" id="prioridade"
                        class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Todas</option>
                        <?php foreach ($prioridade_list as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $filtro_prioridade == $p['id'] ? 'selected' : '' ?>>
                                <?= $p['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="ordem">Ordenar por</label>
                    <select name="ordem" id="ordem"
                        class="shadow-sm border rounded w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-sm">
                        <option value="data_asc" <?= $ordem == 'data_asc' ? 'selected' : '' ?>>Entrega (Crescente)</option>
                        <option value="data_desc" <?= $ordem == 'data_desc' ? 'selected' : '' ?>>Entrega (Decrescente)
                        </option>
                        <option value="prio_desc" <?= $ordem == 'prio_desc' ? 'selected' : '' ?>>Prioridade (Maior)
                        </option>
                        <option value="prio_asc" <?= $ordem == 'prio_asc' ? 'selected' : '' ?>>Prioridade (Menor)</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded transition duration-200 flex justify-center items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        Filtrar
                    </button>
                    <?php if ($filtro_status !== '' || $filtro_prioridade !== '' || $filtro_disciplina !== '' || $ordem !== 'data_asc'): ?>
                        <a href="dashboard.php"
                            class="bg-gray-100 hover:bg-red-100 text-gray-500 hover:text-red-600 font-bold py-2 px-3 rounded transition duration-200 flex justify-center items-center"
                            title="Limpar Filtros">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <?php if (count($tarefas) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($tarefas as $t):
                    // Extrair classes para cor da borda superior dinamicada pela helper php
                    $borderClasses = explode(' ', getCorPrioridade($t['prioridade_id']));
                    $borderColor = $borderClasses[3] ?? 'border-t-gray-500';
                    ?>
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-100 hover:shadow-md transition duration-200 flex flex-col overflow-hidden border-t-4 <?= $borderColor ?>">
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start mb-3 block">
                                <span
                                    class="text-xs font-semibold px-2.5 py-1 rounded-full <?= getCorStatus($t['status_id']) ?>">
                                    <?= htmlspecialchars($t['status_nome']) ?>
                                </span>
                                <span
                                    class="text-xs font-bold px-2 py-1 rounded border <?= getCorPrioridade($t['prioridade_id']) ?>">
                                    <?= htmlspecialchars($t['prioridade_nome']) ?>
                                </span>
                            </div>

                            <h3
                                class="text-lg font-bold text-gray-800 mt-2 mb-1 break-words <?= $t['status_id'] == 3 ? 'line-through text-gray-400' : '' ?>">
                                <?= htmlspecialchars($t['titulo']) ?></h3>

                            <?php if (!empty($t['disciplina'])): ?>
                                <p
                                    class="text-xs font-semibold text-blue-600 mb-3 flex items-center gap-1 uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    <?= htmlspecialchars($t['disciplina']) ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($t['descricao'])): ?>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?= nl2br(htmlspecialchars($t['descricao'])) ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div
                            class="bg-gray-50 p-4 flex flex-col sm:flex-row justify-between items-center border-t border-gray-100 gap-4 sm:gap-0">
                            <div
                                class="flex items-center text-sm text-gray-500 gap-1.5 w-full sm:w-auto justify-center sm:justify-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor"
                                    class="w-4 h-4 <?php echo (strtotime($t['data_entrega']) < time() && $t['status_id'] != 3) ? 'text-red-500' : 'text-gray-400'; ?>">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span
                                    class="<?php echo (strtotime($t['data_entrega']) < time() && $t['status_id'] != 3) ? 'text-red-600 font-bold' : ''; ?>">
                                    <?= $t['data_entrega'] ? date('d/m/Y', strtotime($t['data_entrega'])) : 'Sem prazo' ?>
                                </span>
                            </div>

                            <div class="flex justify-end gap-2 w-full sm:w-auto">
                                <?php if ($t['status_id'] != 3): ?>
                                    <form action="tarefa_concluir.php" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                        <button type="submit" title="Marcar como Concluída"
                                            class="p-2 bg-green-100 text-green-700 hover:bg-green-600 hover:text-white rounded transition duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                                class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                    d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="tarefa_form.php?id=<?= $t['id'] ?>" title="Editar Tarefa"
                                    class="p-2 bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white rounded transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-5 h-5">
                                        <path
                                            d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                                        <path
                                            d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                                    </svg>
                                </a>
                                <form action="tarefa_excluir.php" method="POST" class="inline"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                    <button type="submit" title="Excluir Tarefa"
                                        class="p-2 bg-red-100 text-red-700 hover:bg-red-600 hover:text-white rounded transition duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                            class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-lg shadow-sm border border-gray-100 mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-20 h-20 mx-auto text-gray-300 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Nenhuma tarefa encontrada</h3>
                <p class="text-gray-500 mb-6 font-medium">Você ainda não tem tarefas cadastradas com os critérios
                    selecionados.</p>
                <a href="tarefa_form.php"
                    class="inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow transition duration-200 items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Criar Primeira Tarefa
                </a>
            </div>
        <?php endif; ?>

    </main>

</body>

</html>