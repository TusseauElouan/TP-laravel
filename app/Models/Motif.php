<?php

namespace App\Models;

use Database\Factories\MotifFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $libelle
 * @property bool $is_accessible_salarie
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Motif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif query()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereIsAccessibleSalarie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereUpdatedAt($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Motif> $Absences
 * @property-read int|null $absences_count
 *
 * @method static \Database\Factories\MotifFactory factory($count = null, $state = [])
 *
 * @property-read \App\Models\Absence|null $Absence
 *
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @property-read int|null $absence_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Motif onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Motif extends Model
{
    /** @use HasFactory<MotifFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Summary of getToutMotif
     *
     * @return Collection<int, Motif>
     */
    public function getToutMotif()
    {
        return Motif::all();
    }

    /**
     * Summary of Absence
     *
     * @return HasMany<Absence>
     */
    public function Absence()
    {
        return $this->hasMany(Absence::class);
    }

    /**
     * Summary of casts
     *
     * @return array<string>
     */
    protected function casts(): array
    {
        return [
            'is_accessible_salarie' => 'boolean',
        ];
    }
}
