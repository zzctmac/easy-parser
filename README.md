[![Build Status](https://www.travis-ci.org/zzctmac/easy-parser.svg?branch=master)](https://www.travis-ci.org/zzctmac/easy-parser)
[![Coverage Status](https://coveralls.io/repos/github/zzctmac/easy-parser/badge.svg?branch=master)](https://coveralls.io/github/zzctmac/easy-parser?branch=master)


# easy-parser

一个简单的php解析库，可以用来获得一个类的属性、方法、一个函数使用的变量等等，用于快速实现一些脚手架

```php
 $cp = new \st\parse\FileClass(__DIR__ . '/../c.php');
$this->assertEquals('Info\\Test', $cp->getName());
$this->assertEquals('co\\Service', $cp->getParentName());

```