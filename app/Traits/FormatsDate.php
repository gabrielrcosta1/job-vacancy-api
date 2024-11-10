<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsDate
{

    public function formatDate(Carbon|string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }
}
