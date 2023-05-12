<?php

namespace Modules\Acc\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_state',
        'app_name',
        'app_name_backend',
    ];

    protected static function newFactory()
    {
        return \Modules\Acc\Database\factories\SettingFactory::new();
    }
}
