<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

App::uses('Folder', 'Utility');

App::uses('File', 'Utility');

/**
 * Imagens Controller
 */
class ImagensController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Diretórios');
    }

    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }

    /**
     * index method
     */
    public function index($albun_id) {

        $this->set('albun_id', $albun_id);

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->Imagen->Albun->recursive = 0;
        $diretorios = $this->Imagen->Albun->find('list', array('order' => 'titulo ASC', 'fields' => array('id', 'titulo'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'])));

        $this->Imagen->recursive = 0;
        $this->Paginator->settings = array(
            'order' => array('titulo' => 'asc'),
            'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'albun_id' => $albun_id),
        );
        $this->set('imagens', $this->Paginator->paginate('Imagen'));
    }

    /**
     * view method
     */
    public function view($id = null) {

        $this->Imagen->id = $id;
        if (!$this->Imagen->exists($id)) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $imagen = $this->Imagen->read(null, $id);
        if ($imagen['Albun']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }

        $this->set('imagen', $imagen);
    }

    /**
     * add method
     */
    public function add($albun_id) {

        $this->set('albun_id', $albun_id);

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];
        $this->set(compact('empresa_id'));

        $this->Imagen->Albun->recursive = -1;
        $albuns = $this->Imagen->Albun->find('list', array(
            'fields' => array('id', 'titulo'),
            'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'Albun.id = ' . $albun_id),
            'order' => array('titulo' => 'asc')
        ));
        $this->set('albuns', $albuns);

        if ($this->request->is('post')) {
            $this->Imagen->create();

            if ($this->Imagen->save($this->request->data)) {

                $id = $this->Imagen->getLastInsertID();
                $this->Imagen->id = $id;

                if ($this->request->data['Imagen']['imagemfoto']['error'] == 0) {

                    $extensao = substr($this->request->data['Imagen']['imagemfoto']['name'], (strlen($this->request->data['Imagen']['imagemfoto']['name']) - 4), strlen($this->request->data['Imagen']['imagemfoto']['name']));

                    if ((strtoupper($extensao) == strtoupper('.exe')) or (strtoupper($extensao) == strtoupper('.com')) or strtoupper($extensao) == strtoupper('.bat') or strtoupper($extensao) == strtoupper('.php')) {
                        $this->Session->setFlash('A extensão do arquivo é inválido.', 'default', array('class' => 'mensagem_erro'));
                        $this->redirect(array('action' => 'add/' . $id));
                    }

                    $diretorio = 'img/imagemfoto/' . $empresa_id;

                    if (is_dir($diretorio)) {
                        // não faz nada
                    } else {
                        mkdir('img/imagemfoto/' . $empresa_id . '/'); // Cria uma nova pasta dentro do diretório atual
                    }

                    $nome_arquivo = "imagem_" . $id . $extensao;
                    $tamanho = @getimagesize($this->request->data['Imagen']['imagemfoto']['tmp_name']);
                    $arquivo = new File($this->request->data['Imagen']['imagemfoto']['tmp_name'], false);

                    $imagem = $arquivo->read();
                    $arquivo->close();
                    $arquivo = new File(WWW_ROOT . 'img/imagemfoto/' . $empresa_id . "/" . $nome_arquivo, false, 0777);
                    if ($arquivo->create()) {
                        $arquivo->write($imagem);
                        $arquivo->close();
                    }
                    $this->request->data['Imagen']['img_foto'] = $nome_arquivo;

                    $this->Imagen->save($this->request->data);
                }
                $this->Session->setFlash('Arquivo adicionado com sucesso!', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Imagens', 'action' => 'index/' . $albun_id));
            } else {
                $this->Session->setFlash('Registro não foi salvo. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        }
    }

    /**
     * edit method
     */
    public function edit($convenio_id = null, $id = null) {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $this->Imagen->id = $id;
        if (!$this->Imagen->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }

        $imagen = $this->Imagen->read(null, $id);
        if ($imagen['Albun']['empresa_id'] != $empresa_id) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->Imagen->id = $id;
            $this->request->data['Imagen']['modified'] = date("Y-m-d H:i:s");

            if ($this->request->data['Imagen']['imagemfoto']['error'] == 0) {

                $extensao = substr($this->request->data['Imagen']['imagemfoto']['name'], (strlen($this->request->data['Imagen']['imagemfoto']['name']) - 4), strlen($this->request->data['Imagen']['imagemfoto']['name']));

                if ((strtoupper($extensao) == strtoupper('.exe')) or (strtoupper($extensao) == strtoupper('.com')) or strtoupper($extensao) == strtoupper('.bat') or strtoupper($extensao) == strtoupper('.php')) {
                    $this->Session->setFlash('A extensão do arquivo é inválido.', 'default', array('class' => 'mensagem_erro'));
                    $this->redirect(array('action' => 'add/' . $id));
                }

                $diretorio = 'img/imagemfoto/' . $empresa_id;

                if (is_dir($diretorio)) {
                    // não faz nada
                } else {
                    mkdir('img/imagemfoto/' . $empresa_id . '/'); // Cria uma nova pasta dentro do diretório atual
                }

                // Apaga a imagem antiga
                if (!empty($this->request->data['Imagen']['img_foto'])) {
                    $img_antiga = new File(WWW_ROOT . 'img/imagemfoto/' . $empresa_id . "/" . $this->request->data['Imagen']['img_foto'], true, 0755);
                    $img_antiga->delete();
                }
                // Insere a imagem nova
                $extensao = substr($this->request->data['Imagen']['imagemfoto']['name'], (strlen($this->request->data['Imagen']['imagemfoto']['name']) - 4), strlen($this->request->data['Imagen']['imagemfoto']['name']));
                $nome_arquivo = "empresa_" . $id . $extensao;
                $tamanho = @getimagesize($this->request->data['Imagen']['imagemfoto']['tmp_name']);
                $arquivo = new File($this->request->data['Imagen']['imagemfoto']['tmp_name'], false);
                $imagem = $arquivo->read();
                $arquivo->close();
                $arquivo = new File(WWW_ROOT . 'img/imagemfoto/' . $empresa_id . "/" . $nome_arquivo, false, 0777);
                if ($arquivo->create()) {
                    $arquivo->write($imagem);
                    $arquivo->close();
                }
                $this->request->data['Imagen']['img_foto'] = $nome_arquivo;
            }

            if ($this->Imagen->save($this->request->data)) {
                $this->Session->setFlash('Imagen alterado com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
                $this->redirect(array('controller' => 'Imagens', 'action' => 'index/' . $convenio_id));
            } else {
                $this->Session->setFlash('Registro não foi alterado. Por favor tente novamente.', 'default', array('class' => 'mensagem_erro'));
            }
        } else {
            $this->request->data = $imagen;
        }
    }

    /**
     * delete method
     */
    public function delete($id = null) {

        $dadosUser = $this->Session->read();
        $empresa_id = $dadosUser['empresa_id'];

        $this->Imagen->id = $id;
        if (!$this->Imagen->exists()) {
            $this->Session->setFlash('Registro não encontrado.', 'default', array('class' => 'mensagem_erro'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }

        $item = $this->Imagen->read(null, $id);

        $this->request->onlyAllow('post', 'delete');

        if ($this->Imagen->delete()) {

            $path = new Folder(WWW_ROOT . 'img/imagemfoto/' . $empresa_id . '/');
            $foto = $path->find();

            if (!empty($foto)) {
                $arquivo = new File(WWW_ROOT . 'img/imagemfoto/' . $empresa_id . "/" . $foto[0], true, 0755);

                $arquivo->delete();
            }

            $this->Session->setFlash('Imagem deletada com sucesso.', 'default', array('class' => 'mensagem_sucesso'));
            $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
        }
        $this->Session->setFlash('Registro não foi deletado.', 'default', array('class' => 'mensagem_erro'));
        $this->redirect(array('controller' => 'Albuns', 'action' => 'index'));
    }

}

?>
