<?php
namespace RichSvr\Service\Gift;

use Common\Core\ConstDefined;
use Common\Core\UserLevel;
use Exception;
use GiftManager;
use Log;
use Phalcon\Db;
use RichSvr\Library\BannerSendFacade;
use RichSvr\Library\GiftManagerFacade;
use RichSvr\Library\LevelEvent;
use Rpc;
use Seeker\Message\Service;

/**
 * @name 送礼
 * @service  RichSvr.Gift.Send
 * @protocol json
 * @broadcast false
 */
use Seeker\Protocol\Json;

class Send extends Service
{
    const ERROR_NOT_EXISTS = 1;
    const ERROR_NOT_START = 2;
    const ERROR_IS_END = 3;
    const ERROR_IS_CLOSE = 4;
    const ERROR_DB = 5;
    const ERROR_DIAMOND_LIMIT = 6;
    const ERROR_CLOSE_REPLAY_GIFT = 7;
    const ERROR_USER_SAME = 8;
    const ERROR_NO_MONEY = 9;
    const ERROR_NO_DIAMOND = 10;
    const ERROR_NO_SEED = 11;

    // 礼物来源
    const GIFT_FROM_LIVE = 0;
    const GIFT_FROM_IM = 1;
    const GIFT_FROM_REPLAY = 2;

    // 礼物类型
    const GIFT_TYPE_GUARD = 4;
    const GIFT_TYPE_GIFTENVELOPE = 5;

    const CONSUME_TABLE_DIAMON = 'diamond';
    const CONSUME_TABLE_SEED = 'seed';
    const CONSUME_TABLE_STARLIGHT = 'starlight';

    protected $rules = [
        'uid'        => 'required|int',
        'owid'       => 'required|int',
        'gid'        => 'required|int',
        'from'       => 'int',
        'attrId'     => 'int',
        'count'      => 'int',
        'replayId'   => 'int',
        'replayTime' => 'int',
        'txt'        => 'string', // 表达送礼的爱意
    ];

    public function initParam()
    {
        $this->combo = 1;
        $this->comboId = 0;
        $this->req = $this->request->getData();
        $this->uid = (int)$this->req['uid'];
        $this->owid = (int)$this->req['owid'];
        $this->gid = (int)$this->req['gid'];
        $this->count = max((int)isset($this->req['count']) ? $this->req['count'] : 0, 1);
        $this->txt = isset($this->req['txt']) ? $this->req['txt'] : "";

        if (is_local()) {
            Log::alert(sprintf('RichSvr.Gift.Send %s -> ［%d］个 gid:[%d] 给 uid: %s', $this->uid, $this->count, $this->gid,
                $this->owid));
        }

        if (is_online() && in_array($this->uid, GiftManager::getTestUid()) && !in_array($this->owid,
                GiftManager::getTestUid())
        ) {
            throw new \Exception('测试账号只能赠送给测试直播间', 50);
        }

        if ($this->uid <= 0 || $this->owid <= 0 || $this->gid <= 0) {
            throw new \Exception('params error', 100);
        }

        $json = new Json;
        $json->setData(['uid' => $this->uid]);

        $this->time = time();
        $this->dateline = date('Y-m-d H:i:s', $this->time);

        // 回放
        $this->replayId = isset($this->req['replayId']) ? (int)$this->req['replayId'] : 0;
        $this->replayTime = isset($this->req['replayTime']) ? (int)$this->req['replayTime'] : 0;
        $this->attrId = isset($this->req['attrId']) ? (int)$this->req['attrId'] : 0;

        // king活动升级礼物特效
        GiftManager::onBeforeSend($this, $this->gid);

        $this->gift = GiftManager::get($this->gid, $this->owid, $this->attrId);

        $this->from = static::GIFT_FROM_LIVE;
        if ($this->replayId) {
            $this->from = static::GIFT_FROM_REPLAY;
        } elseif (isset($this->req['isIm']) && $this->req['isIm']) {
            $this->from = static::GIFT_FROM_IM;
        } elseif (isset($this->req['from']) && $this->req['from']) {
            $this->from = (int)$this->req['from'];
        }
        $this->type = $this->from;
        if (!$this->gift) {
            throw new \Exception('Gift not exists', 100);
        }

        if (!$this->gift->batch && $this->count > 1) {
            throw new \Exception('礼物不允许批量送', 100);
        }

        if (!$this->attrId) {
            $this->attrId = $this->gift->attrId;
        }
        $uidCrc = crc32(uniqid(sprintf(
            '%d-%d-%d',
            $this->uid,
            $this->owid,
            $this->gid
        )));
        $this->orderId = date('YmdHis', $this->time) . sprintf('%05d', $uidCrc) . rand(10, 99);

    }

