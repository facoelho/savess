<?php

App::uses('AppModel', 'Model');

/**
 * Empresa Model
 *
 * @property Holding $Holding
 * @property Contato $Contato
 * @property Endereco $Endereco
 * @property Usergroupempresa $Usergroupempresa
 */
class Empresa extends AppModel {

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate = array(
        'razaosocial' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'nomefantasia' => array(
            'notempty' => array(
                'rule' => array('notempty'),
            ),
        ),
        'cnpjEmpresa' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'rule' => array('notempty'),
            ),
        ),
        'inscEstadualEmpresa' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'rule' => array('notempty'),
            ),
        ),
        'inscMunicipalEmpresa' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'rule' => array('notempty'),
            ),
        ),
        'email' => array(
            'email' => array(
                'rule' => array('email'),
            ),
        ),
        'holding_id' => array(
            'numeric' => array(
                'rule' => array('numeric'),
            ),
        ),
        'logo_empresa' => array(
            'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
            'message' => 'Informe uma imagem válida (gif, jpeg, jpg, png).'
        )
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
     *
     * @var array
     */
    public $hasMany = array(
        'Usergroupempresa' => array(
            'className' => 'Usergroupempresa',
            'foreignKey' => 'empresa_id',
            'dependent' => false,
        ),
        'Categoria' => array(
            'className' => 'Categoria',
            'foreignKey' => 'empresa_id',
            'dependent' => false,
        ),
        'Caixa' => array(
            'className' => 'Caixa',
            'foreignKey' => 'empresa_id',
            'dependent' => false,
        ),
//        'Especialista' => array(
//            'className' => 'Especialista',
//            'foreignKey' => 'empresa_id',
//            'dependent' => false,
//        ),
//        'ContasReceber' => array(
//            'className' => 'ContasReceber',
//            'foreignKey' => 'empresa_id',
//            'dependent' => false,
//        ),
//        'Endereco' => array(
//            'className' => 'Endereco',
//            'foreignKey' => 'empresa_id',
//            'dependent' => false,
//        ),
//        'Albun' => array(
//            'className' => 'Albun',
//            'foreignKey' => 'empresa_id',
//            'dependent' => false,
//        ),
    );

}
