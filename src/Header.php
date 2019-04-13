<?php
namespace obray\pdf;

class Header
{

    public $version = NULL;

    public function __construct(string $pdf)
    {
        $this->parse($pdf);
    }

    public function parse($pdf)
    {
        $pdf = str_replace("\r\n", "\n", $pdf);
        $pdf = str_replace("\r", "\n", $pdf);
        $lines = explode("\n",$pdf);

        // get version
        $this->getVersion($lines[0]);

        print_r($lines[0]);
        print_r("\n");
        print_r($lines[1]);
        print_r("\n");
        print_r($lines[2]);
        print_r("\n");
        print_r($lines[3]);
        print_r("\n");
        print_r($lines[4]);
        print_r("\n");
        print_r($lines[5]);
        print_r("\n");
        print_r($lines[6]);
        print_r("\n");
        
    }

    public function getVersion($line)
    {
        $this->version = str_replace("%PDF-","",$line);
    }
}