    public function createTradeNo()
    {
        $crcId = sprintf('%05d', crc32(uniqid($this->orderId)));

        return date('YmdHis', $this->time) . $crcId . rand(10, 99);
    }

    public function createCombo()
    {
        // 判断礼物是否能combo
        $comboLimitTime = $this->gift->comboTime;
        $combo = 0;
        $comboId = 0;
        if ($comboLimitTime) {
            // 如果礼物有升级 使用原始礼物连击数
            $gid = isset($this->ogid) ? $this->ogid : $this->gid;

            // 带有时间的代表能连击
            $comboCacheKey = sprintf('STR:GIFT:COMBO:%d-%d-%d', $this->uid, $gid, $this->owid);
            $lastCombo = redis('cache')->get($comboCacheKey);
            $combo = $this->count;
            $comboId = crc32($this->orderId);
            if ($lastCombo) {
                list($lastComboTime, $combo, $lastComboId) = explode('@', $lastCombo);
                if (time() - $lastComboTime < $comboLimitTime) {
                    $combo += $this->count;
                    $comboId = (int)$lastComboId;
                } else {
                    $combo = $this->count;
                }
            }
            if (!$comboId) {
                $comboId = crc32($this->orderId);
            }

            if ($comboId > (float)2147483647) {
                $comboId -= 4294967296;
            }
            //todo 根据礼物配置决定combo 时间
            redis('cache')->setEx($comboCacheKey, $comboLimitTime, time() . '@' . $combo . '@' . $comboId);
        }
        $this->combo = $combo;
        $this->comboId = $comboId;
    }

    public function checkParms()
    {
        if ($this->uid == $this->owid) {
            return static::ERROR_USER_SAME;
        }
        // 判断礼物状态
        if (!$this->gift) {
            return static::ERROR_NOT_EXISTS;
        }

        if ($this->gift->startTime > $this->time) {
            return static::ERROR_NOT_START;
        }

        if (($this->gift->endTime + 4 * 3600) < $this->time) {
            return static::ERROR_IS_END;
        }

        if ($this->gift->status < 0) {
            return static::ERROR_IS_CLOSE;
        }

        if ($this->gift->diamond < 1 && $this->gift->seed < 1) {
            return static::ERROR_NO_MONEY;
        }

        return 0;
    }

    public function tradeLog($type, $data = [])
    {
        $default = [
            'orderId'  => $this->orderId,
            'name'     => $this->gift->name,
            'attrName' => $this->gift->attrName,
            'uid'      => $this->uid,
            'toUid'    => $this->owid,
            'count'    => $this->count,
            'gid'      => $this->gid,
        ];
        Log::alert($type, array_merge($default, $data));
    }

    public function getConsumeSql($table = '', $uid = 0, $cnt = 0)
    {
        return sprintf(
            'UPDATE %s SET `cnt` = `cnt` - %d WHERE `uid` = %d AND `cnt` >= %d',
            $table,
            $cnt,
            $uid,
            $cnt
        );
    }

    public function getBalance($uid, $table)
    {
        $ret = db('rich')->fetchOne(
            sprintf(
                'SELECT * FROM %s WHERE uid = :uid LIMIT 1',
                $table
            ),
            Db::FETCH_ASSOC,
            ['uid' => $uid]
        );

        return isset($ret['cnt']) ? (int)$ret['cnt'] : 0;
    }

