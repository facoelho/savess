<?php

App::uses('AppModel', 'Model');

/**
 * Holding Model
 *
 */
class Holding extends AppModel {

    /**
     * Validation rules
     */
    public $validate = array(
        'nome' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'datepicker' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );

    /**
     * hasMany associations
     */
    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'holding_id',
            'dependent' => false,
        ),
        'Empresa' => array(
            'className' => 'Empresa',
            'foreignKey' => 'holding_id',
            'dependent' => false,
        ),
        'Holdingmenu' => array(
            'className' => 'Holdingmenu',
            'foreignKey' => 'holding_id',
            'dependent' => false,
        ),
//        'Paciente' => array(
//            'className' => 'Paciente',
//            'foreignKey' => 'holding_id',
//            'dependent' => false,
//        ),
    );

    /**
     * hasAndBelongsToMany associations
     */
    public $hasAndBelongsToMany = array(
        'Menu' =>
        array(
            'className' => 'Menu',
            'joinTable' => 'holdingmenus',
            'foreignKey' => 'holding_id',
            'associationForeignKey' => 'menu_id',
            'order' => 'Menu.menu, Menu.ordem',
        )
    );

    public function afterFind($dados, $primary = false) {
        foreach ($dados as $key => $value) {
            if (!empty($value["Holding"]["validade"])) {
                $dados[$key]["Holding"]["validade"] = $this->formataData($value["Holding"]["validade"], 'PT', 'N');
            }
        }
        return $dados;
    }

}
