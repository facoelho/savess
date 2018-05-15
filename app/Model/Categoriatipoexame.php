<?php

class Categoriatipoexame extends AppModel {

    /**
     * belongsTo associations
     *
     */
    public $belongsTo = array(
        'Categoria' => array(
            'className' => 'Categoria',
            'foreignKey' => 'categoria_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Tipoexame' => array(
            'className' => 'Tipoexame',
            'foreignKey' => 'tipoexame_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

}

?>
