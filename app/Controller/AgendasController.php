<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Agenda Controller
 */
class AgendasController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Agenda');
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

        $data = '';
        $dadosUser = $this->Session->read();

        $this->Agenda->Especialista->recursive = -1;
        $especialistas = $this->Agenda->Especialista->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S')));

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Agenda.data' => array(
                            'operator' => '=',
                        )
                    ),
                    'filter2' => array(
                        'Agenda.especialista_id' => array(
                            'select' => $especialistas
                        )
                    ),
                )
        );

        foreach ($this->Filter->getConditions() as $key => $item) :
            if ($key == 'Agenda.data =') {
                $data = str_replace('/', '-', $item);
            }
            if ($key == 'Agenda.especialista_id =') {
                $especialista_id = $item;
            }
        endforeach;

        if (!empty($data) and (!empty($especialista_id))) {
            //hora inicial
            $horaInicial = new DateTime('07:00');
            $this->set('horaInicial', $horaInicial);

            //hora final
            $horaFinal = new DateTime('22:00');
            $this->set('horaFinal', $horaFinal);

//        $DiasSemana = array("domingo", "segunda-feira", "terça-feira", "quarta-feira", "quinta-feira", "sexta-feira", "sábado");
//        $this->set('DiasSemana', $DiasSemana);
//
//        $Meses = array("01" => "Janeiro", "02" => "Fevereiro", "03" => "Março", "04" => "Abril", "05" => "Maio", "06" => "Junho",
//            "07" => "Julho", "08" => "Agosto", "09" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro");
//        $this->set('Meses', $Meses);

            $consulta_periodo = 50;
            $this->set('consulta_periodo', $consulta_periodo);

            $data_atual = date('Y-m-d');
            $this->set('data_atual', $data_atual);

            $this->set('data', $data);
            $this->set('especialista_id', $especialista_id);
        }
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Tiposervico->id = $id;
        if (!$this->Tiposervico->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tiposervico = $this->Tiposervico->read(null, $id);
        if ($tiposervico ['Tiposervico']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('tiposervico', $tiposervico);
    }

    /**
     * add method
     */
    public function add($id = null) {

        $param = explode('|', $id);

        $data = str_replace('-', '/', $param[0]);
        $this->set('data', $data . ' ' . $param[1] . ':' . $param[2]);

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));

        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->Agenda->Paciente->recursive = -1;
        $pacientes_aux = $this->Agenda->Paciente->find('all', array('order' => 'nome ASC', 'fields' => array('id', 'nome', 'sobrenome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'ativo' => 'S')));

        foreach ($pacientes_aux as $key => $item) :
            $pacientes[$item['Paciente']['id']] = ($item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome']);
        endforeach;
        $this->set('pacientes', $pacientes);

        $this->Agenda->Especialista->recursive = -1;
        $especialistas = $this->Agenda->Especialista->find('list', array('order' => 'nome ASC', 'fields' => array('id', 'nome'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S', 'Especialista.id' => $param[3])));
        $this->set('especialistas', $especialistas);

        $this->Agenda->Tiposervico->recursive = -1;
        $tiposervico = $this->Agenda->Tiposervico->find('list', array('order' => 'descricao ASC', 'fields' => array('id', 'descricao'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'])));
        $this->set('tiposervico', $tiposervico);

        if ($this->request->is('post')) {

            $this->Agenda->Tiposervico->recursive = -1;
            $consulta = $this->Agenda->Tiposervico->find('list', array('fields' => array('duracao_consulta', 'descricao'), 'conditions' => array('id' => $this->request->data['Agenda']['tiposervico_id'])));

            foreach ($consulta as $key => $item) :
                $duracao_consulta = $key;
            endforeach;

            $this->request->data['Agenda']['data'] = substr($this->request->data['Agenda']['datahora'], 6, 4) . '-' . substr($this->request->data['Agenda']['datahora'], 3, 2) . '-' . substr($this->request->data['Agenda']['datahora'], 0, 2);
            $this->request->data['Agenda']['hora_inicio'] = substr($this->request->data['Agenda']['datahora'], 11, 5);
            $this->request->data['Agenda']['hora_fim'] = date('H:i', strtotime($duracao_consulta . 'minute', strtotime($this->request->data['Agenda']['hora_inicio'])));

            $this->Agenda->create();
            if ($this->Agenda->save($this->request->data)) {
                $this->Session->setFlash('Agendamento concluido com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Agenda->id = $id;
        if (!$this->Agenda->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tiposervico = $this->Agenda->read(null, $id);
        if ($tiposervico['Agenda']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('cortiposervico', $tiposervico['Agenda']['cor']);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Tiposervico->save($this->request->data)) {
                $this->Session->setFlash('Tipo de serviço alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Tiposervico->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Tiposervico->id = $id;
        if (!$this->Tiposervico->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->Tiposervico->delete()) {
            $this->Session->setFlash('Tipo de serviço deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * delete method
     */
    public function busca_consulta($data = null, $horaInicial = null, $especialista_id = null) {

        $dadosUser = $this->Session->read();

        foreach ($horaInicial as $key => $item) :
            if ($key == 'date') {
                $horaconsulta = substr($item, 11, 5);
            }
        endforeach;

        $conditions[] = "'" . $horaconsulta . "'" . ' BETWEEN Agenda.hora_inicio AND Agenda.hora_fim';
        $conditions[] = 'Agenda.especialista_id = ' . $especialista_id;
        $conditions[] = 'Agenda.empresa_id = ' . $dadosUser['empresa_id'];

        $this->Agenda->recursive = 0;
        $consulta = $this->Agenda->find('all', array(
            'fields' => array('Agenda.id', 'Agenda.data', 'Agenda.observacao', 'Agenda.periodos', 'Agenda.paciente_id', 'Especialista.id', 'Especialista.nome', 'Paciente.id', 'Paciente.nome', 'Tiposervico.id', 'Tiposervico.descricao', 'Tiposervico.cor'),
            'conditions' => array('Agenda.data' => $data, $conditions),
            'order' => array('hora_inicio' => 'asc')
        ));

        if (!empty($consulta)) {
            return $consulta[0]['Paciente']['nome'] . '|' . $consulta[0]['Tiposervico']['cor'] . '|' . $consulta[0]['Agenda']['observacao'] . '|' . $consulta[0]['Agenda']['paciente_id'] . '|' . $consulta[0]['Tiposervico']['id'] . '|' . $consulta[0]['Agenda']['periodos'];
        } else {
            return '';
        }
    }

}

?>
