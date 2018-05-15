<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Usergroupempresas Controller
 */
class UsergroupempresasController extends AppController {
        
    public function beforeFilter() {
        $this->set('title_for_layout', 'Perfil');
    }
    
    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }
    
    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $dadosUser = $this->Session->read();
        $this->Usergroupempresa->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('User.holding_id' => $dadosUser['Auth']['User']['holding_id'],
                                  'Empresa.holding_id' => $dadosUser['Auth']['User']['holding_id'],
                                  'Group.holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('User.nome' => 'asc', 'Empresa.nomefantasia' => 'asc', 'Group.name' => 'asc')
        );
        $this->set('usergroupempresas', $this->Paginator->paginate('Usergroupempresa'));
    }
    
    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Usergroupempresa->exists($id)) {
            throw new NotFoundException(__('Perfil inválido.'));
        }
        $dadosUser = $this->Session->read();
        $usergroupempresa = $this->Usergroupempresa->findById($id);
        if ($usergroupempresa['User']['holding_id'] == $dadosUser['Auth']['User']['holding_id'] && $usergroupempresa['Empresa']['holding_id'] == $dadosUser['Auth']['User']['holding_id'] && $usergroupempresa['Group']['holding_id'] == $dadosUser['Auth']['User']['holding_id']) {
            $this->set('usergroupempresa', $usergroupempresa);
        } else {
            throw new NotFoundException(__('Invalid group'));
        }
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        $dadosUser = $this->Session->read();
        $usuarios = $this->Usergroupempresa->User->find('list', array('fields'=>array('id','nome'),'conditions'=>array('holding_id' => $dadosUser['Auth']['User']['holding_id']),'order'=>array('nome' => 'asc')));
        $grupos = $this->Usergroupempresa->Group->find('list', array('fields'=>array('id','name'),'conditions'=>array('holding_id' => $dadosUser['Auth']['User']['holding_id']),'order'=>array('name' => 'asc')));
        $empresas = $this->Usergroupempresa->Empresa->find('list', array('fields'=>array('id','nomefantasia'),'conditions'=>array('holding_id' => $dadosUser['Auth']['User']['holding_id']),'order'=>array('nomefantasia' => 'asc')));
        $opcoes = array(1 => 'SIM', 2 => 'NAO');
        $this->set(compact('usuarios', 'grupos', 'empresas', 'opcoes'));
        
        if ($this->request->is('post')) {
            $this->Usergroupempresa->create();
            if ($this->Usergroupempresa->save($this->request->data)) {
                $this->Session->setFlash('Perfil adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
        
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Usergroupempresa->exists($id)) {
            throw new NotFoundException(__('Perfil inválido.'));
        }
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        
        $usergroupempresa = $this->Usergroupempresa->read(null, $id);
        if ($usergroupempresa['User']['holding_id'] != $holding_id || $usergroupempresa['Empresa']['holding_id'] != $holding_id && $usergroupempresa['Group']['holding_id'] != $holding_id) {
            throw new NotFoundException(__('Perfil inválido.sss'));
        }
        
        $this->Usergroupempresa->id = $id;
        
        $usuarios = $this->Usergroupempresa->User->find('list', array('fields'=>array('id','nome'),'conditions'=>array('holding_id' => $holding_id),'order'=>array('nome' => 'asc')));
        $grupos = $this->Usergroupempresa->Group->find('list', array('fields'=>array('id','name'),'conditions'=>array('holding_id' => $holding_id),'order'=>array('name' => 'asc')));
        $empresas = $this->Usergroupempresa->Empresa->find('list', array('fields'=>array('id','nomefantasia'),'conditions'=>array('holding_id' => $holding_id),'order'=>array('nomefantasia' => 'asc')));
        $opcoes = array(1 => 'SIM', 2 => 'NAO');
        $this->set(compact('usuarios', 'grupos', 'empresas', 'opcoes'));
        
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Usergroupempresa->save($this->request->data)) {
                $this->Session->setFlash('Perfil alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $usergroupempresa;
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->Usergroupempresa->id = $id;
        if (!$this->Usergroupempresa->exists()) {
            throw new NotFoundException(__('Perfil inválido.'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Usergroupempresa->delete()) {
            $this->Session->setFlash('Perfil deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Perfil não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}
