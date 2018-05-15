<?php
App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Menus Controller
 */
class EstadosController extends AppController {
    
    function beforeFilter() {
        $this->set('title_for_layout', 'Estados');
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
        $this->Estado->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('Estado.holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        );
        $this->set('estados', $this->Paginator->paginate('Estado'));
    }

    /**
     * view method
     */
    public function view($id = null) {
        
        $this->Estado->id = $id;
        if (!$this->Estado->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $estado = $this->Estado->read(null, $id);
        if ($estado['Paise']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->set('estado', $estado);
        
    }

    /**
     * add method
     */
    public function add() {
               
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->set(compact('holding_id'));
        
        $paises = $this->Estado->Paise->find('list', array(
            'fields' => array('id', 'nome'), 
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set(compact('paises'));
        
        if ($this->request->is('post')) {
            $this->Estado->create();
            if ($this->Estado->save($this->request->data)) {
                $this->Session->setFlash('Estado adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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
        
        $this->Estado->id = $id;
        if (!$this->Estado->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $estado = $this->Estado->read(null, $id);
        if ($estado['Paise']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $paises = $this->Estado->Paise->find('list', array(
            'fields' => array('id', 'nome'), 
            'conditions' => array('holding_id' => $holding_id),
            'order' => array('nome' => 'asc')
        ));
        $this->set(compact('paises'));
        
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Estado->save($this->request->data)) {
                $this->Session->setFlash('Estado alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $estado;
        }
        
    }

    /**
     * delete method
     */
    public function delete($id = null) {
        
        $this->Estado->id = $id;
        if (!$this->Estado->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        
        $this->request->onlyAllow('post', 'delete');
        if ($this->Estado->delete()) {
            $this->Session->setFlash('Estado deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
        
    }
    
    /**
     * Funções ajax
     */
    
    public function buscaEstados($chave) {
        $this->layout = 'ajax';
        if (array_key_exists("pais_id", $this->request->data[$chave])) {
            $catID = $this->request->data[$chave]['pais_id'];
        }
        $estados = $this->Estado->find('list' , array('order' => 'nome ASC','fields' => array('Estado.id', 'Estado.nome'),'conditions' => array('Estado.pais_id' => $catID)));
        $this->set('estados', $estados);
    }

}
