<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'price', 'duration_days', 'features'])]
class SubscriptionPlan extends Model
{
    protected function casts(): array
    {
        return [
            'features' => 'array',
        ];
    }
}
