<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;


/**
 * App\Models\HistoryOrganization
 *
 * @property int $id
 * @property int $org_from
 * @property int $org_to
 * @property string $action
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereOrgFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereOrgTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\HistoryOrganization whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HistoryOrganization extends Model
{
    /**
     * @var string
     */
    protected $table = "histories_organization";
    /**
     * @var array
     */
    protected $fillable = ['id', 'org_from', 'org_to',
        'action', 'description',
        'created_at', 'updated_at'];


}
