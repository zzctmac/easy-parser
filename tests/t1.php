<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-4
 * Time: 10:19
 */

include __DIR__ . '/../vendor/autoload.php';


use PhpParser\Error;
use PhpParser\ParserFactory;

$code = file_get_contents(__DIR__ . '/A.php');
$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP5);

try {
    $stmts = $parser->parse($code);
    var_dump( count($stmts));
    //print_r($stmts);
} catch (Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}