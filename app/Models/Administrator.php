<?php

namespace App\Models;

use Encore\Admin\Auth\Database\Administrator as BaseAdministrator;
class Administrator extends BaseAdministrator
{
    public function channels() {
        return $this->belongsToMany(Channel::class);
    }
}
