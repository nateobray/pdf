<?php
namespace obray\pdf;

class Trailer
{
    private $fh;
    private $byteOffset = 0;
    private $TrailerDictionary;

    public function __construct(\obray\pdf\Reader $Reader)
    {
        $this->Reader = $Reader;
        $lines = $this->Reader->until("startxref");
        // valid file format
        if( $lines[1] !== "%%EOF" || $lines[3] != "startxref"){
            throw new \Exception("Invalid file format");
        }
        
        $this->byteOffset = (int)$lines[2];
        
        $this->Reader->until("trailer");
        $this->Reader->pop();
        $this->TrailerDictionary = new \obray\pdf\Dictionary($this->Reader->merge());
        
    }

    public function getRoot()
    {
        return $this->TrailerDictionary["Root"];
    }

    public function getSize()
    {
        return $this->TrailerDictionary["Size"];
    }

    public function getInfo()
    {
        return $this->TrailerDictionary["Info"];
    }

    public function getId()
    {
        return $this->TrailerDictionary["ID"];
    }

}