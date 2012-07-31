<?php
require_once __DIR__ . '/ITemplate.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhpTemplate
 *
 * @author akimsko
 */
class PhpTemplate implements ITemplate {
    private static $_types = array(
        Builder::TYPE_STRING => 'string',
        Builder::TYPE_INT => 'int',
        Builder::TYPE_FLOAT => 'float',
        Builder::TYPE_BOOL => 'bool',
        Builder::TYPE_ARRAY => '{subtype}[]',
        Builder::TYPE_OBJECT => '{subtype}'
    );

    public function getClass() {
        $snippet = "<?php\n\n";
        $snippet .= "class {classname} {\n";
        $snippet .= "{properties}";
        $snippet .= "{methods}";
        $snippet .= "}\n";
        $snippet .= "?>";
        
        return $snippet;
    }
    
    public function getPropertyMethods() {
        $snippet = "\n\t/**\n";
        $snippet .= "\t* @return {type}\n";
        $snippet .= "\t*/\n";
        
        $snippet .= "\tpublic function get{name.uc}() {\n";
        $snippet .= "\t\treturn \$this->_{name.lc};\n";
        $snippet .= "\t}\n";
        
        $snippet .= "\n\t/**\n";
        $snippet .= "\t* @param {type} \${name.lc}\n";
        $snippet .= "\t* @return {classname}\n";
        $snippet .= "\t*/\n";
        
        $snippet .= "\tpublic function set{name.uc}(\${name.lc}) {\n";
        $snippet .= "\t\t\$this->_{name.lc} = \${name.lc};\n";
        $snippet .= "\t\treturn \$this;\n";
        $snippet .= "\t}\n";
        
        return $snippet;
    }
    
    public function getProperty() {
        $snippet = "\n\t/** @var {type} */\n";
        $snippet .= "\tprivate \$_{name.lc};\n";

        return $snippet;
    }
    
    public function getType($typeIndex) {
        return array_key_exists($typeIndex, self::$_types) ? self::$_types[$typeIndex] : self::$_types[Builder::TYPE_STRING];
    }

    public function getFileExtension() {
        return ".php";
    }
}
?>
