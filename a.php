<?php

require 'vendor/autoload.php';

use Google\Cloud\Translate\V2\TranslateClient;
//
//$translate = new \Kaadon\Translate\TranslateV2("");
//$a = $translate->translateText("你好","en");
$a = $config['cachePath']??null;
var_dump($a);
//// Translate text from english to french.
//$result = $translate->translate('Hello world!', [
//    'target' => 'fr'
//]);
//
//echo $result['text'] . "\n";

// Detect the language of a string.
//$result = $translate->detectLanguage('news');
//
//echo $result['languageCode'] . "\n";
//
//// Get the languages supported for translation specifically for your target language.
//$languages = $translate->localizedLanguages([
//    'target' => 'en'
//]);
//
//foreach ($languages as $language) {
//    echo $language['name'] . "\n";
//    echo $language['code'] . "\n";
//}
//
//// Get all languages supported for translation.
//$languages = $translate->languages();
//
//foreach ($languages as $language) {
//    echo $language . "\n";
//}