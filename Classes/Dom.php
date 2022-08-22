<?php
namespace classes;

class Dom
{
    private $html;
    private $dom;

    function __construct($html)
    {
        $this->html = $html;
        $this->dom = $this->parseDom();
    }

    private function parseDom()
    {        
        return new Element($this->html, null);
    }

    public function getDom() {
        return $this->dom;
    }

    public function getElementsCount() {
        $result = [];
        $arr = $this->toArray();
        array_walk_recursive($arr, function ($item, $key) use (&$result) {
            if($key == 'tag_name') {
                $result[$item]++;
            }   
        });
        return $result;
    }

    public function toArray() {
        return json_decode(json_encode($this->dom),true);
    }
}
