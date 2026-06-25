"use client";

import { useEffect, useState } from "react";
import { api, User } from "@/mocks/db";
import { useRouter } from "next/navigation";
import { Users, FileText, CheckCircle, Clock, ArrowLeft } from "lucide-react";
import Link from "next/link";

interface ReportData {
  totalUsers: number;
  totalTasks: number;
  completedTasks: number;
  pendingTasks: number;
  inProgressTasks: number;
  recentTasks: any[];
}

export default function AdminDashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [report, setReport] = useState<ReportData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser) {
      router.push("/login");
      return;
    }
    
    const parsedUser = JSON.parse(storedUser);
    if (parsedUser.perfil !== 'ADMIN') {
      router.push("/dashboard");
      return;
    }
    
    setUser(parsedUser);
    
    api.getReport(parsedUser.id).then((data) => {
      setReport(data);
      setLoading(false);
    }).catch(err => {
      setError(err.message || 'Erro ao carregar relatório');
      setLoading(false);
    });
  }, [router]);

  if (loading || !user) {
    return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>;
  }

  if (error) {
    return (
      <div className="flex flex-col items-center justify-center h-64 text-red-600 gap-4">
        <p>{error}</p>
        <Link href="/dashboard" className="text-blue-600 underline">Voltar para o Início</Link>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Painel de Administração</h1>
          <p className="text-slate-500 text-sm mt-1">Visão geral do sistema e relatório de tarefas.</p>
        </div>
        <Link 
          href="/dashboard" 
          className="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2"
        >
          <ArrowLeft size={20} /> Voltar
        </Link>
      </div>

      {report && (
        <>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {/* Cards de Estatísticas */}
            <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
              <div className="p-4 bg-blue-100 text-blue-600 rounded-xl">
                <Users size={24} />
              </div>
              <div>
                <p className="text-sm text-slate-500 font-medium">Total de Usuários</p>
                <p className="text-2xl font-bold text-slate-800">{report.totalUsers}</p>
              </div>
            </div>

            <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
              <div className="p-4 bg-indigo-100 text-indigo-600 rounded-xl">
                <FileText size={24} />
              </div>
              <div>
                <p className="text-sm text-slate-500 font-medium">Total de Tarefas</p>
                <p className="text-2xl font-bold text-slate-800">{report.totalTasks}</p>
              </div>
            </div>

            <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
              <div className="p-4 bg-green-100 text-green-600 rounded-xl">
                <CheckCircle size={24} />
              </div>
              <div>
                <p className="text-sm text-slate-500 font-medium">Concluídas</p>
                <p className="text-2xl font-bold text-slate-800">{report.completedTasks}</p>
              </div>
            </div>

            <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
              <div className="p-4 bg-yellow-100 text-yellow-600 rounded-xl">
                <Clock size={24} />
              </div>
              <div>
                <p className="text-sm text-slate-500 font-medium">Pendentes/Em Andamento</p>
                <p className="text-2xl font-bold text-slate-800">{report.pendingTasks + report.inProgressTasks}</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 mt-8">
            <h2 className="text-lg font-bold text-slate-800 mb-4">Últimas Tarefas Criadas na Plataforma</h2>
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-slate-200 text-slate-500 text-sm">
                    <th className="pb-3 font-medium">Título</th>
                    <th className="pb-3 font-medium">Criador</th>
                    <th className="pb-3 font-medium">Status</th>
                    <th className="pb-3 font-medium">Data Entrega</th>
                  </tr>
                </thead>
                <tbody className="text-sm text-slate-700">
                  {report.recentTasks.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="py-8 text-center text-slate-400">Nenhuma tarefa encontrada.</td>
                    </tr>
                  ) : (
                    report.recentTasks.map((t) => (
                      <tr key={t.id} className="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td className="py-3 font-medium">{t.titulo}</td>
                        <td className="py-3">{t.usuario?.nome || 'Desconhecido'}</td>
                        <td className="py-3">
                          <span className={`px-2 py-1 rounded-full text-xs font-bold ${
                            t.status === 'Concluída' ? 'bg-green-100 text-green-700' :
                            t.status === 'Em andamento' ? 'bg-yellow-100 text-yellow-700' :
                            'bg-slate-100 text-slate-700'
                          }`}>
                            {t.status}
                          </span>
                        </td>
                        <td className="py-3">{new Date(t.data_entrega).toLocaleDateString('pt-BR')}</td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        </>
      )}
    </div>
  );
}
