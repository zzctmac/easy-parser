<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-16
 * Time: 16:43
 */

namespace st\handle;


use st\bean\Call;
use st\bean\ImportClass;
use st\bean\Namespace_ as NamespaceBean;
use st\bean\Variable;

class GlobalContainer extends Container
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * GlobalContainer constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    protected static $ins;
    public static function destroy()
    {
            self::$ins = null;
    }

    public static function create(Container $container)
    {
        if(self::$ins == null)
            self::$ins = new self($container);
        else
            self::$ins->container = $container;
        return self::$ins;
    }

    public function addCall(Call $call)
    {
        $this->container->addCall($call);
    }

    public function addVariable(Variable $variable)
    {
        $this->container->addVariable($variable);
    }

    public function addImportClasses(ImportClass $importClass)
    {
        $this->container->addImportClasses($importClass);
        parent::addImportClasses($importClass);
    }

    public function addNamespace(NamespaceBean $n)
    {
        $this->container->addNamespace($n);
        parent::addNamespace($n);
    }

    public function findClassNameByAlias($alias)
    {
        return parent::findClassNameByAlias($alias);
    }

    public function findClassByAlias($alias)
    {
        return parent::findClassByAlias($alias);
    }

    /**
     * @return Container
     */
    public function getLocalContainer()
    {
        return $this->container;
    }


}