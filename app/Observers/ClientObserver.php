<?php

namespace App\Observers;

use App\Models\Client;
use danog\MadelineProto\auth;

class ClientObserver
{
    public function creating(Client $client) {
        $client->administrator_id = auth('admin')->id();
    }
    public function created(Client $client)
    {

    }

    public function updated(Client $client)
    {
    }

    public function deleted(Client $client)
    {
    }

    public function restored(Client $client)
    {
    }

    public function forceDeleted(Client $client)
    {
    }
}
