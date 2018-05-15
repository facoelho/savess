<?php

App::uses('AppModel', 'Model');

/**
 * Caixa Model
 *
 */
class Caixa extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'dtcaixa' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'valor' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'user_alt_id' => array(
            'notempty' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'created' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'dtalteracao' => array(
            'notempty' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'status' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        )
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
        )
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
//        'ContasRecebersMov' => array(
//            'className' => 'ContasRecebersMov',
//            'foreignKey' => 'forma_pagamento_id',
//            'dependent' => true,
//        ),
    );

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Caixa"]["dtcaixa"])) {
                $dados[$key]["Caixa"]["dtcaixa"] = $this->formataData($value["Caixa"]["dtcaixa"], 'PT', 'N');
            }
        }
        return $dados;
    }

}

?>
