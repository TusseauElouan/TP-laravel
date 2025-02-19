<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property-read \App\Models\Motif|null $motif
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ColorPreference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ColorPreference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ColorPreference query()
 * @mixin \Eloquent
 */
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
