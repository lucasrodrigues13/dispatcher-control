# Regras de Planos - Sistema de Billing

## 📋 Plano Freemium (Automático)

**Limites:**
- ✅ 75 cargas/mês
- ✅ 2 cadastros (dispatcher + carrier OU carrier + dispatcher)
- ✅ 0 employees, 0 drivers, 0 brokers
- ✅ Sistema 100% funcional

**Condição para permanecer free:**
- ≤ 75 cargas/mês + ≤ 2 cadastros = **FREE PARA SEMPRE**

---

## 🎁 Primeiro Mês (Promocional)

**Limites especiais:**
- ✅ 2 cadastros (dispatcher + carrier)
- ✅ **Cargas ILIMITADAS**
- ✅ Duração: 30 dias a partir da criação da conta

**Após primeiro mês:**
- Volta automaticamente para freemium (75 cargas/mês)

---

## 💰 Plano Premium Customizado

**Quando precisa:**
- Mais de 75 cargas/mês **OU**
- Mais de 2 cadastros

**Preço:**
- $10 por usuário/mês
- Mínimo: $20/mês (2 usuários)

**Tipos de usuários contabilizados:**
- Carriers
- Dispatchers
- Employees
- Drivers
- Brokers

**Exemplo:**
```
10 carriers + 1 dispatcher + 3 drivers = 14 usuários
Custo: 14 × $10 = $140/mês
```

---

## 🔄 Fluxos

### Novo Usuário
1. Cria conta → Entra no freemium automaticamente
2. Primeiro mês: 2 cadastros + cargas ilimitadas
3. Após 30 dias: freemium padrão (75 cargas/mês)

### Upgrade para Premium
1. Usuário tenta criar carga #76 **OU** adicionar 3º cadastro
2. Sistema bloqueia e mostra "Montar Seu Plano"
3. Usuário escolhe quantidades de cada tipo
4. Sistema calcula preço em tempo real
5. Checkout Stripe
6. Após pagamento: limites aplicados

### Ajustar Plano Existente
1. Usuário já tem premium (ex: 5 usuários = $50/mês)
2. Quer adicionar mais 3 drivers
3. Acessa "Gerenciar Plano"
4. Ajusta quantidades
5. Sistema calcula diferença proporcional
6. Paga diferença → Acesso imediato

---

## 📊 Estrutura Banco de Dados

### Tabela `plans`
```php
- id
- user_id (NULL = plano global, preenchido = customizado)
- name
- slug
- price
- max_loads_per_month (NULL = ilimitado)
- max_carriers
- max_dispatchers
- max_employees
- max_drivers
- max_brokers
- is_custom (boolean)
- active (boolean)
```

### Contador Permanente de Cargas
- Contar TODAS as cargas criadas/importadas por conta
- **NUNCA diminui**, mesmo se deletar cargas
- Usado para verificar limite de 75 cargas/mês

---

## ✅ Validações

1. **Mínimo 2 usuários** sempre (freemium ou premium)
2. **Valores não negativos** nas quantidades
3. **Não pode reduzir** para menos usuários do que já cadastrados
4. **Bloqueio automático** ao exceder limites

---

## 🎯 Próximos Passos de Implementação

1. ✅ Atualizar Model Plan (já feito)
2. ⏳ Criar migration para `max_brokers`
3. ⏳ Implementar contador permanente de cargas
4. ⏳ Implementar lógica de primeiro mês promocional
5. ⏳ Implementar bloqueio ao exceder limites
6. ⏳ Criar tela "Montar Seu Plano"
7. ⏳ Integração Stripe para checkout
8. ⏳ Lógica de upgrade/downgrade de planos

