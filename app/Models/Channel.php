<?php

namespace App\Models;


use App\Traits\TimeZone;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Channel extends Model
{
    use TimeZone;

    protected $appends = ['today_subscribers', 'total_subscribers', 'profit', 'consumption'];

    public function stats(): HasMany
    {
        return $this->hasMany(Stat::class);
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
            get: fn() => $this->daily_subscribers->day_subscribers,
        );
    }

    public function totalSubscribers(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->all_time_subscribers->total_subscribers,
        );
    }

    public function daily_transaction(): HasOne
    {
        return $this->hasOne(Transaction::class)->whereRaw('date = DATE(NOW())')->withDefault([
            'profit' => 0.00,
            'consumption' => 0.00,
        ]);
    }

    public function profit() : Attribute
    {
        return Attribute::make(
            get: fn()=>$this->daily_transaction->profit,
        );
    }

    public function consumption() : Attribute
    {
        return Attribute::make(
            get: fn()=>$this->daily_transaction->consumption,
        );
    }
}
