<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Parametros Controller
 */
class ParametrosController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Parametros');
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
        $this->Parametro->recursive = 0;
        $this->Paginator->settings = array(
            'order' => array('parametro' => 'asc')
        );
        $this->set('parametros', $this->Paginator->paginate('Parametro'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Parametro->id = $id;
        if (!$this->Parametro->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();

        $parametro = $this->Parametro->read(null, $id);

        $this->set('parametro', $parametro);

        if (!empty($parametro['Parametro']['user_alt_id'])) {

            $this->loadModel('User');

            $user_alteracao = $this->User->find('all', array(
                'fields' => array('User.id', 'nome', 'sobrenome', 'Parametro.modified'),
                'joins' => array(
                    array(
                        'table' => 'parametros',
                        'alias' => 'Parametro',
                        'type' => 'INNER',
                        'conditions' => array('Parametro.user_alt_id = User.id')
                    )
                ),
            ));
            $this->set('user_alteracao', $user_alteracao[0]);
        }
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();

        if ($this->request->is('post')) {

            $cont = $this->Parametro->query('select count(*) as cont
                                               from public.parametros
                                              where parametro  = ' . "'" . $this->request->data['Parametro']['parametro'] . "'" . '
                                                and empresa_id = ' . $dadosUser['empresa_id']);

            if ($cont[0][0]['cont'] > 0) {
                $this->Session->setFlash('Parâmetro já cadastrado!', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            $this->Parametro->create();

            $this->request->data['Parametro']['created'] = date('d/m/Y H:i:s');
            $this->request->data['Parametro']['user_id'] = $dadosUser['Auth']['User']['id'];
            $this->request->data['Parametro']['empresa_id'] = $dadosUser['empresa_id'];
            $this->request->data['Parametro']['created'] = date('Y-m-d h:i:s');

            if ($this->Parametro->save($this->request->data)) {
                $this->Session->setFlash('Parâmetro adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Parametro->id = $id;
        if (!$this->Parametro->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['Parametro']['modified'] = date('d/m/Y H:i:s');
            $this->request->data['Parametro']['user_alt_id'] = $dadosUser['Auth']['User']['id'];

            if ($this->Parametro->save($this->request->data)) {
                $this->Session->setFlash('Parametro alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Parametro->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Parametro->id = $id;
        if (!$this->Parametro->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Parametro->delete()) {
            $this->Session->setFlash('Parametro deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        if (!$this->Session->check('Message.flash')) {
            $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        }
        $this->redirect(array('action' => 'index'));
    }

}
