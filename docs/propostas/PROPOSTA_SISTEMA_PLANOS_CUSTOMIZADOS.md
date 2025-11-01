# Proposta: Sistema de Planos Customizados por Usuário

## 📋 Visão Geral

Este documento apresenta a proposta de implementação de um sistema de planos customizados onde cada usuário pode montar seu próprio plano baseado na quantidade de usuários necessários, ao invés de escolher entre planos pré-definidos.

---

## 🎯 Objetivo

Permitir que os usuários:
- **Montem seu plano personalizado** escolhendo quantos usuários de cada tipo precisam
- **Vejam o custo em tempo real** conforme ajustam as quantidades
- **Tenham planos exclusivos** vinculados à sua conta
- **Escalem conforme necessário** ajustando o plano quando precisar

---

## 💰 Modelo de Preços

### Regra de Cobrança
- **$10 por usuário/mês**
- **Mínimo:** 2 usuários = **$20/mês**
- Cada usuário adicional = +$10

### Tipos de Usuários Contabilizados
1. **Carriers**
2. **Dispatchers**
3. **Employees**
4. **Drivers**
5. **Brokers**

### Exemplo de Cálculo
```
Usuário quer:
- 2 Carriers
- 1 Dispatcher
- 3 Employees
- 5 Drivers

Total: 11 usuários
Custo: 11 × $10 = $110/mês
```

---

## 🏗️ Arquitetura Proposta

### Estrutura do Banco de Dados

#### Tabela `plans` (Expandida)
Cada usuário terá seu próprio plano customizado salvo na tabela `plans`:

| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | bigint | ID do plano |
| `user_id` | bigint (FK) | **NOVO:** ID do usuário proprietário (NULL = plano global) |
| `name` | string | Nome do plano |
| `slug` | string | Identificador único (único por user_id) |
| `price` | decimal | Preço mensal calculado |
| `max_carriers` | integer | Quantidade de carriers permitidos |
| `max_dispatchers` | integer | Quantidade de dispatchers permitidos |
| `max_employees` | integer | Quantidade de employees permitidos |
| `max_drivers` | integer | Quantidade de drivers permitidos |
| `max_loads_per_month` | integer | Limite de cargas/mês (null = ilimitado) |
| `is_custom` | boolean | **NOVO:** Flag para identificar plano customizado |
| `active` | boolean | Status ativo/inativo |
| `created_at` | datetime | Data de criação |
| `updated_at` | datetime | Data de atualização |

**Planos Globais vs Customizados:**
- **Planos Globais:** `user_id = NULL` (freemium, trial, etc)
- **Planos Customizados:** `user_id` preenchido (exclusivo para cada usuário)

---

## 🔄 Fluxos de Uso

### Fluxo 1: Usuário Novo (Freemium Automático)

```
1. Usuário cria conta
   ↓
2. Sistema cria automaticamente assinatura FREEMIUM:
   - 50 cargas/mês
   - 1 Carrier E 1 Dispatcher 
   - 0 Employees
   - 0 Drivers
   - $0/mês
   ↓
3. Primeiro mês: 2 usuários + cargas ILIMITADAS (promoção)
   ↓
4. Após primeiro mês: volta para freemium (50 cargas)
```

---

### Fluxo 2: Upgrade para Premium Customizado

```
1. Usuário tenta adicionar usuário além do limite OU usar mais de 50 cargas
   ↓
2. Sistema bloqueia ação e exibe tela "Montar Seu Plano"
   ↓
3. Usuário monta plano:
   - Seleciona quantidades de cada tipo de usuário
   - Sistema calcula preço em tempo real
   - Exemplo: 2 carriers + 1 dispatcher + 3 drivers = 6 usuários = $60/mês
   ↓
4. Usuário confirma configuração
   ↓
5. Sistema cria plano customizado na tabela plans vinculado ao user_id
   ↓
6. Sistema redireciona para checkout Stripe
   ↓
7. Após pagamento confirmado:
   - Cria/atualiza subscription com o plano customizado
   - Aplica limites conforme configuração
   - Usuário pode usar o sistema com novos limites
```

