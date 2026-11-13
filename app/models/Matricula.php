<?php

/**
 * Modelo de Matrícula
 * Gerencia operações de banco de dados para a tabela de matrículas,
 * vinculando alunos a turmas em um ano letivo.
 */
class Matricula extends Model
{
    protected string $table = 'matricula';

    /**
     * Busca uma matrícula específica pelo aluno, turma e ano letivo.
     *
     * @param int $alunoId ID do aluno
     * @param int $turmaId ID da turma
     * @param int $anoLetivoId ID do ano letivo
     * @return object|false Objeto da matrícula ou false se não encontrada
     */
    public function findByAlunoTurma(int $alunoId, int $turmaId, int $anoLetivoId): object|false
    {
        $stmt = $this->db->prepare("SELECT * FROM matricula WHERE aluno_id = :aluno_id AND turma_id = :turma_id AND ano_letivo_id = :ano_letivo_id");
        $stmt->execute(['aluno_id' => $alunoId, 'turma_id' => $turmaId, 'ano_letivo_id' => $anoLetivoId]);
        return $stmt->fetch();
    }
}
