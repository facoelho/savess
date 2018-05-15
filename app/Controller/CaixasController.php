<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

App::uses('GoogleCharts', 'GoogleCharts.Lib');

/**
 * Caixas Controller
 */
class CaixasController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Caixa');
    }

    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }

    /**
     * index method
     */
    public function index() {
        $dadosUser = $this->Session->read();
        $this->Caixa->recursive = -1;
        $this->Paginator->settings = array(
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('dtcaixa' => 'desc')
        );
        $this->set('caixas', $this->Paginator->paginate('Caixa'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->FormasPagamento->id = $id;
        if (!$this->FormasPagamento->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];

        $formapagamento = $this->FormasPagamento->read(null, $id);
        if ($formapagamento ['FormasPagamento']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('formapagamento', $formapagamento);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $valida_caixa = $this->Caixa->query('select max(dtcaixa) as dtcaixa from public.caixas where empresa_id = ' . $empresa_id);

        if (!empty($valida_caixa[0][0]['dtcaixa'])) {
            $saldo = $this->Caixa->query('select saldo from public.caixas where empresa_id = ' . $empresa_id . ' and dtcaixa = ' . "'" . $valida_caixa[0][0]['dtcaixa'] . "'");
            $this->set(compact('saldo'));
        }

        if ($this->request->is('post')) {

            $valida_caixa = $this->Caixa->find('list', array(
                'conditions' => array('empresa_id' => $empresa_id,
                    'dtcaixa <=' . "'" . substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2) . "'" . '
                    and status = ' . "'A'",
            )));

            if (!empty($valida_caixa)) {
                $this->Session->setFlash('É necessário fechar o caixa anterior!', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            $valida_caixa = $this->Caixa->find('list', array(
                'conditions' => array('empresa_id' => $empresa_id,
                    'dtcaixa >=' . "'" . substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2) . "'",
            )));

            if (!empty($valida_caixa)) {
                $this->Session->setFlash('Existe caixa aberto com data maior do que a informada!', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            $this->Caixa->create();

            $this->request->data['Caixa']['user_id'] = $dadosUser['Auth']['User']['id'];
            $this->request->data['Caixa']['created'] = date('Y-m-d h:i:s');
            $this->request->data['Caixa']['dtcaixa'] = substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2);
//            $this->request->data['Caixa']['saldo'] = str_replace(',', '.', $this->request->data['Caixa']['saldo']);

            if ($this->Caixa->save($this->request->data)) {

//                $ultimo_id = $this->Caixa->getLastInsertId();
//
//                $this->Caixa->query('insert into public.lancamentos(caixa_id,
//                                                                    descricao,
//                                                                    tipo,
//                                                                    valor,
//                                                                    user_id,
//                                                                    categoria_id,
//                                                                    created,
//                                                                    saldo)
//                                                            values(' . $ultimo_id . ',
//                                                                   ' . "'INICIALIZAÇÃO DE CAIXA'" . ',
//                                                                   ' . "'I'" . ',
//                                                                   ' . $this->request->data['Caixa']['saldo'] . ',
//                                                                   ' . $this->request->data['Caixa']['user_id'] . ',
//                                                                   0,
//                                                                   ' . "'" . $this->request->data['Caixa']['created'] . "'" . ',
//                                                                   ' . $this->request->data['Caixa']['saldo'] . ')');

                $this->Session->setFlash('Caixa aberto com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * edit method
     */
    public function edit($id = null) {

        $this->Caixa->id = $id;
        if (!$this->Caixa->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $status = array('A' => 'ABERTO', 'F' => 'FECHADO');
        $this->set('status', $status);

        $caixa = $this->Caixa->read(null, $id);
        if ($caixa['Caixa']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->request->data['Caixa']['status'] == 'A') {
                $valida_caixa = $this->Caixa->find('list', array(
                    'conditions' => array('empresa_id' => $empresa_id,
                        'dtcaixa >=' . "'" . substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2) . "'",
                )));

                if (!empty($valida_caixa)) {
                    $this->Session->setFlash('Caixa não pode ser reaberto!', 'default', array('class' => 'mensagem_erro'));
                    return;
                }
            }

            if ($this->Caixa->save($this->request->data)) {
                $this->Session->setFlash('Caixa alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Caixa->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Caixa->id = $id;
        if (!$this->Caixa->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Caixa->delete()) {
            $this->Session->setFlash('Caixa deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * confere_caixa method
     */
    public function confere_caixa($id) {

        $dadosUser = $this->Session->read();
        $this->Caixa->recursive = 0;
        $this->Paginator->settings = array(
            'fields' => array('Caixa.id', 'Caixa.dtcaixa', 'Caixa.saldo', 'Lancamento.descricao', 'Lancamento.valor', 'Lancamento.saldo', 'Lancamento.created', 'User.id', 'User.nome', 'User.sobrenome', 'Categoria.descricao', 'Tipoexame.descricao'),
            'joins' => array(
                array(
                    'table' => 'lancamentos',
                    'alias' => 'Lancamento',
                    'type' => 'INNER',
                    'conditions' => array('Lancamento.caixa_id = Caixa.id')
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Lancamento.user_id')
                ),
                array(
                    'table' => 'categorias',
                    'alias' => 'Categoria',
                    'type' => 'INNER',
                    'conditions' => array('Categoria.id = Lancamento.categoria_id')
                ),
                array(
                    'table' => 'tipoexames',
                    'alias' => 'Tipoexame',
                    'type' => 'LEFT',
                    'conditions' => array('Tipoexame.id = Lancamento.tipoexame_id')
                ),
            ),
            'conditions' => array('Caixa.empresa_id' => $dadosUser['empresa_id'], 'Caixa.id' => $id),
            'order' => array('Lancamento.id' => 'asc')
        );
        $this->set('lancamentos', $this->Paginator->paginate('Caixa'));
    }

    /**
     * indices method
     */
    public function indices() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->set('dadosUser', $dadosUser);

        $this->loadModel('Categoria');
        $categorias_pai = $this->Categoria->find('list', array('fields' => array('id', 'descricao'),
            'conditions' => array('empresa_id' => $empresa_id, 'categoria_pai_id IS NULL'),
            'order' => array('descricao')));
        $this->set('categorias_pai', $categorias_pai);


        $tipografico = array('B' => 'Barras', 'L' => 'Linhas', 'P' => 'Pizza');
        $this->set('tipografico', $tipografico);

        if ($this->request->is('post') || $this->request->is('put')) {
            if (empty($this->request->data['Relatorio']['categorias_pai'])) {
                $this->Session->setFlash('Campo categoria pai é obrigatório.', 'default', array('class' => 'mensagem_erro'));
                return;
            }
            CakeSession::write('relatorio', $this->request->data);
//            if ($this->request->data['Relatorio']['tipografico'] == 'B') {
            $this->redirect(array('action' => 'relatorio_indices'));
//            } elseif ($this->request->data['Relatorio']['tipografico'] == 'L') {
//                $this->redirect(array('action' => 'relatorio_indices_linhas'));
//            } elseif ($this->request->data['Relatorio']['tipografico'] == 'P') {
//                $this->redirect(array('action' => 'relatorio_indices_pizza'));
//            }
        }
    }

    /**
     * indices method
     */
    public function relatorio_indices() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $indices = $this->Session->read('relatorio');
        $categorias_pai = $indices['Relatorio']['categorias_pai'];
        $exames = '';

        if (!empty($indices['Relatorio']['categoria_id'])) {
            $categoria_id = $indices['Relatorio']['categoria_id'];
        } else {
            $categoria_id = '';
        }

        if (!empty($indices['Tipoexame']['Tipoexame'])) {
            foreach ($indices['Tipoexame']['Tipoexame'] as $key => $item) :
                if (empty($exames)) {
                    $exames = $item;
                } else {
                    $exames = $exames . ',' . $item;
                }
            endforeach;
        }

        if (empty($categoria_id)) {
            $todas_categorias = $this->Caixa->query('select id, descricao from categorias where categoria_pai_id = ' . $categorias_pai . ' order by descricao');
            foreach ($todas_categorias as $key => $item) :
                if (empty($categorias)) {
                    $categorias = $item[0]['id'];
                } else {
                    $categorias = $categorias . ',' . $item[0]['id'];
                }
            endforeach;

            //Relatório Valor Total x Categorias
            $chart = new GoogleCharts();
            $chart->type("ColumnChart");
            $chart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Valor x Categoria", 'titleTextStyle' => array('color' => 'blu'),
                'fontSize' => 12));
            $chart->columns(array(
                'categoria' => array(
                    'type' => 'string',
                    'label' => 'Categoria'
                ),
                'valor' => array(
                    'type' => 'number',
                    'label' => 'Valor',
                    'format' => '#,###'
                ),
                'annotation' => array(
                    'type' => 'number',
                    'role' => 'annotation',
                )
            ));

            $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                            from categorias,
                                                 lancamentos,
                                                 caixas
                                           where caixas.id = lancamentos.caixa_id
                                             and lancamentos.categoria_id in (' . $categorias . ')
                                             and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                             and caixas.empresa_id = ' . $empresa_id . '
                                             and lancamentos.categoria_id = categorias.id
                                           group by categorias.descricao
                                           order by sum(valor) desc');

            foreach ($result as $item) {
                $chart->addRow(array('categoria' => $item[0]['descricao'], 'valor' => $item[0]['valor'], $item[0]['valor'], 'annotation' => $item[0]['valor']));
            }

            $this->set(compact('chart'));

            //
            //GRAFICO DE PIZZA
            //
            $piechart = new GoogleCharts();
            $piechart->type("PieChart");
            $piechart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Valor x Categoria", 'titleTextStyle' => array('color' => 'blu'),
                'fontSize' => 12));
            $piechart->columns(array(
                'categoria' => array(
                    'type' => 'string',
                    'label' => 'Categoria'
                ),
                'valor' => array(
                    'type' => 'number',
                    'label' => 'Valor',
                    'format' => '#,###',
                    'role' => 'annotation'
                )
            ));

            $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                            from categorias,
                                                 lancamentos,
                                                 caixas
                                           where caixas.id = lancamentos.caixa_id
                                             and lancamentos.categoria_id in (' . $categorias . ')
                                             and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                             and caixas.empresa_id = ' . $empresa_id . '
                                             and lancamentos.categoria_id = categorias.id
                                           group by categorias.descricao
                                           order by sum(valor) desc');

            foreach ($result as $item) {
                $piechart->addRow(array('categoria' => $item[0]['descricao'], $item[0]['valor'], 'valor' => $item[0]['valor']));
            }

            $this->set(compact('piechart'));

            //
            //Relatório Valor Total x Categorias
            //*
        } else {
            //Relatório Valor Total x Tipos de exame
            $chart = new GoogleCharts();
            $chart->type("ColumnChart");
            $chart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Valor x Categoria", 'titleTextStyle' => array('color' => 'blu'),
                'fontSize' => 12));
            $chart->columns(array(
                'tipoexame' => array(
                    'type' => 'string',
                    'label' => 'Categoria'
                ),
                'valor' => array(
                    'type' => 'number',
                    'label' => 'Valor',
                    'format' => '#,###'
                ),
                'annotation' => array(
                    'type' => 'number',
                    'role' => 'annotation',
                )
            ));

            $result = $this->Caixa->query('select tipoexames.descricao, sum(valor)::float as valor
                                            from categorias,
                                                 lancamentos,
                                                 caixas,
                                                 tipoexames
                                           where caixas.id = lancamentos.caixa_id
                                             and lancamentos.tipoexame_id = tipoexames.id
                                             and caixas.empresa_id = ' . $empresa_id . '
                                             and lancamentos.categoria_id in (' . $categoria_id . ')
                                             and tipoexames.id in (' . $exames . ')
                                             and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                             and lancamentos.categoria_id = categorias.id
                                           group by tipoexames.descricao
                                           order by sum(valor) desc');

            foreach ($result as $item) {
                $chart->addRow(array('tipoexame' => $item[0]['descricao'], 'valor' => $item[0]['valor'], $item[0]['valor'], 'annotation' => $item[0]['valor']));
            }

            $this->set(compact('chart'));

            //
            //PIZZA
            //
            $piechart = new GoogleCharts();
            $piechart->type("PieChart");
            $piechart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Valor x Tipos de exame"));
            $piechart->columns(array(
                'tipoexame' => array(
                    'type' => 'string',
                    'label' => 'Categoria'
                ),
                'valor' => array(
                    'type' => 'number',
                    'label' => 'Valor',
                    'format' => '#,###',
                    'role' => 'annotation'
                )
            ));

            $result = $this->Caixa->query('select tipoexames.descricao, sum(valor)::float as valor
                                            from categorias,
                                                 lancamentos,
                                                 caixas,
                                                 tipoexames
                                           where caixas.id = lancamentos.caixa_id
                                             and lancamentos.tipoexame_id = tipoexames.id
                                             and caixas.empresa_id = ' . $empresa_id . '
                                             and lancamentos.categoria_id in (' . $categoria_id . ')
                                             and tipoexames.id in (' . $exames . ')
                                             and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                             and lancamentos.categoria_id = categorias.id
                                           group by tipoexames.descricao
                                           order by sum(valor) desc');

            foreach ($result as $item) {
                $piechart->addRow(array('tipoexame' => $item[0]['descricao'], 'valor' => $item[0]['valor'], $item[0]['valor']));
            }
            $this->set(compact('piechart'));

            //
            //Relatório Valor Total x Tipos de exame
            //*
        }
    }

}