---

### Fluxo 3: Ajustar Plano Existente

```
1. Usuário já tem plano premium customizado (ex: 5 usuários = $50/mês)
   ↓
2. Usuário precisa adicionar mais 3 drivers
   ↓
3. Usuário acessa "Gerenciar Plano"
   ↓
4. Sistema mostra configuração atual:
   - 2 carriers, 1 dispatcher, 2 drivers
   - Total: 5 usuários = $50/mês
   ↓
5. Usuário ajusta: adiciona 3 drivers
   - Nova configuração: 2 carriers, 1 dispatcher, 5 drivers
   - Novo total: 8 usuários = $80/mês
   - Diferença: +$30/mês
   ↓
6. Sistema mostra:
   - Ajuste será aplicado no próximo ciclo
   - OU aplicado imediatamente com recálculo proporcional
   ↓
7. Usuário confirma
   ↓
8. Sistema atualiza plano customizado existente (não cria novo)
   ↓
9. Sistema processa pagamento da diferença (se imediato)
```

---

## 📱 Interface Proposta

### Tela "Montar Seu Plano"

```
┌─────────────────────────────────────────────────────────┐
│  MONTE SEU PLANO PERSONALIZADO                         │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Selecione quantos usuários de cada tipo você precisa: │
│                                                         │
│  ┌─────────────────────────────────────────────────┐  │
│  │ Carriers                                        │  │
│  │ [  -  ]  2  [  +  ]    @ $10 cada              │  │
│  └─────────────────────────────────────────────────┘  │
│                                                         │
│  ┌─────────────────────────────────────────────────┐  │
│  │ Dispatchers                                     │  │
│  │ [  -  ]  1  [  +  ]    @ $10 cada              │  │
│  └─────────────────────────────────────────────────┘  │
│                                                         │
│  ┌─────────────────────────────────────────────────┐  │
│  │ Employees                                       │  │
│  │ [  -  ]  0  [  +  ]    @ $10 cada              │  │
│  └─────────────────────────────────────────────────┘  │
│                                                         │
│  ┌─────────────────────────────────────────────────┐  │
│  │ Drivers                                         │  │
│  │ [  -  ]  3  [  +  ]    @ $10 cada              │  │
│  └─────────────────────────────────────────────────┘  │
│                                                         │
│  ───────────────────────────────────────────────────  │
│                                                         │
│  Total de usuários: 6                                   │
│  Preço mensal: $60.00                                  │
│                                                         │
│  ℹ️  Mínimo de 2 usuários obrigatório ($20/mês)      │
│                                                         │
│  [Cancelar]                    [Continuar para Pagamento]│
└─────────────────────────────────────────────────────────┘
```

### Tela de Checkout (Integração Stripe)

