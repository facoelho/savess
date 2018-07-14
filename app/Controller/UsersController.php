<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 */
class UsersController extends AppController {

    public $name = 'Users';

    public function isAuthorized($user) {
        if ($this->action == 'login' || $this->action == 'logout' || $this->action == 'validaAcesso' || $this->action == 'trocaEmpresa') {
            return true;
        } else {
            return $this->validaAcesso($this->Session->read(), $this->request->controller);
        }
        return parent::isAuthorized($user);
    }

    public function beforeFilter() {
        Security::setHash('sha256');
        $this->set('title_for_layout', 'Usuários');
    }

    public function index() {
        $dadosUser = $this->Session->read();
        $this->set('adminholding', $dadosUser['Auth']['User']['adminholding']);

        $this->User->recursive = 0;
        if ($dadosUser['Auth']['User']['adminmaster'] == 1) {
            $this->Paginator->settings = array(
                'order' => array('ultimoacesso' => 'desc',
                    'ultimoacesso' => 'desc',)
            );
        } else {
            $this->Paginator->settings = array(
                'conditions' => array('User.id' => $dadosUser['Auth']['User']['id']),
                'order' => array('ultimoacesso' => 'desc',
                    'ultimoacesso' => 'desc',)
            );
        }
        $this->set('users', $this->Paginator->paginate('User'));
    }

