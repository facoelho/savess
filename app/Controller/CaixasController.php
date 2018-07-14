<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

App::uses('GoogleCharts', 'GoogleCharts.Lib');

App::uses('ExportComponent', 'Export.Controller/Component');

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
        $this->set('adminholding', $dadosUser['Auth']['User']['adminholding']);

        $empresa_id = $dadosUser['empresa_id'];

        CakeSession::write('conditions_filtro_caixa', '');

        $this->loadModel('Categoria');
        $categorias_pai = $this->Categoria->find('list', array('fields' => array('id', 'descricao'),
            'conditions' => array('empresa_id' => $empresa_id, 'categoria_pai_id IS NULL', 'ativo' => 'S'),
            'order' => array('descricao')));
        $this->set('categorias_pai', $categorias_pai);

        $tipo = array('S' => 'Saida', 'E' => 'Entrada', 'R' => 'Retirada');

        $this->Caixa->recursive = -1;

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Lancamento.descricao' => array(
                            'operator' => 'ILIKE',
                            'value' => array(
                                'before' => '%',
                                'after' => '%'
                            )
                        )
                    ),
                    'filter2' => array(
                        'Categoria.categoria_pai_id' => array(
                            'select' => $categorias_pai
                        ),
                    ),
                    'categoria_id' => array(
                        'Categoria.id' => array(
                            'select' => ''
                        ),
                    ),
                    'filter5' => array(
                        'Categoria.tipo' => array(
                            'select' => $tipo
                        ),
                    ),
                    'filter4' => array(
                        'Caixa.dtcaixa' => array(
                            'operator' => 'BETWEEN',
                            'between' => array(
                                'text' => __(' e ', true),
                                'date' => true
                            )
                        )
                    ),
                )
        );
        $this->Paginator->settings = array(
            'fields' => array('DISTINCT Caixa.id', 'Caixa.dtcaixa', 'Caixa.saldo', 'Caixa.status'),
            'joins' => array(
                array(
                    'table' => 'lancamentos',
                    'alias' => 'Lancamento',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.caixa_id = Caixa.id')
                ),
                array(
                    'table' => 'categorias',
                    'alias' => 'Categoria',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.categoria_id = Categoria.id')
                )
            ),
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'group' => array('Caixa.id', 'Caixa.dtcaixa', 'Caixa.saldo', 'Caixa.status'),
            'order' => array('dtcaixa' => 'desc')
        );

        $this->Filter->setPaginate('conditions', array($this->Filter->getConditions(), 'Caixa.empresa_id' => $dadosUser['empresa_id']));

        $this->set('caixas', $this->Paginator->paginate('Caixa'));

        CakeSession::write('conditions_filtro_caixa', array($this->Filter->getConditions(), 'Caixa.empresa_id' => $dadosUser['empresa_id']));
    }

    /**
     * imprimir_lista_caixas method
     */
    public function imprimir_lista_caixas() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $conditions_filtro_caixa = $this->Session->read('conditions_filtro_caixa');

        $this->Paginator->settings = array(
            'fields' => array('Caixa.id', 'Caixa.dtcaixa', 'Caixa.saldo', 'Lancamento.id', 'Lancamento.descricao', 'Lancamento.valor', 'Lancamento.saldo', 'Lancamento.created', 'User.id', 'User.nome', 'User.sobrenome', 'Categoria.descricao', 'Categoria.tipo'),
            'joins' => array(
                array(
                    'table' => 'lancamentos',
                    'alias' => 'Lancamento',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.caixa_id = Caixa.id')
                ),
                array(
                    'table' => 'categorias',
                    'alias' => 'Categoria',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.categoria_id = Categoria.id')
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Lancamento.user_id')
                ),
            ),
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('dtcaixa' => 'desc'),
            'limit' => ''
        );

        $this->Filter->setPaginate('conditions', array($conditions_filtro_caixa));

        $this->set('caixas', $this->Paginator->paginate('Caixa'));
    }

    /**
     * exportar_csv method
     */
    public function exportar_csv() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $conditions_filtro_caixa = $this->Session->read('conditions_filtro_caixa');

        $this->Paginator->settings = array(
            'fields' => array('Caixa.dtcaixa', 'Caixa.saldo', 'Lancamento.id', 'Lancamento.descricao', 'Lancamento.valor', 'Lancamento.created', 'User.id', 'User.nome', 'User.sobrenome', 'Categoria.descricao', 'Categoria.tipo'),
            'joins' => array(
                array(
                    'table' => 'lancamentos',
                    'alias' => 'Lancamento',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.caixa_id = Caixa.id')
                ),
                array(
                    'table' => 'categorias',
                    'alias' => 'Categoria',
                    'type' => 'LEFT',
                    'conditions' => array('Lancamento.categoria_id = Categoria.id')
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array('User.id = Lancamento.user_id')
                ),
            ),
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('dtcaixa' => 'asc'),
            'limit' => ''
        );

        $this->Filter->setPaginate('conditions', array($conditions_filtro_caixa));

        $this->Export->exportCsv($this->Paginator->paginate('Caixa'), 'caixas.csv');
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

