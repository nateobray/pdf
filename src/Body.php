<?php

namespace obray\pdf;

class Body
{
    
    private $Reader;
    private $XRefTable;
    private $Catalog;
    private $indirectObjects = [];
    private $rootXRef;
    

    public function __construct(\obray\pdf\Reader $PDFReader, \obray\pdf\XRefTable $PDFXRefTable, int $root)
    {
        $this->Reader = $Reader;
        $this->XRefTable = $XRefTable;
        $this->root = $root;
        $this->rootXRef = $this->XRefTable->get($root);
        $this->loadCatalog();
    }

    public function loadCatalog()
    {
        $PDFCatalog = new \obray\pdf\Catalog($this->Reader, $this->XRefTable, new \obray\pdf\IndirectObject($this->Reader, $this->rootXRef));
        
    }

}