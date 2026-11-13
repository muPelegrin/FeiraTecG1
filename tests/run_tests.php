<?php

/**
 * Suite de Testes Funcionais e Regras de Negócio
 * Central de Frequência e Alimentação Escolar (Grupo 01)
 *
 * @package FeiraTecG1
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Helpers.php';
require_once ROOT_PATH . '/app/models/Usuario.php';
require_once ROOT_PATH . '/app/models/AnoLetivo.php';
require_once ROOT_PATH . '/app/models/Turma.php';
require_once ROOT_PATH . '/app/models/Aluno.php';
require_once ROOT_PATH . '/app/models/Matricula.php';
require_once ROOT_PATH . '/app/models/Chamada.php';
require_once ROOT_PATH . '/app/models/Frequencia.php';
require_once ROOT_PATH . '/app/models/Cardapio.php';
require_once ROOT_PATH . '/app/models/ItemCardapio.php';
require_once ROOT_PATH . '/app/models/RestricaoAlimentar.php';
require_once ROOT_PATH . '/app/models/HistoricoAlteracao.php';

$totalTestes = 0;
$testesPassados = 0;

function assertTest(string $descricao, bool $condicao): void {
    global $totalTestes, $testesPassados;
    $totalTestes++;
    if ($condicao) {
        $testesPassados++;
        echo "  [OK] " . $descricao . "\n";
    } else {
        echo "  [FALHA] " . $descricao . "\n";
    }
}

echo "============================================================\n";
echo " INICIANDO SUÍTE DE TESTES: MOSTRA DE TECNOLOGIA 2026\n";
echo " Sistema: Central de Frequência e Alimentação Escolar\n";
echo "============================================================\n\n";

$db = Database::connect();

// ------------------------------------------------------------
// TESTE 1: Autenticação e Usuários
// ------------------------------------------------------------
echo "1. TESTES DE AUTENTICAÇÃO E PERFIS DE ACESSO\n";
$usuarioModel = new Usuario();
$admin = $usuarioModel->findByEmail('admin@escola.com');
assertTest('Usuário admin existe no banco', $admin !== false);
assertTest('Perfil do admin é "admin"', $admin && $admin->perfil === 'admin');
assertTest('Senha do admin confere com "senha123"', $admin && password_verify('senha123', $admin->senha_hash));

$inspetora = $usuarioModel->findByEmail('maria@escola.com');
assertTest('Inspetora Maria existe com perfil "inspetor"', $inspetora && $inspetora->perfil === 'inspetor');

$cozinheiro = $usuarioModel->findByEmail('joao@escola.com');
assertTest('Cozinheiro João existe com perfil "cozinha"', $cozinheiro && $cozinheiro->perfil === 'cozinha');

$gestora = $usuarioModel->findByEmail('ana@escola.com');
assertTest('Gestora Ana existe com perfil "gestor"', $gestora && $gestora->perfil === 'gestor');

// ------------------------------------------------------------
// TESTE 2: Cadastros Institucionais (Turmas e Alunos)
// ------------------------------------------------------------
echo "\n2. TESTES DE CADASTROS INSTITUCIONAIS\n";
$turmaModel = new Turma();
$turmas = $turmaModel->findAllComAnoLetivo();
assertTest('Existem turmas cadastradas para o ano letivo', count($turmas) >= 4);

$alunoModel = new Aluno();
$totalAlunos = $alunoModel->totalAtivos();
assertTest('Existem 30 alunos fictícios cadastrados e ativos', $totalAlunos >= 30);

$alunosTurma6A = $alunoModel->findByTurma(1);
assertTest('Turma 6A possui 8 alunos matriculados', count($alunosTurma6A) === 8);

// ------------------------------------------------------------
// TESTE 3: Regra de Negócio de Chamada (Unicidade por Turma e Data)
// ------------------------------------------------------------
echo "\n3. TESTES DE CHAMADA DIGITAL E UNICIDADE\n";
$chamadaModel = new Chamada();
$dataHoje = date('Y-m-d');

// Turma 1 (6A) já tem chamada hoje do seed
$chamada6A = $chamadaModel->findByTurmaData(1, $dataHoje);
assertTest('Chamada da turma 6A existe para hoje', $chamada6A !== false);
assertTest('Chamada da turma 6A está com status "concluida"', $chamada6A && $chamada6A->status === 'concluida');

// Teste de constraint UNIQUE: tentar criar duplicata deve falhar
$duplicadaFalhou = false;
try {
    $chamadaModel->create([
        'turma_id'   => 1,
        'data'       => $dataHoje,
        'status'     => 'aberta',
        'usuario_id' => $inspetora->id,
        'aberta_em'  => date('Y-m-d H:i:s'),
    ]);
} catch (PDOException $e) {
    $duplicadaFalhou = true;
}
assertTest('Regra de Negócio: Bloqueada criação de segunda chamada para mesma turma/data', $duplicadaFalhou);

// ------------------------------------------------------------
// TESTE 4: Frequência Nominal Completa
// ------------------------------------------------------------
echo "\n4. TESTES DE FREQUÊNCIA NOMINAL E INTEGRIDADE\n";
$frequenciaModel = new Frequencia();
$frequencias6A = $frequenciaModel->findByChamada($chamada6A->id);
assertTest('Todos os 8 alunos da Turma 6A possuem registro de frequência', count($frequencias6A) === 8);

$chamadaDetalhes = $chamadaModel->findComDetalhes($chamada6A->id);
assertTest('Totais da 6A: 6 presentes, 1 ausente, 1 justificado', 
    $chamadaDetalhes && 
    $chamadaDetalhes->total_presentes == 6 && 
    $chamadaDetalhes->total_ausentes == 1 && 
    $chamadaDetalhes->total_justificados == 1
);

// ------------------------------------------------------------
// TESTE 5: Consolidação Operacional da Cozinha e Alerta de Pendência
// ------------------------------------------------------------
echo "\n5. TESTES DO PAINEL DA COZINHA E REGRAS DE PREVISÃO\n";
$turmasPendentes = $turmaModel->turmasPendentesHoje();
assertTest('Cozinha identifica corretamente se existem turmas pendentes', count($turmasPendentes) > 0);

$cardapioModel = new Cardapio();
$cardapiosHoje = $cardapioModel->findHoje();
assertTest('Existem cardápios cadastrados para a data de hoje', count($cardapiosHoje) >= 2);

// ------------------------------------------------------------
// TESTE 6: Proteção e Privacidade de Restrições Alimentares
// ------------------------------------------------------------
echo "\n6. TESTES DE PRIVACIDADE EM RESTRIÇÕES ALIMENTARES\n";
$restricaoModel = new RestricaoAlimentar();
$restricoes = $restricaoModel->findAllAtivas();
assertTest('Existem diretrizes de restrição alimentar cadastradas', count($restricoes) > 0);

$semDiagnosticosMedicos = true;
foreach ($restricoes as $r) {
    // Verifica se as orientações são puramente operacionais e não termos de prontuário/CID
    if (stripos($r->orientacao_operacional, 'CID') !== false || stripos($r->orientacao_operacional, 'prontuario') !== false) {
        $semDiagnosticosMedicos = false;
    }
}
assertTest('Regra de Privacidade: Apenas orientações operacionais registradas (sem diagnósticos/CID)', $semDiagnosticosMedicos);

// ------------------------------------------------------------
// TESTE 7: Correção Autorizada com Auditoria Obrigatória
// ------------------------------------------------------------
echo "\n7. TESTES DE AUDITORIA E CORREÇÃO PÓS-FECHAMENTO\n";
$historicoModel = new HistoricoAlteracao();

// Simula alteração auditada pelo Gestor
$registroFreqParaAlterar = $frequencias6A[0]; // Primeiro aluno
$valorAnterior = $registroFreqParaAlterar->situacao;
$novoValor = ($valorAnterior === 'presente') ? 'justificado' : 'presente';
$justificativaTeste = 'Teste automatizado: correção autorizada pela direção da escola';

$historicoId = $historicoModel->registrar(
    'frequencia',
    $registroFreqParaAlterar->id,
    'situacao',
    $valorAnterior,
    $novoValor,
    $gestora->id,
    $justificativaTeste
);
assertTest('Auditoria registrou histórico da alteração com autor e justificativa', $historicoId !== false);

$historicoSalvo = $historicoModel->findById($historicoId);
assertTest('Auditoria guardou valor anterior e novo valor corretamente', 
    $historicoSalvo && 
    $historicoSalvo->valor_anterior === $valorAnterior && 
    $historicoSalvo->valor_novo === $novoValor
);

// ------------------------------------------------------------
// RELATÓRIO FINAL
// ------------------------------------------------------------
echo "\n============================================================\n";
echo " RESULTADO FINAL DOS TESTES\n";
echo " Total executado: {$totalTestes} testes\n";
echo " Aprovados: {$testesPassados} / {$totalTestes}\n";
if ($totalTestes === $testesPassados) {
    echo " STATUS: TODOS OS TESTES PASSARAM COM SUCESSO! (100% OK)\n";
} else {
    echo " STATUS: FORAM ENCONTRADAS FALHAS!\n";
}
echo "============================================================\n";
