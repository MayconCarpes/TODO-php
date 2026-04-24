<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?= $tarefa ? 'Editar Tarefa' : 'Nova Tarefa' ?></h1>
        <a href="index.php?c=tarefa&a=index" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Voltar
        </a>
    </div>

    <div class="glass p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="index.php?c=tarefa&a=form<?= $tarefa ? '&id=' . $tarefa['id'] : '' ?>" method="POST" class="space-y-6">
            
            <div>
                <label for="titulo" class="block text-sm font-semibold text-gray-700 mb-1">Título da Tarefa</label>
                <input type="text" name="titulo" id="titulo" required 
                       value="<?= htmlspecialchars($tarefa['titulo'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="disciplina" class="block text-sm font-semibold text-gray-700 mb-1">Disciplina</label>
                    <input type="text" name="disciplina" id="disciplina" 
                           value="<?= htmlspecialchars($tarefa['disciplina'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70">
                </div>
                <div>
                    <label for="data_entrega" class="block text-sm font-semibold text-gray-700 mb-1">Data de Entrega</label>
                    <input type="date" name="data_entrega" id="data_entrega" 
                           value="<?= htmlspecialchars($tarefa['data_entrega'] ?? '') ?>"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70">
                </div>
            </div>
            
            <div>
                <label for="descricao" class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70 resize-none"><?= htmlspecialchars($tarefa['descricao'] ?? '') ?></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="prioridade_id" class="block text-sm font-semibold text-gray-700 mb-1">Prioridade</label>
                    <select name="prioridade_id" id="prioridade_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70">
                        <option value="">Selecione...</option>
                        <?php foreach ($prioridades as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= ($tarefa['prioridade_id'] ?? '') == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="status_id" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status_id" id="status_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none bg-white/70">
                        <option value="">Selecione...</option>
                        <?php foreach ($status_list as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= ($tarefa['status_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="index.php?c=tarefa&a=index" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors">Cancelar</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 shadow-md text-white rounded-xl font-medium transition-transform transform hover:-translate-y-0.5">
                    <?= $tarefa ? 'Salvar Alterações' : 'Criar Tarefa' ?>
                </button>
            </div>
            
        </form>
    </div>
</div>
