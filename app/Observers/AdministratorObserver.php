<?php

namespace App\Observers;

use App\Models\Administrator;

class AdministratorObserver
{

    public function saved(Administrator $administrator)
    {
//        $channels = request('channels');
//        if(null!==$channels) {
//            $channels = array_filter($channels);
//            $administrator->channels()->sync($channels);
//        }
    }

    public function created(Administrator $administrator)
    {

    }

    public function updated(Administrator $administrator)
    {
    }

    public function deleted(Administrator $administrator)
    {
    }

    public function restored(Administrator $administrator)
    {
    }

    public function forceDeleted(Administrator $administrator)
    {
    }
}
