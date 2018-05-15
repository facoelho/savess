<?php

App::uses('AppModel', 'Model');

/**
 * EventType Model
 *
 */
class EventType extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_type_id',
            'dependent' => false,
        ),
    );

}

