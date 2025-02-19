<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JourFerie newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JourFerie newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JourFerie query()
 * @mixin \Eloquent
 */
class JourFerie extends Model
{
    protected $table = 'jours_feries';

    protected $fillable = ['nom', 'date', 'is_recurring'];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];
}
