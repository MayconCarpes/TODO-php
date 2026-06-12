import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const usuario_id = searchParams.get('usuario_id');

  if (!usuario_id) {
    return NextResponse.json({ error: 'usuario_id não fornecido' }, { status: 400 });
  }

  try {
    const tasks = await prisma.task.findMany({
      where: {
        usuario_id: parseInt(usuario_id, 10),
      },
      orderBy: [
        { data_entrega: 'asc' },
        { id: 'desc' }
      ]
    });

    return NextResponse.json(tasks);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  try {
    const data = await request.json();
    const { titulo, descricao, disciplina, data_entrega, prioridade, status, usuario_id } = data;

    if (!titulo || !usuario_id) {
      return NextResponse.json({ error: 'Título e usuario_id são obrigatórios' }, { status: 400 });
    }

    const newTask = await prisma.task.create({
      data: {
        titulo,
        descricao,
        disciplina,
        data_entrega,
        prioridade: prioridade || 'Baixa',
        status: status || 'Pendente',
        usuario_id: parseInt(usuario_id, 10),
      },
    });

    return NextResponse.json(newTask);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
