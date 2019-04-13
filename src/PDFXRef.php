<?php
namespace obray;

class PDFXRef
{
    public $bytes = 0;
    public $generation = 0;
    public $type = "n";
    public function __construct($bytes, $generation, $type)
    {
        $this->bytes = $bytes;
        $this->generation = $generation;
        $this->type = $type;
    }
}