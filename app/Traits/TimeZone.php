<?php


namespace App\Traits;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;
trait TimeZone
{
    public $tz = 'Europe/Kiev';

    public function getCreatedAtAttribute($value)
    {
        $date = new Carbon($value);
        $date->setTimezone(new CarbonTimeZone($this->tz));

        return $date;
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = new Carbon($value);
        $date->setTimezone(new CarbonTimeZone($this->tz));
        return $date;
    }
}
