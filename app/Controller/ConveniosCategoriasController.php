<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * ConveniosCategorias Controller
 */
class ConveniosCategoriasController extends AppController {

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
    public function index($convenio_id = null) {

        $dadosUser = $this->Session->read();

        $this->set('convenio_id', $convenio_id);

        $this->ConveniosCategoria->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('Convenio.empresa_id' => $dadosUser['empresa_id'], 'Convenio.ativo' => 'S', 'convenio_id' => $convenio_id),
            'order' => array('descricao' => 'asc')
        );
        $this->set('convenioscategorias', $this->Paginator->paginate('ConveniosCategoria'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->ConveniosCategoria->id = $id;
        if (!$this->ConveniosCategoria->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $convenios = $this->ConveniosCategoria->read(null, $id);
        if ($convenios['ConveniosCategoria']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('convenios', $convenios);
    }

    /**
     * add method
     */
    public function add($convenio_id = null) {

        $dadosUser = $this->Session->read();

        $convenios = $this->ConveniosCategoria->Convenio->find('list', array(
            'fields' => array('id', 'descricao'),
            'conditions' => array('ativo' => 'S', 'empresa_id' => $dadosUser['empresa_id'], 'id' => $convenio_id),
            'order' => array('descricao' => 'asc')
        ));
        $this->set('convenios', $convenios);

        if ($this->request->is('post')) {
            $this->ConveniosCategoria->create();
            if ($this->ConveniosCategoria->save($this->request->data)) {
                $this->Session->setFlash('Plano adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'ConveniosCategorias', 'action' => 'index/' . $convenio_id));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * edit method
     */
    public function edit($id = null) {

        $this->ConveniosCategoria->id = $id;
        if (!$this->ConveniosCategoria->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index/' . $id));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $convenios = $this->ConveniosCategoria->read(null, $id);
        if ($convenios['Convenio']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Convenios', 'action' => 'index'));
        }

        $ativo = array('S' => 'Sim', 'N' => 'Não');
        $this->set(compact('ativo'));

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->ConveniosCategoria->save($this->request->data)) {
                $this->Session->setFlash('Convênio alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index/' . $convenios['Convenio']['id']));
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
    public function delete($convenio_id = null, $id = null) {

        $this->ConveniosCategoria->id = $id;
        if (!$this->ConveniosCategoria->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->ConveniosCategoria->delete()) {
            $this->Session->setFlash('Categoria deletada com sucesso.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'ConveniosCategorias', 'action' => 'index/' . $convenio_id));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('controller' => 'Convenios', 'action' => 'index'));
    }

    /**
     * Funções ajax
     */
    public function buscaCategorias($chave) {
        $this->layout = 'ajax';
        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        if (array_key_exists("convenio_id", $this->request->data[$chave])) {
            $catID = $this->request->data[$chave]['convenio_id'];
        }
        $convenioscategorias = $this->ConveniosCategoria->find('list', array('order' => 'descricao ASC', 'fields' => array('ConveniosCategoria.id', 'ConveniosCategoria.descricao'), 'conditions' => array('ConveniosCategoria.convenio_id' => $catID, 'ativo' => 'S')));
        $this->set('convenioscategorias', $convenioscategorias);
    }

}

