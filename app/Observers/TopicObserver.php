<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        //过滤xss
        $topic->body = clean($topic->body, 'user_topic_body');
        //生成话题摘录
        $topic->execrpt = make_excerpt($topic->body);
        //如果slug字段无内容,使用翻译器对title进行翻译
        if(!$topic->slug){
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}