```
┌─────────────────────────────────────────────────────────┐
│  RESUMO DO PLANO                                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  ✅ Plano Customizado                                  │
│                                                         │
│  Configuração:                                          │
│  • 2 Carriers                                           │
│  • 1 Dispatcher                                         │
│  • 3 Drivers                                            │
│  • Total: 6 usuários                                   │
│                                                         │
│  ───────────────────────────────────────────────────  │
│  Total: $60.00/mês                                     │
│                                                         │
│  ┌─────────────────────────────────────────────────┐  │
│  │  [Stripe Payment Form]                          │  │
│  │  Cartão: [________________]                     │  │
│  │                                                   │  │
│  │  [Finalizar Assinatura - $60.00]                │  │
│  └─────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

---

## 📊 Casos de Uso Detalhados

### Caso 1: Usuário Novo - Primeiro Mês Promocional

**Situação:**
- Usuário criou conta hoje
- Está no primeiro mês (promoção ativa)

**Limites:**
- ✅ 2 usuários gratuitos
- ✅ Cargas ILIMITADAS
- ❌ Não pode adicionar mais usuários (só 2)

**Após Primeiro Mês:**
- Se ≤ 50 cargas/mês e ≤ 2 usuários → Freemium ($0)
- Se > 50 cargas OU > 2 usuários → Precisa upgrade

---

### Caso 2: Upgrade por Limite de Cargas

**Situação:**
- Usuário no freemium: 50 cargas/mês, 1 usuário
- Tentou criar carga #51

**Sistema:**
1. Bloqueia criação
2. Mostra mensagem: "Você atingiu o limite de 50 cargas/mês"
3. Oferece: "Upgrade para Premium - Cargas ilimitadas"
4. Redireciona para tela "Montar Seu Plano"

**Usuário escolhe:**
- Apenas manter 1 usuário
- Mas precisa de cargas ilimitadas

**Solução:**
- Mínimo 2 usuários = $20/mês
- Plano: 1 carrier + 1 dispatcher + cargas ilimitadas

---

### Caso 3: Upgrade por Adicionar Usuário

**Situação:**
- Usuário no freemium: 50 cargas, 1 carrier, 1 dispatcher
- Tenta adicionar +1 carrier

**Sistema:**
1. Bloqueia adição
2. Mostra: "Limite do plano freemium: 1 usuário carrier e 1 dispatcher"
3. Oferece upgrade

**Usuário monta:**
- 1 carrier (já tem)
- 1 dispatcher (já tem)
- 1 dispatcher (quer adicionar)
- Total: 3 usuários = $30/mês ✅

---

### Caso 4: Escalar Plano Existente

**Situação:**
- Usuário já tem premium: 3 carriers + 2 drivers = $50/mês
- Quer adicionar 5 drivers

**Nova configuração:**
- 3 carriers + 7 drivers = 10 usuários = $100/mês
- Diferença: +$50/mês

**Opções:**
1. **Aplicar no próximo ciclo:** Mantém $50 até final do mês, depois $100
2. **Aplicar imediato:** Paga proporcional ($50 + ajuste proporcional)

---

## 🔐 Regras de Validação

### Validações no Backend

1. **Mínimo de Usuários:**
   - Erro se total < 2 usuários
   - Mensagem: "Mínimo de 2 usuários obrigatório ($20/mês)"

2. **Valores Negativos:**
   - Não permitir quantidades negativas
   - Mínimo 0 para cada tipo

3. **Limite Superior (Opcional):**
   - Definir máximo? (ex: 100 usuários por conta?)
   - Por enquanto: sem limite superior

4. **Usuários Existentes:**
   - Ao reduzir plano, não pode ter menos usuários do que já cadastrados
   - Exemplo: Tem 5 carriers cadastrados, não pode reduzir para 3

---

## 💾 Persistência de Dados

### Plano Customizado Criado

Quando usuário finaliza o checkout:
1. **Cria plano** na tabela `plans`:
   ```php
   Plan::create([
       'user_id' => $user->id,
       'name' => "Plano Customizado - 6 usuários",
       'slug' => 'custom-user-123-timestamp',
       'price' => 6000,
       'max_carriers' => 2,
       'max_dispatchers' => 1,
       'max_drivers' => 3,
       'max_employees' => 0,
       'max_loads_per_month' => null, // Ilimitado
       'is_custom' => true,
   ]);
   ```

2. **Cria/Atualiza subscription**:
   ```php
   Subscription::create([
       'user_id' => $user->id,
       'plan_id' => $customPlan->id,
       'status' => 'active',
       'amount' => 6000,
       // ...
   ]);
   ```

### Atualização de Plano Existente

Quando usuário ajusta plano já existente:
- **Não cria novo plano**
- **Atualiza plano customizado existente**
- Mantém mesmo `plan_id` na subscription
- Histórico preservado

---

## 🔄 Ciclo de Vida do Plano

### Estados Possíveis

1. **Freemium** (automático)
   - $0/mês
   - 50 cargas/mês
   - 1 usuário

2. **Premium Customizado** (pago)
   - $20+ /mês (conforme configuração)
   - Cargas ilimitadas (ou conforme regras finais)
   - 2+ usuários

3. **Cancelado**
   - Subscription marcada como `cancelled`
   - Volta para freemium no final do ciclo
   - Plano customizado mantido no banco (para histórico)

4. **Bloqueado**
   - Pagamento falhou
   - Acesso limitado até regularizar

---

## 📈 Escalabilidade

### Vantagens da Abordagem

1. **Flexibilidade:**
   - Cada usuário paga exatamente pelo que usa
   - Não precisa escolher entre "plano pequeno" ou "plano grande"

2. **Transparência:**
   - Preço calculado em tempo real
   - Usuário vê exatamente quanto vai pagar

3. **Escalabilidade:**
   - Fácil adicionar novos tipos de usuários no futuro
   - Fácil ajustar preços por tipo (ex: carrier = $10, dispatcher = $15)

4. **Histórico:**
   - Cada plano customizado é salvo
   - Possível gerar relatórios de uso
   - Possível analisar padrões de consumo

---

## ⚠️ Pontos de Atenção

### 1. Planos Globais vs Customizados

**Problema:** Como diferenciar?
**Solução:** Usar `user_id`:
- `user_id = NULL` → Plano global (freemium, trial)
- `user_id` preenchido → Plano customizado

### 2. Busca de Planos

**Problema:** Ao listar planos, não mostrar planos de outros usuários
**Solução:** Sempre filtrar:
```php
// Planos globais
Plan::whereNull('user_id')->get();

