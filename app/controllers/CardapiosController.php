<?php

/**
 * Controlador de Cardápios e Refeições
 * Planejamento nutricional, previsão de porções e itens do cardápio
 *
 * @package FeiraTecG1
 */
class CardapiosController extends Controller
{
    /**
     * Lista cardápios cadastrados por data
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'cozinha']);

        $cardapioModel = $this->model('Cardapio');
        $itemModel = $this->model('ItemCardapio');

        $dataFiltro = $_GET['data'] ?? date('Y-m-d');
        $cardapios = $cardapioModel->findByData($dataFiltro);

        $itensMap = [];
        foreach ($cardapios as $c) {
            $itensMap[$c->id] = $itemModel->findByCardapio($c->id);
        }

        $this->view('cardapios/index', [
            'titulo'     => 'Cardápio Escolar',
            'cardapios'  => $cardapios,
            'itensMap'   => $itensMap,
            'dataFiltro' => $dataFiltro,
        ]);
    }

    /**
     * Cadastro de cardápio para uma data e tipo de refeição
     */
    public function criar(): void
    {
        Middleware::requireRole(['admin', 'cozinha']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/cardapios/criar');
            }

            $cardapioModel = $this->model('Cardapio');
            $data = $_POST['data'] ?? date('Y-m-d');
            $tipo = sanitize($_POST['tipo_refeicao'] ?? '');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $previsao = !empty($_POST['previsao_quantidade']) ? (int) $_POST['previsao_quantidade'] : null;

            if (empty($data) || empty($tipo) || empty($descricao)) {
                flash('mensagem', 'Preencha todos os campos obrigatórios.', 'warning');
                $_SESSION['old_input'] = $_POST;
                redirect('/cardapios/criar');
            }

            // Verifica se já existe cardápio para esta data e tipo
            if ($cardapioModel->findByDataTipo($data, $tipo)) {
                flash('mensagem', 'Já existe um cardápio cadastrado para esta data e refeição.', 'danger');
                $_SESSION['old_input'] = $_POST;
                redirect('/cardapios/criar');
            }

            $cardapioId = $cardapioModel->create([
                'data'                => $data,
                'tipo_refeicao'       => $tipo,
                'descricao'           => $descricao,
                'previsao_quantidade' => $previsao,
            ]);

            // Salva itens individuais do cardápio
            if ($cardapioId && !empty($_POST['item_nome'])) {
                $itemModel = $this->model('ItemCardapio');
                $nomes = $_POST['item_nome'];
                $quantidades = $_POST['item_quantidade'] ?? [];
                $observacoes = $_POST['item_observacao'] ?? [];

                for ($i = 0; $i < count($nomes); $i++) {
                    $nomeItem = sanitize($nomes[$i] ?? '');
                    if (!empty($nomeItem)) {
                        $itemModel->create([
                            'cardapio_id'         => $cardapioId,
                            'nome_item'           => $nomeItem,
                            'quantidade_prevista' => !empty($quantidades[$i]) ? (int) $quantidades[$i] : null,
                            'observacao'          => !empty($observacoes[$i]) ? sanitize($observacoes[$i]) : null,
                        ]);
                    }
                }
            }

            flash('mensagem', 'Cardápio cadastrado com sucesso!', 'success');
            redirect('/cardapios?data=' . $data);
        }

        $this->view('cardapios/criar', [
            'titulo' => 'Novo Cardápio',
        ]);
    }

    /**
     * Edição de cardápio e seus itens
     */
    public function editar(int $id = 0): void
    {
        Middleware::requireRole(['admin', 'cozinha']);

        $cardapioModel = $this->model('Cardapio');
        $cardapio = $cardapioModel->findById($id);

        if (!$cardapio) {
            flash('mensagem', 'Cardápio não encontrado.', 'danger');
            redirect('/cardapios');
        }

        $itemModel = $this->model('ItemCardapio');
        $itens = $itemModel->findByCardapio($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/cardapios/editar/' . $id);
            }

            $descricao = sanitize($_POST['descricao'] ?? '');
            $previsao = !empty($_POST['previsao_quantidade']) ? (int) $_POST['previsao_quantidade'] : null;

            // Registra alteração para auditoria se a descrição mudou
            if ($cardapio->descricao !== $descricao) {
                $historicoModel = $this->model('HistoricoAlteracao');
                $historicoModel->registrar(
                    'cardapio',
                    $id,
                    'descricao',
                    $cardapio->descricao,
                    $descricao,
                    Middleware::currentUserId(),
                    'Atualização do cardápio pela equipe de alimentação'
                );
            }

            $cardapioModel->update($id, [
                'descricao'           => $descricao,
                'previsao_quantidade' => $previsao,
            ]);

            // Recria itens
            $itemModel->deleteByCardapio($id);
            if (!empty($_POST['item_nome'])) {
                $nomes = $_POST['item_nome'];
                $quantidades = $_POST['item_quantidade'] ?? [];
                $observacoes = $_POST['item_observacao'] ?? [];

                for ($i = 0; $i < count($nomes); $i++) {
                    $nomeItem = sanitize($nomes[$i] ?? '');
                    if (!empty($nomeItem)) {
                        $itemModel->create([
                            'cardapio_id'         => $id,
                            'nome_item'           => $nomeItem,
                            'quantidade_prevista' => !empty($quantidades[$i]) ? (int) $quantidades[$i] : null,
                            'observacao'          => !empty($observacoes[$i]) ? sanitize($observacoes[$i]) : null,
                        ]);
                    }
                }
            }

            flash('mensagem', 'Cardápio atualizado com sucesso!', 'success');
            redirect('/cardapios?data=' . $cardapio->data);
        }

        $this->view('cardapios/editar', [
            'titulo'   => 'Editar Cardápio',
            'cardapio' => $cardapio,
            'itens'    => $itens,
        ]);
    }

    /**
     * Exclusão de cardápio
     */
    public function excluir(int $id = 0): void
    {
        Middleware::requireRole(['admin', 'cozinha']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cardapioModel = $this->model('Cardapio');
            $cardapioModel->delete($id);
            flash('mensagem', 'Cardápio excluído com sucesso.', 'success');
        }

        redirect('/cardapios');
    }
}
