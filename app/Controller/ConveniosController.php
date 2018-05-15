<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Convenios Controller
 */
class ConveniosController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Convênios');
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
        $this->Convenio->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('descricao' => 'asc')
        );
        $this->set('convenios', $this->Paginator->paginate('Convenio'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Convenio->id = $id;
        if (!$this->Convenio->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $convenios = $this->Convenio->read(null, $id);

        if ($convenios['Convenio']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('convenios', $convenios);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $ativo = array('S' => 'Ativo', 'N' => 'Inativo');
        $this->set(compact('ativo'));

        if ($this->request->is('post')) {
            $this->Convenio->create();
            if ($this->Convenio->save($this->request->data)) {
                $this->Session->setFlash('Convênio adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Convenios', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * edit method
     */
    public function edit($id = null) {

        $this->Convenio->id = $id;
        if (!$this->Convenio->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $convenios = $this->Convenio->read(null, $id);
        if ($convenios['Convenio']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $ativo = array('S' => 'Ativo', 'N' => 'Inativo');
        $this->set(compact('ativo'));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Convenio->save($this->request->data)) {
                $this->Session->setFlash('Convênio alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Convenios', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $convenios;
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Convenio->id = $id;
        if (!$this->Convenio->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Convenio->delete()) {
            $this->Session->setFlash('Convênio deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}