//        $valida_caixa = $this->Caixa->query('select max(dtcaixa) as dtcaixa from public.caixas where empresa_id = ' . $empresa_id);
//
//        if (!empty($valida_caixa[0][0]['dtcaixa'])) {
//            $saldo = $this->Caixa->query('select saldo from public.caixas where empresa_id = ' . $empresa_id . ' and dtcaixa = ' . "'" . $valida_caixa[0][0]['dtcaixa'] . "'");
//            $this->set(compact('saldo'));
//        }

        if ($this->request->is('post')) {

//            $valida_caixa = $this->Caixa->find('list', array(
//                'conditions' => array('empresa_id' => $empresa_id,
//                    'dtcaixa <=' . "'" . substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2) . "'" . '
//                    and status = ' . "'A'",
//            )));
//
//            if (!empty($valida_caixa)) {
//                $this->Session->setFlash('É necessário fechar o caixa anterior!', 'default', array('class' => 'mensagem_erro'));
//                return;
//            }
//
//            $valida_caixa = $this->Caixa->find('list', array(
//                'conditions' => array('empresa_id' => $empresa_id,
//                    'dtcaixa >=' . "'" . substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2) . "'",
//            )));
//
//            if (!empty($valida_caixa)) {
//                $this->Session->setFlash('Existe caixa aberto com data maior do que a informada!', 'default', array('class' => 'mensagem_erro'));
//                return;
//            }

            $this->Caixa->create();

            $this->request->data['Caixa']['user_id'] = $dadosUser['Auth']['User']['id'];
            $this->request->data['Caixa']['created'] = date('Y-m-d h:i:s');
            $this->request->data['Caixa']['dtcaixa'] = substr($this->request->data['Caixa']['dtcaixa'], 6, 4) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 3, 2) . "-" . substr($this->request->data['Caixa']['dtcaixa'], 0, 2);
            $this->request->data['Caixa']['saldo'] = 0;

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

        $cont = $this->Caixa->query('select count(*) as cont from public.lancamentos where caixa_id = ' . $id);

        if ($cont[0][0]['cont'] > 0) {
            $this->Session->setFlash('Caixa não pode ser excluido pois possui registros associados.', 'default', array('class' => 'mensagem_erro'));
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
        $this->set('adminholding', $dadosUser['Auth']['User']['adminholding']);

        $this->Caixa->recursive = 0;
        $this->Paginator->settings = array(
            'fields' => array('Caixa.id', 'Caixa.dtcaixa', 'Caixa.saldo', 'Lancamento.id', 'Lancamento.descricao', 'Lancamento.valor', 'Lancamento.saldo', 'Lancamento.created', 'User.id', 'User.nome', 'User.sobrenome', 'Categoria.descricao', 'Categoria.tipo', 'Tipoexame.descricao'),
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
            'order' => array('Caixa.dtlancamento' => 'asc')
        );
        $this->set('lancamentos', $this->Paginator->paginate('Caixa'));

        $this->set('id', $id);
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

        $filhas = array('S' => 'SIM');
        $this->set('filhas', $filhas);

        $tipo = array('E' => 'Entradas', 'S' => 'Saídas', 'R' => 'Retiradas');
        $this->set('tipo', $tipo);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ((empty($this->request->data['Relatorio']['dtdespesa_inicio'])) or (empty($this->request->data['Relatorio']['dtdespesa_fim']))) {
                $this->Session->setFlash('Período obrigatório.', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            if (!empty($this->request->data['Relatorio']['pais'])) {
                if (empty($this->request->data['Relatorio']['tipo'])) {
                    $this->Session->setFlash('Tipo de lançamento é obrigatório para listar somente as categorias filhas.', 'default', array('class' => 'mensagem_erro'));
                    return;
                }
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

        if (!empty($indices['Relatorio']['tipo'])) {
            $tipo = $indices['Relatorio']['tipo'];
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

        if (!empty($indices['Relatorio']['filhas'])) {
            if (empty($categorias_pai) and (!empty($indices['Relatorio']['filhas']))) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                     from categorias,
                                                          lancamentos,
                                                          caixas
                                                    where caixas.id = lancamentos.caixa_id
                                                      and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                      and caixas.empresa_id = ' . $empresa_id . '
                                                      and lancamentos.categoria_id = categorias.id
                                                      and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                    group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                             categorias.descricao
                                                    order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                             sum(valor) desc');

                $columns['data'] = array('type' => 'string', 'label' => 'Data');
                foreach ($result as $key => $item) :
                    $columns[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns[] = array('type' => 'number', 'role' => 'annotation');
                endforeach;

                $column_chart = new GoogleCharts();

                $column_chart->type('ColumnChart');

                $column_chart->options(array('width' => '80%',
                    'heigth' => '70%',
                    'title' => 'Relatório Valor Total x Categorias',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                    'titleTextStyle' => array('color' => 'grenn'),
                    'fontSize' => 12,
                ));

                $column_chart->columns($columns);

                $columns_linha['data'] = array('type' => 'string', 'label' => 'Data');
                foreach ($result as $key => $item) :
                    $columns_linha[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns_linha[] = array('type' => 'number', 'role' => 'annotation');
                endforeach;

                $column_chart_linha = new GoogleCharts();

                $column_chart_linha->type('LineChart');

                $column_chart_linha->options(array('width' => '80%',
                    'heigth' => '70%',
                    'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                    'titleTextStyle' => array('color' => 'grenn'),
                    'fontSize' => 12,
                ));

                $column_chart_linha->columns($columns_linha);

                //
                //GRAFICO DE PIZZA
                //
            $piechart = new GoogleCharts();
                $piechart->type("PieChart");
                $piechart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Relatório Valor Total x Categorias pai", 'titleTextStyle' => array('color' => 'blu'),
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
//                    'role' => 'annotation'
                    )
                ));

                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');

                foreach ($datas as $d => $data):
                    $string = '';
                    $string_fim = '';
                    $string['data'] = $data[0]['anomes'];

                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');

                    foreach ($result as $k => $item):
                        $string[$item[0]['descricao']] = $item[0]['valor'];
                        $string[] = $item[0]['valor'];
                    endforeach;
                    $string_fim[] = $string;
                    $column_chart->addRow($string_fim[0]);
                    $column_chart_linha->addRow($string_fim[0]);
                endforeach;

                $this->set(compact('column_chart'));
                $this->set(compact('column_chart_linha'));

                $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by categorias.descricao
                                               order by sum(valor) desc');

                foreach ($result as $item) {
                    $piechart->addRow(array('categoria' => $item[0]['descricao'], $item[0]['valor'], 'valor' => $item[0]['valor']));
                }
                $this->set(compact('piechart'));
            }
        }

        if (empty($categorias_pai) and (empty($indices['Relatorio']['filhas']))) {
            //Relatório Valor Total X categoria
            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas,
                                                      categorias as categoriapai
                                                where caixas.id = lancamentos.caixa_id
                                                  and categoriapai.id = categorias.categoria_pai_id
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categoriapai.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas,
                                                      categorias as categoriapai
                                                where caixas.id = lancamentos.caixa_id
                                                  and categoriapai.id = categorias.categoria_pai_id
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categoriapai.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            }

            $columns['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart = new GoogleCharts();

            $column_chart->type('ColumnChart');

            $column_chart->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart->columns($columns);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }

            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas,
                                                         categorias as categoriapai
                                                   where caixas.id = lancamentos.caixa_id
                                                     and categoriapai.id = categorias.categoria_pai_id
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categoriapai.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas,
                                                         categorias as categoriapai
                                                   where caixas.id = lancamentos.caixa_id
                                                     and categoriapai.id = categorias.categoria_pai_id
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categoriapai.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                }
                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart'));

            //Relatório Linha

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas,
                                                      categorias as categoriapai
                                                where caixas.id = lancamentos.caixa_id
                                                  and categoriapai.id = categorias.categoria_pai_id
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categoriapai.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas,
                                                      categorias as categoriapai
                                                where caixas.id = lancamentos.caixa_id
                                                  and categoriapai.id = categorias.categoria_pai_id
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categoriapai.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            }

            $columns_linha['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns_linha[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns_linha[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart_linha = new GoogleCharts();

            $column_chart_linha->type('LineChart');

            $column_chart_linha->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart_linha->columns($columns_linha);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }

            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas,
                                                         categorias as categoriapai
                                                   where caixas.id = lancamentos.caixa_id
                                                     and categoriapai.id = categorias.categoria_pai_id
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categoriapai.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categoriapai.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas,
                                                         categorias as categoriapai
                                                   where caixas.id = lancamentos.caixa_id
                                                     and categoriapai.id = categorias.categoria_pai_id
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categoriapai.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                }

                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart_linha->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart_linha'));

            //
            //GRAFICO DE PIZZA
            //
            $piechart = new GoogleCharts();
            $piechart->type("PieChart");
            $piechart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Relatório Valor Total x Categorias pai", 'titleTextStyle' => array('color' => 'blu'),
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
//                    'role' => 'annotation'
                )
            ));

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select categoriapai.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by categoriapai.descricao
                                               order by sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select categoriapai.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas,
                                                     categorias as categoriapai
                                               where caixas.id = lancamentos.caixa_id
                                                 and categoriapai.id = categorias.categoria_pai_id
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               group by categoriapai.descricao
                                               order by sum(valor) desc');
            }

            foreach ($result as $item) {
                $piechart->addRow(array('categoria' => $item[0]['descricao'], $item[0]['valor'], 'valor' => $item[0]['valor']));
            }

            $this->set(compact('piechart'));
        } elseif ((empty($categoria_id)) and (empty($indices['Relatorio']['filhas']))) {
            $todas_categorias = $this->Caixa->query('select id, descricao from categorias where categoria_pai_id = ' . $categorias_pai . ' order by descricao');
            foreach ($todas_categorias as $key => $item) :
                if (empty($categorias)) {
                    $categorias = $item[0]['id'];
                } else {
                    $categorias = $categorias . ',' . $item[0]['id'];
                }
            endforeach;

            //Relatório Valor Total x Categorias - Barras

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where lancamentos.categoria_id in (' . $categorias . ')
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                  and caixas.id = lancamentos.caixa_id
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor)::float desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where lancamentos.categoria_id in (' . $categorias . ')
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and caixas.id = lancamentos.caixa_id
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor)::float desc');
            }

            $columns['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart = new GoogleCharts();

            $column_chart->type('ColumnChart');

            $column_chart->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart->columns($columns);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and caixas.id = lancamentos.caixa_id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and caixas.id = lancamentos.caixa_id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }

            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categorias . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and caixas.id = lancamentos.caixa_id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categorias . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and caixas.id = lancamentos.caixa_id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor)::float desc');
                }
                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart'));

            //Relatório Valor Total x Categorias - Linhas
            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        categorias.descricao
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        categorias.descricao
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        sum(valor) desc');
            }

            $columns_linha['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns_linha[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns_linha[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart_linha = new GoogleCharts();

            $column_chart_linha->type('LineChart');

            $column_chart_linha->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart_linha->columns($columns_linha);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }

            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categorias . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categorias . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                }

                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart_linha->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart_linha'));

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
//                    'role' => 'annotation'
                )
            ));

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categorias . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by categorias.descricao
                                               order by sum(valor) desc');
            } else {
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
            }

            foreach ($result as $item) {
                $piechart->addRow(array('categoria' => $item[0]['descricao'], $item[0]['valor'], 'valor' => $item[0]['valor']));
            }

            $this->set(compact('piechart'));

            //
            //Relatório Valor Total x Categorias
            //*
        } elseif (empty($indices['Relatorio']['filhas'])) {
            //Relatório Valor Total x Categorias - Barras

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where lancamentos.categoria_id in (' . $categoria_id . ')
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categorias.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where lancamentos.categoria_id in (' . $categoria_id . ')
                                                  and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         categorias.descricao
                                                order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                         sum(valor) desc');
            }

            $columns['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart = new GoogleCharts();

            $column_chart->type('ColumnChart');

            $column_chart->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart->columns($columns);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }


            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categoria_id . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categoria_id . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                }

                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart'));

            //Relatório Valor Total x Categorias - Linhas
            if (!empty($tipo)) {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        categorias.descricao
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        categorias.descricao
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                        sum(valor) desc');
            }

            $columns_linha['data'] = array('type' => 'string', 'label' => 'Data');
            foreach ($result as $key => $item) :
                $columns_linha[$item[0]['descricao']] = array('type' => 'number', 'label' => $item[0]['descricao']);
