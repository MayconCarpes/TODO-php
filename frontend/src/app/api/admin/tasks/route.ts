import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const usuario_id = searchParams.get('usuario_id');

  if (!usuario_id) return NextResponse.json({ error: 'Acesso negado' }, { status: 400 });

  try {
    const adminUser = await prisma.user.findUnique({ where: { id: parseInt(usuario_id, 10) } });
    if (!adminUser || adminUser.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Permissão negada' }, { status: 403 });
    }

    const tasks = await prisma.task.findMany({
      orderBy: { id: 'desc' },
      include: {
        usuario: { select: { nome: true, email: true, perfil: true } }
      }
    });

    return NextResponse.json(tasks);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
