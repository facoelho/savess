<?php

App::uses('AppModel', 'Model');

/**
 * Evolucao Model
 *
 */
class Evolucao extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'empresa_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'paciente_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'event_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'obs' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                'allowEmpty' => false,
                'last' => false
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
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
    );

}