    public function invoke()
    {
        $this->initParam();
        $checkStatus = $this->checkParms();
        if ($checkStatus) {
            return $this->send($checkStatus);
        }

        $starlight = intval($this->gift->starlight) * $this->count;
        $fight = intval($this->gift->fight) * $this->count;
        $diamond = $this->gift->diamond * $this->count;
        $seed = $this->gift->seed * $this->count;
        $exp = $this->gift->exp * $this->count;

        $this->tradeLog('送礼开始', [
            'diamond'   => $diamond,
            'seed'      => $seed,
            'starlight' => $starlight,
            'fight'     => $fight,
            'exp'       => $exp,
        ]);

        if (!db('rich')->begin()) {
            throw new \Exception('送礼事务开启失败', 1);
        }

        $this->response->setData([
            'orderId' => $this->orderId,
        ]);

        try {
            if ($diamond > 0) {
                // 开始扣钱
                $this->tradeLog('扣除钻石', ['diamond' => $diamond]);

                $affectRows = db('rich')->execute($this->getConsumeSql(static::CONSUME_TABLE_DIAMON, $this->uid,
                    $diamond));

                Log::info("-------> 跑到这了" . $affectRows);

                if ($affectRows < 1) {
                    db('rich')->rollBack();

                    return $this->send(static::ERROR_NO_DIAMOND);
                }

                //记录流水日志
                $diamondTrade = [
                    'dateline' => $this->dateline,
                    'tradeNo'  => $this->createTradeNo(),
                    'uid'      => $this->uid,
                    'val'      => $diamond,
                    'channel'  => ConstDefined::OPTION_GIFT,
                    'type'     => ConstDefined::TYPE_DIAMOND,
                    'action'   => ConstDefined::ACTION_DEC,
                    'orderId'  => $this->orderId,
                    'balance'  => $this->getBalance($this->uid, static::CONSUME_TABLE_DIAMON),
                ];
                db('rich')->insertAsDict('trade_record', $diamondTrade);
            }

            if ($seed > 0) {
                // 开始扣钱
                $this->tradeLog('扣除种子', ['seed' => $seed]);
                Log::alert($this->getConsumeSql(static::CONSUME_TABLE_SEED, $this->uid, $seed));
                $affectRows = db('rich')->execute($this->getConsumeSql(static::CONSUME_TABLE_SEED, $this->uid, $seed));
                if ($affectRows < 1) {
                    db('rich')->rollBack();

                    return $this->send(static::ERROR_NO_SEED);
                }
                //记录流水日志
                $seedTrade = [
                    'dateline' => $this->dateline,
                    'tradeNo'  => $this->createTradeNo(),
                    'uid'      => $this->uid,
                    'val'      => $seed,
                    'channel'  => ConstDefined::OPTION_GIFT,
                    'type'     => ConstDefined::TYPE_SEED,
                    'action'   => ConstDefined::ACTION_DEC,
                    'orderId'  => $this->orderId,
                    'balance'  => $this->getBalance($this->uid, static::CONSUME_TABLE_SEED),
                ];
                db('rich')->insertAsDict('trade_record', $seedTrade);
            }
            if ($starlight) {
                $this->tradeLog('增加星光值', ['starlight' => $starlight]);
                //开始增加星光
                $affectRows = db('rich')->execute(
                    'INSERT INTO starlight SET uid = ?, cnt = ? ON DUPLICATE KEY UPDATE cnt = cnt + ?',
                    [$this->owid, $starlight, $starlight]
                );
                if ($affectRows < 1) {
                    db('rich')->rollBack();

                    return $this->send(static::ERROR_DB);
                }
                //记录流水日志
                $starlightTrade = [
                    'dateline' => $this->dateline,
                    'tradeNo'  => $this->createTradeNo(),
                    'uid'      => $this->owid,
                    'val'      => $starlight,
                    'channel'  => ConstDefined::OPTION_GIFT,
                    'type'     => ConstDefined::TYPE_STARLIGHT,
                    'action'   => ConstDefined::ACTION_INC,
                    'orderId'  => $this->orderId,
                    'balance'  => $this->getBalance($this->owid, static::CONSUME_TABLE_STARLIGHT),
                ];
                db('rich')->insertAsDict('trade_record', $starlightTrade);
            }

            $giftRecode = [
                'dateline' => $this->dateline,
                'orderId'  => $this->orderId,
                'uid'      => $this->uid,
                'toUid'    => $this->owid,
                'gid'      => $this->gid,
                'attrId'   => $this->attrId,
                'count'    => $this->count,
            ];
            $res = db('rich')->insertAsDict('gift_record', $giftRecode);
            if (!$res) {
                db('rich')->rollBack();

                return $this->send(static::ERROR_DB);
            }
            if ($starlight || $fight) {
                $this->tradeLog('增加主播收获星光、战斗力', ['starlight' => $starlight, 'fight' => $fight]);
                $affectRows = db('rich')->execute(
                    'INSERT INTO attr SET uid = ?, starlight = ?, fight = ? ON DUPLICATE KEY UPDATE starlight = starlight + ?, fight = fight + ?',
                    [$this->owid, $starlight, $fight, $starlight, $fight]
                );
                if ($affectRows < 1) {
                    db('rich')->rollBack();

                    return $this->send(static::ERROR_DB);
                }
            }

            if ($diamond || $exp) {
                $this->tradeLog('增加用户送出钻石、经验', ['diamond' => $diamond, 'exp' => $exp]);
                $affectRows = db('rich')->execute(
                    'INSERT INTO attr SET uid = ?, exp = ?, diamond = ? ON DUPLICATE KEY UPDATE exp = exp + ?, diamond = diamond + ?',
                    [$this->uid, $exp, $diamond, $exp, $diamond]
                );

                if ($affectRows < 1) {
                    db('rich')->rollBack();

                    return $this->send(static::ERROR_DB);
                }
            }

            $observerBeforeCommitData = [
                'uid'       => $this->uid,
                'owid'      => $this->owid,
                'dateline'  => $this->dateline,
                'orderId'   => $this->orderId,
                'gid'       => $this->gid,
                'attrId'    => $this->attrId,
                'count'     => $this->count,
                'diamond'   => $diamond,
                'fight'     => $fight,
                'exp'       => $exp,
                'starlight' => $starlight,
                'from'      => $this->from,
                'gift'      => $this->gift,
            ];

            GiftManager::onBeforeCommit($observerBeforeCommitData, $this->gift->type);

            db('rich')->commit();
        } catch (Exception $e) {
            $this->tradeLog('送礼发生错误......', ['error' => $e->getMessage()]);
            db('rich')->rollBack();

            return $this->send(static::ERROR_DB);
        }
        $this->createCombo();
        // 获取当前主播的分类
        $categoryId = redis('storage')->hGet(sprintf('HASH:ROOM:%d', $this->owid), 'categoryId');
        $categoryId = $categoryId === false ? -1 : (int)$categoryId;

        // 记录日志
        $giftRecode = [
            'uid'        => $this->uid,
            'toUid'      => $this->owid,
            'dateline'   => $this->dateline,
            'orderId'    => $this->orderId,
            'gid'        => $this->gid,
            'attrId'     => $this->attrId,
            'attrName'   => $this->gift->attrName,
            'count'      => $this->count,
            'diamond'    => $diamond,
            'fight'      => $fight,
            'seed'       => $seed,
            'categoryId' => $categoryId,
            'exp'        => $exp,
            'starlight'  => $starlight,
            'from'       => $this->from,
        ];
        db('log')->insertAsDict('log_gift_record', $giftRecode);

        $nowExp = redis('user')->hIncrBy('HASH:U:INFO:' . $this->uid, 'exp', $exp);
        $nowDiamond = redis('user')->hIncrBy('HASH:U:INFO:' . $this->uid, 'diamond', $diamond);
        $user = redis('user')->hGetAll('HASH:U:INFO:' . $this->uid);
        //计算认证等级.....
        $level = UserLevel::getLevel($nowExp);
        $verified = isset($user['verified']) ? (int)$user['verified'] : 0;
        $user['verified'] = UserLevel::getVerified($level, $verified);
        if ($level != $user['level']) {
            LevelEvent::upgrade($user['uid'], $level, $user['level']);
            $type = 1;
            $updateUser = [
                'level'        => $level,
                'verified'     => $user['verified'],
                'verifiedInfo' => UserLevel::getVerifiedInfo($user['verified'], $user['verifiedInfo']),
            ];
            redis('user')->hMSet('HASH:U:INFO:' . $this->uid, $updateUser);
            // 触发事件
            $this->InfoUpdateEmit($user, [
                'level' => $level,
            ]);
            $user = array_merge($user, $updateUser);
        }

        $nowStartlight = redis('user')->hIncrBy('HASH:U:INFO:' . $this->owid, 'starlight', $starlight);
        $nowFight = redis('user')->hIncrBy('HASH:U:INFO:' . $this->owid, 'fight', $fight);

        redis('user')->zAdd('ZSET:U:ALL', $user['exp'], $this->uid);
        //增加贡献榜（数据丢失需要从DB恢复）
        if ($starlight > 0) {
            redis('user')->zIncrBy(sprintf('ZSET:U:CONTRIBUTE:%d', $this->owid), $starlight, $this->uid);
            $weekKey = sprintf('ZSET:U:WEEK_CONTRIBUTE_%d:%d', date('W'), $this->owid);
            redis('user')->zIncrBy($weekKey, $starlight, $this->uid);
            redis('user')->expire($weekKey, 14 * 86400);
        }

        //发送星光协议...
        $eventOw = new Json;
        $eventOw->setData([
            'uid'       => $this->owid,
            'starlight' => $nowStartlight,
            'fight'     => $nowFight,
        ]);

        Rpc::instanceEvent('Event:RichSvr.Starlight.Update')
            ->setData($eventOw)
            ->send();

        $eventUser = new Json;
        $eventUser->setData([
            'uid'      => $this->uid,
            'owid'     => $this->owid,
            'diamond'  => $nowDiamond,
            'level'    => (int)$user['level'],
            'verified' => (int)$user['verified'],
            'exp'      => $nowExp,
        ]);
        Rpc::instanceEvent('Event:RichSvr.User.Update')
            ->setData($eventUser)
            ->send();
        //开始发送事件
        //处理新协议
        $priv = (int)redis('storage')->zScore('ZSET:ROOM:PRIV:' . $this->owid, $this->uid);
        $giftNotify = [
            'user'             => (new \Common\Struct\User\Normal($user))->toArray(),
            'roomAttr'         => [
                'priv' => $priv,
            ],
            'retetionAttr'     => [
                'aliveTime' => $this->gift->delayTime,
                'startTime' => time(),
                'id'        => time(),
            ],
            'gid'              => $this->gid,
            'attrId'           => $this->attrId,
            'combo'            => $this->combo,
            'comboId'          => $this->comboId,
            'type'             => $this->type,
            'uid'              => $this->uid,
            'owid'             => $this->owid,
            'attrName'         => $this->gift->attrName,
            'diamond'          => $diamond,
            'seed'             => $seed,
            'fight'            => $fight,
            'exp'              => $exp,
            'starlight'        => $starlight,
            'count'            => $this->count,
            'replayId'         => $this->replayId,
            'replayTime'       => $this->replayTime,
            'ext'              => $this->gift->ext,
            'isBox'            => $this->gift->isBox,
            'broadcast'        => $this->gift->broadcast,
            'broadcastTrigger' => $this->gift->broadcastTrigger,
            'templateId'       => $this->gift->templateId,
            'categoryId'       => $categoryId,
            'txt'              => $this->txt,
        ];

        try {
            $ret = GiftManager::onAfterCommit($giftNotify, $this->gift->type);
            if (is_array($ret) && $ret) {
                $giftNotify = array_merge($giftNotify, $ret);
            }

            // 取出最新守护状态
            $roomGuard = redis('storage')->zScore('ZSET:GUARD:ROOM:' . $this->owid, $this->uid);
            $roomGuard = $roomGuard ? (64 - ($roomGuard >> 45) & 0x3F) : 0;
            $giftNotify['roomAttr']['guard'] = $roomGuard;

            // 发送横幅
            if ($this->gift->broadcast && $this->count >= $this->gift->broadcastTrigger) {
                BannerSendFacade::sendLogic($giftNotify);
            }
        } catch (\Exception $e) {
            Log::error('onAfterCommit Error', [
                'errorMessgae' => $e->getMessage(),
                'code'         => $e->getCode(),
                'file'         => $e->getFile(),
                'line'         => $e->getLine(),
            ]);
        }

        $giftNotifyProtocol = new Json;
        $giftNotifyProtocol->setData($giftNotify);
        Rpc::instanceEvent('Event:RichSvr.Gift.Send')
            ->setData($giftNotifyProtocol)
            ->send();

        $this->response->setData($giftNotify);
        $this->send();

        GiftManager::onAfterSend($this, $this->gid, $categoryId);

        $this->tradeLog('送礼完成');

        return;

        //前20名排名变更时发送透传事件
        //            Log::alert('Gateway.Event.RankStatus.Chg:...' . $rankNew . '....' . $rank);
        //
        //            if ($rankNew <= 20 && $rankNew != $rank) {
        //                $rankData = [
        //                    'rank' => $rankNew,
        //                    'user' => [
        //                        'uid'       => $uid,
        //                        'no'        => (int)$user['no'],
        //                        'nickname'  => $user['nickname'],
        //                        'gender'    => (int)$user['gender'],
        //                        'authed'    => (int)$user['authed'],
        //                        'verified'  => (int)$user['verified'],
        //                        'portrait'  => $user['portrait'],
        //                        'status'    => (int)$user['status'],
        //                        'exp'       => (int)$user['exp'],
        //                        'starlight' => (int)$user['starlight'],
        //                        'diamond'   => (int)$user['diamond'],
        //                        'fans'      => (int)$user['fans'],
        //                        'follows'   => (int)$user['follows'],
        //                        'location'  => (int)$user['location'],
        //                        'level'     => (int)$user['level'],
        //                    ]
        //                ];
        //
        //                Log::alert('Gateway.Event.RankStatus.Chg:...' . json_encode($rankData));
        //
        //                //发透传协议.....
        //                $targetSvc = crc32('Gateway.Event.RankStatus.Chg');
        //                $targetSvc = unpack('iid', pack('N', $targetSvc))['id'];
        //                $transData = [
        //                    'roomId'    => $roomId,
        //                    'targetSvc' => $targetSvc,
        //                    'body'      => json_encode($rankData)
        //                ];
        //                $req = new Json;
        //                $req->setData($transData);
        //                Rpc::instance('Gateway.Deliver.Msg')
        //                    ->setBroadcast()
        //                    ->setData($req)
        //                    ->send();
        //            }

//            if ($level > 32) {
        //                if ($type) {
        //                    // 升级广播
        //                    $levelData = [
        //                        'type'        => $type,
        //                        'uid'         => $uid,
        //                        'percent'     => 0,
        //                        'roomId'      => $roomId,
        //                        'needDiamond' => 0,
        //                        'nextLevel'   => $level + 1,
        //                        'portrait'    => $user['portrait'],
        //                        'nickname'    => $user['nickname'],
        //                        'level'       => $level
        //                    ];
        //                    $levelReq = new Json;
        //                    $levelReq->setData($levelData);
        //                    Rpc::instance('Gateway.UserLevelUp.Notify')
        //                        ->setData($levelReq)
        //                        ->send();
        //
        //                    Log::alert('Gateway.UserLevelUp.Notify:...' . json_encode($levelData));
        //                } else {
        //                    // 升级提醒
        //                    $nextExp = UserLevel::getNextExp($nowExp);
        //                    // 下一等级需要的总经验
        //                    $levelExp = UserLevel::getLevelNeedExp($level + 1);
        //                    $percent = floor(100 * (($levelExp - $nextExp) / $levelExp));
        //                    if ($percent >= 90) {
        //                        $needDiamond = (int)ceil($nextExp / 10);
        //                        if ($needDiamond <= 10000) {
        //                            $levelData = [
        //                                'type'        => $type,
        //                                'uid'         => $uid,
        //                                'percent'     => $percent,
        //                                'roomId'      => $roomId,
        //                                'needDiamond' => $needDiamond,
        //                                'nextLevel'   => $level + 1,
        //                                'portrait'    => $user['portrait'],
        //                                'nickname'    => $user['nickname'],
        //                                'level'       => $level
        //                            ];
        //                            $levelReq = new Json;
        //                            $levelReq->setData($levelData);
        //                            Rpc::instance('Gateway.UserLevelUp.Notify')
        //                                ->setData($levelReq)
        //                                ->send();
        //                        }
        //                    }
        //                }
        //            }
        // } catch (\Exception $e) {
        //     Log::error('送礼发生错误......' . $e->getMessage());
        //     db('rich')->rollBack();

        //     return $this->send(static::ERROR_DB);
        // }
        //生成Ａ流水号
        //生成Ｂ的流水号
        //经验操作。。。
        //属性操作
    }

