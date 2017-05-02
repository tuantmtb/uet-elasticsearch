<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;

/**
 * App\Models\Organize
 *
 * @property int $id
 * @property string $name
 * @property string $name_en
 * @property string $address
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $_lft
 * @property int $_rgt
 * @property int $parent_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Author[] $authors
 * @property-read \Kalnoy\Nestedset\Collection|\App\Models\Organize[] $children
 * @property-read \App\Models\Organize $parent
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize d()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereLft($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereNameEn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereParentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereRgt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $glink
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Organize whereGlink($value)
 */
class Organize extends Model
{
    use NodeTrait;

    protected $table = "organizes";
    protected $fillable = ['name', 'id', 'name_en', 'address', 'description', NestedSet::PARENT_ID, NestedSet::LFT, NestedSet::RGT];

    public function authors()
    {
        return $this->belongsToMany('\App\Models\Author', 'authors_organizes', 'organize_id', 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function articles() {
        return Article::query()
            ->join('articles_authors', 'articles_authors.article_id', '=', 'articles.id')
            ->join('authors_organizes', 'authors_organizes.author_id', '=', 'articles_authors.author_id')
            ->whereIn('authors_organizes.organize_id', $this->descendants_with_this_ids())
            ->groupBy('articles.id');
    }

    /**
     * @return int
     */
    public function articles_count() {
        return $this->articles()->get()->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function descendants_with_this_ids() {
        return $this->descendants()->getQuery()->pluck('id')->push($this->id);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAncestorsWithSelf() {
        return $this
            ->ancestors()
            ->getQuery()
            ->where('name', '<>', 'Các cơ quan nước ngoài')
            ->get()
            ->push($this);
    }
}
