<?php

namespace obray;

class PDFBody
{
    
    private $PDFReader;
    private $PDFXRefTable;
    private $PDFCatalog;
    private $indirectObjects = [];
    private $rootXRef;
    

    public function __construct(\obray\PDFReader $PDFReader, \obray\PDFXRefTable $PDFXRefTable, int $root)
    {
        $this->PDFReader = $PDFReader;
        $this->PDFXRefTable = $PDFXRefTable;
        $this->root = $root;
        $this->rootXRef = $this->PDFXRefTable->get($root);
        $this->loadCatalog();
    }

    public function loadCatalog()
    {
        $PDFCatalog = new \obray\PDFCatalog($this->PDFReader, $this->PDFXRefTable, new \obray\PDFIndirectObject($this->PDFReader, $this->rootXRef));
        
    }

}