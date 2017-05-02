<?php namespace App\Models;

use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Zizaco\Entrust\EntrustRole;


/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $perms
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends EntrustRole
{

    /**
     * @var array $fillable
     */
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * @param string $name
     * @return Role
     * @throws RoleDoesNotExist
     */
    public static function findByName($name)
    {
        $permission = static::whereName($name)->first();

        if (!$permission) {
            throw new RoleDoesNotExist();
        }

        return $permission;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.providers.users.model'), Config::get('entrust.role_user_table'), Config::get('entrust.role_foreign_key'), Config::get('entrust.user_foreign_key'));
    }
}