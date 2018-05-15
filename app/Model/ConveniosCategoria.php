<?php

App::uses('AppModel', 'Model');

/**
 * ConveniosCategoria Model
 *
 */
class ConveniosCategoria extends AppModel {

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
        'convenio_id' => array(
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
        'Convenio' => array(
            'className' => 'Convenio',
            'foreignKey' => 'convenio_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
    );

}

