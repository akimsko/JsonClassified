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
 * Template for Java
 *
 * @author bo@unpossiblesystems.dk
 */
class JavaTemplate implements ITemplate {
    private static $_types = array(
        Builder::TYPE_STRING => 'String',
        Builder::TYPE_INT => 'Integer',
        Builder::TYPE_FLOAT => 'Double',
        Builder::TYPE_BOOL => 'Boolean',
        Builder::TYPE_ARRAY => 'List<{subtype}>',
        Builder::TYPE_OBJECT => '{subtype}'
    );

    public function getClass() {
        $snippet = "public class {classname} {\n";
        $snippet .= "{properties}";
        $snippet .= "{methods}";
        $snippet .= "}";
        
        return $snippet;
    }
    
    public function getPropertyMethods() {
        $snippet = "\n\tpublic {type} get{name.uc}() {\n";
        $snippet .= "\t\treturn m{name.uc};\n";
        $snippet .= "\t}\n\n";
        
        $snippet .= "\tpublic {classname} set{name.uc}({type} {name.lc}) {\n";
        $snippet .= "\t\tm{name.uc} = {name.lc};\n";
        $snippet .= "\t\treturn this;\n";
        $snippet .= "\t}\n";
        
        return $snippet;
    }
    
    public function getProperty() {
        return "\tprivate {type} m{name.uc};\n";
    }
    
    public function getType($typeIndex) {
        return array_key_exists($typeIndex, self::$_types) ? self::$_types[$typeIndex] : self::$_types[Builder::TYPE_STRING];
    }

    public function getFileExtension() {
        return ".java";
    }
}

?>
