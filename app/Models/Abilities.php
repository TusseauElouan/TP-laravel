<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property int|null $entity_id
 * @property string|null $entity_type
 * @property int $only_owned
 * @property string|null $options
 * @property int|null $scope
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities query()
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereEntityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereOnlyOwned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Abilities whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Abilities extends Model
{
}
