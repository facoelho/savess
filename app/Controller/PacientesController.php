<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Pacientes Controller
 */
class PacientesController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Clientes');
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

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Paciente.nome' => array(
                            'operator' => 'LIKE',
                            'value' => array(
                                'before' => '%',
                                'after' => '%'
                            )
                        )
                    ),
                    'filter2' => array(
                        'Paciente.sobrenome' => array(
                            'operator' => 'LIKE',
                            'value' => array(
                                'before' => '%',
                                'after' => '%'
                            )
                        )
                    ),
                    'filter3' => array(
                        'Paciente.celular' => array(
                            'operator' => 'LIKE',
                            'value' => array(
                                'before' => '%',
                                'after' => '%'
                            )
                        )
                    ),
                )
        );

        $this->Paciente->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], $this->Filter->getConditions()),
            'order' => array('nome' => 'asc')
        );
        $this->set('pacientes', $this->Paginator->paginate('Paciente'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Paciente->id = $id;
        if (!$this->Paciente->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $this->Paciente->recursive = 2;
        $paciente = $this->Paciente->read(null, $id);

        $evolucaos = $this->Paciente->Evolucao->find('all', array(
            'conditions' => array('Paciente.id' => $id),
            'order' => array('Event.start' => 'desc')
        ));
        $this->set('evolucaos', $evolucaos);

        if ($paciente ['Paciente']['holding_id'] != $dadosUser['Auth']['User']['holding_id']) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set('paciente', $paciente);
    }

    /**
     * add method
     */
    public function add() {

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));

        $opcoes = array(1 => 'Masculino', 2 => 'Feminino');
        $this->set('opcoes', $opcoes);

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        $this->Paciente->Endereco->Cidade->Estado->recursive = 1;
        $estados = $this->Paciente->Endereco->Cidade->Estado->find('list', array(
            'fields' => array('id', 'nome'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set('estados', $estados);

        if ($this->request->is('post')) {
            $this->Paciente->create();

            $separadores = array(".", "-", "/");
            $this->request->data['Paciente']['cpf'] = str_replace($separadores, '', $this->request->data['Paciente']['cpf']);
            $this->request->data['Paciente']['rg'] = str_replace($separadores, '', $this->request->data['Paciente']['rg']);

            if (!empty($this->request->data['Endereco'])) {
                $this->request->data['Endereco'][0]['cep'] = str_replace($separadores, '', $this->request->data['Endereco'][0]['cep']);
            }
            if ($this->Paciente->saveAll($this->request->data)) {
                $this->Session->setFlash('Paciente adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
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

        $this->Paciente->id = $id;
        if (!$this->Paciente->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->set(compact('id'));

        $dadosUser = $this->Session->read();
        $holding_id = $dadosUser['Auth']['User']['holding_id'];
        $this->set(compact('holding_id'));

        $opcoes = array(1 => 'Masculino', 2 => 'Feminino');
        $this->set('opcoes', $opcoes);

        $status = array('S' => 'ATIVO', 'N' => 'INATIVO');
        $this->set('status', $status);

        $paciente = $this->Paciente->read(null, $id);

        $this->Paciente->Endereco->Cidade->Estado->recursive = 1;
        $estados = $this->Paciente->Endereco->Cidade->Estado->find('list', array(
            'fields' => array('id', 'nome'),
            'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id']),
            'order' => array('nome' => 'asc')
        ));
        $this->set('estados', $estados);

        if (!empty($paciente['Endereco'][0])) {
            $this->Paciente->Endereco->Cidade->recursive = 1;
            $cidades = $this->Paciente->Endereco->Cidade->find('list', array(
                'fields' => array('id', 'nome'),
                'conditions' => array('holding_id' => $dadosUser['Auth']['User']['holding_id'], 'estado_id' => $paciente['Endereco'][0]['estado_id']),
                'order' => array('nome' => 'asc')
            ));
            $this->set('cidades', $cidades);
        }

        if ($paciente['Paciente']['holding_id'] != $holding_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $separadores = array(".", "-", "/");
            $this->request->data['Paciente']['cpf'] = str_replace($separadores, '', $this->request->data['Paciente']['cpf']);
            $this->request->data['Paciente']['rg'] = str_replace($separadores, '', $this->request->data['Paciente']['rg']);

            if (!empty($this->request->data['Endereco'])) {
                $this->request->data['Endereco'][0]['cep'] = str_replace($separadores, '', $this->request->data['Endereco'][0]['cep']);
            }

            if ($this->request->data['Paciente']['valordesconto'] > 0) {
                $this->request->data['Paciente']['valordesconto'] = str_replace(',', '.', $this->request->data['Paciente']['valordesconto']);
            }

            if ($this->Paciente->save($this->request->data)) {

                if (!empty($this->request->data['Endereco'][0])) {

                    $result = $this->Paciente->query('select count(*) as cont from enderecos where paciente_id = ' . $id);

                    if ($result[0][0]['cont'] > 0) {

                        debug($this->request->data['Endereco'][0]);
                        if ($this->request->data['Endereco'][0]['cidade_id'] == '') {
                            $this->Session->setFlash('A cidade não foi informada.', 'default', array('class' => 'mensagem_erro'));
                            return;
                        }
                        $this->Paciente->query('update enderecos
                                           set estado_id   = ' . $this->request->data['Endereco'][0]['estado_id'] . ',' . '
                                               cidade_id   = ' . $this->request->data['Endereco'][0]['cidade_id'] . ',' . '
                                               rua         = ' . "'" . $this->request->data['Endereco'][0]['rua'] . "'" . ',' . '
                                               bairro      =' . "'" . $this->request->data['Endereco'][0]['bairro'] . "'" . ',' . '
                                               numero      = ' . "'" . $this->request->data['Endereco'][0]['numero'] . "'" . ',' . '
                                               complemento = ' . "'" . $this->request->data['Endereco'][0]['complemento'] . "'" . ',' . '
                                               cep         = ' . "'" . $this->request->data['Endereco'][0]['cep'] . "'" . ',' . '
                                               observacao  = ' . "'" . $this->request->data['Endereco'][0]['observacao'] . "'" . '
                                         where paciente_id = ' . $id);
                    } else {
                        $this->Paciente->query('insert into enderecos(paciente_id, estado_id, cidade_id, rua, bairro, numero, complemento, cep, observacao)
                                                values (' . $id . ',' . $this->request->data['Endereco'][0]['estado_id'] . ',' . $this->request->data['Endereco'][0]['cidade_id'] . ',' . "'" . $this->request->data['Endereco'][0]['rua'] . "'" . ',' . "'" . $this->request->data['Endereco'][0]['bairro'] . "'" . ',' . "'" . $this->request->data['Endereco'][0]['numero'] . "'" . ',' . "'" . $this->request->data['Endereco'][0]['complemento'] . "'" . ',' . "'" . $this->request->data['Endereco'][0]['cep'] . "'" . ',' . "'" . $this->request->data['Endereco'][0]['observacao'] . "'" . ')');
                    }
                }
                $this->Session->setFlash('Paciente alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('action' => 'view/' . $paciente['Paciente']['id']));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $this->Paciente->read(null, $id);
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $this->Paciente->id = $id;
        if (!$this->Paciente->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('action' => 'index'));
        }

        $this->Paciente->query('delete from enderecos where paciente_id = ' . $id);

        if ($this->Paciente->delete()) {
            $this->Session->setFlash('Paciente deletado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('action' => 'index'));
    }

}
