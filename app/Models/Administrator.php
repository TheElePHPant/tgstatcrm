<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator as BaseAdministrator;

class Administrator extends BaseAdministrator
{

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'token_2fa_expires' => 'datetime',
    ];

    public function channels()
    {
        return $this->belongsToMany(Channel::class);
    }
}
