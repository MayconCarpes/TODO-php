import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const usuario_id = searchParams.get('usuario_id');

  if (!usuario_id) {
    return NextResponse.json({ error: 'usuario_id não fornecido' }, { status: 400 });
  }

  try {
    // Verifica se o usuário é ADMIN
    const user = await prisma.user.findUnique({
      where: { id: parseInt(usuario_id, 10) }
    });

    if (!user || user.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Acesso negado. Apenas administradores.' }, { status: 403 });
    }

    // Coleta as estatísticas
    const totalUsers = await prisma.user.count();
    const totalTasks = await prisma.task.count();
    const completedTasks = await prisma.task.count({ where: { status: 'Concluída' } });
    const pendingTasks = await prisma.task.count({ where: { status: 'Pendente' } });
    const inProgressTasks = await prisma.task.count({ where: { status: 'Em andamento' } });

    // Pega as últimas tarefas criadas
    const recentTasks = await prisma.task.findMany({
      take: 10,
      orderBy: { data_criacao: 'desc' },
      include: {
        usuario: { select: { nome: true, email: true } }
      }
    });

    return NextResponse.json({
      totalUsers,
      totalTasks,
      completedTasks,
      pendingTasks,
      inProgressTasks,
      recentTasks
    });
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
