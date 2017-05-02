<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Subject
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $_lft
 * @property int $_rgt
 * @property int $parent_id
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Subject[] $children
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Journal[] $journals
 * @property-read \App\Models\Subject $parent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject d()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereNameEn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Subject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subject extends Model
{
    use NodeTrait;

    protected $table = 'subjects';

    protected $fillable = ['name', 'name_en', 'parent_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function journals() {
        return $this->belongsToMany('App\Models\Journal', 'journals_subjects');
    }

    /**
     * @param Journal $journal
     */
    public function assignJournal($journal) {
        $this->journals()->attach($journal);
    }

    /**
     * @param Journal $journal
     * @return int
     */
    public function removeJournal($journal) {
        return $this->journals()->detach($journal);
    }

    public function descendants_with_this_ids()
    {
        return $this->descendants()->getQuery()->pluck('id')->push($this->id);
    }
}
