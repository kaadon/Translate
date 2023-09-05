<?php

namespace Kaadon\Translate;

class StringFilter
{
    private $character = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    private $filter_1 = '/\%[a-zA-Z]{1,10}/';
    private $filter_2 = '/\<one_[a-zA-Z]{1,10}\>\<\/one_[a-zA-Z]{1,10}\>/';
    private $str      = null;

    public function __construct(string $str)
    {
        $this->str = $str;
    }

    public function getFilter(int $filterType = 1):string
    {
        switch ($filterType){
            case 1:
                $this->toCharacter();
                break;
            case 2:
                $this->toVariable();
                break;
            default:
                break;
        }
        return $this->str;
    }

    private function toCharacter():void
    {
        preg_match_all(
            $this->filter_1,
            $this->str,
            $matches,
            PREG_PATTERN_ORDER
        );
        foreach ($matches[0] as $match) {
            if (empty($match)) continue;
            preg_match_all(
                "/[a-zA-Z]{1,10}/",
                $match,
                $matches_1,
                PREG_PATTERN_ORDER
            );
            $filter = $matches_1[0][0]??'';
            if (empty($filter)) continue;
            $this->str = str_replace($match,"<one_{$filter}></one_{$filter}>",$this->str);
        }
        $this->str = (new StringFilterTwo($this->str))->getFilter();
        $this->str = preg_replace("/\s(?=\s)/","\\1",$this->str);
    }

    private function toVariable():void
    {
        preg_match_all(
            $this->filter_2,
            $this->str,
            $matches,
            PREG_PATTERN_ORDER
        );
        if (empty($matches) || empty($matches[0]) || empty($matches[0][0])){
            $this->str = (new StringFilterTwo($this->str))->getFilter(2);
        }else{
            foreach ($matches[0] as $match) {
                if (empty($match)) continue;
                $filter_one = explode("></",$match);
                $filter_two = str_replace("<one_","",$filter_one[0]);
                if (empty($filter_two)) continue;
                $this->str = str_replace($match," %".$filter_two,$this->str);
            }
            $this->str = (new StringFilterTwo($this->str))->getFilter(2);
        }
        $this->str = preg_replace("/\s(?=\s)/","\\1",$this->str);
    }


}