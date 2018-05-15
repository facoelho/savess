<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Evolucaos Controller
 */
class EvolucaosController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Evolução');
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
//        $pacientes_aux = $this->Paciente->find('all', array('order'=>'Paciente.nome' => 'asc'), 'fields'=>array('Paciente.id','Paciente.nome', 'Paciente.sobrenome'), 'conditions' => array('Paciente.holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'Paciente.ativo' => 'S'));

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
        $this->set(compact('especialistas'));

        $this->Filter->addFilters(
                array(
                    'filter3' => array(
                        'Event.especialista_id' => array(
                            'select' => $especialistas
                        ),
                    ),
                    'filter1' => array(
                        'Event.paciente_id' => array(
                            'select' => $pacientes
                        ),
                    ),
                    'filter2' => array(
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

        $this->loadModel('Event');

        $this->Paginator->settings = array(
            'conditions' => array('Event.empresa_id' => $dadosUser['empresa_id'], $this->Filter->getConditions()),
            'order' => array('Event.start' => 'desc')
        );

        $this->set('events', $this->Paginator->paginate('Event'));
    }

    /**
     * edit method
     */
    public function edit($event_id = null, $paciente_id = null) {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set('empresa_id', $empresa_id);

        $this->set('event_id', $event_id);
        $this->set('paciente_id', $paciente_id);

        $evolucao = $this->Evolucao->Event->read(null, $event_id);
        $this->set('evolucao', $evolucao);

        if ($this->request->is('post') || $this->request->is('put')) {
            try {

                $this->Evolucao->begin();

                $result = $this->Evolucao->query('select count(*) as cont from evolucaos where event_id = ' . $this->request->data['Evolucao']['event_id']);

                if ($result[0][0]['cont'] == 0) {
                    $obs = str_replace("'", "", $this->request->data['Evolucao']['obs']);
                    $this->Evolucao->query('insert into evolucaos (event_id, paciente_id, empresa_id, obs)
                                             values (' . $this->request->data['Evolucao']['event_id'] . "," . $this->request->data['Evolucao']['paciente_id'] . "," . $this->request->data['Evolucao']['empresa_id'] . "," . "'" . $obs . "'" . ')');
                } else {
                    $obs = str_replace("'", "", $this->request->data['Evolucao']['obs']);
                    $this->Evolucao->query('update evolucaos set obs = ' . "'" . $obs . "'" . '
                                             where event_id = ' . $this->request->data['Evolucao']['event_id'] . '
                                               and paciente_id = ' . $this->request->data['Evolucao']['paciente_id'] . '
                                               and empresa_id  = ' . $this->request->data['Evolucao']['empresa_id']);
                }

                $this->Evolucao->commit();
                $this->Session->setFlash('Evolução alterada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => '../Pacientes/view/' . $this->request->data['Evolucao']['paciente_id']));
            } catch (Exception $event_id) {
                $this->Evolucao->rollback();
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Paise->id = $id;
        if (!$this->Paise->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Paise->delete()) {
            $this->Session->setFlash('País deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}

