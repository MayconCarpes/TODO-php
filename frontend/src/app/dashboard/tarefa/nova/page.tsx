"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { api, TaskPriority, TaskStatus, User } from "@/mocks/db";
import { Save, ArrowLeft, Loader2 } from "lucide-react";
import Link from "next/link";

export default function NovaTarefaPage() {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [user, setUser] = useState<User | null>(null);

  const [formData, setFormData] = useState({
    titulo: "",
    descricao: "",
    disciplina: "",
    data_entrega: "",
    prioridade: "Média" as TaskPriority,
    status: "Pendente" as TaskStatus,
  });

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser) {
      router.push("/login");
      return;
    }
    setUser(JSON.parse(storedUser));
  }, [router]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    
    setLoading(true);
    try {
      await api.createTask({
        ...formData,
        usuario_id: user.id
      });
      router.push("/dashboard");
    } catch (err) {
      alert("Erro ao criar tarefa");
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  if (!user) return null;

  return (
    <div className="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
      <Link href="/dashboard" className="text-slate-400 hover:text-slate-600 inline-flex items-center gap-1 mb-6 transition-colors">
        <ArrowLeft size={16} /> <span className="text-sm font-medium">Voltar ao Dashboard</span>
      </Link>

      <h1 className="text-2xl font-bold text-slate-800 tracking-tight mb-6">Criar Nova Tarefa</h1>

      <form onSubmit={handleSubmit} className="space-y-5">
        <div>
          <label className="block text-sm font-medium text-slate-700 mb-1">Título da Tarefa</label>
          <input 
            type="text" 
            name="titulo"
            required
            value={formData.titulo}
            onChange={handleChange}
            className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
            placeholder="Ex: Lista de Exercícios de Cálculo"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-slate-700 mb-1">Descrição</label>
          <textarea 
            name="descricao"
            rows={3}
            value={formData.descricao}
            onChange={handleChange}
            className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all resize-none"
            placeholder="Detalhes sobre o que precisa ser feito..."
          />
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Disciplina</label>
            <input 
              type="text" 
              name="disciplina"
              required
              value={formData.disciplina}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
              placeholder="Ex: Cálculo I"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Data de Entrega</label>
            <input 
              type="date" 
              name="data_entrega"
              required
              value={formData.data_entrega}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Prioridade</label>
            <select 
              name="prioridade"
              value={formData.prioridade}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-white"
            >
              <option value="Baixa">Baixa</option>
              <option value="Média">Média</option>
              <option value="Alta">Alta</option>
              <option value="Urgente">Urgente</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium text-slate-700 mb-1">Status Inicial</label>
            <select 
              name="status"
              value={formData.status}
              onChange={handleChange}
              className="w-full px-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-white"
            >
              <option value="Pendente">A Fazer</option>
              <option value="Em andamento">Em Andamento</option>
              <option value="Concluída">Concluída</option>
            </select>
          </div>
        </div>

        <div className="pt-4 border-t border-slate-100 flex justify-end">
          <button 
            type="submit" 
            disabled={loading}
            className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-70"
          >
            {loading ? <Loader2 size={20} className="animate-spin" /> : <Save size={20} />}
            Salvar Tarefa
          </button>
        </div>
      </form>
    </div>
  );
}
