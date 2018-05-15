<?php

App::uses('AppController', 'Controller');

App::import('Controller', 'Users');

/**
 * Especialistas Controller
 */
class EventsController extends AppController {

    function beforeFilter() {
        $this->set('title_for_layout', 'Agenda por especialista');
    }

    public function isAuthorized($user) {
        $Users = new UsersController;
        return $Users->validaAcesso($this->Session->read(), $this->request->controller);
        return parent::isAuthorized($user);
    }

    /**
     * index method
     */
    public function index($param_data = null) {

        $dadosUser = $this->Session->read();

        $this->loadModel('Especialista');

        $this->Especialista->recursive = 0;
        $especialistas_aux = $this->Especialista->find('all', array('order' => 'Especialista.nome ASC', 'fields' => array('Especialista.id', 'Especialista.nome', 'Especialista.sobrenome'), 'conditions' => array('empresa_id' => $dadosUser['empresa_id'], 'ativo' => 'S')));

        foreach ($especialistas_aux as $key => $item):
            $especialistas[$item['Especialista']['id']] = $item['Especialista']['nome'] . ' ' . $item['Especialista']['sobrenome'];
        endforeach;

        $this->set('especialistas', $especialistas);

        $this->Filter->addFilters(
                array(
                    'filter1' => array(
                        'Event.start' => array(
                            'operator' => '=',
                            '=' => array(
                                'text' => __(' e ', true),
                                'date' => true
                            )
                        )
                    ),
                )
        );

        if (!empty($param_data)) {
            $data = $param_data;
        } elseif (empty($data)) {
            $data = date('Y-m-d');
        }

        foreach ($this->Filter->getConditions() as $key => $item) :
            if ($key == 'Event.start =') {
                $data = substr($item, 6, 4) . '-' . substr($item, 3, 2) . '-' . substr($item, 0, 2);
                $param_data = substr($item, 6, 4) . '-' . substr($item, 3, 2) . '-' . substr($item, 0, 2);
            }
        endforeach;

        $this->Event->recursive = 0;
        $this->Paginator->settings = array(
            'conditions' => array('Event.empresa_id' => $dadosUser['empresa_id'], 'Event.start BETWEEN' . "'" . $data . ' ' . "00:00:00" . "'" . ' and ' . "'" . $data . ' ' . "23:59:59" . "'"),
            'order' => 'Event.start',
            'limit' => 99999999,
        );
        $this->set('events', $this->Paginator->paginate('Event'));

        $cont = 0;
        $fim = 0;

        $hora = '7';
        $min = '00';

        while ($cont == $fim) :
            $horarios[] = $hora . ':' . $min;
            if ($min >= 45) {
                $hora = $hora + 1;
                $min = '00';
            } else {
                $min = $min + 15;
            }
            if ($hora > 22) {
                $fim = 1;
            }
        endwhile;

        $this->set('horarios', $horarios);
        if (!empty($param_data)) {
            $this->set('data', $param_data);
        } else {
            $this->set('data', $data);
        }
    }

    /**
     * calcula_diferenca method
     */
    public function calcula_diferenca($hora_inicio = null, $hora_fim = null) {

        $result = $this->Event->query('select TIMEDIFF(' . "'" . $hora_fim . "'" . ', ' . "'" . $hora_inicio . "'" . ') diferenca');

        $hora = (substr($result[0][0]['diferenca'], 0, 2) * 60);
        $minutos = substr($result[0][0]['diferenca'], 3, 2);

        return ($hora + $minutos) / 15;
    }

    /**
     * agenda method
     */
    public function agendamento($hora = null, $especialista_id = null) {

        $dadosUser = $this->Session->read();

        $horario = base64_decode($hora);

        $result = $this->Event->query(' select id,  count(*) as cont
                from events
                where time(start) = time(' . "'" . $horario . "')
                                          and empresa_id      = " . $dadosUser['empresa_id'] . '
                and especialista_id = ' . $especialista_id);

        debug($result);

        if ($result[0][0]['cont'] > 0) {
            $this->redirect(array('action' => '../../calendario/full_calendar/events/edit/' . $result[0]['events']['id']));
        } else {
            $this->redirect(array('action' => '../../calendario/full_calendar/events/add/' . $hora));
        }
    }

}
