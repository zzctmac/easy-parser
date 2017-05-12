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
    /**
     * @var Variable[]
     */
    public $variables = [];
    /**
     * @var ImportClass[]
     */
    public $importClasses = [];
    /**
     * @var NamespaceBean[]
     */
    public $namespaces = [];

    public function addCall(Call $call)
    {
        $this->calls[] = $call;
    }

    public function addVariable(Variable $variable)
    {
        if($variable->isObject) {
            $variable->type = $this->findClassNameByAlias($variable->type);
        }
        $this->variables[$variable->name] = $variable;
    }

    public function addImportClasses(ImportClass $importClass)
    {
        $this->importClasses[] = $importClass;
    }

    public function addNamespace(NamespaceBean $n)
    {
        $this->namespaces[] = $n;
    }



    public function findClassNameByAlias($alias) {
        $o =  $this->findClassByAlias($alias);
        return $o ? $o->name : $alias;
    }
    public function findClassByAlias($alias) {
        foreach ($this->importClasses as $n) {
            if($n->alias === $alias)
                return $n;
        }
        return null;
    }
}