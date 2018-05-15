<?php

class Holdingmenu extends AppModel {
    
    /**
     * belongsTo associations
     *
     */
    public $belongsTo = array(
        'Holding' => array(
            'className' => 'Holding',
            'foreignKey' => 'holding_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Menu' => array(
            'className' => 'Menu',
            'foreignKey' => 'menu_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
}

?>
