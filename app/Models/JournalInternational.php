<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;


/**
 * App\Models\JournalInternational
 *
 * @property int $id
 * @property string $title
 * @property string $publisher
 * @property string $issn
 * @property string $eissn
 * @property string $country
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereEissn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereIssn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational wherePublisher($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\JournalInternational whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JournalInternational extends Model
{
    /**
     * @var string
     */
    protected $table = "journals_international";
    /**
     * @var array
     */
    protected $fillable = ['id', 'title', 'publisher',
        'issn', 'eissn', 'country', 'type',
        'created_at', 'updated_at'];

    
}
