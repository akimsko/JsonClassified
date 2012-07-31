<?php
require_once __DIR__ . '/ITemplate.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JavaTemplate
 *
 * @author both
 */
class JavaTemplate implements ITemplate {
    private static $_types = array(
        Builder::TYPE_STRING => 'String',
        Builder::TYPE_INT => 'int',
        Builder::TYPE_FLOAT => 'double',
        Builder::TYPE_BOOL => 'boolean',
        Builder::TYPE_ARRAY => 'List<{subtype}>',
        Builder::TYPE_OBJECT => '{subtype}'
    );

    public function getClass() {
        $snippet = "public class {classname} {\n";
        $snippet .= "{properties}";
        $snippet .= "{mutators}";
        $snippet .= "}";
        
        return $snippet;
    }
    
    public function getPropertyMethods() {
        $snippet = "\n\tpublic {type} get{name}() {\n";
        $snippet .= "\t\treturn m{name};\n";
        $snippet .= "\t}\n\n";
        
        $snippet .= "\tpublic {classname} set{name}({type} {name}) {\n";
        $snippet .= "\t\tm{name} = {name};\n";
        $snippet .= "\t\treturn this;\n";
        $snippet .= "\t}\n";
        
        return $snippet;
    }
    
    public function getProperty() {
        return "\tprivate {type} m{name};\n";
    }
    
    public function getType($typeIndex) {
        return array_key_exists($typeIndex, self::$_types) ? self::$_types[$typeIndex] : self::$_types[Builder::TYPE_STRING];
    }

    public function getFileExtension() {
        return ".java";
    }
}

?>
