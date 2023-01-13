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


    public $cachePath = null;
    public $isfilter  = 2;

    /**
     * @param string|null $key
     */
    public function __construct(string $key = null, ?string $cachePath = null, ?int $isfilter = null)
    {
        $this->translate = new TranslateClient([
            'key' => $key
        ]);
        if (!is_null($cachePath)) {
            $this->cachePath = $cachePath;
        }
        if (!is_null($isfilter)) {
            $this->isfilter = $isfilter;
        }
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
        if (empty($text)) {
            return '';
        }
        $toText = null;
        if (isset($this->cachePath) && !empty($this->cachePath)) {
            $md5Text  = md5($text);
            $filePath = "{$this->cachePath}langCache/{$lang}/{$md5Text}";
            if (is_file($filePath)) {
                $toText = file_get_contents($filePath);
            }
        }
        if (empty($toText)) {
            /** 过滤 **/
            if ($this->isfilter == 1) $text = (new StringFilter($toText))->getFilter();
            $result = $this->translate->translate($text, [
                'target' => $lang
            ]);
            $toText = $result['text'];
            /** 过滤 **/
            if ($this->isfilter == 1) $toText = (new StringFilter($toText))->getFilter(2);
            $toText = html_entity_decode($result['text'],ENT_QUOTES);
            if (isset($this->cachePath) && !empty($this->cachePath)) {
                $file = "{$this->cachePath}langCache/{$lang}/{$md5Text}";
                $path = dirname($file);
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $fopen = fopen($file, "w") or die('cannot open file');
                if (!empty($toText)) {
                    fwrite($fopen, $toText);
                }
                fclose($fopen);
            }
        }
        return $toText;
    }

    /**
     * 翻译短信文件
     * @param string $text
     * @param string $lang
     * @return array
     */
    public function translateJson(string $text, string $lang): array
    {
        $translateArray = json_decode($text, true);
        if (!is_array($translateArray) || empty($text)) throw new Exception("Please provide JSON format data ");
        $result = [];
        foreach ($translateArray as $key => $translate) {
            if (is_array($translate)) {
                $result[$key] = $this->translateJson(json_encode($translate), $lang);
            } else {
                $result[$key] = $this->translateText($translate, $lang);
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
    public function localizedLanguages(string $lang = "en"): array
    {
        $languages = $this->translate->localizedLanguages([
            'target' => $lang
        ]);
        return $languages;
    }
}