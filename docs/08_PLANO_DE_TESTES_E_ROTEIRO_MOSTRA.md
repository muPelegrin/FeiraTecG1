# 8. Plano de Testes, Casos de Teste e Roteiro da Mostra
**Papel Técnico:** QA / Integração / Apresentação  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Suíte de Testes Automatizada (`tests/run_tests.php`)

Foi desenvolvido um script automatizado que executa 20 asserções de integridade no banco de dados e na lógica de negócio.
Para executar via terminal:
```powershell
php c:\Turma2\xampp\htdocs\FeiraTecG1\tests\run_tests.php
```

### Resultados Obtidos:
- **Testes executados:** 20
- **Testes aprovados:** 20 / 20 (100% de sucesso)
- **Cobertura:** Autenticação, perfis de acesso, integridade de matrículas, regra de chamada única por dia, contagem nominal de frequência, cálculo da cozinha, alerta de pendências, restrições alimentares operacionais e trilha de auditoria.

---

## 2. Roteiro Sugerido para a Apresentação na Mostra de Tecnologia

Este roteiro reproduz fielmente a simulação de um início de dia letivo:

### Passo 1: O Início do Dia e o Problema do Desperdício (1 minuto)
1. Abrir a tela de login (`http://localhost/FeiraTecG1/`).
2. Explicar brevemente aos avaliadores o problema: escolas perdem comida por falta de comunicação entre as salas de aula e a cozinha.

### Passo 2: O Inspetor Registra as Chamadas (2 minutos)
1. Clicar no botão da conta de demonstração: **Maria (Inspetora)** (`maria@escola.com`).
2. Entrar no sistema e acessar **Chamada Digital** ou **Painel da Inspetoria**.
3. Notar que a **Turma 6A** já foi concluída com 6 presentes e 2 faltas.
4. Abrir a chamada da **Turma 6B** (que estava pendente).
5. Na lista nominal de alunos da 6B:
   - Clicar no botão de produtividade *"Marcar Todos Presentes"*.
   - Alterar manualmente 1 aluno para *Ausente* e 1 para *Justificado* com observação "Consulta dentista".
   - Clicar no botão **✅ Concluir Chamada**.
6. Fazer logout da Maria.

### Passo 3: A Cozinha Acompanha a Previsão em Tempo Real (2 minutos)
1. Fazer login com a conta de **João (Cozinha)** (`joao@escola.com`).
2. Acessar o **Painel da Cozinha** (`/cozinha`).
3. **Demonstrar aos professores:**
   - O total de *Presentes Confirmados* subiu automaticamente com a conclusão da Turma 6B!
   - O sistema exibe o **Alerta de Informação Incompleta (Amarelo)**, indicando que as turmas do turno vespertino (7A e 7B) ainda não foram chamadas, calculando-as apenas como projeção provisória.
   - Mostrar a seção de **Dietas Especiais**: ressaltar que a Cozinha vê apenas orientações como *"Sem glúten - substituir por fruta"*, sem diagnósticos médicos invasivos (respeito à privacidade do estudante).
4. Fazer logout do João.

### Passo 4: A Gestora e a Correção Auditada (2 minutos)
1. Fazer login com **Ana (Gestora)** (`ana@escola.com`).
2. Acessar **Chamada Digital** -> Ver detalhes da **Turma 6A**.
3. Clicar em **✏️ Corrigir** em um dos alunos que constavam ausentes (simulando que o aluno chegou atrasado com autorização dos pais).
4. Tentar salvar sem justificativa -> Mostrar o bloqueio do sistema.
5. Digitar a justificativa: *"Aluno apresentou comprovante de transporte atrasado na recepção"* e salvar.
6. Acessar **Relatórios -> Auditoria** (`/relatorios/auditoria`):
   - Mostrar aos avaliadores o registro imutável com nome da Ana, data, hora, valor anterior (*ausente*), valor novo (*presente*) e a justificativa digitada.
7. Acessar **Relatórios -> Frequência** (`/relatorios/frequencia`):
   - Mostrar a taxa percentual de presença e clicar em **📥 Exportar CSV** para demonstrar o arquivo gerado para o Excel.

### Passo 5: Encerramento e Conclusão (1 minuto)
- Concluir destacando a arquitetura MVC pura, aderência às regras da escola e impacto prático na sustentabilidade e combate ao desperdício de merenda escolar.
