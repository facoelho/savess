<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Menus Controller
 */
class MenusController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Menus');
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

        if ($dadosUser['Auth']['User']['adminmaster'] <> 1) {
            $this->redirect(array('controller' => 'Homes', 'action' => 'index'));
        }

        $this->Menu->recursive = 0;
        $this->Paginator->settings = array(
            'order' => array('menu' => 'asc')
        );
        $this->set('menus', $this->Paginator->paginate('Menu'));
    }

    /**
     * view method
     */
    public function view($id = null) {
        if (!$this->Menu->exists($id)) {
            throw new NotFoundException(__('Invalid menu'));
        }
        $options = array('conditions' => array('Menu.' . $this->Menu->primaryKey => $id));
        $this->set('menu', $this->Menu->find('first', $options));
    }

    /**
     * add method
     */
    public function add() {

        $opcoes = array(1 => 'SIM', 2 => 'NAO');
        $this->set('opcoes', $opcoes);

        if ($this->request->is('post')) {
            if ($this->request->data['Menu']['mostramenu'] == 2) {
                $this->Menu->validator()->remove('menu');
                $this->Menu->validator()->remove('ordem');
            }
            $this->Menu->create();
            if ($this->Menu->save($this->request->data)) {
                $this->Session->setFlash('Menu adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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
        $this->Menu->id = $id;
        if (!$this->Menu->exists($id)) {
            throw new NotFoundException(__('Menu inválido'));
        }

        $opcoes = array(1 => 'SIM', 2 => 'NAO');
        $this->set('opcoes', $opcoes);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Menu->save($this->request->data)) {
                $this->Session->setFlash('Menu alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Menu->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {
        $this->Menu->id = $id;
        if (!$this->Menu->exists()) {
            throw new NotFoundException(__('Menu inválido'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Menu->delete()) {
            $this->Session->setFlash('Menu deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    /**
     * montamenu method
     */
    public function montamenu($id = null) {

        $dadosUser = $this->Session->read();

        if (!empty($dadosUser['Auth']['User'])) {
            $this->loadModel('Usergroupempresa');
            $perfil = $this->Usergroupempresa->find('all', array(
                'conditions' => array('user_id' => $dadosUser['Auth']['User']['id'],
                    'empresa_id' => $dadosUser['empresa_id'],
            )));
            $perfis = "";
            for ($i = 0; $i < count($perfil); $i++) {
                if ($i > 0) {
                    $perfis = $perfis . ",";
                }
                $perfis = $perfis . $perfil[$i]['Group']['id'];
            }
            if ($dadosUser['Auth']['User']['adminmaster'] == 1) {
                $arrayConditions = array('Group.id IN (' . $perfis . ')',
                    'Menu.mostramenu' => 1,
                    'Menu.menu' => $id);
            } else {
                $arrayConditions = array('Group.id IN (' . $perfis . ')',
                    'Menu.mostramenu' => 1,
                    'Menu.menu' => $id,
                    array('NOT' => array('Menu.id' => array(1, 6))));
            }
            $this->loadModel('Groupmenu');
            $this->Groupmenu->recursive = 1;
            $menuCarregado = $this->Groupmenu->find('all', array('conditions' => $arrayConditions,
                'fields' => array('Menu.id',
                    'Menu.nome',
                    'Menu.ordem',
                    'Menu.menu',
                    'Menu.controller'),
                'order' => array('Menu.menu' => 'asc',
                    'Menu.ordem' => 'asc'),
            ));
            $this->set('menus', $menuCarregado);
        }
    }

}
