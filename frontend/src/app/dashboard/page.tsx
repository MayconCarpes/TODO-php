"use client";

import { useEffect, useState } from "react";
import { api, Task, User } from "@/mocks/db";
import { useRouter } from "next/navigation";
import { Plus, Clock, Calendar, AlertCircle, CheckSquare } from "lucide-react";
import Link from "next/link";

const priorityColors = {
  Baixa: "bg-green-100 text-green-700",
  Média: "bg-yellow-100 text-yellow-700",
  Alta: "bg-orange-100 text-orange-700",
  Urgente: "bg-red-100 text-red-700",
};

export default function DashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [tasks, setTasks] = useState<Task[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser) {
      router.push("/login");
      return;
    }
    
    const parsedUser = JSON.parse(storedUser);
    setUser(parsedUser);
    
    api.getTasks(parsedUser.id).then((data) => {
      setTasks(data);
      setLoading(false);
    });
  }, [router]);

  const changeStatus = async (task: Task, newStatus: Task["status"]) => {
    try {
      const updated = await api.updateTask(task.id, user!.id, { status: newStatus });
      setTasks(tasks.map(t => t.id === updated.id ? updated : t));
    } catch (e) {
      alert("Erro ao atualizar status");
    }
  };

  const columns: { id: Task["status"]; title: string }[] = [
    { id: "Pendente", title: "A Fazer" },
    { id: "Em andamento", title: "Em Andamento" },
    { id: "Concluída", title: "Concluído" },
  ];

  if (loading || !user) {
    return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>;
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Minhas Tarefas</h1>
          <p className="text-slate-500 text-sm mt-1">Organize seu dia, {user.nome.split(' ')[0]}!</p>
        </div>
        <Link 
          href="/dashboard/tarefa/nova" 
          className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2"
        >
          <Plus size={20} /> Nova Tarefa
        </Link>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {columns.map((column) => {
          const columnTasks = tasks.filter(t => t.status === column.id);
          
          return (
            <div key={column.id} className="flex flex-col bg-slate-100/50 rounded-2xl p-4 border border-slate-200/60 min-h-[500px]">
              <div className="flex items-center justify-between mb-4 px-2">
                <h2 className="font-semibold text-slate-700">{column.title}</h2>
                <span className="bg-slate-200 text-slate-600 text-xs font-bold px-2 py-1 rounded-full">
                  {columnTasks.length}
                </span>
              </div>
              
              <div className="flex-1 space-y-3">
                {columnTasks.map((task) => (
                  <div key={task.id} className="bg-white rounded-xl p-4 shadow-sm border border-slate-200 hover:shadow-md transition-shadow group">
                    <div className="flex justify-between items-start mb-2">
                      <span className={`text-xs font-bold px-2 py-1 rounded-md ${priorityColors[task.prioridade]}`}>
                        {task.prioridade}
                      </span>
                      
                      {/* Status actions dropdown (simplified for mock) */}
                      <div className="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        {column.id !== "Pendente" && (
                          <button onClick={() => changeStatus(task, "Pendente")} className="p-1 text-slate-400 hover:text-blue-600" title="Mover para Pendente">
                            <AlertCircle size={16} />
                          </button>
                        )}
                        {column.id !== "Em andamento" && (
                          <button onClick={() => changeStatus(task, "Em andamento")} className="p-1 text-slate-400 hover:text-yellow-600" title="Mover para Em Andamento">
                            <Clock size={16} />
                          </button>
                        )}
                        {column.id !== "Concluída" && (
                          <button onClick={() => changeStatus(task, "Concluída")} className="p-1 text-slate-400 hover:text-green-600" title="Mover para Concluída">
                            <CheckSquare size={16} />
                          </button>
                        )}
                      </div>
                    </div>
                    
                    <Link href={`/dashboard/tarefa/${task.id}`}>
                      <h3 className="font-semibold text-slate-800 leading-tight mb-1 hover:text-blue-600 transition-colors">
                        {task.titulo}
                      </h3>
                      <p className="text-sm text-slate-500 line-clamp-2 mb-3">
                        {task.descricao}
                      </p>
                    </Link>
                    
                    <div className="flex items-center justify-between text-xs text-slate-400 border-t border-slate-100 pt-3">
                      <span className="font-medium text-slate-500 truncate max-w-[100px]">{task.disciplina}</span>
                      <div className="flex items-center gap-1">
                        <Calendar size={14} />
                        <span>{new Date(task.data_entrega).toLocaleDateString('pt-BR')}</span>
                      </div>
                    </div>
                  </div>
                ))}
                
                {columnTasks.length === 0 && (
                  <div className="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center flex flex-col items-center justify-center text-slate-400 h-32">
                    <p className="text-sm">Nenhuma tarefa</p>
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
