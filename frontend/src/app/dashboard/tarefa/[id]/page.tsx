"use client";

import { useState, useEffect, use } from "react";
import { useRouter } from "next/navigation";
import { api, Task, TaskPriority, TaskStatus, User } from "@/mocks/db";
import { Save, ArrowLeft, Loader2, Trash2 } from "lucide-react";
import Link from "next/link";

export default function EditarTarefaPage({ params }: { params: Promise<{ id: string }> }) {
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [loadingInitial, setLoadingInitial] = useState(true);
  const [user, setUser] = useState<User | null>(null);

  const unwrappedParams = use(params);
  const taskId = parseInt(unwrappedParams.id, 10);

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
    const parsedUser = JSON.parse(storedUser);
    setUser(parsedUser);

    // Fetch the task to edit
    api.getTasks(parsedUser.id).then(tasks => {
      const task = tasks.find(t => t.id === taskId);
      if (task) {
        setFormData({
          titulo: task.titulo,
          descricao: task.descricao,
          disciplina: task.disciplina,
          data_entrega: task.data_entrega,
          prioridade: task.prioridade,
          status: task.status,
        });
      } else {
        alert("Tarefa não encontrada");
        router.push("/dashboard");
      }
      setLoadingInitial(false);
    });
  }, [router, taskId]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    
    setLoading(true);
    try {
      await api.updateTask(taskId, formData);
      router.push("/dashboard");
    } catch (err) {
      alert("Erro ao atualizar tarefa");
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async () => {
    if (!confirm("Tem certeza que deseja excluir esta tarefa?")) return;
    
    try {
      await api.deleteTask(taskId, user.id);
      router.push("/dashboard");
    } catch (e) {
      alert("Erro ao excluir tarefa");
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setFormData(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

  if (loadingInitial) {
    return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>;
  }

  return (
    <div className="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
      <Link href="/dashboard" className="text-slate-400 hover:text-slate-600 inline-flex items-center gap-1 mb-6 transition-colors">
        <ArrowLeft size={16} /> <span className="text-sm font-medium">Voltar ao Dashboard</span>
      </Link>

      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Editar Tarefa</h1>
        <button 
          type="button" 
          onClick={handleDelete}
          className="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors flex items-center gap-1 text-sm font-medium"
        >
          <Trash2 size={18} />
          Excluir
        </button>
      </div>

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
            <label className="block text-sm font-medium text-slate-700 mb-1">Status</label>
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
            Salvar Alterações
          </button>
        </div>
      </form>
    </div>
  );
}
