<?php

namespace classes;

class Element
{
    public $parent_element;
    public $tag_name;
    public $children_elements;
    private $html;
    private $attributes;

    function __construct($html, $parent_element)
    {
        $element_info = $this->getElementInfo($html);
        $this->tag_name = $element_info[1][0];
        $this->attributes = $element_info[2][0];
        $this->parent_element = $parent_element;
        $offset = 0;

        preg_match_all("/<" . $this->tag_name . ".*(>)/", $html, $start, PREG_OFFSET_CAPTURE);
        preg_match_all("/<\/" . $this->tag_name . "(>)/", $html, $end, PREG_OFFSET_CAPTURE);

        $start_pos = $start[1][0][1] + 1;
        for ($i = 1; $i < count($start[0]); $i++) {
            if ($start[0][$i][1] < $end[0][$i - 1][1]) {
                $offset++;
            }
        }
        $end_pos = $end[0][$offset][1] - $start[1][0][1] - 1;

        $this->html = substr($html, $start_pos, $end_pos);

        if ($this->tag_name != '') {
            $this->createChildNodes($this->html);
        }
    }

    private function createChildNodes($html)
    {
        if ($html == '') {
            return;
        }
        $element_info = $this->getElementInfo($html);
        $tag_name = $element_info[1][0];
        $offset = 0;

        preg_match_all("/<" . $tag_name . ".*(>)/", $html, $start, PREG_OFFSET_CAPTURE);
        preg_match_all("/<\/" . $tag_name . "(>)/", $html, $end, PREG_OFFSET_CAPTURE);

        if (!isset($end[0][0][1])) {
            return;
        }

        for ($i = 1; $i < count($start[0]); $i++) {
            // echo "<b>".$start[0][$i][1]." : ".$end[0][$i][1]."</b><br>";
            if ($start[0][$i][1] < $end[0][$i - 1][1]) {
                $offset++;
            }
        }
        $end_pos = $end[1][$offset][1] + 1;


        $element_html = substr($html, 0, $end_pos);
        $html = substr($html, $end_pos);
        $new_element = new Element($element_html, $this->tag_name);
        $this->children_elements[] = $new_element;
        $this->createChildNodes($html);
    }

    public function getAttribute()
    {
        return $this->attribute;
    }

    private function getElementInfo($html) {
        preg_match("/<([a-z]+)\s*([^>]*)>/", $html, $element_info, PREG_OFFSET_CAPTURE);
        return $element_info;
    }
}
