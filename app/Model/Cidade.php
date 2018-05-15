<?php

App::uses('AppModel', 'Model');

/**
 * Estado Model
 * 
 */
class Cidade extends AppModel {

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
            'maximo' => array(
                'rule'    => array('maxLength', '200'),
                'message' => 'Máximo 200 caracteres',
            )
        ),      
        'estado_id' => array(
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
        'Estado' => array(
            'className' => 'Estado',
            'foreignKey' => 'estado_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Endereco' => array(
            'className' => 'Endereco',
            'foreignKey' => 'cidade_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );
       
}

?>
