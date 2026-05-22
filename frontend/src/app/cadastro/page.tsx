"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { CheckSquare, Loader2, ArrowLeft } from "lucide-react";
import Link from "next/link";

export default function CadastroPage() {
  const router = useRouter();
  const [nome, setNome] = useState("");
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const [loading, setLoading] = useState(false);

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      // Simulating registration delay
      await new Promise(r => setTimeout(r, 800));
      // In a real app we'd call api.register()
      // For the mock, just push to login
      alert("Cadastro realizado com sucesso! Faça login para continuar.");
      router.push("/login");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex items-center justify-center p-4">
      <div className="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        
        <Link href="/login" className="text-slate-400 hover:text-slate-600 inline-flex items-center gap-1 mb-6 transition-colors">
          <ArrowLeft size={16} /> <span className="text-sm font-medium">Voltar</span>
        </Link>

        <div className="flex flex-col items-center mb-8">
          <div className="bg-blue-100 p-3 rounded-xl mb-4">
            <CheckSquare size={32} className="text-blue-600" />
          </div>
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Crie sua conta</h1>
          <p className="text-slate-500 mt-2 text-center">Organize suas tarefas acadêmicas agora mesmo</p>
        </div>

        <form onSubmit={handleRegister} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Nome completo</label>
            <input 
              type="text" 
              required
              value={nome}
              onChange={(e) => setNome(e.target.value)}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              placeholder="Ex: João da Silva"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">E-mail</label>
            <input 
              type="email" 
              required
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              placeholder="seu@email.com"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Senha</label>
            <input 
              type="password" 
              required
              minLength={6}
              value={senha}
              onChange={(e) => setSenha(e.target.value)}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              placeholder="Mínimo de 6 caracteres"
            />
          </div>

          <button 
            type="submit" 
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-70 mt-2"
          >
            {loading ? <Loader2 size={20} className="animate-spin" /> : "Criar minha conta"}
          </button>
        </form>
      </div>
    </div>
  );
}
