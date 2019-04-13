<?php
namespace obray;

class PDFXRefTable implements \Iterator
{
    private $PDFReader;
    private $xref = [];

    public function __construct(\obray\PDFReader $PDFReader)
    {
        $this->PDFReader = $PDFReader;
        $this->PDFReader->until("xref");
        $this->PDFReader->pop();
        $lines = $this->PDFReader->reverse();
        $this->parse($lines);
    }

    public function parse($lines)
    {
        $line = explode(" ",array_shift($lines));
        if(count($line) === 2){
            $start = $line[0];
            $rows = $line[1];
            for($i=$start;$i<$rows;++$i){
                $ref = explode(" ",array_shift($lines));
                $this->xref[$i] = new \obray\PDFXRef($ref[0],$ref[1],$ref[2]);
            }
        }
    }

    public function rewind()
    {
        reset($this->xref);
    }

    public function current()
    {
        $val = current($this->xref);
        return $val;
    }

    public function key() 
    {
        $key = key($this->xref);
        return $key;
    }

    public function next() 
    {
        $next = next($this->xref);
        return $next;
    }

    public function valid()
    {
        $key = key($this->xref);
        $valid = ($key !== NULL && $key !== FALSE);
        return $valid;
    }

    public function get($index)
    {
        return $this->xref[$index];
    }

}