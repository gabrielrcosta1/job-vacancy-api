<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsDate
{

    public function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }
}
