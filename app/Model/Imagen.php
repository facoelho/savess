<?php

App::uses('AppModel', 'Model');

/**
 * Albun Model
 * 
 */
class Imagen extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'albun_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'titulo' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => true,
                'last' => false
            ),
        ),
        'descricao' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => true,
                'last' => false
            ),
        ),
        'imagem_foto' => array(
            'rule'    => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
            'message' => 'Informe uma imagem válida (gif, jpeg, jpg, png).'
        ),
    );    

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Albun' => array(
            'className' => 'Albun',
            'foreignKey' => 'albun_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),       
    );
    
}

?>
