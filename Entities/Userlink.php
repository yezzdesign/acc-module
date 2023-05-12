<?php

namespace Modules\Acc\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userlink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'link_name',
        'link_address',
        'link_icon',
    ];

    protected static function newFactory()
    {
        return \Modules\Acc\Database\factories\UserlinkFactory::new();
    }
}
