<?php

/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public function begin() {
        $dataSource = $this->getDataSource();
        $dataSource->begin();
    }

    public function commit() {
        $dataSource = $this->getDataSource();
        $dataSource->commit();
    }

    public function rollback() {
        $dataSource = $this->getDataSource();
        $dataSource->rollback();
    }

    public function formataDataHora($datahora, $formato) {

        $data = substr($datahora, 0, 10);

        if ($formato == "PT") {
            if (preg_match('/^\d{4}[-]\d{1,2}[-]\d{1,2}$/', $data)) {
                $data = explode("-", $data);
                $data = $data[2] . "/" . $data[1] . "/" . $data[0];
                $hora = substr($datahora, 11, 8);
                $datahora = $data . " " . $hora;
                return $datahora;
            } else {
                return $datahora;
            }
        }

        if ($formato == 'EN') {
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', substr($data, 0, 10))) {
                $data = explode("/", $data);
                $data = $data[2] . "-" . $data[1] . "-" . $data[0];
                $hora = substr($datahora, 11, 8);
                $datahora = $data . " " . $hora;
                return $datahora;
            } else {
                return $datahora;
            }
        }
    }

    public function formataData($data, $formato) {

        if (!empty($data)) {

            $data = substr($data, 0, 10);

            if ($formato == "PT") {
                if (preg_match('/^\d{4}[-]\d{1,2}[-]\d{1,2}$/', $data)) {
                    $data = explode("-", $data);
                    $data = $data[2] . "/" . $data[1] . "/" . $data[0];
                    return $data;
                } else {
                    return $data;
                }
            }

            if ($formato == 'EN') {
                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', substr($data, 0, 10))) {
                    $data = explode("/", $data);
                    $data = $data[2] . "-" . $data[1] . "-" . $data[0];
                    return $data;
                } else {
                    return $data;
                }
            }
        }
    }

}
