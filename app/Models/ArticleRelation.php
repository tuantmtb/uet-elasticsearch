<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\ArticleRelation
 *
 * @property int $id
 * @property int $cite_id
 * @property int $cited_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ArticleRelation whereCiteId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ArticleRelation whereCitedId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ArticleRelation whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ArticleRelation whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\ArticleRelation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ArticleRelation extends Model
{
    protected $table = "articles_relations";

}
