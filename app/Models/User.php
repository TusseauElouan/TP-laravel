<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 *
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 *
 * @property string $nom
 * @property string $prenom
 *
 * @property-read \App\Models\Absence|null $Absence
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePrenom($value)
 *
 * @property string $adresse
 * @property string $genre
 * @property string $telephone
 * @property string $date_naissance
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDateNaissance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelephone($value)
 *
 * @property bool $isAdmin
 *
 * @property-read int|null $absence_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Silber\Bouncer\Database\Ability> $abilities
 * @property-read int|null $abilities_count
 * @property-read mixed $initiales
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Silber\Bouncer\Database\Role> $roles
 * @property-read int|null $roles_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIs($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAll($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsNot($role)
 *
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /** @use HasRolesAndAbilities */
    use HasRolesAndAbilities;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'adresse',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
     * Summary of getAbsenceCountAttribute
     *
     * @return int
     */
    public function getAbsenceCountAttribute(): int
    {
        return $this->Absence()->count();
    }

    /**
     * Summary of getInitialesAttribute
     *
     * @return string
     */
    public function getInitialesAttribute()
    {
        return ucfirst($this->prenom). ucfirst(strtolower($this->nom));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'isAdmin' => 'boolean',
        ];
    }
}
