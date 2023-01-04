<?php
require 'vendor/autoload.php';

use Google\Cloud\Translate\V3\TranslationServiceClient;

$translationClient = new TranslationServiceClient();
$content = ['one', 'two', 'three'];
$targetLanguage = 'es';
var_dump(TranslationServiceClient::locationName("translation-330800", 'global'));
return;
$response = $translationClient->translateT88ext(
    $content,
    $targetLanguage,
    TranslationServiceClient::locationName('translation-330800-58870712e853', 'global')
);

foreach ($response->getTranslations() as $key => $translation) {
    $separator = $key === 2
        ? '!'
        : ', ';
    echo $translation->getTranslatedText() . $separator;
}