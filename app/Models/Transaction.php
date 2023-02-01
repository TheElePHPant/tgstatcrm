<?php

namespace App\Models;

use App\Enums\TransactionType;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use LogsActivity;
    protected $appends = ['type_title'];
    protected $guarded = [
        'id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function administrator() {
        return $this->belongsTo(Administrator::class);
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function typeTitle() : Attribute
    {

        return Attribute::make(
            get: fn()=>match($this->type) {
                TransactionType::PROFIT->value => 'Доход',
                TransactionType::CONSUMPTION=>'Расход',
                default=>'Неизвестная операция',
            });
    }
}
