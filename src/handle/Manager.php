<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-10
 * Time: 15:29
 */

namespace st\handle;


class Manager
{
    protected static $classes = [
        Namespace_::class,
        Use_::class,
        Assign::class,
        New_::class,
        MethodCall::class,
        FuncCall::class,
        StaticCall::class
    ];

    /**
     * @return Manager
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @var IBase[]
     */
    protected $handlers;
    /**
     * @var Container
     */
    protected $container;

    /**
     * Manager constructor.
     */
    private function __construct()
    {
        $this->container = new Container();
        foreach (self::$classes as $class) {
            $this->handlers[] = new $class($this->container);
        }

    }

    public function hit($stmt)
    {
        if(!is_object($stmt))
            return false;
        foreach ($this->handlers as $handler) {
            if($handler->hit($stmt)) {
                return $handler;
            }
        }
        return false;
    }

    public function handle($stmts)
    {
        if(!is_array($stmts))
            $stmts = [$stmts];
        if(count($stmts) == 0)
            return ;
        foreach ($stmts as $stmt) {
            $handler = $this->hit($stmt);
            if($handler === false)
                continue;
            $handler->handle();
            $sons = $handler->getSons();
            if($sons != null)
                $this->handle($sons);
        }
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

}