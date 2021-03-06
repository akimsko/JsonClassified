<?php
/**
 * This file is part of JsonClassified.
 * @link https://github.com/akimsko/JsonClassified
 *
 * @copyright Copyright 2012 Bo Thinggaard
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

/**
 * Builds the class from a template and some JSON.
 *
 * @author bo@unpossiblesystems.dk
 */
class Builder {
    protected $_json;
    protected $_classname;
    protected $_outputdir;
    
    private $_buildTypes = array();
    
    /** @var ITemplate */
    protected $_template;
    
    const TYPE_STRING = 0;
    const TYPE_INT = 1;
    const TYPE_FLOAT = 2;
    const TYPE_BOOL = 3;
    const TYPE_ARRAY = 4;
    const TYPE_OBJECT = 5;
    
    public function __construct($json, ITemplate $template, $classname = "MyClass", $outputdir = "output/") {
        $this->_json = (is_array($json) && array_key_exists(0, $json)) ? $json[0] : $json;
        $this->_classname = $classname;
        $this->_outputdir = $outputdir;
        $this->_template = $template;
        
        if (!file_exists($outputdir)) {
            mkdir($outputdir);
        }
    }
    
    public function getClassName() {
        return $this->_classname;
    }
    
    /**
     * Writes the generated class to a file.
     * 
     * @return bool Success
     */
    public function writeClass() {
        $file = $this->_outputdir.$this->_classname.$this->_template->getFileExtension();
        return (!file_exists($file) || $this->_json !== null) ? file_put_contents($file, $this->buildClass()) : false;
    }
    
    /**
     * Generates the class from the template and json data.
     * 
     * @return string Generated class.
     */
    public function buildClass() {
        $content = $this->buildClassContent();
        $all = str_replace(array_keys($content), $content, $this->_template->getClass());
        return str_replace('{classname}', $this->_classname, $all);
    }
    
    /**
     * Resolves the type of the JSON value.
     * 
     * @param string $value JSON value.
     * @return int Type.
     */
    private function getType($value) {
        if (is_array($value)) {
            return self::TYPE_ARRAY;
        }

		if ($value === null) {
            return self::TYPE_STRING;
		}
        
        if (is_object($value)) {
            return self::TYPE_OBJECT;
        }

        if (is_numeric($value)) {
            return (strpos($value, ".") == false) ? self::TYPE_INT : self::TYPE_FLOAT;
        }
        
        $value = strtolower($value);
        return ($value == "true" || $value == "false") ? self::TYPE_BOOL : self::TYPE_STRING;
    }
    
    /**
     * Builds the class properties and property methods.
     * 
     * @return array Properties and methods indexed by template key.
     */
    private function buildClassContent() {
        $content = array('{properties}' => '', '{methods}' => '');
        if ($this->_json !== null) {
			$properties = array();
            foreach ($this->_json as $key => $value) {
                $properties[] = $vars = array(
                    '{type}' => $this->buildPropertyType($key, $value),
                    '{name.lc}' => lcfirst($key),
                    '{name.uc}' => ucfirst($key),
                );
                $content['{properties}'] .= str_replace(array_keys($vars), $vars, $this->_template->getProperty());
                $content['{methods}'] .= str_replace(array_keys($vars), $vars, $this->_template->getPropertyMethods());
            }
			$content['{extras}'] = $this->_template->getExtras($properties, $this->_classname);
        }
        return $content;
    }
    
    /**
     * Translates the type, and handles nested arrays.
     *
     * @param mixed $key
     * @param mixed $value
     * @return string Translated type 
     */
    private function buildPropertyType($key, $value) {
        if (array_key_exists($key, $this->_buildTypes)) {
            return $this->_buildTypes[$key];
        }
        
        $type = $this->getType($value);
        
        if ($type == self::TYPE_OBJECT) {
            $builder = new Builder($value, $this->_template, ucfirst($key));
            $builder->writeClass();
            return $this->_buildTypes[$key] = $builder->getClassName();
        }
        
        if ($type == self::TYPE_ARRAY) {
            $nest = array_key_exists(0, $value) ? $this->buildPropertyType($key, $value[0]) : $this->_template->getType(self::TYPE_STRING);
            return $this->_buildTypes[$key] = str_replace('{subtype}', $nest, $this->_template->getType(self::TYPE_ARRAY));
        }
        
        return $this->_buildTypes[$key] = $this->_template->getType($type);
    }
}

?>