// Plano customizado do usuário
Plan::where('user_id', $user->id)->first();
```

### 3. Slug Único

**Problema:** Slug precisa ser único, mas vários usuários podem ter planos customizados
**Solução:** Índice composto `(slug, user_id)`:
- Planos globais: slug único
- Planos customizados: slug único por user_id

### 4. Atualização de Plano

**Problema:** Se usuário ajusta plano, criar novo ou atualizar?
**Solução:** Sempre atualizar plano existente do usuário (não criar novo)

---

## 📋 Checklist de Implementação

### Backend
- [ ] Migration: adicionar `user_id`, `max_dispatchers`, `is_custom` em `plans`
- [ ] Model: adicionar relacionamento `user()` e scopes
- [ ] Service: métodos `createCustomPlan()` e `calculateCustomPlanPrice()`
- [ ] Controller: rotas para criar/atualizar plano customizado
- [ ] Validação: mínimo 2 usuários, valores não negativos
- [ ] Integração Stripe: PaymentIntent com valor calculado

### Frontend
- [ ] View: tela "Montar Seu Plano" com contadores dinâmicos
- [ ] JavaScript: cálculo em tempo real do preço
- [ ] Integração: checkout Stripe integrado
- [ ] Validação: feedback visual de limites mínimos

### Testes
- [ ] Testar criação de plano customizado
- [ ] Testar atualização de plano existente
- [ ] Testar validação de mínimo 2 usuários
- [ ] Testar cálculo de preço
- [ ] Testar integração Stripe
- [ ] Testar limites de usuários vs usuários cadastrados

---

## ❓ Perguntas

1. **Limite de Cargas no Premium:**
   - Premium tem cargas ilimitadas?
   - Ou também tem limite (ex: 75, 100, 200)?

2. **Ajuste de Plano:**
   - Quando usuário ajusta plano, aplica imediatamente ou no próximo ciclo?
   - Como calcular proporcional se imediato?

3. **Preços Diferentes por Tipo:**
   - Todos os tipos custam $10 ou pode variar?
   - Exemplo: Carrier = $10, Dispatcher = $15?

4. **Máximo de Usuários:**
   - Tem limite superior? (ex: máximo 100 usuários por conta)
   - Ou sem limite?

5. **Desconto para Volume:**
   - Se tiver muitos usuários, há desconto?
   - Exemplo: 10+ usuários = 10% desconto?

---

## 📝 Próximos Passos

1. **Aprovação:**
   - Revisar proposta
   - Responder perguntas pendentes
   - Aprovar estrutura

2. **Ajustes Baseados em Feedback:**
   - Modificar estrutura se necessário
   - Ajustar regras de negócio

---

