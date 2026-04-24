<div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Painel de Tarefas</h1>
        <p class="text-gray-500 mt-1">Gerencie suas atividades acadêmicas e prazos importantes.</p>
    </div>
    <a href="index.php?c=tarefa&a=form" class="bg-blue-600 hover:bg-blue-700 text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all px-5 py-2.5 rounded-xl font-medium inline-flex items-center gap-2">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Nova Tarefa
    </a>
</div>

<!-- Filtros -->
<div class="glass p-5 rounded-xl border border-gray-200 shadow-sm mb-8">
    <form method="GET" action="index.php" class="flex flex-wrap items-end gap-4">
        <input type="hidden" name="c" value="tarefa">
        <input type="hidden" name="a" value="index">
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Status</label>
            <select name="status_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white/70">
                <option value="">Todos</option>
                <?php foreach ($status_list as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= ($filtros['status_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Prioridade</label>
            <select name="prioridade_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white/70">
                <option value="">Todas</option>
                <?php foreach ($prioridades as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($filtros['prioridade_id'] ?? '') == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg transition-colors font-medium">Filtrar</button>
            <a href="index.php?c=tarefa&a=index" class="ml-2 text-gray-500 hover:text-gray-700 text-sm font-medium border-b border-gray-300">Limpar</a>
        </div>
    </form>
</div>

<!-- Lista de Tarefas -->
<?php if (empty($tarefas)): ?>
    <div class="text-center py-16 bg-white/60 backdrop-blur-md rounded-2xl border border-gray-100 shadow-sm border-dashed">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma tarefa encontrada</h3>
        <p class="mt-1 text-sm text-gray-500">Comece criando uma nova tarefa para organizar seus estudos.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($tarefas as $t): 
            $corPri = 'green';
            if ($t['prioridade_nome'] == 'Média') $corPri = 'yellow';
            if ($t['prioridade_nome'] == 'Alta') $corPri = 'orange';
            if ($t['prioridade_nome'] == 'Urgente') $corPri = 'red';
            
            $corSta = 'gray';
            if ($t['status_nome'] == 'Em andamento') $corSta = 'blue';
            if ($t['status_nome'] == 'Concluída') $corSta = 'green';
            
            $concluida = $t['status_nome'] == 'Concluída';
        ?>
        <div class="relative group bg-white rounded-2xl shadow-sm border <?= $concluida ? 'border-green-200 bg-green-50/30 opacity-75 hover:opacity-100' : 'border-gray-200 hover:shadow-md' ?> p-6 transition-all duration-300">
            
            <!-- Badges -->
            <div class="flex justify-between items-start mb-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $corPri ?>-100 text-<?= $corPri ?>-800">
                    <?= htmlspecialchars($t['prioridade_nome']) ?>
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?= $corSta ?>-100 text-<?= $corSta ?>-800">
                    <?= htmlspecialchars($t['status_nome']) ?>
                </span>
            </div>
            
            <!-- Content -->
            <h3 class="text-xl font-bold text-gray-900 mb-1 leading-tight <?= $concluida ? 'line-through text-gray-500' : '' ?>"><?= htmlspecialchars($t['titulo']) ?></h3>
            <p class="text-sm text-blue-600 font-semibold mb-3"><?= htmlspecialchars($t['disciplina']) ?></p>
            
            <p class="text-sm text-gray-600 mb-4 line-clamp-3 min-h-[4.5rem]">
                <?= nl2br(htmlspecialchars($t['descricao'])) ?>
            </p>
            
            <div class="flex items-center text-xs text-gray-500 mb-5 font-medium">
                <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Prazo: <?= !empty($t['data_entrega']) ? date('d/m/Y', strtotime($t['data_entrega'])) : 'Não definido' ?>
            </div>
            
            <!-- Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <div>
                    <?php if (!$concluida): ?>
                    <a href="index.php?c=tarefa&a=concluir&id=<?= $t['id'] ?>" class="text-green-600 hover:text-green-800 text-sm font-semibold flex items-center gap-1 group/btn" onclick="return confirm('Concluir esta tarefa?')">
                        <svg class="w-4 h-4 bg-green-100 rounded-full group-hover/btn:bg-green-200 p-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        Concluir
                    </a>
                    <?php endif; ?>
                </div>
                <div class="flex space-x-3">
                    <a href="index.php?c=tarefa&a=form&id=<?= $t['id'] ?>" class="text-gray-400 hover:text-blue-600 transition-colors" title="Editar">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </a>
                    <a href="index.php?c=tarefa&a=excluir&id=<?= $t['id'] ?>" onclick="return confirm('Deseja excluir a tarefa?');" class="text-gray-400 hover:text-red-600 transition-colors" title="Excluir">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </a>
                </div>
            </div>
            
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
