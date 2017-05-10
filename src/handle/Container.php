<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 15:40
 */

namespace st\handle;


use st\bean\Call;
use st\bean\ImportClass;
use st\bean\Variable;
use st\bean\Namespace_ as NamespaceBean;

class Container
{
    public $calls = [];
    public $variables = [];
    public $importClasses = [];
    public $namespaces = [];

    public function addCall(Call $call)
    {
        $this->calls[] = $call;
    }

    public function addVariable(Variable $variable)
    {
        $this->variables[] = $variable;
    }

    public function addImportClasses(ImportClass $importClass)
    {
        $this->importClasses[] = $importClass;
    }

    public function addNamespace(NamespaceBean $n)
    {
        $this->namespaces[] = $n;
    }
}