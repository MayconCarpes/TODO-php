<div class="flex items-center justify-center min-h-[80vh]">
    <div class="bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-xl border border-white/50 w-full max-w-md">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-teal-500 to-blue-500">
                Criar Nova Conta
            </h2>
            <p class="text-gray-500 mt-2 font-medium">Crie sua conta para gerenciar tarefas</p>
        </div>
        
        <?php if (!empty($erro)): ?>
            <div class="bg-red-50 flex items-center p-4 mb-6 rounded-lg text-red-800 border-l-4 border-red-500">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <div class="text-sm font-medium"><?= htmlspecialchars($erro) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($sucesso)): ?>
            <div class="bg-teal-50 flex items-center p-4 mb-6 rounded-lg text-teal-800 border-l-4 border-teal-500">
                <svg class="w-5 h-5 mr-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <div class="text-sm font-medium"><?= htmlspecialchars($sucesso) ?></div>
            </div>
        <?php endif; ?>

        <form action="index.php?c=auth&a=cadastro" method="POST" class="space-y-4">
            <div>
                <label for="nome" class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                <input type="text" name="nome" id="nome" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50 transition-all outline-none">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" id="email" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50 transition-all outline-none">
            </div>
            
            <div>
                <label for="senha" class="block text-sm font-semibold text-gray-700 mb-1">Senha Segura</label>
                <input type="password" name="senha" id="senha" required minlength="6"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-50 transition-all outline-none"
                       placeholder="Mínimo de 6 caracteres">
            </div>
            
            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-teal-500 to-blue-500 hover:from-teal-600 hover:to-blue-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-transform focus:outline-none focus:ring-2 focus:ring-teal-300 transform hover:-translate-y-0.5">
                    Registrar Conta
                </button>
            </div>
        </form>
        
        <div class="text-center mt-6 pt-5 border-t border-gray-100">
            <p class="text-sm text-gray-600 font-medium">Já possui cadastro? 
                <a href="index.php?c=auth&a=index" class="text-teal-600 hover:text-teal-800 hover:underline transition-colors">Voltar para o Login</a>
            </p>
        </div>
    </div>
</div>
