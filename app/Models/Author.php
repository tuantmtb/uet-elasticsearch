<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Query\Builder;

/**
 * App\Models\Author
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $articles
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Organize[] $organizes
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Author whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Author extends Model
{
    protected $table = "authors";
    protected $fillable = ["id", "name", "description", "email"];

    /**
     * [articles description]
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany('App\Models\Article', 'articles_authors', 'author_id', 'article_id')->withTimestamps();
    }

    /**
     * Trả về list, nhưng chỉ cần ->first
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function organizes()
    {
        return $this->belongsToMany('App\Models\Organize', 'authors_organizes', 'author_id', 'organize_id');
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array $ids
     * @return array
     */
    public function syncOrganizes($ids) {
        return $this->organizes()->sync($ids);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getOrganizeAndAncestors() {
        if ($this->organizes->isNotEmpty()) {
            return $this->organizes->first()->getAncestorsWithSelf();
        } else {
            return collect();
        }
    }

    public function firstOrganize() {
        return $this->organizes()->first();
    }
}
