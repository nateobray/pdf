<?php
namespace obray\pdf;

class Dictionary implements \ArrayAccess
{
    public $dictionary = array();

    public function __construct(string $dictionary)
    {
        $this->dictionary = $this->parseDictionary($dictionary);
    }

    public function parseDictionary($string)
    {
        $newDictionary = [];
        // remove dictionary header and footer
        $string = str_replace("<< /","",$string);
        $string = str_replace(" >>","",$string);
        // parse into key/value pairs
        $results = explode(" /",$string); $skip = -1;
        forEach($results as $index => $resultant){
            if( $skip === $index ) continue;
            $key = substr($resultant, 0, strpos($resultant," "));
            if( empty($key) ){
                $key = $resultant;
                $value = $results[$index+1];
                $skip = $index+1;
            } else {
                $value = substr($resultant, strpos($resultant," ") );
            }
            $newDictionary[$key] = trim($value);
        }
        // parse substructures
        forEach($newDictionary as $key => $value){
            // check if value is array
            if( $value[0] === "[" && $value[strlen($value)-1] === "]" ){
                $newDictionary[$key] = $this->parseArray($value);
            }
            // check if value is sub-dictionary
            if( $value[0] === "<" && $value[1] === "<" && $value[strlen($value)-1] === ">" && $value[strlen($value)-2] === ">" ){
                $newDictionary[$key] = $this->parseDictionary($value);
            }
        }
        return $newDictionary;
    }

    public function parseArray($string)
    {
        $string = trim($string," [ ]");
        $array = explode(" ", $string);
        return $array;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->dictionary[] = $value;
        } else {
            $this->dictionary[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->dictionary[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->dictionary[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->dictionary[$offset]) ? $this->dictionary[$offset] : null;
    }
}
