<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColorPreference extends Model
{
    protected $fillable = [
        'user_id',
        'motif_id',
        'background_color',
        'text_color',
        'border_color'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function motif(): BelongsTo
    {
        return $this->belongsTo(Motif::class);
    }
}
