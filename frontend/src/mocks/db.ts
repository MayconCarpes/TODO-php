export type User = {
  id: number;
  nome: string;
  email: string;
  perfil: 'ALUNO' | 'ADMIN';
};

export type TaskStatus = 'Pendente' | 'Em andamento' | 'Concluída';
export type TaskPriority = 'Baixa' | 'Média' | 'Alta' | 'Urgente';

export type Task = {
  id: number;
  titulo: string;
  descricao: string;
  disciplina: string;
  data_criacao: string;
  data_entrega: string;
  prioridade: TaskPriority;
  status: TaskStatus;
  usuario_id: number;
};

// Initial Mock Data
export const mockUsers: User[] = [
  { id: 1, nome: 'Matheus Guedes', email: 'matheusguedes@professor.com.br', perfil: 'ALUNO' },
  { id: 2, nome: 'Michael Tadeu', email: 'tadeu@professor.com', perfil: 'ALUNO' },
  { id: 4, nome: 'Administrador Sistema', email: 'admin@admin.com', perfil: 'ADMIN' },
];

export let mockTasks: Task[] = [
  {
    id: 1,
    titulo: 'Configurar Next.js',
    descricao: 'Criar a estrutura base do projeto front-end.',
    disciplina: 'Desenvolvimento Web',
    data_criacao: '2026-05-20',
    data_entrega: '2026-05-22',
    prioridade: 'Alta',
    status: 'Concluída',
    usuario_id: 2
  },
  {
    id: 2,
    titulo: 'Criar Componentes Base',
    descricao: 'Construir Navbar, Sidebar e Cards.',
    disciplina: 'Desenvolvimento Web',
    data_criacao: '2026-05-21',
    data_entrega: '2026-05-23',
    prioridade: 'Média',
    status: 'Em andamento',
    usuario_id: 2
  },
  {
    id: 3,
    titulo: 'Estudar React Hooks',
    descricao: 'Revisar useEffect e useState.',
    disciplina: 'Programação Frontend',
    data_criacao: '2026-05-22',
    data_entrega: '2026-05-25',
    prioridade: 'Baixa',
    status: 'Pendente',
    usuario_id: 2
  }
];

// Mock API Functions (Simulating delays)
const delay = (ms: number) => new Promise(resolve => setTimeout(resolve, ms));

export const api = {
  login: async (email: string, senha: string): Promise<User> => {
    await delay(800);
    const user = mockUsers.find(u => u.email === email);
    if (!user || senha.length < 3) throw new Error('Credenciais inválidas');
    return user;
  },

  getTasks: async (userId: number): Promise<Task[]> => {
    await delay(500);
    return mockTasks.filter(t => t.usuario_id === userId);
  },

  createTask: async (task: Omit<Task, 'id' | 'data_criacao'>): Promise<Task> => {
    await delay(600);
    const newTask: Task = {
      ...task,
      id: Math.max(...mockTasks.map(t => t.id), 0) + 1,
      data_criacao: new Date().toISOString().split('T')[0]
    };
    mockTasks = [...mockTasks, newTask];
    return newTask;
  },

  updateTask: async (id: number, updates: Partial<Task>): Promise<Task> => {
    await delay(500);
    const index = mockTasks.findIndex(t => t.id === id);
    if (index === -1) throw new Error('Tarefa não encontrada');
    mockTasks[index] = { ...mockTasks[index], ...updates };
    return mockTasks[index];
  },

  deleteTask: async (id: number): Promise<void> => {
    await delay(500);
    mockTasks = mockTasks.filter(t => t.id !== id);
  }
};