//                $columns_linha[] = array('type' => 'number', 'role' => 'annotation');
            endforeach;

            $column_chart_linha = new GoogleCharts();

            $column_chart_linha->type('LineChart');

            $column_chart_linha->options(array('width' => '80%',
                'heigth' => '70%',
                'title' => 'Relatório Valor Total x Categorias pai',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
                'titleTextStyle' => array('color' => 'grenn'),
                'fontSize' => 12,
            ));

            $column_chart_linha->columns($columns_linha);

            if (!empty($tipo)) {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            } else {
                $datas = $this->Caixa->query('select distinct SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4)');
            }

            foreach ($datas as $d => $data):
                $string = '';
                $string_fim = '';
                $string['data'] = $data[0]['anomes'];

                if (!empty($tipo)) {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categoria_id . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and categorias.tipo = ' . "'" . $tipo . "'" . '
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                } else {
                    $result = $this->Caixa->query('select SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4) as anomes, categorias.descricao, sum(valor)::float as valor
                                                    from categorias,
                                                         lancamentos,
                                                         caixas
                                                   where caixas.id = lancamentos.caixa_id
                                                     and lancamentos.categoria_id in (' . $categoria_id . ')
                                                     and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                     and caixas.empresa_id = ' . $empresa_id . '
                                                     and lancamentos.categoria_id = categorias.id
                                                     and to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') = ' . "'" . $data[0]['anomes'] . "'" . '
                                                   group by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            categorias.descricao
                                                   order by SUBSTRING(dtcaixa::varchar, 6,2) ||' . "'-'" . '|| SUBSTRING(dtcaixa::varchar, 1,4),
                                                            sum(valor) desc');
                }
                foreach ($result as $k => $item):
                    $string[$item[0]['descricao']] = $item[0]['valor'];
                    $string[] = $item[0]['valor'];
                endforeach;
                $string_fim[] = $string;
                $column_chart_linha->addRow($string_fim[0]);
            endforeach;

            $this->set(compact('column_chart_linha'));

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
//                    'role' => 'annotation'
                )
            ));

            if (!empty($tipo)) {
                $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                                 and categorias.tipo = ' . "'" . $tipo . "'" . '
                                               group by categorias.descricao
                                               order by sum(valor) desc');
            } else {
                $result = $this->Caixa->query('select categorias.descricao, sum(valor)::float as valor
                                                from categorias,
                                                     lancamentos,
                                                     caixas
                                               where caixas.id = lancamentos.caixa_id
                                                 and lancamentos.categoria_id in (' . $categoria_id . ')
                                                 and caixas.dtcaixa BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 6, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 3, 2) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . " 23:59:59'" . '
                                                 and caixas.empresa_id = ' . $empresa_id . '
                                                 and lancamentos.categoria_id = categorias.id
                                               group by categorias.descricao
                                               order by sum(valor) desc');
            }

            foreach ($result as $item) {
                $piechart->addRow(array('categoria' => $item[0]['descricao'], $item[0]['valor'], 'valor' => $item[0]['valor']));
            }

            $this->set(compact('piechart'));

            //
            //Relatório Valor Total x Categorias
            //*
        }
    }

    /**
     * movimentacaos method
     */
    public function movimentacaos() {

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
            if ((empty($this->request->data['Relatorio']['dtdespesa_inicio'])) or (empty($this->request->data['Relatorio']['dtdespesa_fim']))) {
                $this->Session->setFlash('Período obrigatório.', 'default', array('class' => 'mensagem_erro'));
                return;
            }
            CakeSession::write('relatorio', $this->request->data);
            $this->redirect(array('action' => 'relatorio_movimentacaos'));
        }
    }

    /**
     * relatorio_movimentacaos method
     */
    public function relatorio_movimentacaos() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $indices = $this->Session->read('relatorio');

        $result = $this->Caixa->query('select to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') as mesano,
                                              case when (categorias.tipo = ' . "'E'" . ') then ' . "'Entradas'" . '
                                              else case when (categorias.tipo = ' . "'S'" . ') then ' . "'Saidas'" . '
                                              else case when (categorias.tipo = ' . "'R'" . ') then ' . "'Retiradas'" . ' end end end as tipo,
                                              case when (categorias.tipo = ' . "'E'" . ') then ' . "'1'" . '
                                              else case when (categorias.tipo = ' . "'S'" . ') then ' . "'2'" . '
                                              else case when (categorias.tipo = ' . "'R'" . ') then ' . "'3'" . ' end end end as ordem,
                                              sum(valor)::float as valor
                                         from categorias,
                                              lancamentos,
                                              caixas
                                        where caixas.id = lancamentos.caixa_id
                                          and to_char(caixas.dtcaixa, ' . "'yyyy-mm'" . ') BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . "'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . "'" . '
                                          and caixas.empresa_id = ' . $empresa_id . '
                                          and lancamentos.categoria_id = categorias.id
                                          and categorias.ativo = ' . "'S'" . '
                                        group by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . '), categorias.tipo
                                        order by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') asc, ordem asc');

        $this->set('result', $result);

        //Relatório de movimentações - Linhas

        $tipos = array('E' => 'Entradas', 'S' => 'Saidas', 'L' => 'Lucro', 'R' => 'Retiradas', 'D' => 'Saldo');

        $columns_linha['data'] = array('type' => 'string', 'label' => 'Data');

        foreach ($tipos as $key => $item) :
            $columns_linha[$item] = array('type' => 'number', 'label' => $item);
