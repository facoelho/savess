<?php

App::uses('AppModel', 'Model');

/**
 * ContasReceber Model
 *
 */
class ContasReceber extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'paciente_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'empresa_id' => array(
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
        'valordesconto' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Este campo é obrigatório.',
            ),
        ),
        'event_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'convenio_id' => array(
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
     */
    public $hasMany = array(
        'ContasRecebersMov' => array(
            'className' => 'ContasRecebersMov',
            'foreignKey' => 'contas_receber_id',
            'dependent' => true,
        ),
    );

}

?>
