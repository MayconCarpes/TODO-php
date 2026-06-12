import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';

export async function PUT(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const data = await request.json();
    const { usuario_id, ...updates } = data;

    if (!usuario_id) {
      return NextResponse.json({ error: 'usuario_id é obrigatório' }, { status: 400 });
    }

    const task = await prisma.task.findUnique({
      where: { id: parseInt(id, 10) },
    });

    if (!task || task.usuario_id !== parseInt(usuario_id, 10)) {
      return NextResponse.json({ error: 'Tarefa não encontrada' }, { status: 404 });
    }

    const updatedTask = await prisma.task.update({
      where: { id: parseInt(id, 10) },
      data: updates,
    });

    return NextResponse.json(updatedTask);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}

export async function DELETE(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const { searchParams } = new URL(request.url);
    const usuario_id = searchParams.get('usuario_id');

    if (!usuario_id) {
      return NextResponse.json({ error: 'usuario_id é obrigatório' }, { status: 400 });
    }

    const task = await prisma.task.findUnique({
      where: { id: parseInt(id, 10) },
    });

    if (!task || task.usuario_id !== parseInt(usuario_id, 10)) {
      return NextResponse.json({ error: 'Tarefa não encontrada' }, { status: 404 });
    }

    await prisma.task.delete({
      where: { id: parseInt(id, 10) },
    });

    return NextResponse.json({ message: 'Tarefa deletada com sucesso' });
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
