<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    protected $cache_expire_in_minutes = 1440;
    public $cache_key = 'larabbs_links';

    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function(){
            return $this->all();
        });
    }
}
