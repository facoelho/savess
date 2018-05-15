<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

App::uses('GoogleCharts', 'GoogleCharts.Lib');

/**
 * Contas a receber Controller
 */
class ContasRecebersController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Lançamentos');
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

        $this->loadModel('Paciente');

        $this->Paciente->recursive = 0;
        $pacientes_aux = $this->Paciente->find('all', array('order' => 'Paciente.nome ASC', 'fields' => array('Paciente.id', 'Paciente.nome', 'Paciente.sobrenome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'ativo' => 'S')));

        foreach ($pacientes_aux as $key => $item):
            $pacientes[$item['Paciente']['id']] = $item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome'];
        endforeach;

        $this->loadModel('Especialista');

        $this->Especialista->recursive = 0;
        $especialistas_aux = $this->Especialista->find('all', array('order' => 'Especialista.nome ASC', 'fields' => array('Especialista.id', 'Especialista.nome', 'Especialista.sobrenome'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S')));

        foreach ($especialistas_aux as $key => $item):
            $especialistas[$item['Especialista']['id']] = $item['Especialista']['nome'] . ' ' . $item['Especialista']['sobrenome'];
        endforeach;

        $lancamentos = array('S' => 'Em aberto', 'N' => 'Fechados');

        $this->Filter->addFilters(
                array(
                    'filter4' => array(
                        'Event.especialista_id' => array(
                            'select' => $especialistas
                        ),
                    ),
                    'filter1' => array(
                        'ContasReceber.paciente_id' => array(
                            'select' => $pacientes
                        ),
                    ),
                    'filter2' => array(
                        'ContasReceber.saldo' => array(
                            'select' => $lancamentos
                        ),
                    ),
                    'filter3' => array(
                        'Event.start' => array(
                            'operator' => 'BETWEEN',
                            'between' => array(
                                'text' => __(' e ', true),
                                'date' => true
                            )
                        )
                    ),
                )
        );

        $conditions = array();

        if (is_array($this->Filter->getConditions())) {
            foreach ($this->Filter->getConditions() as $key => $item) :
                if ($key == 'Event.especialista_id =') {
                    $conditions[] = 'Event.especialista_id =' . $item;
                }
                if ($key == 'ContasReceber.paciente_id =') {
                    $conditions[] = 'ContasReceber.paciente_id =' . $item;
                }
                if ($key == 'ContasReceber.saldo =') {
                    if ($item == 'S') {
                        $conditions[] = 'ContasReceber.saldo <> 0';
                    } else {
                        $conditions[] = 'ContasReceber.saldo = 0';
                    }
                }
                if ($key == 'Event.start BETWEEN ? AND ?') {
                    $conditions[] = 'Event.start BETWEEN ' . "'" . $item[0] . "'" . ' AND ' . "'" . $item[1] . "'";
                }
            endforeach;
        }

        $this->ContasReceber->recursive = 0;
        $this->Paginator->settings = array(
            'joins' => array(
                array(
                    "table" => "events",
                    "alias" => "Event",
                    "type" => "INNER",
                    "conditions" => "Event.id = ContasReceber.event_id",
                ),
            ),
            'conditions' => array('ContasReceber.empresa_id' => $dadosUser['empresa_id'], $conditions),
            'order' => 'Event.start desc',
        );

        $this->set('contasrecebers', $this->Paginator->paginate('ContasReceber'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->ContasReceber->id = $id;
        if (!$this->ContasReceber->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $this->set('user_id', $dadosUser['Auth']['User']['id']);

        $this->ContasReceber->recursive = 2;
        $contas_recebers = $this->ContasReceber->read(null, $id);

        $this->set('contas_recebers', $contas_recebers);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();

        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $mensal = array('S' => 'Sim', 'N' => 'Não');
        $this->set('mensal', $mensal);

        if ($this->request->is('post')) {
            $this->Tiposervico->create();
            if ($this->Tiposervico->save($this->request->data)) {
                $this->Session->setFlash('Tipo de serviço adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * add_lancamento method
     */
    public function add_lancamento($id = null) {

        $this->ContasReceber->id = $id;
        if (!$this->ContasReceber->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $this->set('user_id', $dadosUser['Auth']['User']['id']);

        $contas_recebers = $this->ContasReceber->read(null, $id);

        $this->set('contas_recebers', $contas_recebers);

        $formaspagamentos = $this->ContasReceber->ContasRecebersMov->FormasPagamento->find('list', array(
            'fields' => array('id', 'descricao'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        ));
        $this->set(compact('formaspagamentos'));

        if ($this->request->is('post') || $this->request->is('put')) {

            if (round($this->request->data['ContasReceber']['valorlancto']) > round($this->request->data['ContasReceber']['saldo'])) {
                $this->Session->setFlash('Valor do lançamento não pode ser maior que o saldo.', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            try {

                $this->ContasReceber->begin();

                $this->ContasReceber->query('insert into contas_recebers_movs(contas_receber_id, valorlancto, user_id, obs, created, forma_pagamento_id)
                                             values(' . $id . "," . "'" . str_replace(',', '.', $this->request->data['ContasReceber']['valorlancto']) . "'" . "," . $this->request->data['ContasReceber']['user_id'] . "," . "'" . $this->request->data['ContasReceber']['obs'] . "'" . "," . "'" . date('Y-m-d') . " " . date('H:i:s') . "'" . "," . $this->request->data['ContasReceber']['forma_pagamento_id'] . ')');

                $this->ContasReceber->query('update contas_recebers set saldo = saldo - ' . "'" . str_replace(',', '.', $this->request->data['ContasReceber']['valorlancto']) . "'" . ' where id = ' . $id);

                $this->ContasReceber->commit();
                $this->Session->setFlash('Lançamento efetuado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } catch (Exception $id) {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
                $this->Event->rollback();
                $this->redirect(array('action' => 'index'));
            }
        } else {
            $this->request->data = $this->ContasReceber->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->ContasReceber->id = $id;
        if (!$this->ContasReceber->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

//        $this->request->onlyAllow('post', 'delete');
        if ($this->ContasReceber->delete()) {
            $this->Session->setFlash('Lançamento deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * busca_evento method
     */
    public function busca_evento($id = null) {

        $result = $this->ContasReceber->query('select title, start, end from events where id = ' . $id);

        return $result;
    }

    /**
     * imprimir_recibo method
     */
    public function imprimir_recibo($param = null) {

        $id = base64_decode($param);

        $chave = array();

        $chave = explode('|', $id);

        $dadosUser = $this->Session->read();
        $this->set('dadosUser', $dadosUser);

        $this->loadModel('Endereco');
        $this->Endereco->recursive = -1;
        $endereco = $this->Endereco->find('all', array(
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
        ));
        $this->set('endereco', $endereco);

        $this->set('valor', $chave[0]);

        $this->set('start', $chave[1]);

        $extenso = $this->extenso($chave[0]);
        $this->set('extenso', $extenso);
    }

    /**
     * soma_lancamentos method
     */
    public function excluir_lancamento($creceber_id = null, $lancto_id = null) {

        try {
            $this->ContasReceber->begin();

            $result = $this->ContasReceber->query('select valorlancto from contas_recebers_movs where id = ' . $lancto_id);

            $this->ContasReceber->query('delete from contas_recebers_movs where id = ' . $lancto_id);

            $this->ContasReceber->query('update contas_recebers set saldo = saldo + ' . $result[0]['contas_recebers_movs']['valorlancto'] . 'where id = ' . $creceber_id);

            $this->ContasReceber->commit();
            $this->Session->setFlash('Lançamento excluido com sucesso.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'add_lancamento/' . $creceber_id));
        } catch (Exception $lancto_id) {
            $this->Event->rollback();
            $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'add_lancamento/' . $creceber_id));
        }
    }

    /**
     * busca_usuario method
     */
    public function busca_usuario($id = null) {

        $result = $this->ContasReceber->query('select nome, sobrenome from users where id = ' . $id);

        return $result[0]['users']['nome'] . ' ' . $result[0]['users']['sobrenome'];
    }

    /**
     * relatorio_movimentacaos method
     */
    public function relatorio_movimentacaos() {

        $conditions = '';
        $dadosUser = $this->Session->read();

        $this->loadModel('Paciente');

        $this->Paciente->recursive = 0;
        $pacientes_aux = $this->Paciente->find('all', array('order' => 'Paciente.nome ASC', 'fields' => array('Paciente.id', 'Paciente.nome', 'Paciente.sobrenome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'ativo' => 'S')));

        foreach ($pacientes_aux as $key => $item):
            $pacientes[$item['Paciente']['id']] = $item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome'];
        endforeach;
        $this->set(compact('pacientes'));

        $this->loadModel('Especialista');

        $this->Especialista->recursive = 0;
        $especialistas_aux = $this->Especialista->find('all', array('order' => 'Especialista.nome ASC', 'fields' => array('Especialista.id', 'Especialista.nome', 'Especialista.sobrenome'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S')));

        foreach ($especialistas_aux as $key => $item):
            $especialistas[$item['Especialista']['id']] = $item['Especialista']['nome'] . ' ' . $item['Especialista']['sobrenome'];
        endforeach;
        $this->set(compact('especialistas'));

        $this->loadModel('FormasPagamento');

        $this->FormasPagamento->recursive = 0;
        $formaspagamento = $this->FormasPagamento->find('list', array('order' => 'FormasPagamento.descricao ASC', 'fields' => array('FormasPagamento.id', 'FormasPagamento.descricao'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'ativo' => 'S')));
        $this->set(compact('formaspagamento'));

        $lancamentos = array('S' => 'Em aberto', 'N' => 'Fechados');
        $this->set(compact('lancamentos'));

        $ordenar = array('D' => 'Período', 'P' => 'Paciente', 'E' => 'Especialista');
        $this->set(compact('ordenar'));

        $tprelatorio = array('S' => 'Sintético', 'A' => 'Analítico');
        $this->set(compact('tprelatorio'));

        $forma_consulta = array('P' => 'Consulta particular', 'C' => 'Consulta por convênio');
        $this->set(compact('forma_consulta'));

        $this->loadModel('Event');
        $eventTypes = $this->Event->EventType->find('list', array(
            'fields' => array('EventType.id', 'EventType.name'),
            'joins' => array(
                array(
                    'table' => 'empresa_event_types',
                    'alias' => 'EmpresaEventType',
                    'type' => 'INNER',
                    'conditions' => 'EventType.id = EmpresaEventType.event_type_id',
                ),
            ),
            'conditions' => array('EmpresaEventType.empresa_id' => $dadosUser['empresa_id']),
            'order' => array('name' => 'asc')
        ));
        $this->set('eventTypes', $eventTypes);

        $this->loadModel('Convenio');
        $this->Convenio->recursive = 0;
        $convenios = $this->Convenio->find('list', array('order' => 'Convenio.descricao ASC', 'fields' => array('Convenio.id', 'Convenio.descricao'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S')));
        $this->set(compact('convenios'));

        if ($this->request->is('post')) {

            $conditions = ' and events.empresa_id = ' . $dadosUser['empresa_id'];

            if ((!empty($this->request->data['Relatorio']['inicio'])) and (!empty($this->request->data['Relatorio']['inicio']))) {
                $conditions .= ' AND events.start BETWEEN ' . "'" . substr($this->request->data['Relatorio']['inicio'], 6, 4) . '-' . substr($this->request->data['Relatorio']['inicio'], 3, 2) . '-' . substr($this->request->data['Relatorio']['inicio'], 0, 2) . " 00:00:00'" . ' AND ' . "'" . substr($this->request->data['Relatorio']['fim'], 6, 4) . '-' . substr($this->request->data['Relatorio']['fim'], 3, 2) . '-' . substr($this->request->data['Relatorio']['fim'], 0, 2) . " 23:59:59'";
            }

            if (!empty($this->request->data['Relatorio']['especialista_id'])) {
                $conditions .= ' AND events.especialista_id = ' . $this->request->data['Relatorio']['especialista_id'];
            }

            if (!empty($this->request->data['Relatorio']['paciente_id'])) {
                $conditions .= ' AND contas_recebers.paciente_id = ' . $this->request->data['Relatorio']['paciente_id'];
            }

            if (!empty($this->request->data['Relatorio']['event_type_id'])) {
                $conditions .= ' AND events.event_type_id = ' . $this->request->data['Relatorio']['event_type_id'];
            }

            if (!empty($this->request->data['ContasReceber']['convenio_id'])) {
                $conditions .= ' AND convenios.id = ' . $this->request->data['ContasReceber']['convenio_id'];
            }

            if (!empty($this->request->data['Relatorio']['convenio_categoria_id'])) {
                $conditions .= ' AND events.convenio_categoria_id = ' . $this->request->data['Relatorio']['convenio_categoria_id'];
            }

            if (!empty($this->request->data['Relatorio']['formapagamento'])) {
                $conditions .= ' AND contas_recebers_movs.forma_pagamento_id = ' . $this->request->data['Relatorio']['formapagamento'];
            }

            if ($this->request->data['Relatorio']['lancamentos'] == 'S') {
                $conditions .= ' AND contas_recebers.saldo > 0';
            } else {
                $conditions .= ' AND contas_recebers.saldo <= 0';
            }


            if ($this->request->data['Relatorio']['tprelatorio'] == 'S') {

                $result = $this->ContasReceber->query('select pacientes.nome,
                                                          pacientes.sobrenome,
                                                          especialistas.nome,
                                                          especialistas.sobrenome,
                                                          contas_recebers.event_id,
                                                          events.especialista_id,
                                                          contas_recebers.paciente_id,
                                                          contas_recebers.valor,
                                                          contas_recebers.saldo,
                                                          convenios.id,
                                                          convenios.descricao,
                                                          convenios_categorias.descricao,
                                                          events.start,
                                                          event_types.name,
                                                          sum(contas_recebers_movs.valorlancto) as valorlancto
                                                     from contas_recebers left join contas_recebers_movs on (contas_recebers.id = contas_recebers_movs.contas_receber_id),
                                                          events left join convenios on (events.convenio_id = convenios.id)
                                                                 left join event_types on (events.event_type_id = event_types.id)
                                                                 left join convenios_categorias on (events.convenio_categoria_id = convenios_categorias.id),
                                                          pacientes,
                                                          especialistas
                                                    where contas_recebers.event_id = events.id
                                                      and contas_recebers.paciente_id = pacientes.id
                                                      and events.especialista_id = especialistas.id
                                                      ' . $conditions . '
                                                    group by pacientes.nome,
                                                          pacientes.sobrenome,
                                                          especialistas.nome,
                                                          especialistas.sobrenome,
                                                          contas_recebers.event_id,
                                                          events.especialista_id,
                                                          contas_recebers.paciente_id,
                                                          contas_recebers.saldo,
                                                          events.start,
                                                          event_types.name
                                                    order by events.start');

                $this->relatorio_sintetico($result);

                $this->render('relatorio_sintetico');
            } else {
                $result = $this->ContasReceber->query('select pacientes.nome,
                                                          pacientes.sobrenome,
                                                          especialistas.nome,
                                                          especialistas.sobrenome,
                                                          contas_recebers.event_id,
                                                          events.especialista_id,
                                                          contas_recebers.id,
                                                          contas_recebers.paciente_id,
                                                          contas_recebers.valor,
                                                          contas_recebers.saldo,
                                                          convenios.id,
                                                          convenios.descricao,
                                                          convenios_categorias.descricao,
                                                          contas_recebers_movs.valorlancto,
                                                          formas_pagamentos.descricao,
                                                          events.start,
                                                          event_types.name,
                                                          count(contas_recebers_movs.contas_receber_id) as cont
                                                     from contas_recebers left join contas_recebers_movs on (contas_recebers.id = contas_recebers_movs.contas_receber_id)
                                                                          left join formas_pagamentos on (formas_pagamentos.id = contas_recebers_movs.forma_pagamento_id),
                                                          events left join convenios on (events.convenio_id = convenios.id)
                                                                 left join event_types on (events.event_type_id = event_types.id)
                                                                 left join convenios_categorias on (events.convenio_categoria_id = convenios_categorias.id),
                                                          pacientes,
                                                          especialistas
                                                    where contas_recebers.event_id    = events.id
                                                      and contas_recebers.paciente_id = pacientes.id
                                                      and events.especialista_id      = especialistas.id
                                                      ' . $conditions . '
                                                    group by pacientes.nome,
                                                            pacientes.sobrenome,
                                                            especialistas.nome,
                                                            especialistas.sobrenome,
                                                            contas_recebers.event_id,
                                                            events.especialista_id,
                                                            contas_recebers.id,
                                                            contas_recebers.paciente_id,
                                                            contas_recebers.valor,
                                                            contas_recebers.saldo,
                                                            convenios.id,
                                                            convenios.descricao,
                                                            convenios_categorias.descricao,
                                                            contas_recebers_movs.valorlancto,
                                                            formas_pagamentos.descricao,
                                                            events.start,
                                                            event_types.name
                                                    order by events.start');

                $this->relatorio_analitico($result);

                $this->render('relatorio_analitico');
            }
        }
    }

    /**
     * relatorio_sintetico_paciente method
     */
    public function relatorio_sintetico($result = array()) {

        $this->set('result', $result);

        $chart = new GoogleCharts();
        $chart->type("ColumnChart");
        $chart->options(array('width' => '80%', 'heigth' => '40%', 'title' => "Défice por cliente"));
        $chart->columns(array(
            'paciente' => array(
                'type' => 'string',
                'label' => 'Paciente'
            ),
            'saldo' => array(
                'type' => 'number',
                'label' => 'Saldo a receber',
                'format' => '#,###'
            )
        ));

        foreach ($result as $item) {
            $chart->addRow(array('paciente' => $item['pacientes']['nome'] . ' ' . $item['pacientes']['sobrenome'], 'saldo' => $item['contas_recebers']['saldo']));
        }
        $this->set(compact('chart'));
    }

    /**
     * relatorio_analitico_paciente method
     */
    public function relatorio_analitico($result = array()) {

        $this->set('result', $result);
    }

    /**
     * relatorio_analitico_paciente method
     */
    public function busca_numero_movimentacoes($id = null) {

        $result = $this->ContasReceber->query('select count(*) as cont
                                                 from contas_recebers_movs
                                                where contas_receber_id = ' . $id);

        return $result[0][0]['cont'];
    }

    public function extenso($valor = 0, $maiusculas = false) {

        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
            "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
            "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
            "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
            "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
            "sete", "oito", "nove");

        $z = 0;
        $rt = "";

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];

        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                    $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                        ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }

        if (!$maiusculas) {
            return($rt ? $rt : "zero");
        } else {

            if ($rt)
                $rt = ereg_replace(" E ", " e ", ucwords($rt));
            return (($rt) ? ($rt) : "Zero");
        }

        $dim = extenso($valor);
        $dim = ereg_replace(" E ", " e ", ucwords($dim));

        $valor = number_format($valor, 2, ",", ".");

        return $valor;
    }

}

?>
