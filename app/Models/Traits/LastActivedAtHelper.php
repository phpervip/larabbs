<?php

namespace App\Models\Traits;

use Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        // 获取今天的日期
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2019-8-2
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 字段名称，如：user_1
        $field = $this->getHashField();

        // dd(Redis::hGetAll($hash));

        // 当前时间，如：2019-08-02 15:53:23
        $now = Carbon::now()->toDateTimeString();

        // 数据写入 Redis ，字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }


    public function syncUserActivedAt(){
        // 获取昨天的日期，格式如：2019-8-2
        $hash = $this->getHashFromDateString(Carbon::yesterday()->toDateString());

        // 从 Redis 中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);
        // 遍历，并同步到数据库
        foreach($dates as $user_id=>$actived_at){
            $user_id = str_replace($this->field_prefix, '' , $user_id);

            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }

        }
        // 以数据库为中心的存储，既已同步，即可删除
        Redis::del($hash);

    }

    public function getLastActivedAtAttribute($value)
    {
        // 获取今天对应的
        // Redis 哈希表的命名，如 larabbs_last_actived_at_2018_8_2
        $hash = $this->getHashFromDateString(Carbon::now()->toDateString());

        // 字段名称，如: user_id
        $field = $this->getHashField();

        // 三元运算符，优先选择 Redis 的数据，否则使用数据库中
        $datetime = Redis::hGet($hash, $field)?:$value;

        // 如果存在的话，返回时间对应的 Carbon 实体
        if($datetime){
            return new Carbon($datetime);
        }else{
            // 否则使用用户注册时间
            return $this->created_at;
        }

    }

 public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        return $this->hash_prefix . $date;
    }

    public function getHashField()
    {
        // 字段名称，如：user_1
        return $this->field_prefix . $this->id;
    }

}
