const bcrypt = require('bcryptjs');
const { PrismaClient } = require('@prisma/client');
const prisma = new PrismaClient();

async function main() {
  const hash = await bcrypt.hash('123', 10);
  await prisma.user.upsert({
    where: { email: 'admin@admin.com' },
    update: { senha: hash, perfil: 'ADMIN' },
    create: {
      nome: 'Professor Admin',
      email: 'admin@admin.com',
      senha: hash,
      perfil: 'ADMIN'
    }
  });
  console.log('Admin criado!');
}

main()
  .catch(console.error)
  .finally(() => prisma.$disconnect());
