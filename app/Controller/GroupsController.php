<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Groups Controller
 */
class GroupsController extends AppController {
    
    public function beforeFilter() {
        $this->set('title_for_layout', 'Grupos');
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
        $this->Group->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('name' => 'asc')
        );
        $this->set('groups', $this->Paginator->paginate('Group'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Grupo inválido.'));
        }
        $dadosUser = $this->Session->read();
        $group = $this->Group->findById($id);
        if ($group['Holding']['id'] == $dadosUser['Auth']['User']['holding_id']) {
            $this->set('group', $group);
        } else {
            throw new NotFoundException(__('Grupo inválido.'));
        }
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        $this->set(compact('holding_id'));
        
        $this->loadModel('Holdingmenu');
        $this->Holdingmenu->recursive = 1;
        $holdingmenu = $this->Holdingmenu->find('all', array('fields'=>array('Menu.id','Menu.nome'),'conditions'=>array('holding_id'=>$holding_id), 'order' => array('Menu.menu' => 'asc', 'Menu.ordem' => 'asc')));
        $menus = array();
        foreach($holdingmenu as $key => $subcat){
            $menus = $menus + array($subcat['Menu']['id'] => $subcat['Menu']['nome']);
        }        
        $this->set(compact('menus'));
        
        if ($this->request->is('post')) {
            $this->Group->create();
//            debug($this->request->data);
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash('Grupo adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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
        $this->Group->id = $id;
        if (!$this->Group->exists($id)) {
            throw new NotFoundException(__('Grupo inválido.'));
        }
        
        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['Holding']['id'];
        
        $group = $this->Group->read(null, $id);
        if ($group['Holding']['id'] != $dadosUser['Auth']['User']['holding_id']) {
            throw new NotFoundException(__('Grupo inválido.'));
        }
        
        $this->loadModel('Holdingmenu');
        $this->Holdingmenu->recursive = 1;
        $holdingmenu = $this->Holdingmenu->find('all', array('fields'=>array('Menu.id','Menu.nome'),'conditions'=>array('holding_id'=>$holding_id), 'order' => array('Menu.menu' => 'asc', 'Menu.ordem' => 'asc')));
        $menus = array();
        foreach($holdingmenu as $key => $subcat){
            $menus = $menus + array($subcat['Menu']['id'] => $subcat['Menu']['nome']);
        }        
        $this->set(compact('menus'));
        
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash('Grupo alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $group;
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
        $this->Group->id = $id;
        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Grupo inválido.'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Group->delete()) {
            $this->Session->setFlash('Grupo deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}
