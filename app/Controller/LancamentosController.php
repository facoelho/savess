<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Lancamentos Controller
 */
class LancamentosController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Lancamentos de caixa');
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
        $this->Categoria->recursive = 0;
        $this->Paginator->settings = array(
            'fields' => array('Categoria.id', 'Categoria.descricao', 'Categoria.ativo', 'Categoriapai.descricao'),
            'joins' => array(
                array(
                    'table' => 'categorias',
                    'alias' => 'Categoriapai',
                    'type' => 'LEFT',
                    'conditions' => array('Categoria.categoria_pai_id = Categoriapai.id')
                )
            ),
            'conditions' => array('Categoria.empresa_id' => $dadosUser['empresa_id']),
            'order' => array('Categoria.descricao' => 'asc', 'Categoriapai.descricao', 'asc')
        );
        $this->set('categorias', $this->Paginator->paginate('Categoria'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Categoria->id = $id;
        if (!$this->Categoria->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $categoria = $this->Categoria->read(null, $id);

        if ($categoria['Categoria']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('categoria', $categoria);
    }

    /**
     * add method
     */
    public function add($caixa_id = null) {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $caixa = $this->Lancamento->Caixa->find('all', array('fields' => array('id', 'dtcaixa'),
            'conditions' => array('Caixa.id' => $caixa_id)));
        $this->set('caixa', $caixa);

        $tipo = array('S' => 'SAÍDA', 'E' => 'ENTRADA', 'RETIRADA');
        $this->set(compact('tipo'));

        $categorias_pai = $this->Lancamento->Categoria->find('list', array('fields' => array('id', 'descricao'),
            'conditions' => array('empresa_id' => $empresa_id, 'categoria_pai_id IS NULL', 'ativo' => 'S'),
            'order' => array('descricao')));
        $this->set('categorias_pai', $categorias_pai);

        if ($this->request->is('post')) {

            try {
                $this->Lancamento->begin();

                $this->request->data['Lancamento']['caixa_id'] = $caixa_id;
                $this->request->data['Lancamento']['user_id'] = $dadosUser['Auth']['User']['id'];
                $this->request->data['Lancamento']['created'] = date('Y-m-d h:i:s');
                $this->request->data['Lancamento']['valor'] = str_replace(',', '.', $this->request->data['Lancamento']['valor']);

//                $id = $this->Lancamento->query('select max(id) as id from public.lancamentos where empresa_id = ' . $empresa_id);
//                $saldo = $this->Lancamento->query('select saldo from public.caixa where id = ' . $caixa_id);

                $this->Lancamento->create();

                if ($this->Lancamento->save($this->request->data)) {

//                    $ultimo_id = $this->Lancamento->getLastInsertId();
//
//                    if ($this->request->data['Lancamento']['tipo'] == 'E') {
//                        $this->Lancamento->query('update public.caixas
//                                                     set saldo = saldo + ' . $this->request->data['Lancamento']['valor'] . '
//                                                   where id = ' . $caixa_id);
//                    } else {
//                        $this->Lancamento->query('update public.caixas
//                                                     set saldo = saldo - ' . $this->request->data['Lancamento']['valor'] . '
//                                                   where id = ' . $caixa_id);
//                    }
//
//                    $saldo_caixa = $this->Lancamento->query('select saldo from public.caixas where id = ' . $caixa_id);
//
//                    $this->Lancamento->query('update public.lancamentos
//                                                 set saldo = ' . $saldo_caixa[0][0]['saldo'] . '
//                                               where caixa_id = ' . $caixa_id . '
//                                                and id = ' . $ultimo_id);

                    $this->Lancamento->commit();
                    $this->Session->setFlash('Lançamento adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                    $this->redirect(array('controller' => 'Lancamentos', 'action' => 'add/' . $caixa_id));
                } else {
                    $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
                }
            } catch (Exception $caixa_id) {
                $this->Lancamento->rollback();
                $this->Session->setFlash('Erro ao tentar salvar o lançamento!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Lancamentos', 'action' => 'add/' . $caixa_id));
            }
        }
    }

    /**
     * edit method
     */
    public function edit($id = null) {

        $this->Categoria->id = $id;
        if (!$this->Categoria->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $categorias_pai = $this->Categoria->find('list', array('fields' => array('id', 'descricao'),
            'conditions' => array('empresa_id' => $empresa_id, 'categoria_pai_id IS NULL')));
        $this->set(compact('categorias_pai'));

        $ativo = array('S' => 'Ativo', 'N' => 'Inativo');
        $this->set(compact('ativo'));

        $categorias = $this->Categoria->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Categoria->save($this->request->data)) {
                $this->Session->setFlash('Categoria alterada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Categorias', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $categorias;
        }
    }

    /**
     * sangria method
     */
    public function sangria() {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->Lancamento->Caixa->recursive = 0;

        $caixa = $this->Lancamento->Caixa->find('all', array(
            'conditions' => array('empresa_id' => $empresa_id,
                'status' => 'A',
        )));

        $this->set('caixa', $caixa);

        if ($this->request->is('post') || $this->request->is('put')) {

            if ($this->request->data['Lancamento']['valor'] <= 0) {
                $this->Session->setFlash('Somente é permitido lançar valores maiores que zero.', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            $this->request->data['Lancamento']['valor'] = str_replace(',', '.', $this->request->data['Lancamento']['valor']);
            $this->request->data['Lancamento']['saldo'] = str_replace(',', '.', $this->request->data['Lancamento']['saldo']);

            if ($this->request->data['Lancamento']['saldo'] < $this->request->data['Lancamento']['valor']) {
                $this->Session->setFlash('Saldo de caixa insuficiente.', 'default', array('class' => 'mensagem_erro'));
                return;
            }

            $this->request->data['Lancamento']['caixa_id'] = $this->request->data['Lancamento']['caixa_id'];
            $this->request->data['Lancamento']['user_id'] = $dadosUser['Auth']['User']['id'];
            $this->request->data['Lancamento']['created'] = date('Y-m-d h:i:s');
            $this->request->data['Lancamento']['valor'] = str_replace(',', '.', $this->request->data['Lancamento']['valor']);
            $this->request->data['Lancamento']['descricao'] = 'SANGRIA DE CAIXA';
            $this->request->data['Lancamento']['tipo'] = 'G';
            $this->request->data['Lancamento']['categoria_id'] = -1;

            if ($this->Lancamento->save($this->request->data)) {

                $ultimo_id = $this->Lancamento->query('select max(id) as id
                                                         from public.lancamentos
                                                        where caixa_id = ' . $this->request->data['Lancamento']['caixa_id']);

                $this->Lancamento->query('update public.caixas
                                             set saldo = saldo - ' . $this->request->data['Lancamento']['valor'] . '
                                           where id = ' . $this->request->data['Lancamento']['caixa_id']);

                $saldo_caixa = $this->Lancamento->query('select saldo from public.caixas where id = ' . $this->request->data['Lancamento']['caixa_id']);

                $this->Lancamento->query('update public.lancamentos
                                                 set saldo = ' . $saldo_caixa[0][0]['saldo'] . '
                                               where caixa_id = ' . $this->request->data['Lancamento']['caixa_id'] . '
                                                and id = ' . $ultimo_id[0][0]['id']);

                $this->Session->setFlash('Sangria de caixa realizada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Caixas', 'action' => 'index'));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $caixa;
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Convenio->id = $id;
        if (!$this->Convenio->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Convenio->delete()) {
            $this->Session->setFlash('Convênio deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}

