<?php

App::uses('AppModel', 'Model');

/**
 * Potreiro Model
 *
 */
class Agenda extends AppModel {

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
        'paciente_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'especialista_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'tiposervico_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
//        'periodo' => array(
//            'rule' => array('maxLength', '5'),
//            'allowEmpty' => array(
//                'message' => 'Este campo não pode ter mais que 5 caracteres.',
//                'rule' => array('notempty'),
//            ),
//        ),
        'data' => array(
            'tamanho' => array(
                'rule' => array('maxLength', 10),
                'notempty' => true
            ),
        ),
        'hora_inicio' => array(
            'tamanho' => array(
                'rule' => array('maxLength', 5),
                'message' => 'Este campo não pode ter mais que 5 caracteres.',
                'notempty' => true
            ),
        ),
        'hora_fim' => array(
            'tamanho' => array(
                'rule' => array('maxLength', 5),
                'message' => 'Este campo não pode ter mais que 5 caracteres.',
                'notempty' => true
            ),
        ),
        'encaixe' => array(
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
        'Especialista' => array(
            'className' => 'Especialista',
            'foreignKey' => 'especialista_id',
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
        'Tiposervico' => array(
            'className' => 'Tiposervico',
            'foreignKey' => 'tiposervico_id',
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
