<?php
namespace obray\pdf;

class PDF
{

    private $Reader;
    private $Trailer;
    private $XRefTable;
    private $Body;

    public function __construct(string $pdf=NULL)
    {
        $this->PDFReader = new \obray\pdf\Reader($pdf);
        $this->decode();
    }

    public function decode()
    {
        $this->Trailer = new \obray\pdf\Trailer($this->Reader);
        $this->XRefTable = new \obray\pdf\XRefTable($this->Reader);
        $this->Body = new \obray\pdf\Body($this->Reader, $this->XRefTable, $this->Trailer->getRoot());
    }
    
    public function encode()
    {

    }
}