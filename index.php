<?php

require 'Classes/CurlConnection.php';
require 'Classes/Dom.php';
require 'Classes/Element.php';

use classes\CurlConnection;
use classes\DOM;

$html = CurlConnection::getHtml("https://www.php.net");
$dom = new Dom($html);
print_r($dom->getElementsCount());

