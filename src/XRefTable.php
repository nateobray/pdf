<?php
namespace obray\pdf;

class XRefTable implements \Iterator
{
    private $Reader;
    private $xref = [];

    public function __construct(\obray\pdf\Reader $Reader)
    {
        $this->Reader = $Reader;
        $this->Reader->until("xref");
        $this->Reader->pop();
        $lines = $this->Reader->reverse();
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
                $this->xref[$i] = new \obray\XRef($ref[0],$ref[1],$ref[2]);
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