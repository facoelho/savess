<?php

App::uses('AppModel', 'Model');

/**
 * Parametro Model
 *
 */
class Parametro extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'parametro' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'descricao' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'conteudo' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
            'maximo' => array(
                'rule' => array('maxLength', '50'),
                'message' => 'Máximo 100 caracteres',
            )
        ),
        'user_id' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'empresa_id' => array(
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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Parametro"]["created"])) {
                $dados[$key]["Parametro"]["created"] = $this->formataDataHora($value["Parametro"]["created"], 'PT');
            }
            if (!empty($value["Parametro"]["modified"])) {
                $dados[$key]["Parametro"]["modified"] = $this->formataDataHora($value["Parametro"]["modified"], 'PT');
            }
        }
        return $dados;
    }

}

?>
