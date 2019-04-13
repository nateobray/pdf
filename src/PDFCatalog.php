<?php
namespace obray;

class PDFCatalog
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

    private $PDFReader;
    private $PDFXRefTable;

    public function __construct(\obray\PDFReader $PDFReader, \obray\PDFXRefTable $PDFXRefTable, \obray\PDFIndirectObject $indirectObject=NULL)
    {
        $this->PDFReader = $PDFReader;
        $this->PDFXRefTable = $PDFXRefTable;
        if($indirectObject !== NULL){
            $this->loadCatalog($indirectObject);
        }
    }

    public function loadCatalog(\obray\PDFIndirectObject $indirectObject)
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
                $this->xRef = $this->PDFXRefTable->get($indirectRef[0]);
                $this->$key = new \obray\PDFIndirectObject($this->PDFReader, $this->xRef);
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
            $xref = $this->PDFXRefTable->get($xref[0]);
            $page = new PDFIndirectObject($this->PDFReader, $xref);
            print_r($page->dictionary);
        }
        exit();
    }
}