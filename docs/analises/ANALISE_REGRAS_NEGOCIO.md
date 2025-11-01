# Análise das Regras de Negócio - Sistema de Planos

## 📋 REGRAS DEFINIDAS

### 1. **PLANO FREEMIUM (Automático para novos cadastros)**
```
✅ CLARO:
- Automático ao criar conta
- 50 cargas por mês
- 1 carrier OU 1 dispatcher (depende de quem criou)
- 0 employee
- 0 driver
- 100% do sistema liberado
```

### 2. **PRIMEIRO MÊS (Promocional)**
```
✅ CLARO:
- 2 usuários gratuitos
- Cargas ILIMITADAS
- Depois volta para freemium (50 cargas/mês)
```

### 3. **PLANO PREMIUM (Pago - $10 por usuário)**
```
✅ CLARO:
- $10 por usuário
- Mínimo 2 usuários = $20/mês
- Cada usuário adicional = +$10
```

### 4. **CONDIÇÕES PARA IR PARA PREMIUM**
```
❌ CONTRADIÇÃO ENCONTRADA:

CONTRADIÇÃO 1:
- Diz: "Acima de 50 loads"
- E também: "Acima de 2 users"
→ Isso está claro

CONTRADIÇÃO 2 (CRÍTICA):
- Diz: "Se mensalmente movimento menos de 75 cargas/loads. free Eternamente"
- Mas também diz: "Acima de 50 cargas mes. $10,00 dólares por user"
→ Então qual é o limite? 50 ou 75?

CONTRADIÇÃO 3:
- Diz: "Acima de 50 cargas mes. $10,00 dólares por user"
- Mas também: "Se mensalmente movimento menos de 75 cargas/loads. free Eternamente"
→ Se passar de 50 = premium, mas se menos de 75 = free eternamente?
→ Isso significa que entre 50-74 = free eternamente? Ou premium?
```

### 5. **REGRA "MARIDO E MULHER"**
```
❌ MUITO CONFUSO:

CENÁRIO 1:
"Se mensalmente movimento menos de 75 cargas/loads. free Eternamente"
- 2 usuários (marido + mulher)
- < 75 cargas = FREE eternamente

CENÁRIO 2:
"Acima de 50 cargas mes. $10,00 dólares por user"
- 2 usuários (marido + mulher)
- > 50 cargas = $20/mês (2 x $10)

PERGUNTAS SEM RESPOSTA:
1. A regra "marido e mulher" se aplica a TODOS ou só a casais?
2. Se for só casais, como identificar isso no sistema?
3. Qual é o limite real: 50 ou 75 cargas?
4. Entre 50-74 cargas: free ou premium?
```

### 6. **CÁLCULO DO PREMIUM**
```
✅ CLARO:
- $10 por usuário
- Exemplo: 10 carrier + 1 dispatcher + 3 drivers = 14 × $10 = $140
```

### 7. **CONTADOR DE CARGAS**
```
✅ CLARO:
- Precisa contar TODAS as cargas criadas/importadas
- NUNCA diminui, mesmo se deletar cargas
- Por conta (não por usuário individual)
```

### 8. **ADICIONAR USUÁRIOS**
```
⚠️ PARCIALMENTE CLARO:
- Ao tentar adicionar usuário além do limite → página de assinatura
- $10 por usuário adicional
- Mas não ficou claro: adicionar mais de 50 cargas também leva ao premium?
```

---

## 🔴 CONTRADIÇÕES IDENTIFICADAS

### CONTRADIÇÃO PRINCIPAL: Limite de Cargas
```
REGRA 1: "Acima de 50 loads" → precisa premium
REGRA 2: "Menos de 75 loads" → free eternamente

PROBLEMA:
- Se tiver 60 cargas:
  → Passa de 50? SIM → Deveria ser PREMIUM
  → Menos de 75? SIM → Deveria ser FREE
  → CONFLITO!

POSSÍVEIS INTERPRETAÇÕES:
A) Limite real é 50 para premium, 75 é só para casais especiais?
B) Limite real é 75, e 50 foi um erro de digitação?
C) Entre 50-74 é uma "zona cinza" não definida?
```

