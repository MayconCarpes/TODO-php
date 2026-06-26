import { NextResponse } from 'next/server';
import { prisma } from '@/lib/prisma';
import bcrypt from 'bcryptjs';

export async function GET(request: Request) {
  const { searchParams } = new URL(request.url);
  const usuario_id = searchParams.get('usuario_id');

  if (!usuario_id) return NextResponse.json({ error: 'Acesso negado' }, { status: 400 });

  try {
    const adminUser = await prisma.user.findUnique({ where: { id: parseInt(usuario_id, 10) } });
    if (!adminUser || adminUser.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Permissão negada' }, { status: 403 });
    }

    const users = await prisma.user.findMany({
      orderBy: { id: 'desc' }
    });
    
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const sanitizedUsers = users.map(({ senha, ...user }) => user);

    return NextResponse.json(sanitizedUsers);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}

export async function POST(request: Request) {
  const { searchParams } = new URL(request.url);
  const usuario_id = searchParams.get('usuario_id');

  if (!usuario_id) return NextResponse.json({ error: 'Acesso negado' }, { status: 400 });

  try {
    const adminUser = await prisma.user.findUnique({ where: { id: parseInt(usuario_id, 10) } });
    if (!adminUser || adminUser.perfil !== 'ADMIN') {
      return NextResponse.json({ error: 'Permissão negada' }, { status: 403 });
    }

    const data = await request.json();
    const { nome, email, senha, perfil } = data;

    if (!nome || !email || !senha) {
      return NextResponse.json({ error: 'Campos obrigatórios faltando' }, { status: 400 });
    }

    const existingUser = await prisma.user.findUnique({ where: { email } });
    if (existingUser) {
      return NextResponse.json({ error: 'E-mail já está em uso' }, { status: 400 });
    }

    const hashedPassword = await bcrypt.hash(senha, 10);
    const newUser = await prisma.user.create({
      data: {
        nome,
        email,
        senha: hashedPassword,
        perfil: perfil || 'ALUNO',
      },
    });

    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { senha: _, ...userWithoutPassword } = newUser;
    return NextResponse.json(userWithoutPassword);
  } catch (error) {
    return NextResponse.json({ error: 'Erro interno do servidor' }, { status: 500 });
  }
}
