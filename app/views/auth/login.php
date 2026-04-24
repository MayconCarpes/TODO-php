<div class="flex items-center justify-center min-h-[80vh]">
    <div class="bg-white/80 backdrop-blur-xl p-8 rounded-2xl shadow-xl border border-white/50 w-full max-w-md transform transition-all duration-300 hover:shadow-2xl">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4 shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                TaskSys
            </h2>
            <p class="text-gray-500 mt-2 font-medium">Faça login para continuar</p>
        </div>
        
        <?php if (!empty($erro)): ?>
            <div class="bg-red-50/80 border-l-4 border-red-500 text-red-700 p-4 rounded-md mb-6 animate-pulse" role="alert">
                <p class="font-medium"><?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>

        <form action="index.php?c=auth&a=index" method="POST" class="space-y-5">
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" id="email" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none bg-white/50 backdrop-blur-sm"
                       placeholder="seu@email.com">
            </div>
            
            <div>
                <label for="senha" class="block text-sm font-semibold text-gray-700 mb-1">Senha</label>
                <input type="password" name="senha" id="senha" required 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none bg-white/50 backdrop-blur-sm"
                       placeholder="••••••••">
            </div>
            
            <div class="pt-2">
                <button type="submit" 
                        class="w-full relative group overflow-hidden bg-gradient-to-br from-blue-600 to-indigo-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all focus:outline-none focus:ring-4 focus:ring-indigo-100 flex justify-center items-center transform hover:-translate-y-0.5">
                    <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black"></span>
                    <span class="relative">Acessar Sistema</span>
                    <svg class="w-5 h-5 ml-2 relative group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
        
        <div class="text-center mt-8 pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Não tem uma conta? 
                <a href="index.php?c=auth&a=cadastro" class="text-blue-600 hover:text-indigo-600 hover:underline transition-colors focus:outline-none">Cadastre-se grátis</a>
            </p>
        </div>
    </div>
</div>
