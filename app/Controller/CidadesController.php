<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Menus Controller
 */
class CidadesController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Cidades');
    }

    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }

    public $components = array('Paginator');

    /**
     * index method
     */
    public function index() {
        $dadosUser = $this->Session->read();
        $this->Cidade->Estado->Paise->recursive = -1;
        $paises = $this->Cidade->Estado->Paise->find('list', array(
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->Cidade->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('Estado.pais_id' => $paises),
            'order' => array('Estado.nome' => 'asc', 'Cidade.nome' => 'asc')
        );
        $this->set('cidades', $this->Paginator->paginate('Cidade'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Cidade->id = $id;
        if (!$this->Cidade->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->Cidade->recursive = 2;
        $cidade = $this->Cidade->read(null, $id);
        if ($cidade['Estado']['Paise']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('cidade', $cidade);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->set(compact('holding_id'));

        $this->Cidade->Estado->Paise->recursive = -1;
        $paises = $this->Cidade->Estado->Paise->find('list', array(
            'fields' => array('id', 'nome'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set('paises', $paises);

        if ($this->request->is('post')) {
            $this->Cidade->create();
            if ($this->Cidade->save($this->request->data)) {
                $this->Session->setFlash('Cidade adicionada com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Cidade->id = $id;
        if (!$this->Cidade->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->Cidade->recursive = 2;
        $cidade = $this->Cidade->read(null, $id);
        if ($cidade['Estado']['Paise']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Cidade->save($this->request->data)) {
                $this->Session->setFlash('Cidade alterada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $cidade;
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Cidade->id = $id;
        if (!$this->Cidade->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Cidade->delete()) {
            $this->Session->setFlash('Cidade deletada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Funções ajax
     */
    public function buscaCidades($chave) {
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->layout = 'ajax';
        if (array_key_exists("estado_id", $this->request->data[$chave])) {
            $catID = $this->request->data[$chave]['estado_id'];
        }
        $cidades = $this->Cidade->find('list', array('order' => 'nome ASC', 'fields' => array('Cidade.id', 'Cidade.nome'), 'conditions' => array('Cidade.estado_id' => $catID, 'Cidade.holding_id' => $holding_id)));
        $this->set('cidades', $cidades);
    }

}