//            $columns_linha[] = array('type' => 'number', 'role' => 'annotation');
        endforeach;

        $column_chart_linha = new GoogleCharts();

        $column_chart_linha->type('LineChart');

        $column_chart_linha->options(array('width' => '80%',
            'heigth' => '70%',
            'title' => '',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
            'titleTextStyle' => array('color' => 'grenn'),
            'fontSize' => 12,
        ));

        $column_chart_linha->columns($columns_linha);

        $datas = $this->Caixa->query('select distinct to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') as mesano
                                        from categorias,
                                             lancamentos,
                                             caixas
                                       where caixas.id = lancamentos.caixa_id
                                         and to_char(caixas.dtcaixa, ' . "'yyyy-mm'" . ') BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . "'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . "'" . '
                                         and caixas.empresa_id = ' . $empresa_id . '
                                         and lancamentos.categoria_id = categorias.id
                                         and categorias.ativo = ' . "'S'" . '
                                       group by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ')
                                       order by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') asc');

        foreach ($datas as $d => $data):
            $string = '';
            $string_fim = '';
            $string['data'] = $data[0]['mesano'];

            $result_aux = $this->Caixa->query('select to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') as mesano,
                                                      case when (categorias.tipo = ' . "'E'" . ') then ' . "'Entradas'" . '
                                                      else case when (categorias.tipo = ' . "'S'" . ') then ' . "'Saidas'" . '
                                                      else case when (categorias.tipo = ' . "'R'" . ') then ' . "'Retiradas'" . ' end end end as tipo,
                                                      case when (categorias.tipo = ' . "'E'" . ') then ' . "'1'" . '
                                                      else case when (categorias.tipo = ' . "'S'" . ') then ' . "'2'" . '
                                                      else case when (categorias.tipo = ' . "'R'" . ') then ' . "'3'" . ' end end end as ordem,
                                                      sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where caixas.id = lancamentos.caixa_id
                                                  and to_char(caixas.dtcaixa, ' . "'yyyy-mm'" . ') = ' . "'" . substr($data[0]['mesano'], 3, 4) . '-' . substr($data[0]['mesano'], 0, 2) . "'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.ativo = ' . "'S'" . '
                                                group by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . '), categorias.tipo
                                                order by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') asc, ordem asc');

            $entradas = 0;
            $saidas = 0;
            $retiradas = 0;

            foreach ($result_aux as $k => $item):
                $string[$item[0]['tipo']] = $item[0]['valor'];
                $string[] = $item[0]['valor'];
                if ($item[0]['tipo'] == 'Entradas') {
                    $entradas = $item[0]['valor'];
                }
                if ($item[0]['tipo'] == 'Saidas') {
                    $saidas = $item[0]['valor'];
                }
                if ($item[0]['tipo'] == 'Retiradas') {
                    $retiradas = $item[0]['valor'];
                }
            endforeach;
            $string['Lucro'] = $entradas - $saidas;
            $string[] = $entradas - $saidas;
            $string['Saldo'] = ($entradas - $saidas) - $retiradas;
            $string[] = ($entradas - $saidas) - $retiradas;
            $string_fim[] = $string;
            $column_chart_linha->addRow($string_fim[0]);
        endforeach;

        $this->set(compact('column_chart_linha'));

        //Relatório de movimentações - Barras

        $tipos = array('E' => 'Entradas', 'S' => 'Saidas', 'R' => 'Retiradas', 'L' => 'Lucro', 'D' => 'Saldo');

        $columns_barras['data'] = array('type' => 'string', 'label' => 'Data');

        foreach ($tipos as $key => $item) :
            $columns_barras[$item] = array('type' => 'number', 'label' => $item);