### OUTRAS INCONSISTÊNCIAS

1. **Primeiro Mês:**
   - "2 usuários apenas" mas "cargas ilimitadas"
   - No freemium normal: só 1 usuário (carrier OU dispatcher)
   - Isso significa que primeiro mês permite 2 usuários + ilimitado?

2. **Regra de Casais:**
   - Aplica a todos ou só casais identificados?
   - Como identificar casal no sistema?
   - Isso quebra a lógica de "primeiro mês = 2 usuários"?

3. **Contador Permanente:**
   - "Cargas criadas ou importadas" - conta importações também?
   - Reseta mensalmente ou é acumulativo?
   - Precisa de um campo separado do usage_tracking mensal?

---

## ✅ O QUE ESTÁ CLARO E PODE SER IMPLEMENTADO

1. ✅ Freemium automático para novos cadastros
2. ✅ Freemium: 50 cargas/mês, 1 carrier OU 1 dispatcher
3. ✅ Premium: $10 por usuário, mínimo $20 (2 usuários)
4. ✅ Primeiro mês: 2 usuários + cargas ilimitadas
5. ✅ Contador permanente de cargas (nunca diminui)
6. ✅ Bloqueio ao exceder limites → página de assinatura

---

## ❓ PERGUNTAS PARA ESCLARECER COM O STAKEHOLDER

### CRÍTICAS (Impedem implementação):

1. **Limite de cargas para premium:**
   - É 50 ou 75 cargas/mês?
   - Se tiver entre 50-74 cargas, é free ou premium?

2. **Regra "Marido e Mulher":**
   - Se aplica a todos os usuários ou apenas casais identificados?
   - Como identificar um casal no sistema?
   - A regra de 75 cargas se aplica a todos ou só casais?

3. **Primeiro mês:**
   - Conta a partir da criação da conta?
   - Depois do primeiro mês volta para freemium (50 cargas)?
   - Ou pode ficar free eternamente se < 75 cargas?

### IMPORTANTES (Ajudam na implementação):

4. **Cálculo de usuários:**
   - Conta todos os usuários (carrier, dispatcher, employee, driver)?
   - O usuário principal (quem criou a conta) conta?
   - Usuários deletados contam?

5. **Adicionar funcionalidades:**
   - Se quiser apenas aumentar cargas (sem adicionar usuários), como funciona?
   - Tem um plano só de "mais cargas" ou precisa entrar no premium?

6. **Cancelamento:**
   - Se cancelar premium, volta para freemium?
   - O histórico de cargas permanece?

---

## 💡 SUGESTÃO DE ESTRUTURA CLARA

Para facilitar, sugiro simplificar as regras assim:

### FREEMIUM (Automático)
- 50 cargas/mês
- 1 usuário principal (carrier OU dispatcher)
- 0 adicionais (employee, driver)

### PREMIUM (A partir de $20/mês)
- **Condição 1:** Mais de 50 cargas/mês
- **Condição 2:** Mais de 2 usuários
- **Preço:** $10 por usuário (mínimo 2 = $20)

### PRIMEIRO MÊS (Promoção)
- 2 usuários
- Cargas ilimitadas
- Depois volta para freemium (se ≤50 cargas e ≤2 usuários)

### CASOS ESPECIAIS (Precisam definição)
- Regra de 75 cargas: precisa esclarecer
- Casais: precisa definir como identificar e aplicar regra

---

## 🎯 PRÓXIMOS PASSOS RECOMENDADOS

1. **Conversar com stakeholder** para esclarecer contradições
2. **Documentar regras finais** de forma clara e sem ambiguidade
3. **Criar diagrama de fluxo** das decisões de plano
4. **Implementar apenas o que está claro** (freemium básico)
5. **Deixar "casos especiais"** para fase 2 após esclarecimento

