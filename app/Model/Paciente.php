<?php

App::uses('AppModel', 'Model');

/**
 * Paciente Model
 *
 */
class Paciente extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'nome' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => false,
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 300),
                'message' => 'Este campo não pode ter mais que 300 caracteres.',
                'allowEmpty' => false,
                'last' => false
            ),
        ),
        'sobrenome' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => true,
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 200),
                'message' => 'Este campo não pode ter mais que 300 caracteres.',
                'allowEmpty' => false,
                'last' => false
            ),
        ),
        'cpf' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'allowEmpty' => true
            ),
        ),
        'sexo' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'dtnascimento' => array(
            'tamanho' => array(
                'rule' => array('maxLength', 10),
                'message' => 'Este campo não pode ter mais que 10 caracteres.',
                'allowEmpty' => true
            ),
        ),
        'ativo' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
            ),
        ),
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => true,
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 250),
                'message' => 'Este campo não pode ter mais que 300 caracteres.',
                'allowEmpty' => true,
                'last' => false
            ),
        ),
        'dddfone' => array(
            'rule' => array('maxLength', 3),
            'allowEmpty' => true,
        ),
        'fone' => array(
            'numeric' => array(
                'rule' => array('maxLength', 9),
                'allowEmpty' => true,
            ),
        ),
        'dddcelular' => array(
            'rule' => array('maxLength', 3),
            'allowEmpty' => true,
        ),
        'celular' => array(
            'numeric' => array(
                'rule' => array('maxLength', 9),
                'allowEmpty' => true,
            ),
        ),
        'obs' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Este campo não pode ser vazio.',
                'allowEmpty' => true,
                'last' => false
            ),
            'tamanho' => array(
                'rule' => array('maxLength', 5000),
                'message' => 'Este campo não pode ter mais que 300 caracteres.',
                'allowEmpty' => true,
                'last' => false
            ),
        ),
    );

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Holding' => array(
            'className' => 'Holding',
            'foreignKey' => 'holding_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Endereco' => array(
            'className' => 'Endereco',
            'foreignKey' => 'paciente_id',
            'dependent' => false,
        ),
        'ContasReceber' => array(
            'className' => 'ContasReceber',
            'foreignKey' => 'paciente_id',
            'dependent' => false,
        ),
        'Evolucao' => array(
            'className' => 'Evolucao',
            'foreignKey' => 'paciente_id',
            'dependent' => false,
        ),
        'Albun' => array(
            'className' => 'Albun',
            'foreignKey' => 'paciente_id',
            'dependent' => false,
        ),
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['dtnascimento'])) {
            $this->data[$this->alias]['dtnascimento'] = $this->formataData($this->data[$this->alias]['dtnascimento'], 'EN', 'N');
        }
        return true;
    }

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Paciente"]["dtnascimento"])) {
                $dados[$key]["Paciente"]["dtnascimento"] = $this->formataData($value["Paciente"]["dtnascimento"], 'PT', 'N');
            }
        }
        return $dados;
    }

}

?>
