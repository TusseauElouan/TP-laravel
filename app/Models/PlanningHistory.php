<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlanningHistory query()
 * @mixin \Eloquent
 */
class PlanningHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'action_type',
        'user_id',
        'details'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
