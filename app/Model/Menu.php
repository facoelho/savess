<?php

App::uses('AppModel', 'Model');

/**
 * Menu Model
 *
 * @property Groupmenu $Groupmenu
 */
class Menu extends AppModel {

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
        ),
        'controller' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'mostramenu' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'menu' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'ordem' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
    );
    
    /**
     * hasMany associations
     */
    public $hasMany = array(
        'Holdingmenu' => array(
            'className' => 'Holdingmenu',
            'foreignKey' => 'menu_id',
            'dependent' => false,
        )
    );

}
