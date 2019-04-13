<?php
namespace obray;

class PDF
{

    private $PDFReader;
    private $PDFTrailer;
    private $PDFXRefTable;
    private $PDFBody;

    public function __construct(string $pdf=NULL)
    {
        $this->PDFReader = new \obray\PDFReader($pdf);
        $this->decode();
    }

    public function decode()
    {
        $this->PDFTrailer = new \obray\PDFTrailer($this->PDFReader);
        $this->PDFXRefTable = new \obray\PDFXRefTable($this->PDFReader);
        $this->PDFBody = new \obray\PDFBody($this->PDFReader, $this->PDFXRefTable, $this->PDFTrailer->getRoot());
    }
    
    public function encode()
    {

    }
}