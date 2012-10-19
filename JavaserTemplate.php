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
class JavaserTemplate implements ITemplate {
    private static $_types = array(
        Builder::TYPE_STRING => 'String',
        Builder::TYPE_INT => 'int',
        Builder::TYPE_FLOAT => 'double',
        Builder::TYPE_BOOL => 'boolean',
        Builder::TYPE_ARRAY => 'List<{subtype}>',
        Builder::TYPE_OBJECT => '{subtype}'
    );

    public function getClass() {
        $snippet = "public class {classname} implements JsonSerializable {\n";
        $snippet .= "{properties}";
        $snippet .= "{methods}";
		$snippet .= "{extras}";
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

	public function getExtras($properties, $class) {
		
		$snippet  = "\n\t@Override\n";
		$snippet .= "\tpublic JSONObject JsonSerialize() throws JSONException {\n";
		$snippet .= "\t\tJSONObject json = new JSONObject();\n";
		
		foreach ($properties as $property) {
			$snippet .= $this->_getPut($property);
		}
		
		$snippet .= "\t\treturn json;\n";
		$snippet .= "\n\t}\n";
		
		
		$snippet .= "\n\t@Override\n";
		$snippet .= "\tpublic $class JsonDeserialize(JSONObject json) throws JSONException {\n";
		$snippet .= "\t\tif (json != null) {;\n";
		
		foreach ($properties as $property) {
			$snippet .= $this->_getOpt($property);
		}
		
		$snippet .= "\t\t}\n";
		$snippet .= "\t\treturn this;\n";
		$snippet .= "\t}\n";
		
		return $snippet;
	}
	
	private function _getPut($property) {
		switch ($property['{type}']) {
			case self::$_types[Builder::TYPE_STRING]:
			case self::$_types[Builder::TYPE_INT]:
			case self::$_types[Builder::TYPE_FLOAT]:
			case self::$_types[Builder::TYPE_BOOL]:
				return "\t\tjson.put(\"{$property['{name.lc}']}\", m{$property['{name.uc}']})\n";
			case substr($property['{type}'], 0, 4) != 'List':
				return "\t\tjson.put(\"{$property['{name.lc}']}\", m{$property['{name.uc}']}.JsonSerialize())\n";
			default:
				return "\t\t//m{$property['{name.uc}']} is a LIST!\n";
		}
	}
	
	private function _getOpt($property) {
		switch ($property['{type}']) {
			case self::$_types[Builder::TYPE_STRING]:
				return "\t\t\tm{$property['{name.uc}']} = json.optString(\"{$property['{name.lc}']}\");\n";
			case self::$_types[Builder::TYPE_INT]:
				return "\t\t\tm{$property['{name.uc}']} = json.optInt(\"{$property['{name.lc}']}\");\n";
			case self::$_types[Builder::TYPE_FLOAT]:
				return "\t\t\tm{$property['{name.uc}']} = json.optDouble(\"{$property['{name.lc}']}\");\n";
			case self::$_types[Builder::TYPE_BOOL]:
				return "\t\t\tm{$property['{name.uc}']} = json.optBoolean(\"{$property['{name.lc}']}\");\n";
			case substr($property['{type}'], 0, 4) != 'List':
				return "\t\t\tm{$property['{name.uc}']} = {$property['{type}']}.JsonDeserialize(json.optJSONObject(\"{$property['{name.lc}']}\"));\n";
			default:
				return "\t\t\t//m{$property['{name.uc}']} is a LIST!\n";
		}
	}
}

?>
