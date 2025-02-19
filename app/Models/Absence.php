<?php

namespace App\Models;

use Database\Factories\AbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 
 *
 * @property int $id
 * @property int $motif_id
 * @property int $user_id_salarie
 * @property int $is_deleted
 * @property string $date_absence_debut
 * @property string $date_absence_fin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $isValidated
 * @property-read \App\Models\Motif|null $motif
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AbsenceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Absence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absence query()
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereDateAbsenceDebut($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereDateAbsenceFin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereIsValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereMotifId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereUserIdSalarie($value)
 * @mixin \Eloquent
 */
class Absence extends Model
{
    /** @use HasFactory<AbsenceFactory>  */
    use HasFactory;

    /**
     * Summary of motif
     *
     * @return BelongsTo<Motif, Absence>
     */
    public function motif()
    {
        return $this->belongsTo(Motif::class);
    }

    /**
     * Summary of user
     *
     * @return BelongsTo<User, Absence>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id_salarie');
    }

    /**
     * Summary of casts
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'isValidated' => 'boolean',
        ];
    }
}
