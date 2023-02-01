<?php

namespace App\Models;


use App\Enums\TransactionType;
use App\Traits\TimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Channel extends Model
{
    use TimeZone;

    protected $appends = [
        'today_subscribers',
        'total_subscribers',
        'daily_profit',
        'daily_consumption',
    ];

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
    }
    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function consumptions() {
        return $this->transactions()->where('type', TransactionType::CONSUMPTION->value);
    }
    public function profit() {
        return $this->transactions()->where('type', TransactionType::PROFIT->value);
    }
    public function day_profit() {
        $date = date('Y-m-d');
        return $this->profit()->whereRaw('DATE(created_at) = ?', $date);
    }
    public function day_consumption() {
        $date = date('Y-m-d');
        return $this->consumptions()->whereRaw('DATE(created_at) = ?', $date);
    }

    public function daily_subscribers(): HasOne
    {
        $date = date('Y-m-d');

        return $this->hasOne(Stat::class)->orderBy('created_at', 'desc')
            ->whereRaw('DATE(stats.created_at) = DATE(NOW())');
    }

    public function all_time_subscribers(): HasOne
    {
        return $this->hasOne(Stat::class)->latestOfMany();
    }

    public function todaySubscribers(): Attribute
    {
        return Attribute::make(
            get: fn() => $this?->daily_subscribers?->day_subscribers??0,
        );
    }

    public function totalSubscribers(): Attribute
    {
        return Attribute::make(
            get: fn() => $this?->all_time_subscribers?->total_subscribers??0,
        );
    }





    public function dailyProfit() : Attribute
    {
        $date = date('Y-m-d');
        return Attribute::make(
            get: fn()=>$this->day_profit->sum('amount'),
        );
    }

    public function dailyConsumption() : Attribute
    {
        return Attribute::make(
            get: fn()=>$this->day_consumption->sum('amount')
        );
    }

    public function totalConsumption() : Attribute
    {
        return Attribute::make(
            get: fn()=> 0
        );
    }
    public function totalProfit() : Attribute
    {
        return Attribute::make(
            get: fn()=> $this->profit()->sum('amount'),
        );
    }

}
