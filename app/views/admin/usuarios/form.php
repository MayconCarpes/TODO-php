<div class="max-w-md mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900 tracking-tight"><?= $usuario ? 'Editar Usuário' : 'Novo Usuário' ?></h1>
    </div>

    <div class="glass p-8 rounded-2xl shadow-sm border border-gray-100">
        <?php if (!empty($erro)): ?>
            <div class="bg-red-50 text-red-500 p-3 rounded-xl mb-4 border border-red-200"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form action="index.php?c=admin&a=usuario_form<?= $usuario ? '&id=' . $usuario['id'] : '' ?>" method="POST" class="space-y-6">
            
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                <input type="text" name="nome" id="nome" required 
                       value="<?= htmlspecialchars($usuario['nome'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" id="email" required 
                       value="<?= htmlspecialchars($usuario['email'] ?? '') ?>"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
            </div>

            <div>
                <label for="senha" class="block text-sm font-semibold text-gray-700 mb-1">Senha <?= $usuario ? '(Deixe em branco para manter)' : '' ?></label>
                <input type="password" name="senha" id="senha" <?= $usuario ? '' : 'required' ?>
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
            </div>

            <div>
                <label for="perfil" class="block text-sm font-semibold text-gray-700 mb-1">Perfil</label>
                <select name="perfil" id="perfil" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all outline-none">
                    <option value="ALUNO" <?= ($usuario['perfil'] ?? '') == 'ALUNO' ? 'selected' : '' ?>>ALUNO</option>
                    <option value="ADMIN" <?= ($usuario['perfil'] ?? '') == 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
                </select>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 shadow-md text-white rounded-xl font-medium transition-transform transform hover:-translate-y-0.5">Salvar</button>
                <a href="index.php?c=admin&a=usuarios" class="w-full text-center px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors">Voltar</a>
            </div>
            
        </form>
    </div>
</div>
