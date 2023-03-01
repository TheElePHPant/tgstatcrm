<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignStats extends Model
{
    public function channel() : BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function consumptions()
    {
        return $this->hasMany(Transaction::class, 'campaign_id', 'id')
            ->where('type', TransactionType::CONSUMPTION->value);
    }
    public function profits()
    {
        return $this->hasMany(Transaction::class, 'campaign_id', 'id')
            ->where('type', TransactionType::PROFIT->value);
    }
}
