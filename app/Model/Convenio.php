<?php

App::uses('AppModel', 'Model');

/**
 * Convenio Model
 *
 */
class Convenio extends AppModel {

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
        ),
        'valor' => array(
            'numeric' => array(
                'rule' => array('numeric'),
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
        'ConveniosCategoria' => array(
            'className' => 'ConveniosCategoria',
            'foreignKey' => 'convenio_id',
            'dependent' => false,
        ),);

}

