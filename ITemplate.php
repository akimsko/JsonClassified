<?php
/**
 * This file is part of JsonClassified.
 * @link https://github.com/akimsko/JsonClassified
 *
 * @copyright Copyright 2012 Bo Thinggaard
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Template interface
 *
 * @author bo@unpossiblesystems.dk
 */
interface ITemplate {
    public function getPropertyMethods();
    public function getProperty();
    public function getClass();
    public function getType($typeIndex);
    public function getFileExtension();
	public function getExtras($properties, $class);
}

?>
