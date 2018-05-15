<?php

App::uses('AppModel', 'Model');

/**
 * Especialista Model
 *
 */
class Especialista extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'nome' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maximo' => array(
                'rule' => array('maxLength', '200'),
                'message' => 'Máximo 200 caracteres',
            )
        ),
        'sobrenome' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maximo' => array(
                'rule' => array('maxLength', '200'),
                'message' => 'Máximo 200 caracteres',
            )
        ),
        'empresa_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        )
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
    );

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['dtnascimento'])) {
            $this->data[$this->alias]['dtnascimento'] = $this->formataData($this->data[$this->alias]['dtnascimento'], 'EN', 'N');
        }
        return true;
    }

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Especialista"]["dtnascimento"])) {
                $dados[$key]["Especialista"]["dtnascimento"] = $this->formataData($value["Especialista"]["dtnascimento"], 'PT', 'N');
            }
        }
        return $dados;
    }

}

?>
