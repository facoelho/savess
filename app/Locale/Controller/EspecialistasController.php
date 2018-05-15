<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Especialistas Controller
 */
class EspecialistasController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Especialistas');
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
        $this->Especialista->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('nome' => 'asc')
        );
        $this->set('especialistas', $this->Paginator->paginate('Especialista'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Especialista->id = $id;
        if (!$this->Especialista->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $especialista = $this->Especialista->read(null, $id);
        if ($especialista ['Especialista']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('especialista', $especialista);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        if ($this->request->is('post')) {
            $this->Especialista->create();

            if ($this->Especialista->save($this->request->data)) {
                $this->Session->setFlash('Especialista adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Especialista->id = $id;
        if (!$this->Especialista->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        $especialista = $this->Especialista->read(null, $id);
        if ($especialista['Especialista']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Especialista->save($this->request->data)) {
                $this->Session->setFlash('Especialista alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Especialista->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Especialista->id = $id;
        if (!$this->Especialista->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Especialista->delete()) {
            $this->Session->setFlash('Especialista deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}
