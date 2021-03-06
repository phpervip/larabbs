<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id','excerpt', 'slug'];

    public function category(){
        return $this->BelongsTo(Category::class);
    }

    public function user(){
        return $this->BelongsTo(User::class);
    }

    public function scopeWithOrder($query, $order){
        switch($order){
            case 'recent':
                $query->recent($query);
                break;
            default:
                $query->recentReplied($query);
                break;
        }
        return $query->with('user','category');
    }

    public function scopeRecentReplied($query)
    {
        return $query->orderBy('updated_at','desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at','desc');
    }

    public function link($params = [])
    {
        return route('topics.show',array_merge([$this->id,$this->slug],$params));
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function updateReplyCount()
    {
        $this->replay_count = $this->replies->count();
        $this->save();
    }









}
