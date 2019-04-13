<?php
namespace obray;

class PDFIndirectObject extends \obray\PDFDictionary
{
    private $PDFReader;
    private $PDFXRef;
    private $index;
    private $generation;

    public function __construct(\obray\PDFReader $PDFReader, \obray\PDFXRef $PDFXRef)
    {
        $this->PDFReader = $PDFReader;
        $this->PDFXRef = $PDFXRef;
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
        $this->PDFReader->seek((int)$this->PDFXRef->bytes);
        while(true){
            $line = $this->PDFReader->getLine();
            $lines[] = trim($line);
            $lineLength = strlen($line);
            if($line === "endobj\n"){
                return $lines;
            }
        }
    }
}