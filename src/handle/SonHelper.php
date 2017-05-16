<?php
/**
 * User: ZHOUZHICHAO
 * Date: 2017-5-16
 * Time: 9:50
 */

namespace st\handle;


use PhpParser\Node;

trait SonHelper
{
    /**
     * @return Node[]
     */
    public function getSons()
    {
        $m = Manager::create();
        $sons = [];
        $keys = $this->getAllKeys();
        foreach ($keys as $key)
        {
            if(is_array($this->node->$key)) {
                $sons = array_merge($sons, $this->getSonsFromStmts($this->$key));
            } else {
                if($m->hit($this->node->$key))
                    $sons[] = $this->node->$key;
            }
        }
        return $sons;
    }

    protected function getSonsFromStmts($stmts)
    {
        $m = Manager::create();
        $sons = [];
        foreach ($stmts as $stmt)
        {
            if(is_array($stmt))
                $sons = array_merge($sons, $this->getSonsFromStmts($stmt));
            elseif($m->hit($stmt))
                $sons[] = $stmt;
        }
        return $sons;
    }
}