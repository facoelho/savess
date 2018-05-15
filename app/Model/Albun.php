<?php

App::uses('AppModel', 'Model');

/**
 * Albun Model
 *
 */
class Albun extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'titulo' => array(
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
        ),
        'Paciente' => array(
            'className' => 'Paciente',
            'foreignKey' => 'paciente_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Imagen' => array(
            'className' => 'Imagen',
            'foreignKey' => 'albun_id',
            'dependent' => false,
        ),
    );

}

?>
