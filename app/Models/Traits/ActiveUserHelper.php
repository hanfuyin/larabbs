<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Cache;
use DB;

trait ActiveUserHelper
{
    //用于存放临时用户的数据
    protected $users = [];

    //配置信息
    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 7;
    protected $user_number = 6;

    //缓存相关配置
    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers()
    {
        //尝试从缓存中取出cache_key 对应的数据。如果能取到,便直接返回数据
        //否则运行匿名函数中的代码取出活跃用户数据,返回的同时做缓存
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function (){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateAndCacheActiveUsers()
    {
        //获取活跃用户列表
        $active_users = $this->calculateActiveUsers();
        //加以缓存
        $this->cacheActiveUsers($active_users);
    }

    private function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        $users = array_sort($this->users, function ($user){
            return $user['score'];
        });

        $users = array_reverse($users, true);

        $users = array_slice($users, 0, $this->user_number, true);

        $active_users = collect();
        foreach ($users as $user_id => $user){
            $user = $this->find($user_id);

            if($user){
                $active_users->push($user);
            }
        }

        return $active_users;
    }

    /**
     * 获取话题表限定时间内有发表过话题的用户数据
     */
    private function calculateTopicScore()
    {
        $topic_users = Topic::query()->select(DB::raw('user_id, count(*) as topic_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();

        foreach ($topic_users as $value){
            $this->users[$value->user_id['score']] = $value->topic_count * $this->topic_weight;
        }
    }

    private function calculateReplyScore()
    {
        $reply_users = Reply::query()->select(DB::raw('user_id, count(*) as reply_count'))
                                     ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
                                     ->groupBy('user_id')
                                     ->get();
        foreach ($reply_users as $value){
            $reply_score = $value->reply_count * $this->reply_weight;
            if(isset($this->users[$value->user_id])){
                $this->users[$value->user_id]['score'] += $reply_score;
            }else{
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    private function cacheActiveUsers($active_users)
    {
        //将数据存入缓存中
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
}