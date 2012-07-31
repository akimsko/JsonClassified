<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author both
 */
interface ITemplate {
    public function getPropertyMethods();
    public function getProperty();
    public function getClass();
    public function getType($typeIndex);
    public function getFileExtension();
}

?>
