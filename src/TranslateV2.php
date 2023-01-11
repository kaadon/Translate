<?php

namespace Kaadon\Translate;

use Google\Cloud\Translate\V2\TranslateClient;
use think\Exception;

/**
 *
 */
class TranslateV2
{
    /**
     * @var TranslateClient|null
     */
    public $translate = null;

    /**
     * @param string|null $key
     */
    public function __construct(string $key = null)
    {
        $this->translate = new TranslateClient([
            'key' => $key
        ]);
    }

    /**
     *
     * @return TranslateClient|null
     */
    public function getTranslate(): ?TranslateClient
    {
        return $this->translate;
    }

    /**
     * 设置翻译对象
     * @param null $translate
     * @return Translate
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;
        return $this;
    }

    /**
     * 翻译文字
     * @param string $text
     * @param string $lang
     * @return string
     */
    public function translateText(string $text, string $lang): string
    {
        if (empty($text)){
            return '';
        }
        $result = $this->translate->translate($text, [
            'target' => $lang
        ]);
        return $result['text'];
    }

    /**
     * 翻译短信文件
     * @param string $text
     * @param string $lang
     * @return array
     */
    public function translateJson(string $text, string $lang): array
    {
        $translateArray = json_decode($text,true);
        if (!is_array($translateArray) || empty($text)) throw new Exception("Please provide JSON format data ");
        $result = [];
        foreach ($translateArray as $key => $translate) {
            if (is_array($translate)){
                $result[$key] = $this->translateJson(json_encode($translate),$lang);
            }else{
                $result[$key] = $this->translateText($translate,$lang);
            }
        }
        return $result;
    }

    /**
     * @param string $text
     * @return string
     */
    public function getLanguage(string $text): string
    {
        $result = $this->translate->detectLanguage($text);
        return $result['languageCode'];
    }

    /**
     * @return array
     */
    public function allowLanguages(): array
    {
        $languages = $this->translate->languages();
        return $languages;
    }

    /**
     * @return array
     */
    public function localizedLanguages(string $lang = "en"):array
    {
        $languages = $this->translate->localizedLanguages([
            'target' => $lang
        ]);
        return $languages;
    }
}