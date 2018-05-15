<?php

App::uses('AppModel', 'Model');

/**
 * Caixa Model
 *
 */
class Lancamento extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'caixa_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'descricao' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'tipo' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'valor' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'categoria_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'created' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'tipoexame_id' => array(
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Categoria' => array(
            'className' => 'Categoria',
            'foreignKey' => 'categoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Caixa' => array(
            'className' => 'Caixa',
            'foreignKey' => 'caixa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Tipoexame' => array(
            'className' => 'Tipoexame',
            'foreignKey' => 'tipoexame_id',
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
