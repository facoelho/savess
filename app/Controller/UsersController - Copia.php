<?php

App::uses('AppController', 'Controller');

App::uses('CakeEmail', 'Network/Email');

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
        $this->set('title_for_layout', 'Usuários');
    }

    public function index() {
        $dadosUser = $this->Session->read();
        $this->User->recursive = 0;
        if ($dadosUser['Auth']['User']['adminmaster'] == 1) {
            $this->Paginator->settings = array(
                'order' => array('ultimoacesso' => 'desc',
                    'ultimoacesso' => 'desc',)
            );
        } else {
            $this->Paginator->settings = array(
                'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
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

        $novo_cadastro = $this->Session->read('novo_cadastro');

        if (!empty($novo_cadastro)) {
            $this->set('novo', 1);
        } else {
            CakeSession::write('novo_cadastro', '');
            $this->set('novo', 0);
        }

        if ($this->request->is('post')) {

            if (!empty($novo_cadastro)) {

                try {

                    $this->User->begin();

                    $validar = $this->User->query('select count(*) as cont from users where username = ' . "'" . $novo_cadastro['Signup']['email'] . "'");

                    if ($validar[0][0]['cont'] > 0) {
                        $this->Session->setFlash('Este E-mail já esta em uso, por favor selecione outro.', 'default', array('class' => 'mensagem_erro'));
                        $this->redirect(array('action' => '../../site/Signups/signup'));
                    }

                    $data = date('Y-m-d');
                    $data = date('Y-m-d', strtotime($data . ' + 30 days'));

                    $this->User->query('insert into holdings(nome, validade)
                                   values(' . "'" . $novo_cadastro['Signup']['nome'] . ' ' . $novo_cadastro['Signup']['sobrenome'] . "'" . ',' . "'" . $data . "'" . ')');

                    $holding_id = $this->User->query('select max(id) as id from holdings');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 2 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 3 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 4 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 5 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 8 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 9 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 10 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 12 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 13 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 14 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 17 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 18 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 19 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 20 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 21 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 22 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 24 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 25 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 26 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 27 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 28 . ')');

                    $this->User->query('insert into holdingmenus (holding_id, menu_id)
                    values(' . $holding_id[0][0]['id'] . "," . 29 . ')');

                    $this->User->query('insert into empresas(razaosocial, nomefantasia, cnpj, inscestadual, inscmunicipal, email, holding_id, created, modified)
                    values(' . "'" . $novo_cadastro['Signup']['nome'] . ' ' . $novo_cadastro['Signup']['sobrenome'] . "'" . ',' .
                            "'" . $novo_cadastro['Signup']['nome'] . ' ' . $novo_cadastro['Signup']['sobrenome'] . "'" . ',' .
                            "'000000000000000'" . "," . "'000000000000000'" . "," . "'000000000000000'" . "," . "'" . $novo_cadastro['Signup']['email'] . "'" . "," .
                            $holding_id[0][0]['id'] . "," . "'" . date('Y-m-d') . "'" . "," . "'" . date('Y-m-d') . "'" . ')');

                    $empresa_id = $this->User->query('select max(id) as id from empresas');

                    $this->User->query('insert into users(email, password, nome, sobrenome, holding_id, adminmaster,
                                                      adminholding, username, created, modified, ultimoacesso)
                                    values(' . "'" . $novo_cadastro['Signup']['email'] . "'" . "," .
                            "'a0220456a07fb9bccc6591539ad988ad2b581251'" . "," .
                            "'" . $novo_cadastro['Signup']['nome'] . "'" . "," . "'" . $novo_cadastro['Signup']['sobrenome'] . "'" . ',' .
                            $holding_id[0][0]['id'] . "," .
                            '2' . "," . '1' . "," . "'" . $novo_cadastro['Signup']['email'] . "'" . "," .
                            "'" . date('Y-m-d') . "'" . "," .
                            "'" . date('Y-m-d') . "'" . "," .
                            "'" . date('Y-m-d') . "'" . ')');

                    $user_id = $this->User->query('select max(id) as id from users');

                    $this->User->query('insert into groups(name, holding_id, created, modified)
                    values(' . "'Administrador'" . "," . $holding_id[0][0]['id'] . "," .
                            "'" . date('Y-m-d') . "'" . "," .
                            "'" . date('Y-m-d') . "'" . ')');

                    $group_id = $this->User->query('select id from groups where holding_id =' . $holding_id[0][0]['id']);

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 12 . ')');

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 2 . ')');

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 3 . ')');

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 4 . ')');

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 5 . ')');

                    $this->User->query('insert into groupmenus (group_id, menu_id)
                    values(' . $group_id[0]['groups']['id'] . "," . 20 . ')');

                    $this->User->query('insert into usergroupempresas (empresa_id, user_id, group_id, empresaboot)
                    values(' . $empresa_id[0][0]['id'] . "," . $user_id[0][0]['id'] . "," . $group_id[0]['groups']['id'] . "," . 1 . ')');

                    $this->User->commit();

                    //envio de email

                    $Email = new CakeEmail('gmail');
                    $Email->to($novo_cadastro['Signup']['email']);
                    $Email->subject('Savess | Save Business');
                    $Email->replyTo('the_mail_you_want_to_receive_replies@yourdomain.com');
                    $Email->from('contato@savess.com.br');

                    $mensagem = 'Obrigado ' . $novo_cadastro['Signup']['nome'] . ' ' . $novo_cadastro['Signup']['sobrenome'] . ' por efetuar seu cadastro no sistema Save Business. Sua senha é savess';

                    $Email->send($mensagem);

                    //Fim do email

                    CakeSession::write('novo_cadastro', '');

                    $this->Session->setFlash('Cadastro concluido com sucesso, verifique seu e-mail.', 'default', array('class' => 'mensagem_sucesso'));
                    $this->redirect($this->Auth->logout());
                } catch (Exception $id) {
                    $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
                    $this->User->rollback();
                    $this->redirect(array('action' => 'index'));
                }
            }

            if ($this->Auth->login()) {

                $usuario = $this->User->read(null, $this->Auth->user('id'));
                if (strtotime($usuario['Holding']['validade']) < strtotime(date('Y-m-d H:i:s'))) {
                    $this->Auth->logout();
                    $this->redirect("http://www.savess.com.br/?erro=validade");
                }

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

}
