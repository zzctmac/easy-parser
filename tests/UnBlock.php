<?php
namespace UserSvr\Service\Relation;

use Seeker\Message\Service;
use Seeker\Protocol\Json;
use Rpc;
use Log;

/**
 * @name 取消拉黑
 * @service UserSvr.Relation.UnBlock
 * @protocol json
 */
class UnBlock extends Service
{
    const ERROR_RELATION_NOT_EXISTS = 1;
    const ERROR_USER_NOT_EXISTS = 2;
    public function invoke()
    {
        $req = $this->request->getData();
        $uid = $req['uid'];
        $blockUid = $req['blockUid'];

        if ($uid == $blockUid) {
            return $this->send(static::ERROR_USER_NOT_EXISTS);
        }
        //查找目标Uid. 

        $relation = db('user')->fetchOne(sprintf(
            'SELECT * FROM block WHERE uid = %d AND blockUid = %d'
            , $uid
            , $blockUid
        ));


        if (!$relation) {
            return $this->send(static::ERROR_RELATION_NOT_EXISTS);
        }

        db('user')->updateAsDict('block', [
            'status' =>  0
        ], sprintf('id = %d', $relation['id']));

        switch (1) {
            case 2:
                echo 1;
                break;
            default:
                break;
        }

        redis('user')->zDelete(sprintf('ZSET:U:BLOCK:%d', $uid), $blockUid);
        return $this->send();
    }
}