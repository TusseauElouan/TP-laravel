<?php

namespace App\Models;

use Database\Factories\AbsenceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $motif_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Motif|null $Motifs
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $Users
 * @property-read int|null $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Absences newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absences newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absences query()
 * @method static \Illuminate\Database\Eloquent\Builder|Absences whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absences whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absences whereMotifId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absences whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absences whereUserId($value)
 *
 * @property int $user_id_salarie
 * @property int $is_deleted
 * @property string $date_absence_debut
 * @property string $date_absence_fin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Motif> $Motif
 * @property-read int|null $motif_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $User
 * @property-read int|null $user_count
 *
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
 *
 * @property-read Motif|null $motif
 * @property-read \App\Models\User|null $user
 * @property bool $isValidated
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereIsValidated($value)
 *
 * @property int $is_deleted
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Absence whereIsDeleted($value)
 *
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
