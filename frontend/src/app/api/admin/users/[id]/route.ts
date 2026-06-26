import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import bcrypt from 'bcryptjs';

export async function PUT(
  request: Request,
  { params }: { params: Promise<{ id: string }> }
) {
  try {
    const { id } = await params;
    const { searchParams } = new URL(request.url);
    const admin_id = searchParams.get('usuario_id');

    if (!admin_id) return NextResponse.json({ error: 'Acesso negado' }, { status: 400 });

    const adminUser = await prisma.user.findUnique({ where: { id: parseInt(admin_id, 10) } });
    if (!adminUser || adminUser.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Permissão negada' }, { status: 403 });
    }

    const data = await request.json();
    const { nome, email, senha, perfil } = data;
    
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const updates: any = {};
    if (nome) updates.nome = nome;
    if (email) updates.email = email;
    if (perfil) updates.perfil = perfil;
    if (senha) {
      updates.senha = await bcrypt.hash(senha, 10);
    }

    const updatedUser = await prisma.user.update({
      where: { id: parseInt(id, 10) },
      data: updates,
    });

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { senha: _, ...userWithoutPassword } = updatedUser;
    return NextResponse.json(userWithoutPassword);
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
    const admin_id = searchParams.get('usuario_id');

    if (!admin_id) return NextResponse.json({ error: 'Acesso negado' }, { status: 400 });

    const adminUser = await prisma.user.findUnique({ where: { id: parseInt(admin_id, 10) } });
    if (!adminUser || adminUser.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Permissão negada' }, { status: 403 });
    }

    await prisma.user.delete({
      where: { id: parseInt(id, 10) },
    });

    return NextResponse.json({ message: 'Usuário deletado com sucesso' });
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
