<?php
namespace obray\pdf;

class Catalog
{
    public $type;               // implemented
    public $version;            // implemented
    public $extensions;         // implemented
    public $pages;              // implemented
    public $pageLabels;
    public $names;
    public $dest;
    public $viewPreferences;
    public $pageLayout;
    public $pageMode;
    public $outlines;
    public $threads;
    public $openAction;
    public $aa;
    public $uri;
    public $acroForm;
    public $metaData;
    public $structTreeRoot;
    public $markInfo;
    public $lang;
    public $spiderInfo;
    public $outputIndents;
    public $pieceInfo;
    public $ocProperties;
    public $perms;
    public $legal;
    public $requirements;
    public $collection;
    public $needsRendering;

    private $Reader;
    private $XRefTable;

    public function __construct(\obray\pdf\Reader $PDFReader, \obray\pdf\XRefTable $XRefTable, \obray\pdf\IndirectObject $indirectObject=NULL)
    {
        $this->Reader = $Reader;
        $this->XRefTable = $XRefTable;
        if($indirectObject !== NULL){
            $this->loadCatalog($indirectObject);
        }
    }

    public function loadCatalog(\obray\pdf\IndirectObject $indirectObject)
    {
        if( isSet($indirectObject["Type"]) ){
            $this->type = $indirectObject["Type"];
        }
        if( isSet($indirectObject["Version"]) ){
            $this->version = $indirectObject["Version"];
        }
        if( isSet($indirectObject["Extensions"])){
            $this->extensions = $indirectObject["Extensions"];
        }
        if( isSet($indirectObject["Pages"])){
            $this->pages = $indirectObject["Pages"];
        }
        forEach( $this as $key => $value ){
            if(is_string($value) && $value[strlen($value)-1] === "R"){
                $indirectRef = explode(" ",$value);
                $this->xRef = $this->XRefTable->get($indirectRef[0]);
                $this->$key = new \obray\pdf\IndirectObject($this->Reader, $this->xRef);
            }
        }
        $this->resolvePages();
        $this->loadPages($this->pages['Kids']);
        print_r($this->pages);
    }

    public function resolvePages()
    {
        $kids = []; $kidsIndex = 0;
        forEach($this->pages['Kids'] as $page){
            if(empty($kids[$kidsIndex])) $kids[$kidsIndex] = "";
            $kids[$kidsIndex] .= " " . $page;
            $kids[$kidsIndex] = trim($kids[$kidsIndex]);
            if($page === "R") ++$kidsIndex;
        }
        $this->pages['Kids'] = $kids;
    }

    public function loadPages($kids)
    {
        forEach($kids as $index => $kid){
            $xref = explode(" ",$kid);
            print_r($xref);
            $xref = $this->XRefTable->get($xref[0]);
            $page = new \obray\pdf\PDFIndirectObject($this->Reader, $xref);
            print_r($page->dictionary);
        }
        exit();
    }
}