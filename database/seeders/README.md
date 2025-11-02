# Seeders - Dispatcher Control

Este diretório contém seeders para popular o banco de dados com dados iniciais.

---

## 📋 Seeders Disponíveis

### 1. **PlansSeeder**
Cria os planos fixos do sistema:
- **Trial** - Plano de teste (30 dias)
- **Dispatcher Pro** - Plano profissional

**Como executar:**
```bash
php artisan db:seed --class=PlansSeeder
```

---

### 2. **RolesSeeder**
Cria as roles (perfis) fixas do sistema:
- **Admin** - Administrador com acesso total
- **Dispatcher** - Despachante
- **Carrier** - Transportadora
- **Broker** - Corretor

**Como executar:**
```bash
php artisan db:seed --class=RolesSeeder
```

---

### 3. **PermissionsSeeder**
Cria todas as permissões do sistema:
- Permissões de usuários (CRUD)
- Permissões de cargas (CRUD)
- Permissões de transportadoras (CRUD)
- Permissões de clientes (CRUD)
- Permissões de funcionários (CRUD)
- Permissões de motoristas (CRUD)
- Permissões de corretores (CRUD)
- Permissões de faturas (CRUD)
- Permissões de relatórios
- Permissões de dashboard
- Permissões administrativas
- Permissões de assinaturas

**Como executar:**
```bash
php artisan db:seed --class=PermissionsSeeder
```

---

### 4. **AdminUserSeeder**
Cria usuários administradores com todas as permissões:
- **Email 1:** alexandre.brito.engenharia@gmail.com
- **Email 2:** flucasrodrigues@hotmail.com
- **Senha:** dispatcher123
- **Role:** Admin (com todas as permissions)

**Como executar:**
```bash
php artisan db:seed --class=AdminUserSeeder
```

**Importante:** Este seeder:
- Cria os usuários admin se não existirem
- Atualiza se já existirem (mantém os dados existentes)
- Atribui a role Admin aos usuários
- Atribui todas as permissions à role Admin

---

## 🚀 Executar Todos os Seeders

### Opção 1: Executar todos de uma vez
```bash
php artisan db:seed
```
Isso executará o `DatabaseSeeder` que chama todos os seeders na ordem correta.

### Opção 2: Executar seeders específicos
```bash
# Apenas plans
php artisan db:seed --class=PlansSeeder

# Apenas roles
php artisan db:seed --class=RolesSeeder

# Apenas permissions
php artisan db:seed --class=PermissionsSeeder

# Apenas usuários admin
php artisan db:seed --class=AdminUserSeeder
```

---

## 📝 Ordem de Execução Recomendada

Para garantir que tudo funcione corretamente, execute na seguinte ordem:

1. **PlansSeeder** - Criar planos
2. **RolesSeeder** - Criar roles
3. **PermissionsSeeder** - Criar permissions
4. **AdminUserSeeder** - Criar usuários admin (depende de Roles e Permissions)

**Ou simplesmente execute:**
```bash
php artisan db:seed
```

O `DatabaseSeeder` já executa na ordem correta!

---

## 🔄 Executar Novamente (Idempotente)

Todos os seeders são **idempotentes**, ou seja, podem ser executados múltiplas vezes sem criar duplicatas:

- ✅ **PlansSeeder** - Usa `updateOrCreate` (não duplica)
- ✅ **RolesSeeder** - Usa `updateOrCreate` (não duplica)
- ✅ **PermissionsSeeder** - Usa `updateOrCreate` (não duplica)
- ✅ **AdminUserSeeder** - Usa `updateOrCreate` para usuários e verifica roles antes de atribuir

**Pode executar quantas vezes quiser sem problemas!**

---

## 🔐 Credenciais dos Usuários Admin

Após executar o `AdminUserSeeder`, você poderá fazer login com:

### Usuário 1
- **Email:** alexandre.brito.engenharia@gmail.com
- **Senha:** dispatcher123
- **Nome:** Alexandre Brito

### Usuário 2
- **Email:** flucasrodrigues@hotmail.com
- **Senha:** dispatcher123
- **Nome:** Flucas Rodrigues

**⚠️ IMPORTANTE:** Mude a senha após o primeiro login em produção!

---

## 🛠️ Como Usar em Deploy

### No servidor, após rodar migrations:

```bash
cd /var/www/dispatcher-control

# Executar todos os seeders
php artisan db:seed --force

# Ou executar seeders específicos
php artisan db:seed --class=PlansSeeder --force
php artisan db:seed --class=RolesSeeder --force
php artisan db:seed --class=PermissionsSeeder --force
php artisan db:seed --class=AdminUserSeeder --force
```

**Nota:** O flag `--force` é necessário em produção para evitar confirmações.

---

## 📚 Estrutura dos Seeders

### PlansSeeder
- Cria planos fixos do sistema
- Usa `updateOrCreate` para evitar duplicatas
- Baseado no campo `slug`

### RolesSeeder
- Cria roles fixas do sistema
- Usa `updateOrCreate` para evitar duplicatas
- Baseado no campo `name`

### PermissionsSeeder
- Cria todas as permissions do sistema
- Organizadas por categoria (users, loads, carriers, etc.)
- Usa `updateOrCreate` para evitar duplicatas
- Baseado no campo `name`

### AdminUserSeeder
- Cria usuários administradores
- Atribui role Admin aos usuários
- Atribui todas as permissions à role Admin
- Usa `Hash::make()` para criptografar senhas
- Usa `updateOrCreate` para evitar duplicatas

---

## 🔍 Verificar Se Funcionou

Após executar os seeders, você pode verificar:

```bash
# Verificar plans criados
php artisan tinker
>>> App\Models\Plan::all();

# Verificar roles criadas
>>> App\Models\Role::all();

# Verificar permissions criadas
>>> App\Models\Permission::count();

# Verificar usuários admin criados
>>> App\Models\User::whereIn('email', ['alexandre.brito.engenharia@gmail.com', 'flucasrodrigues@hotmail.com'])->get();

# Verificar se usuários têm a role Admin
>>> $user = App\Models\User::where('email', 'alexandre.brito.engenharia@gmail.com')->first();
>>> $user->roles;
```

---

## ⚠️ Notas Importantes

1. **Não commite senhas em texto plano** - As senhas são criptografadas com `Hash::make()` no seeder
2. **Mude as senhas em produção** - A senha padrão é apenas para desenvolvimento/teste
3. **Os seeders são idempotentes** - Pode executar múltiplas vezes sem criar duplicatas
4. **Execute na ordem correta** - Use o `DatabaseSeeder` para garantir a ordem

---

## 🐛 Troubleshooting

### Erro: "Class not found"
Certifique-se de que os modelos estão sendo importados corretamente nos seeders.

### Erro: "Duplicate entry"
Os seeders usam `updateOrCreate`, então isso não deve acontecer. Se acontecer, verifique se há dados conflitantes no banco.

### Usuário não consegue fazer login
1. Verifique se o email está correto
2. Verifique se a senha está correta: `dispatcher123`
3. Verifique se o usuário tem a role Admin
4. Verifique se o email está verificado: `email_verified_at` deve estar preenchido

---

**Última atualização:** Novembro 2024

