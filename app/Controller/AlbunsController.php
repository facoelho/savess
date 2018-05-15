<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Lotes Controller
 */
class AlbunsController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Albuns');
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

        $this->Albun->recursive = 0;
        $diretorios = $this->Albun->find('list', array('order' => 'titulo ASC', 'fields' => array('id', 'titulo'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'])));

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Albun.id' => array(
                            'select' => $diretorios
                        ),
                    ),
                )
        );

        $this->Paginator->settings = array(
            'conditions' => array($this->Filter->getConditions(), 'empresa_id' => $dadosUser['empresa_id']),
            'order' => array('titulo' => 'asc')
        );
        $this->set('albuns', $this->Paginator->paginate('Albun'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Albun->id = $id;
        if (!$this->Albun->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('id', $id);

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set('empresa_id', $empresa_id);

        $this->Albun->recursive = 1;
        $albun = $this->Albun->read(null, $id);

        if ($albun['Albun']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('albun', $albun);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();

        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->loadModel('Paciente');

        $this->Paciente->recursive = 0;
        $pacientes_aux = $this->Paciente->find('all', array('order' => 'Paciente.nome ASC', 'fields' => array('Paciente.id', 'Paciente.nome', 'Paciente.sobrenome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'ativo' => 'S')));

        foreach ($pacientes_aux as $key => $item):
            $pacientes[$item['Paciente']['id']] = $item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome'];
        endforeach;
        $this->set('pacientes', $pacientes);

        if ($this->request->is('post')) {
            $this->Albun->create();
            if ($this->Albun->save($this->request->data)) {
                $this->Session->setFlash('Albun adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Albun->id = $id;
        if (!$this->Albun->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $this->loadModel('Paciente');

        $this->Paciente->recursive = 0;
        $pacientes_aux = $this->Paciente->find('all', array('order' => 'Paciente.nome ASC', 'fields' => array('Paciente.id', 'Paciente.nome', 'Paciente.sobrenome'), 'conditions' => array('holding_id' => $dadosUser['Auth']['User']['Holding']['id'], 'ativo' => 'S')));

        foreach ($pacientes_aux as $key => $item):
            $pacientes[$item['Paciente']['id']] = $item['Paciente']['nome'] . ' ' . $item['Paciente']['sobrenome'];
        endforeach;
        $this->set('pacientes', $pacientes);

        $albun = $this->Albun->read(null, $id);
        if ($albun['Albun']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Albun->id = $id;
            $this->request->data['Albun']['modified'] = date("Y-m-d H:i:s");
            if ($this->Albun->save($this->request->data)) {
                $this->Session->setFlash('Albun alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $albun;
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Albun->id = $id;
        if (!$this->Albun->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

//        $this->request->onlyAllow('post', 'delete');
        if ($this->Albun->delete()) {
            $this->Session->setFlash('Album deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}

?>
