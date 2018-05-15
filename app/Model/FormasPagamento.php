<?php

App::uses('AppModel', 'Model');

/**
 * FormasPagamento Model
 *
 */
class FormasPagamento extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'descricao' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maximo' => array(
                'rule' => array('maxLength', '200'),
                'message' => 'Máximo 200 caracteres',
            )
        ),
        'ativo' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maximo' => array(
                'rule' => array('maxLength', '1'),
                'message' => 'Máximo 1 caracteres',
            )
        ),
        'holding_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        )
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
        'ContasRecebersMov' => array(
            'className' => 'ContasRecebersMov',
            'foreignKey' => 'forma_pagamento_id',
            'dependent' => true,
        ),
    );

}

?>