    /**
     * 触发更新事件
     *
     * @param array $user
     * @param array $data
     */
    private function InfoUpdateEmit(array $user, array $data)
    {
        // 判断字段是否存在,并是否需要通知更新
        foreach ((array)$data as $key => $item) {
            if (!(isset($user[$key]) && $user[$key] != $item)) {
                unset($data[$key]);
            }
        }

        // 通知更新
        if (!empty($data)) {
            $user = array_merge($user, $data);
            $this->sendEmit($user);
        }
    }

    /**
     * 发送更新
     *
     * @param $user
     */
    private function sendEmit($user)
    {
        $event = new Json;
        $event->setData([
            'uid'          => (int)$user['uid'],
            'no'           => (int)$user['no'],
            'portrait'     => $user['portrait'],
            'nickname'     => $user['nickname'],
            'gender'       => (int)$user['gender'],
            'authed'       => (int)$user['authed'],
            'status'       => (int)$user['status'],
            'weibo'        => $user['weibo'],
            'description'  => $user['description'],
            'birth'        => $user['birth'],
            'emotion'      => (int)$user['emotion'],
            'city'         => (int)$user['city'],
            'location'     => $user['location'],
            'level'        => (int)$user['level'],
            'profession'   => $user['profession'],
            'verifiedInfo' => $user['verifiedInfo'],
            'verified'     => (int)$user['verified'],
        ]);
        Rpc::instanceEvent('Event:UserSvr.Info.Update')
            ->setData($event)
            ->send();
    }
}
