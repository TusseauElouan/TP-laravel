<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeAccess extends Model
{
    protected $table = 'time_access';

    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'is_active' => true,  // Valeur par défaut
        'start_time' => '08:00',
        'end_time' => '18:00'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
