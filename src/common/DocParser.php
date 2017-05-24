<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-24
 * Time: 10:06
 */

namespace st\common;


trait DocParser
{
    public function parseDoc($doc)
    {
        $res = [];
        $lines = explode(PHP_EOL, $doc);
        foreach ($lines as $line) {
            $pos = strpos($line, '@');
            if($pos === false)
                continue;
            $currentDoc = array_values(array_diff(explode(' ', trim(substr($line, $pos+1))), ['']));
            if(count($currentDoc) == 1)
                $currentDoc[] = 1;
            $res[$currentDoc[0]] = $currentDoc[1];
        }
        return $res;
    }
}