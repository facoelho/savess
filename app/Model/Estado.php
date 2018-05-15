<?php

App::uses('AppModel', 'Model');

/**
 * Estado Model
 *
 */
class Estado extends AppModel {

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
                'rule' => array('maxLength', '100'),
                'message' => 'Máximo 200 caracteres',
            )
        ),
        'pais_id' => array(
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
        'Paise' => array(
            'className' => 'Paise',
            'foreignKey' => 'pais_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Cidade' => array(
            'className' => 'Cidade',
            'foreignKey' => 'estado_id',
            'dependent' => false,
        ),
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
