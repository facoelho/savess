<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * FormasPagamentos Controller
 */
class FormasPagamentosController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Formas de pagamento');
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
        $this->FormasPagamento->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('descricao' => 'asc')
        );
        $this->set('formaspagamentos', $this->Paginator->paginate('FormasPagamento'));
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
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        if ($this->request->is('post')) {
            $this->FormasPagamento->create();

            if ($this->FormasPagamento->save($this->request->data)) {
                $this->Session->setFlash('Forma de pagamento adicionada com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->FormasPagamento->id = $id;
        if (!$this->FormasPagamento->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        $formapagamento = $this->FormasPagamento->read(null, $id);
        if ($formapagamento['FormasPagamento']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->FormasPagamento->save($this->request->data)) {
                $this->Session->setFlash('Forma de pagamento alterada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->FormasPagamento->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->FormasPagamento->id = $id;
        if (!$this->FormasPagamento->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->FormasPagamento->delete()) {
            $this->Session->setFlash('Forma de pagamento deletada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}
