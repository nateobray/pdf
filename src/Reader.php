<?php
namespace obray\pdf;

class Reader
{
    private $fh;
    private $position = -1;
    private $lines = array();

    public function __construct($pdf)
    {
        if(is_file($pdf)){
            $this->fh = fopen($pdf, 'r+');
            return $this;
        }
        throw new \Exception("Unable to find PDF file: ".$pdf."");
    }

    public function until(string $key)
    {
        $ln = 0; $this->lines = [];
        while(true){
            if(empty($this->lines[$ln])){
                $this->lines[$ln] = "";
            }
            fseek($this->fh, $this->position, SEEK_END);
            $c = fread($this->fh,1);
            --$this->position;
            if( $c === "\n" || $c === "\r\n" || $c === "\r" ){
                if( $this->lines[$ln] == $key ){
                    break;
                }
                ++$ln;
                continue;
            }
            $this->lines[$ln] = $c . $this->lines[$ln];
        }
        return $this->lines;
    }

    public function pop()
    {
        array_pop($this->lines);
    }

    public function merge()
    {
        $string = "";
        forEach($this->lines as $line){
            $string = $line . ' ' . $string;
        }
        return $string;
    }

    public function reverse()
    {
        $this->lines = array_reverse($this->lines);
        return $this->lines;
    }

    public function seek(int $bytes)
    {
        fseek($this->fh, $bytes);
    }

    public function getLine()
    {
        return fgets($this->fh);
    }
}