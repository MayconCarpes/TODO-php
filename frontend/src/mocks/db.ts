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

const API_BASE_URL = '/api/';

async function fetchApi(action: string, method: string = 'GET', body?: any) {
  const options: RequestInit = {
    method,
    headers: {
      'Content-Type': 'application/json'
    }
  };

  if (body) {
    options.body = JSON.stringify(body);
  }

  const response = await fetch(`${API_BASE_URL}${action}`, options);
  const data = await response.json();

  if (!response.ok) {
    throw new Error(data.error || 'Ocorreu um erro na requisição');
  }

  return data;
}

export const api = {
  login: async (email: string, senha: string): Promise<User> => {
    return fetchApi('login', 'POST', { email, senha });
  },

  register: async (userData: Omit<User, 'id'> & { senha?: string }): Promise<User> => {
    return fetchApi('register', 'POST', userData);
  },

  getTasks: async (userId: number): Promise<Task[]> => {
    return fetchApi(`tasks?usuario_id=${userId}`, 'GET');
  },

  createTask: async (task: Omit<Task, 'id' | 'data_criacao'>): Promise<Task> => {
    return fetchApi('tasks', 'POST', task);
  },

  updateTask: async (id: number, userId: number, updates: Partial<Task>): Promise<Task> => {
    return fetchApi(`tasks/${id}`, 'PUT', { ...updates, usuario_id: userId });
  },

  deleteTask: async (id: number, userId: number): Promise<void> => {
    return fetchApi(`tasks/${id}?usuario_id=${userId}`, 'DELETE');
  },

  getReport: async (userId: number): Promise<any> => {
    return fetchApi(`admin/report?usuario_id=${userId}`, 'GET');
  },

  // ADMIN METHODS
  adminGetUsers: async (adminId: number): Promise<User[]> => {
    return fetchApi(`admin/users?usuario_id=${adminId}`, 'GET');
  },

  adminCreateUser: async (adminId: number, userData: any): Promise<User> => {
    return fetchApi(`admin/users?usuario_id=${adminId}`, 'POST', userData);
  },

  adminUpdateUser: async (adminId: number, userId: number, updates: any): Promise<User> => {
    return fetchApi(`admin/users/${userId}?usuario_id=${adminId}`, 'PUT', updates);
  },

  adminDeleteUser: async (adminId: number, userId: number): Promise<void> => {
    return fetchApi(`admin/users/${userId}?usuario_id=${adminId}`, 'DELETE');
  },

  adminGetTasks: async (adminId: number): Promise<any[]> => {
    return fetchApi(`admin/tasks?usuario_id=${adminId}`, 'GET');
  }
};
