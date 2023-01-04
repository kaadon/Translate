<?php
require 'vendor/autoload.php';
use Kaadon\Translate\TranslateV2;
$TranslateV2 = new TranslateV2("AIzaSyAPMBFS_xQ79RzMICQBfRCKbAPjPggvSx0");
var_dump($TranslateV2->translateText("你好",'en'));
var_dump($TranslateV2->translateJson('{"a":"你好","b":{"b1":"你在哪","b2":"你回家了吗"}}','en'));