Após analisar as regras de negócio, identifiquei alguns pontos que precisam de esclarecimento para implementarmos corretamente. 
---

## 🔴 PERGUNTAS CRÍTICAS (Precisam resposta antes de implementar)

### 1. **Limite de Cargas para Premium**

Você mencionou:
- "Acima de 50 cargas/mês" → precisa premium
- "Menos de 75 cargas/mês" → free eternamente

**Pergunta:** Qual é o limite REAL para ir para premium?
- [ ] **A)** 50 cargas/mês (acima disso = premium)
- [ ] **B)** 75 cargas/mês (acima disso = premium)
- [ ] **C)** Outro valor: _______

**Pergunta de seguimento:** Se alguém tiver entre 50-74 cargas/mês, é FREE ou PREMIUM?

---

### 2. **Regra "Marido e Mulher" - Aplicação**

Você mencionou que se "mensalmente movimento menos de 75 cargas, free eternamente" mas só explicou isso no contexto de "marido e mulher".

**Pergunta:** A regra de "menos de 75 cargas = free eternamente" se aplica:
- [ ] **A)** A TODOS os usuários (não só casais)
- [ ] **B)** Só a casais identificados no sistema
- [ ] **C)** Foi um erro de digitação e o limite real é outro?

**Se a resposta for B:** Como identificamos um casal no sistema?
- [ ] Mesmo endereço de email
- [ ] Campo específico "conta vinculada"
- [ ] Outro método: _______

---

### 3. **Primeiro Mês vs. Regra Permanente**

Você mencionou:
- "Primeiro mês: 2 usuários + cargas ilimitadas"
- "Depois volta para freemium (50 cargas)"

**Pergunta:** Depois do primeiro mês:
- Se a conta tiver < 75 cargas/mês → permanece FREE eternamente? (mesmo depois do primeiro mês)
- OU volta para freemium (50 cargas) e só fica free eternamente se tiver < 75 cargas no mês atual?

---

### 4. **Contagem de Usuários**

**Pergunta:** Quando dizemos "$10 por usuário", contamos:
- [ ] **A)** Todos os tipos: carrier, dispatcher, employee, driver
- [ ] **B)** Só adicionais (não conta o usuário principal)
- [ ] **C)** Outro critério: _______

**Exemplo:** Se tem:
- 1 dispatcher (principal)
- 1 carrier
- 3 drivers

O custo seria:
- [ ] $30 (3 usuários × $10)
- [ ] $40 (4 usuários × $10)
- [ ] Outro: _______

---

## 🟡 PERGUNTAS IMPORTANTES (Ajudam na implementação)

### 5. **Aumentar Apenas Cargas (Sem Adicionar Usuários)**

**Pergunta:** Se alguém está no freemium (50 cargas) e quer usar 75 cargas/mês, mas não quer adicionar usuários:
- [ ] Precisa entrar no premium ($10 por usuário mínimo = $20)?
- [ ] Ou tem uma opção só de "mais cargas"?
- [ ] Ou é sempre $10 por usuário, independente de cargas?

---

### 6. **Cancelamento de Premium**

**Pergunta:** Se alguém cancela o premium:
- [ ] Volta automaticamente para freemium (50 cargas, 1 usuário)?
- [ ] Mantém acesso até o final do mês pago?
- [ ] Outro comportamento: _______

---

### 7. **Usuários Deletados**

**Pergunta:** Se alguém deleta um usuário durante o mês:
- [ ] O valor do plano muda imediatamente?
- [ ] Continua cobrando até o final do mês?
- [ ] A conta volta para freemium se ficar ≤ 2 usuários e ≤ 50 cargas?

---

### 8. **Contador de Cargas Permanente**

Você mencionou que "mesmo deletando cargas, o contador nunca diminui".

**Pergunta:** Isso significa:
- [ ] Contamos TOTAL de cargas já criadas (acumulativo para sempre)?
- [ ] OU contamos cargas criadas no mês atual (mas não diminui se deletar)?

**Exemplo:** Se alguém criou 100 cargas no mês, mas deletou 50:
- O contador mostra: 100 OU 50?

---

## 📋 RESUMO PARA VALIDAÇÃO

Para eu implementar corretamente, preciso confirmar estas regras finais:

### FREEMIUM (Automático)
- [ ] 50 cargas/mês OU 75 cargas/mês? → _______
- [ ] 1 usuário principal (carrier OU dispatcher)
- [ ] 0 adicionais (employee, driver)

### PREMIUM
- [ ] Limite para ir para premium: _______ cargas/mês OU _______ usuários
- [ ] Preço: $10 por usuário (mínimo $20 = 2 usuários)
- [ ] Conta todos os usuários OU só adicionais?

### PRIMEIRO MÊS
- [ ] 2 usuários + cargas ilimitadas
- [ ] Depois: volta para freemium OU pode ficar free se < 75 cargas?

---