    public function validaAcesso($user, $controller) {
        if (!empty($user['Auth']['User'])) {
            $this->loadModel('Usergroupempresa');
            $perfil = $this->Usergroupempresa->find('all', array(
                'conditions' => array('user_id' => $user['Auth']['User']['id'],
                    'empresa_id' => $user['empresa_id'],
            )));
            $perfis = "";
            for ($i = 0; $i < count($perfil); $i++) {
                if ($i > 0) {
                    $perfis = $perfis . ",";
                }
                $perfis = $perfis . $perfil[$i]['Group']['id'];
            }
            $this->loadModel('Groupmenu');
            $this->Groupmenu->recursive = 1;
            $menuCarregado = $this->Groupmenu->find('all', array('conditions' => array('Group.id IN (' . $perfis . ')',
                    'Menu.controller' => $controller),
                'fields' => array('Menu.id',
                    'Menu.nome',
                    'Menu.ordem',
                    'Menu.menu',
                    'Menu.controller'),
                'order' => array('Menu.menu' => 'asc',
                    'Menu.ordem' => 'asc'),
            ));
            if (count($menuCarregado) > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }

    public function login() {

//        $this->loadModel('Empresa');
//        $empresas = $this->Empresa->find('list', array(
//            'fields' => array('id', 'razaosocial'),
//            'order' => array('razaosocial'),
//        ));
//        $this->set('empresas', $empresas);

        if ($this->request->is('post')) {

            if ($this->Auth->login()) {

                $usuario = $this->User->read(null, $this->Auth->user('id'));
//
//                $result = $this->User->query('select count(*) as cont
//                                                from usergroupempresas
//                                               where user_id = ' . $usuario['User']['id'] . '
//                                                 and empresa_id = ' . $this->request->data['User']['empresa_id']);
//
//                if ($result[0][0]['cont'] == 0) {
//                    $this->Session->setFlash('Usuário não tem permissão para acessar a empresa selecionada.', 'default', array('class' => 'mensagem_erro'));
//                    $this->redirect($this->Auth->logout());
//                }
//                if (strtotime($usuario['Holding']['validade']) < strtotime(date('Y-m-d H:i:s'))) {
//                    $this->Auth->logout();
//                    $this->redirect("http://www.savess.com/?erro=validade");
//                }
                //Grava último acesso do usuário
                $this->User->saveField('ultimoacesso', date('Y-m-d H:i:s'));

                // testa se existe um perfil pra ele em uma empresa
                $this->loadModel('Usergroupempresa');
                $empresas = $this->Usergroupempresa->find('all', array(
                    'fields' => array('Empresa.id', 'Empresa.nomefantasia'),
                    'conditions' => array('user_id' => $this->User->id),
                    'order' => array('Empresa.nomefantasia'),
                    'group' => array('Empresa.id', 'Empresa.nomefantasia'),
                ));
                $empresaBoot = $this->Usergroupempresa->find('all', array(
                    'conditions' => array('user_id' => $this->User->id,
                        'empresaboot' => 1,
                )));
                CakeSession::write('nomeEmpresa', $empresaBoot[0]['Empresa']['nomefantasia']);
                CakeSession::write('empresa_id', $empresaBoot[0]['Empresa']['id']);
                CakeSession::write('empresa_logo', $empresaBoot[0]['Empresa']['img_foto']);
                CakeSession::write('empresa_tipologo', $empresaBoot[0]['Empresa']['tipoimagem']);
                CakeSession::write('empresasCombo', $empresas);
                $this->redirect(array('controller' => 'homes', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Usuário ou senha incorretos.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function trocaEmpresa($empresa) {
        if ($this->request->is('get')) {
            // testa se existe um perfil pra ele em uma empresa
            $this->loadModel('Usergroupempresa');
            $perfil = $this->Usergroupempresa->find('all', array(
                'conditions' => array('user_id' => $this->Auth->user('id'),
                    'empresa_id' => $empresa,
            )));
            CakeSession::write('nomeEmpresa', $perfil[0]['Empresa']['nomefantasia']);
            CakeSession::write('empresa_id', $perfil[0]['Empresa']['id']);
            CakeSession::write('empresa_logo', $perfil[0]['Empresa']['img_foto']);
            CakeSession::write('empresa_tipologo', $perfil[0]['Empresa']['tipoimagem']);
            $this->redirect(array('controller' => 'homes', 'action' => 'index'));
        }
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {

        $dadosUser = $this->Session->read();
//        if (!$this->validaPlano($dadosUser['Auth']['User']['holding_id'], $dadosUser['Auth']['User']['Holding']['plano_id'])) {
//            $this->redirect(array('action' => 'index'));
//        }

        $this->set('adminmaster', $this->Auth->user('adminmaster'));
        $this->set('holding_id', $this->Auth->user('holding_id'));

        $holdings = $this->User->Holding->find('list', array(
            'fields' => array('id', 'nome'),
            'order' => 'nome ASC'
        ));
        $this->set(compact('holdings'));

        $opcoes = array(1 => 'SIM', 2 => 'NÃO');
        $this->set('opcoes', $opcoes);

        if ($this->request->is('post')) {
            $this->User->create();
            $this->request->data['User']['username'] = $this->request->data['User']['email'];
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Usuário salvo com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Registro inválido.'));
        }

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Registro inválido.'));
        }

        $opcoes = array(1 => 'SIM', 2 => 'NÃO');
        $this->set('opcoes', $opcoes);

        $this->set('adminmaster', $this->Auth->user('adminmaster'));
        $this->set('holding_id', $this->Auth->user('holding_id'));

        $holdings = $this->User->Holding->find('list', array(
            'fields' => array('id', 'nome'),
            'order' => 'nome ASC'
        ));
        $this->set(compact('holdings'));

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['username'] = $this->request->data['User']['email'];
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('Usuário alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash('Usuário deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

    public function alteraSenha() {

        $this->set('title_for_layout', 'Altera senha');

        $dadosUser = $this->Session->read();
        $id = $dadosUser['Auth']['User']['id'];

        $user = $this->User->findById($id);
        if (!$this->request->data) {
            $this->request->data = $user;
            unset($this->request->data['User']['password']);
        }


        if ($this->request->is('post') || $this->request->is('put')) {

            $this->User->validator()->remove('nome');
            $this->User->validator()->remove('sobrenome');
            $this->User->validator()->remove('holding_id');
            $this->User->validator()->remove('adminmaster');
            $this->User->validator()->remove('adminholding');
            $this->User->validator()->remove('email');
            $this->User->validator()->remove('username');
            $this->User->validator()->remove('password');

            if ($this->User->saveAll($this->request->data, array('validate' => 'only'))) {
                $this->User->id = $id;
                if ($this->User->saveField('password', $this->request->data['User']['new_password'])) {
                    $this->Session->setFlash('Senha alterada com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                    $this->redirect(array('action' => 'alteraSenha'));
                } else {
                    $this->Session->setFlash('Não foi possível editar o registro. Por favor, tente novamente..', 'default', array('class' => 'mensagem_erro'));
                }
            }
        }
    }

    public function validaPlano($holding_id, $plano_id) {

//        $totalUsuarios = $this->User->find('count', array(
//            'conditions' => array('User.holding_id' => $holding_id)
//        ));
//
//        $this->User->Holding->Plano->recursive = 0;
//        $plano = $this->User->Holding->Plano->find('first', array('conditions' => array('id' => $plano_id)));
//
//        if (empty($plano['Plano']['usuario'])) {
//            return true;
//        } else {
//            if ($totalUsuarios >= $plano['Plano']['usuario']) {
//                return false;
//            } else {
//                return true;
//            }
//        }
    }

}
