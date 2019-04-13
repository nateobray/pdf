<?php
namespace obray\pdf;

class IndirectObject extends \obray\pdf\Dictionary
{
    private $Reader;
    private $XRef;
    private $index;
    private $generation;

    public function __construct(\obray\pdf\Reader $Reader, \obray\pdf\XRef $XRef)
    {
        $this->Reader = $Reader;
        $this->XRef = $XRef;
        $lines = $this->loadFromXRef(); $dictionary = ""; $inDictionary = false;
        forEach($lines as $line){
            if($line[0] === "<" && $line[1] === "<" || $inDictionary){
                $dictionary .= $line . " ";
                $inDictionary = true;
            }
            if($line[strlen($line)-1] === ">" && $line[strlen($line)-2] === ">"){
                $inDictionary = false;
            }
        }
        $this->dictionary = $this->parseDictionary($dictionary);
    }

    public function loadFromXRef()
    {
        $lines = [];
        $this->Reader->seek((int)$this->XRef->bytes);
        while(true){
            $line = $this->Reader->getLine();
            $lines[] = trim($line);
            $lineLength = strlen($line);
            if($line === "endobj\n"){
                return $lines;
            }
        }
    }
}