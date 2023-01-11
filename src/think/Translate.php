<?php

namespace Kaadon\Translate\think;

use Kaadon\Translate\TranslateV2;
use think\Exception;
use think\facade\Config;

class Translate extends TranslateV2
{

    protected static $instance;

    public function __construct(string $key, ?string $cachePath = null)
    {
        parent::__construct($key,$cachePath);
    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            $config = [];
            if (Config::has("kaadon.translate.v1")) {
                $config = Config::get("kaadon.translate.v1");
            }
            if (!isset($config['key'])) throw new Exception("Key does not exist");
            self::$instance = new static($config['key'],$config['cachePath']??null);
        }
        return self::$instance;
    }
}