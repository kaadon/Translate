<?php

namespace Kaadon\Translate;

class StringFilter
{
    private $character = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    private $filter_1 = '/\%[a-z]{1,2}/';
    private $filter_2 = '/\<zy[a-zA-Z]{1,2}\>\<\/zy[a-zA-Z]{1,2}\>/';
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
                "/[a-zA-Z][1,2]/",
                $match,
                $matches_1,
                PREG_PATTERN_ORDER
            );
            $filter = $matches_1[0][0]??'';
            if (empty($filter)) continue;
            $this->str = str_replace($match,"<zy{$filter}></zy{$filter}>",$this->str);
        }
    }

    private function toVariable():void
    {
        preg_match_all(
            $this->filter_2,
            $this->str,
            $matches,
            PREG_PATTERN_ORDER
        );
        if (empty($matches) || empty($matches[0]) || empty($matches[0][0])) return;
        foreach ($matches[0] as $match) {
            if (empty($match)) continue;
            $filter_one = explode("></",$match);
            $filter_two = str_replace("<zy","",$filter_one[0]);
            if (empty($filter_two)) continue;
            $this->str = str_replace($match," %".$filter_two,$this->str." ");
        }
    }


}