//            $columns_barras[] = array('type' => 'number', 'role' => 'annotation');
        endforeach;

        $column_chart_barras = new GoogleCharts();

        $column_chart_barras->type('ColumnChart');

        $column_chart_barras->options(array('width' => '80%',
            'heigth' => '70%',
            'title' => '',
//            'colors' => array('#1b9e77', '#d95f02', '#7570b3', '#333222', '#999999'),
            'titleTextStyle' => array('color' => 'grenn'),
            'fontSize' => 12,
        ));

        $column_chart_barras->columns($columns_barras);

        $datas = $this->Caixa->query('select distinct to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') as mesano
                                        from categorias,
                                             lancamentos,
                                             caixas
                                       where caixas.id = lancamentos.caixa_id
                                         and to_char(caixas.dtcaixa, ' . "'yyyy-mm'" . ') BETWEEN ' . "'" . substr($indices['Relatorio']['dtdespesa_inicio'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_inicio'], 0, 2) . "'" . ' AND ' . "'" . substr($indices['Relatorio']['dtdespesa_fim'], 3, 4) . '-' . substr($indices['Relatorio']['dtdespesa_fim'], 0, 2) . "'" . '
                                         and caixas.empresa_id = ' . $empresa_id . '
                                         and lancamentos.categoria_id = categorias.id
                                         and categorias.ativo = ' . "'S'" . '
                                       group by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ')
                                       order by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') asc');

        foreach ($datas as $d => $data):
            $string = '';
            $string_fim = '';
            $string['data'] = $data[0]['mesano'];

            $result_aux = $this->Caixa->query('select to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') as mesano,
                                                      case when (categorias.tipo = ' . "'E'" . ') then ' . "'Entradas'" . '
                                                      else case when (categorias.tipo = ' . "'S'" . ') then ' . "'Saidas'" . '
                                                      else case when (categorias.tipo = ' . "'R'" . ') then ' . "'Retiradas'" . ' end end end as tipo,
                                                      case when (categorias.tipo = ' . "'E'" . ') then ' . "'1'" . '
                                                      else case when (categorias.tipo = ' . "'S'" . ') then ' . "'2'" . '
                                                      else case when (categorias.tipo = ' . "'R'" . ') then ' . "'3'" . ' end end end as ordem,
                                                      sum(valor)::float as valor
                                                 from categorias,
                                                      lancamentos,
                                                      caixas
                                                where caixas.id = lancamentos.caixa_id
                                                  and to_char(caixas.dtcaixa, ' . "'yyyy-mm'" . ') = ' . "'" . substr($data[0]['mesano'], 3, 4) . '-' . substr($data[0]['mesano'], 0, 2) . "'" . '
                                                  and caixas.empresa_id = ' . $empresa_id . '
                                                  and lancamentos.categoria_id = categorias.id
                                                  and categorias.ativo = ' . "'S'" . '
                                                group by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . '), categorias.tipo
                                                order by to_char(caixas.dtcaixa, ' . "'mm-yyyy'" . ') asc, ordem asc');

            $entradas = 0;
            $saidas = 0;
            $retiradas = 0;

            foreach ($result_aux as $k => $item):
                $string[$item[0]['tipo']] = $item[0]['valor'];
                $string[] = $item[0]['valor'];
                if ($item[0]['tipo'] == 'Entradas') {
                    $entradas = $item[0]['valor'];
                }
                if ($item[0]['tipo'] == 'Saidas') {
                    $saidas = $item[0]['valor'];
                }
                if ($item[0]['tipo'] == 'Retiradas') {
                    $retiradas = $item[0]['valor'];
                }
            endforeach;
            $string['Lucro'] = $entradas - $saidas;
            $string[] = $entradas - $saidas;
            $string['Saldo'] = ($entradas - $saidas) - $retiradas;
            $string[] = ($entradas - $saidas) - $retiradas;
            $string_fim[] = $string;
            $column_chart_barras->addRow($string_fim[0]);
        endforeach;

        $this->set(compact('column_chart_barras'));
    }

}

