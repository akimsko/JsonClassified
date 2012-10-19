<?php
/**
 * This file is part of JsonClassified.
 * @link https://github.com/akimsko/JsonClassified
 *
 * @copyright Copyright 2012 Bo Thinggaard
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */
require_once __DIR__ . '/ITemplate.php';
/**
 * Template for C#
 *
 * @author bo@unpossiblesystems.dk
 */
class CsharpTemplate implements ITemplate {
    private static $_types = array(
        Builder::TYPE_STRING => 'string',
        Builder::TYPE_INT => 'int',
        Builder::TYPE_FLOAT => 'double',
        Builder::TYPE_BOOL => 'bool',
        Builder::TYPE_ARRAY => 'IList<{subtype}>',
        Builder::TYPE_OBJECT => '{subtype}'
    );

    public function getClass() {
        $snippet = "public sealed class {classname} {\n";
        $snippet .= "{properties}";
        $snippet .= "}";
        
        return $snippet;
    }
    
    public function getPropertyMethods() {
        return '';
    }
    
    public function getProperty() {
        return "\tpublic {type} {name.uc} { get; set; }\n";
    }
    
    public function getType($typeIndex) {
        return array_key_exists($typeIndex, self::$_types) ? self::$_types[$typeIndex] : self::$_types[Builder::TYPE_STRING];
    }

    public function getFileExtension() {
        return ".cs";
    }

	public function getExtras($properties, $class) {
		
	}
}

?>
