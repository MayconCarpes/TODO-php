<div class="max-w-md mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?= $status ? 'Editar Status' : 'Novo Status' ?></h1>
    </div>

    <div class="glass p-8 rounded-2xl shadow-sm border border-gray-100">
        <form action="index.php?c=admin&a=status_form<?= $status ? '&id=' . $status['id'] : '' ?>" method="POST" class="space-y-6">
            
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-1">Nome do Status</label>
                <input type="text" name="nome" id="nome" required 
                       value="<?= htmlspecialchars($status['nome'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 shadow-md text-white rounded-xl font-medium transition-transform transform hover:-translate-y-0.5">
                    Salvar
                </button>
                <a href="index.php?c=admin&a=status" class="w-full text-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors">Voltar</a>
            </div>
            
        </form>
    </div>
</div>
