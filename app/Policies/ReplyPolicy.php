<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }

    public function showdel(User $user, Reply $reply, Topic $topic)
    {
        return $user->isAuthorOf($reply) || $user->isAuthorOf($topic);
    }
}
