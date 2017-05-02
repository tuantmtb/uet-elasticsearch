<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Journal
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property string $website
 * @property string $address
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Subject[] $subjects
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereNameEn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereWebsite($value)
 * @mixin \Eloquent
 * @property string $issn
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Journal whereIssn($value)
 */
class Journal extends Model
{
    protected $table = "journals";
    protected $fillable = ['name', 'id', 'name_en', 'website', 'address', 'description', 'issn', 'proprietor'];

    public function articles()
    {
        return $this->hasMany('\App\Models\Article', 'journal_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects()
    {
        return $this->belongsToMany('\App\Models\Subject', 'journals_subjects');
    }

    /**
     * @param Subject $subject
     */
    public function assignSubject($subject)
    {
        $this->subjects()->attach($subject);
    }

    /**
     * @param Subject $subject
     * @return int
     */
    public function removeSubject($subject)
    {
        return $this->subjects()->detach($subject);
    }

    /**
     * @param Subject[]|Collection $subjects
     * @return array
     */
    public function syncSubjects($subjects)
    {
        return $this->subjects()->sync($subjects);
    }
}
