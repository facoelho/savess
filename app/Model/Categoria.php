<?php

App::uses('AppModel', 'Model');

/**
 * Categoria Model
 *
 */
class Categoria extends AppModel {

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
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'categoria_pai_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true,
            ),
        ),
        'tipo' => array(
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
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'empresa_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasAndBelongsToMany associations
     */
    public $hasAndBelongsToMany = array(
//        'Tipoexame' =>
//        array(
//            'className' => 'Tipoexame',
//            'joinTable' => 'categoriatipoexames',
//            'foreignKey' => 'categoria_id',
//            'associationForeignKey' => 'tipoexame_id',
//            'order' => 'Tipoexame.descricao',
//        )
    );

}

