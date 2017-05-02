<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;


/**
 * App\Models\Config
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereValue($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    protected $table = "vci_configs";
    protected $fillable = ['key', 'id', 'value'];


}
