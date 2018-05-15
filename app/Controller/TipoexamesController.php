<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Tipos de exame Controller
 */
class TipoexamesController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Tipos de exame');
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
        $this->Tipoexame->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('empresa_id' => $dadosUser['empresa_id']),
            'order' => array('descricao' => 'asc')
        );
        $this->set('tipoexames', $this->Paginator->paginate('Tipoexame'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Tipoexame->id = $id;
        if (!$this->Tipoexame->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tipoexame = $this->Tipoexame->read(null, $id);
        if ($tiposervico ['Tipoexame']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('tipoexame', $tipoexame);
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
            $this->Tipoexame->create();
            if ($this->Tipoexame->save($this->request->data)) {
                $this->Session->setFlash('Tipo de exame adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Tipoexame->id = $id;
        if (!$this->Tipoexame->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tiposervico = $this->Tipoexame->read(null, $id);
        if ($tiposervico['Tipoexame']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Tipoexame->save($this->request->data)) {
                $this->Session->setFlash('Tipo de exame alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Tipoexame->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Tipoexame->id = $id;
        if (!$this->Tipoexame->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->request->onlyAllow('post', 'delete');
        if ($this->Tipoexame->delete()) {
            $this->Session->setFlash('Tipo de exame deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Funções ajax
     */
    public function buscaTipoexames($chave, $categoria_id) {

        $this->layout = 'ajax';

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tipoexames = $this->Tipoexame->find('list', array('order' => 'descricao ASC',
            'joins' => array(
                array(
                    'table' => 'categoriatipoexames',
                    'alias' => 'Categoriatipoexame',
                    'type' => 'INNER',
                    'conditions' => array('Categoriatipoexame.tipoexame_id = Tipoexame.id')
                )
            ),
            'fields' => array('Tipoexame.id', 'Tipoexame.descricao'),
            'conditions' => array('Categoriatipoexame.categoria_id' => $categoria_id,
                'Tipoexame.empresa_id' => $empresa_id)));
        $this->set('tipoexames', $tipoexames);
    }

    /**
     * Funções ajax
     */
    public function buscaRelatoriotipoexames($chave, $categoria_id) {

        $this->layout = 'ajax';

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $tipoexames = $this->Tipoexame->find('list', array('order' => 'descricao ASC',
            'joins' => array(
                array(
                    'table' => 'categoriatipoexames',
                    'alias' => 'Categoriatipoexame',
                    'type' => 'INNER',
                    'conditions' => array('Categoriatipoexame.tipoexame_id = Tipoexame.id')
                )
            ),
            'fields' => array('Tipoexame.id', 'Tipoexame.descricao'),
            'conditions' => array('Categoriatipoexame.categoria_id' => $categoria_id,
                'Tipoexame.empresa_id' => $empresa_id)));
        $this->set('tipoexames', $tipoexames);
    }

}

?>
