"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { api } from "@/mocks/db";
import { CheckSquare, Loader2, AlertCircle } from "lucide-react";
import Link from "next/link";

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [senha, setSenha] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");

    try {
      const user = await api.login(email, senha);
      // Simulating a session by saving to localStorage
      localStorage.setItem("user", JSON.stringify(user));
      router.push("/dashboard");
    } catch (err: any) {
      setError(err.message || "Erro ao fazer login");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-50 flex items-center justify-center p-4">
      <div className="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-100 p-8">
        <div className="flex flex-col items-center mb-8">
          <div className="bg-blue-100 p-3 rounded-xl mb-4">
            <CheckSquare size={32} className="text-blue-600" />
          </div>
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Bem-vindo de volta!</h1>
          <p className="text-slate-500 mt-2 text-center">Faça login para gerenciar suas tarefas acadêmicas</p>
        </div>

        <form onSubmit={handleLogin} className="space-y-4">
          {error && (
            <div className="p-3 bg-red-50 border border-red-100 text-red-600 rounded-lg flex items-center gap-2 text-sm">
              <AlertCircle size={16} />
              {error}
            </div>
          )}

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
              value={senha}
              onChange={(e) => setSenha(e.target.value)}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              placeholder="••••••••"
            />
          </div>

          <button 
            type="submit" 
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-70"
          >
            {loading ? <Loader2 size={20} className="animate-spin" /> : "Entrar na Conta"}
          </button>
        </form>

        <p className="text-center text-sm text-slate-500 mt-6">
          Ainda não tem conta? <Link href="/cadastro" className="text-blue-600 hover:underline font-medium">Cadastre-se grátis</Link>
        </p>


      </div>
    </div>
  );
}
