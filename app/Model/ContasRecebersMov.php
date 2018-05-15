<?php

App::uses('AppModel', 'Model');

/**
 * ContasRecebersMov Model
 *
 */
class ContasRecebersMov extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'contas_receber_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'valorlancto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo é obrigatório.',
            ),
        ),
        'forma_pagamento_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'obs' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo é obrigatório.',
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'ContasReceber' => array(
            'className' => 'ContasReceber',
            'foreignKey' => 'contas_receber_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'FormasPagamento' => array(
            'className' => 'FormasPagamento',
            'foreignKey' => 'forma_pagamento_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
    );

}

?>
