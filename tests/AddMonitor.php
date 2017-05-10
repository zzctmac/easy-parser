<?php
namespace PrivilegeSvr\Service\Power;

use PrivilegeSvr\Library\Power\Monitor;
use PrivilegeSvr\Library\Power\ToggleAddService;

/**
 * @name 添加监控特权
 * @service PrivilegeSvr.Power.AddMonitor
 * @protocol json
 */
class AddMonitor extends ToggleAddService
{

    /**
     * @param $uid
     * @return Monitor
     */
    function init($uid)
    {
        return Monitor::getInstance($uid);
    }
}