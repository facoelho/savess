<?php

App::uses('AppModel', 'Model');

/**
 * Event Model
 *
 */
class Event extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'event_type_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'paciente_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'especialista_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'start' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'convenio_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Paciente' => array(
            'className' => 'Paciente',
            'foreignKey' => 'paciente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Especialista' => array(
            'className' => 'Especialista',
            'foreignKey' => 'especialista_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'EventType' => array(
            'className' => 'EventType',
            'foreignKey' => 'event_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Convenio' => array(
            'className' => 'Convenio',
            'foreignKey' => 'convenio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Evolucao' => array(
            'className' => 'Evolucao',
            'foreignKey' => 'event_id',
            'dependent' => false,
        ),
    );

}

