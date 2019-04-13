<?php
namespace obray;

class PDFTrailer
{
    private $fh;
    private $byteOffset = 0;
    private $TrailerDictionary;

    public function __construct(\obray\PDFReader $PDFReader)
    {
        $this->PDFReader = $PDFReader;
        $lines = $this->PDFReader->until("startxref");
        // valid file format
        if( $lines[1] !== "%%EOF" || $lines[3] != "startxref"){
            throw new \Exception("Invalid file format");
        }
        
        $this->byteOffset = (int)$lines[2];
        
        $this->PDFReader->until("trailer");
        $this->PDFReader->pop();
        $this->TrailerDictionary = new \obray\PDFDictionary($this->PDFReader->merge());
        
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