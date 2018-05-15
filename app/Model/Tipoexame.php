<?php

App::uses('AppModel', 'Model');

/**
 * Tipoexame Model
 *
 */
class Tipoexame extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'descricao' => array(
            'rule' => array('maxLength', '100'),
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'empresa_id' => array(
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
        'Categoriatipoexame' => array(
            'className' => 'Categoriatipoexame',
            'foreignKey' => 'tipoexame_id',
            'dependent' => false,
        ),
    );

}

?>
