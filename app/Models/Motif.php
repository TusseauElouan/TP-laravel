<?php

namespace App\Models;

use Database\Factories\MotifFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $libelle
 * @property bool $is_accessible_salarie
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $is_deleted
 *
 * @property-read Collection<int, \App\Models\Absence> $Absence
 * @property-read int|null $absence_count
 *
 * @method static \Database\Factories\MotifFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Motif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif query()
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereIsAccessibleSalarie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motif whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Motif extends Model
{
    /** @use HasFactory<MotifFactory> */
    use HasFactory;

    /**
     * Summary of getMotifsCache
     *
     * @return Collection<int, Motif>
     */
    public function getMotifsCache()
    {
        return cache()->remember('motifs', 60 * 60 * 24, function () {
            return Motif::all();
        });
    }

    /**
     * Summary of Absence
     *
     * @return HasMany<Absence>
     */
    public function absence()
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
            'is_deleted' => 'boolean',
        ];
    }
}
