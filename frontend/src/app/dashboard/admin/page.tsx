"use client";

import { useEffect, useState } from "react";
import { api, User } from "@/mocks/db";
import { useRouter } from "next/navigation";
import { Users, FileText, CheckCircle, Clock, ArrowLeft, Trash2, Edit, Save, X } from "lucide-react";
import Link from "next/link";

export default function AdminDashboardPage() {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [activeTab, setActiveTab] = useState<'report' | 'users' | 'tasks'>('report');
  
  // Data States
  const [report, setReport] = useState<any>(null);
  const [users, setUsers] = useState<User[]>([]);
  const [tasks, setTasks] = useState<any[]>([]);
  
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  // Edit User State
  const [editingUserId, setEditingUserId] = useState<number | null>(null);
  const [editUserForm, setEditUserForm] = useState<Partial<User>>({});

  useEffect(() => {
    const storedUser = localStorage.getItem("user");
    if (!storedUser) return router.push("/login");
    
    const parsedUser = JSON.parse(storedUser);
    if (parsedUser.perfil !== 'ADMIN') return router.push("/dashboard");
    
    setUser(parsedUser);
    loadData(parsedUser.id, 'report');
  }, [router]);

  const loadData = async (adminId: number, tab: string) => {
    setLoading(true);
    try {
      if (tab === 'report') {
        const data = await api.getReport(adminId);
        setReport(data);
      } else if (tab === 'users') {
        const data = await api.adminGetUsers(adminId);
        setUsers(data);
      } else if (tab === 'tasks') {
        const data = await api.adminGetTasks(adminId);
        setTasks(data);
      }
    } catch (err: any) {
      setError(err.message || 'Erro ao carregar dados');
    } finally {
      setLoading(false);
    }
  };

  const handleTabChange = (tab: 'report' | 'users' | 'tasks') => {
    setActiveTab(tab);
    if (user) loadData(user.id, tab);
  };

  // User CRUD Actions
  const handleDeleteUser = async (userId: number) => {
    if (!confirm("Tem certeza que deseja excluir este usuário? Todas as suas tarefas serão apagadas!")) return;
    try {
      await api.adminDeleteUser(user!.id, userId);
      setUsers(users.filter(u => u.id !== userId));
    } catch (e) {
      alert("Erro ao deletar usuário");
    }
  };

  const handleSaveUserEdit = async (userId: number) => {
    try {
      const updated = await api.adminUpdateUser(user!.id, userId, editUserForm);
      setUsers(users.map(u => u.id === userId ? updated : u));
      setEditingUserId(null);
    } catch (e) {
      alert("Erro ao atualizar usuário");
    }
  };

  // Task Actions
  const handleAdminTaskStatus = async (taskId: number, newStatus: any) => {
    try {
      const updated = await api.updateTask(taskId, user!.id, { status: newStatus });
      setTasks(tasks.map(t => t.id === taskId ? { ...updated, usuario: t.usuario } : t));
    } catch (e) {
      alert("Erro ao alterar tarefa");
    }
  };
  
  const handleAdminTaskDelete = async (taskId: number) => {
    if (!confirm("Tem certeza que deseja excluir esta tarefa de outro usuário?")) return;
    try {
      await api.deleteTask(taskId, user!.id);
      setTasks(tasks.filter(t => t.id !== taskId));
    } catch (e) {
      alert("Erro ao deletar tarefa");
    }
  };

  if (loading && !report && !users.length && !tasks.length) {
    return <div className="flex justify-center items-center h-64"><div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>;
  }

  if (error && !report && !users.length && !tasks.length) {
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
          <h1 className="text-2xl font-bold text-slate-800 tracking-tight">Super Painel Administrativo</h1>
          <p className="text-slate-500 text-sm mt-1">Gerencie relatórios, usuários e tarefas do sistema.</p>
        </div>
        <Link 
          href="/dashboard" 
          className="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2"
        >
          <ArrowLeft size={20} /> Voltar
        </Link>
      </div>

      {/* TABS */}
      <div className="flex gap-4 border-b border-slate-200">
        <button 
          onClick={() => handleTabChange('report')}
          className={`pb-2 px-2 font-medium transition-colors ${activeTab === 'report' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-800'}`}
        >
          Visão Geral
        </button>
        <button 
          onClick={() => handleTabChange('users')}
          className={`pb-2 px-2 font-medium transition-colors ${activeTab === 'users' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-800'}`}
        >
          Gerenciar Usuários
        </button>
        <button 
          onClick={() => handleTabChange('tasks')}
          className={`pb-2 px-2 font-medium transition-colors ${activeTab === 'tasks' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-slate-500 hover:text-slate-800'}`}
        >
          Gerenciar Tarefas Globais
        </button>
      </div>

      {/* TAB: REPORT */}
      {activeTab === 'report' && report && (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-in fade-in">
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
            <div className="p-4 bg-blue-100 text-blue-600 rounded-xl"><Users size={24} /></div>
            <div>
              <p className="text-sm text-slate-500 font-medium">Usuários</p>
              <p className="text-2xl font-bold text-slate-800">{report.totalUsers}</p>
            </div>
          </div>
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
            <div className="p-4 bg-indigo-100 text-indigo-600 rounded-xl"><FileText size={24} /></div>
            <div>
              <p className="text-sm text-slate-500 font-medium">Tarefas Totais</p>
              <p className="text-2xl font-bold text-slate-800">{report.totalTasks}</p>
            </div>
          </div>
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
            <div className="p-4 bg-green-100 text-green-600 rounded-xl"><CheckCircle size={24} /></div>
            <div>
              <p className="text-sm text-slate-500 font-medium">Concluídas</p>
              <p className="text-2xl font-bold text-slate-800">{report.completedTasks}</p>
            </div>
          </div>
          <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex items-center gap-4">
            <div className="p-4 bg-yellow-100 text-yellow-600 rounded-xl"><Clock size={24} /></div>
            <div>
              <p className="text-sm text-slate-500 font-medium">Em Andamento</p>
              <p className="text-2xl font-bold text-slate-800">{report.pendingTasks + report.inProgressTasks}</p>
            </div>
          </div>
        </div>
      )}

      {/* TAB: USERS */}
      {activeTab === 'users' && (
        <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 animate-in fade-in">
          <h2 className="text-lg font-bold text-slate-800 mb-4">Gerenciamento de Contas</h2>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-slate-200 text-slate-500 text-sm">
                  <th className="pb-3 font-medium">ID</th>
                  <th className="pb-3 font-medium">Nome</th>
                  <th className="pb-3 font-medium">E-mail</th>
                  <th className="pb-3 font-medium">Perfil</th>
                  <th className="pb-3 font-medium text-right">Ações</th>
                </tr>
              </thead>
              <tbody className="text-sm text-slate-700">
                {users.map((u) => (
                  <tr key={u.id} className="border-b border-slate-100 hover:bg-slate-50">
                    <td className="py-3 font-medium">#{u.id}</td>
                    
                    {/* Modo Edição */}
                    {editingUserId === u.id ? (
                      <>
                        <td className="py-3"><input type="text" className="border rounded p-1 w-full" value={editUserForm.nome || ''} onChange={e => setEditUserForm({...editUserForm, nome: e.target.value})} /></td>
                        <td className="py-3">{u.email}</td>
                        <td className="py-3">
                          <select className="border rounded p-1" value={editUserForm.perfil || ''} onChange={e => setEditUserForm({...editUserForm, perfil: e.target.value as any})}>
                            <option value="ALUNO">ALUNO</option>
                            <option value="ADMIN">ADMIN</option>
                          </select>
                        </td>
                        <td className="py-3 text-right">
                          <button onClick={() => handleSaveUserEdit(u.id)} className="text-green-600 p-1 mx-1"><Save size={16} /></button>
                          <button onClick={() => setEditingUserId(null)} className="text-slate-400 p-1 mx-1"><X size={16} /></button>
                        </td>
                      </>
                    ) : (
                      <>
                        <td className="py-3">{u.nome}</td>
                        <td className="py-3">{u.email}</td>
                        <td className="py-3">
                          <span className={`px-2 py-1 rounded text-xs font-bold ${u.perfil === 'ADMIN' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700'}`}>{u.perfil}</span>
                        </td>
                        <td className="py-3 text-right">
                          <button onClick={() => { setEditingUserId(u.id); setEditUserForm({ nome: u.nome, perfil: u.perfil }); }} className="text-blue-600 px-2 py-1 mx-1 hover:bg-blue-50 rounded font-medium flex inline-flex items-center gap-1"><Edit size={16} /> Editar</button>
                          <button onClick={() => handleDeleteUser(u.id)} disabled={u.id === user?.id} className={`px-2 py-1 mx-1 rounded font-medium inline-flex items-center gap-1 ${u.id === user?.id ? 'text-slate-300' : 'text-red-600 hover:bg-red-50'}`}><Trash2 size={16} /> Excluir</button>
                        </td>
                      </>
                    )}
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* TAB: TASKS */}
      {activeTab === 'tasks' && (
        <div className="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 animate-in fade-in">
          <h2 className="text-lg font-bold text-slate-800 mb-4">Controle Global de Tarefas</h2>
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-slate-200 text-slate-500 text-sm">
                  <th className="pb-3 font-medium">ID</th>
                  <th className="pb-3 font-medium">Título</th>
                  <th className="pb-3 font-medium">Dono (Criador)</th>
                  <th className="pb-3 font-medium">Status Atual</th>
                  <th className="pb-3 font-medium text-right">Ação Admin</th>
                </tr>
              </thead>
              <tbody className="text-sm text-slate-700">
                {tasks.map((t) => (
                  <tr key={t.id} className="border-b border-slate-100 hover:bg-slate-50">
                    <td className="py-3">#{t.id}</td>
                    <td className="py-3 font-medium">{t.titulo}</td>
                    <td className="py-3">{t.usuario?.nome} <br/><span className="text-xs text-slate-400">{t.usuario?.email}</span></td>
                    <td className="py-3">
                      <select 
                        value={t.status} 
                        onChange={(e) => handleAdminTaskStatus(t.id, e.target.value)}
                        className="border border-slate-200 rounded p-1 text-xs"
                      >
                        <option value="Pendente">Pendente</option>
                        <option value="Em andamento">Em andamento</option>
                        <option value="Concluída">Concluída</option>
                      </select>
                    </td>
                    <td className="py-3 text-right">
                      <button onClick={() => handleAdminTaskDelete(t.id)} className="text-red-600 hover:bg-red-50 p-1 rounded transition-colors"><Trash2 size={16} /></button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}
