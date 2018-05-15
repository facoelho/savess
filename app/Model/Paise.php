<?php

App::uses('AppModel', 'Model');

/**
 * Pais Model
 * 
 */
class Paise extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'nome' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'holding_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Holding' => array(
            'className' => 'Holding',
            'foreignKey' => 'holding_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    /**
     * hasMany associations
     */
    
    public $hasMany = array(
        'Estado' => array(
            'className' => 'Estado',
            'foreignKey' => 'pais_id',
            'dependent' => false,
        ),
    );    
    
}