<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

/**
 * App\Models\Article
 *
 * @property int $id
 * @property string $title
 * @property string $abstract
 * @property string $author
 * @property string $volume
 * @property string $number
 * @property string $year
 * @property string $uri
 * @property string $source
 * @property string $usable
 * @property string $reference
 * @property string $titleOnGoogle
 * @property string $cluster_id
 * @property string $cites_id
 * @property int $cites_count
 * @property bool $is_reviewed
 * @property int $journal_id
 * @property int $editor_id
 * @property string $language
 * @property string $keyword
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $citation_raw
 * @property string $citation_status
 * @property int $num_citation_reviewed
 * @property string $citation_raw_reviewed
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Author[] $authors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $citeds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Article[] $cites
 * @property-read \App\Models\User $editor
 * @property-read \App\Models\Journal $journal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereAbstract($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCitationRaw($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCitationRawReviewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCitationStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCitesCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCitesId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereClusterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereEditorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereIsReviewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereJournalId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereKeyword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereNumCitationReviewed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereReference($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereSource($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereTitleOnGoogle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereUri($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereUsable($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereVolume($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereYear($value)
 * @mixin \Eloquent
 * @property string $mla
 * @property string $apa
 * @property string $chicago
 * @property string $harvard
 * @property string $vancouver
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereApa($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereChicago($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereHarvard($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereMla($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Article whereVancouver($value)
 */
class Article extends Model
{
    /**
     * @var string
     */
    protected $table = "articles";
    /**
     * @var array
     */
    protected $fillable = ['id', 'title', 'author', 'volume', 'number', 'year', 'uri', 'titleOnGoogle', 'cluster_id', 'cites_id', 'cites_count',
        'abstract', 'source', 'usable', 'reference', 'mla', 'apa', 'chicago', 'havard', 'vancouver', 'journal_id', 'editor_id', 'language', 'is_reviewed', 'keyword',
        'citation_raw', 'num_citation_reviewed', 'citation_raw_reviewed', 'doi',
        'created_at', 'updated_at'];

    /**
     * Những bài mà cited được những bài khác trích dẫn đến
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cites()
    {
        return $this->belongsToMany('App\Models\Article', 'articles_relations', 'cite_id', 'cited_id')->withTimestamps();
    }

    /**
     * Những bài mà nó trích dẫn đến
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function citeds()
    {
        return $this->belongsToMany('App\Models\Article', 'articles_relations', 'cited_id', 'cite_id')->withTimestamps();
    }

    /**
     * $this citation $article_cited
     * @param Article $article_cited
     */
    public function assignCitation($article_cited)
    {
        $this->cites()->attach($article_cited);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function authors()
    {
        return $this->belongsToMany('App\Models\Author', 'articles_authors', 'article_id', 'author_id')->withTimestamps();
    }


    /**
     * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array $ids
     * @return array
     */
    public function syncAuthors($ids)
    {
        return $this->authors()->sync($ids);
    }

    /**
     * Assign author
     * @param $author
     */
    public function assignAuthor($author)
    {
        $this->authors()->attach($author);
    }


    /**
     * Article - journal relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function journal()
    {
        return $this->belongsTo('\App\Models\Journal', 'journal_id');
    }

    /**
     * @param Journal $journal
     * @return Journal
     */
    public function assignJournal($journal)
    {
        /**
         * @var Journal $model
         */
        $model = $this->journal()->associate($journal);
        return $model;
    }

    /**
     * @return Journal
     */
    public function removeJournal()
    {
        /**
         * @var Journal $model
         */
        $model = $this->journal()->dissociate();
        return $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'users_articles', 'article_id', 'user_id');
    }

    /**
     * user update article
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function editor()
    {
        return $this->belongsTo('\App\Models\User', 'editor_id');
    }

    /**
     * @return Collection
     */
    public function organizes()
    {
        return $this
            ->authors()
            ->getQuery()
            ->has('organizes')
            ->with('organizes')
            ->get()
            ->map(function ($author) {
                /**
                 * @var Author $author
                 */
                return $author->organizes;
            })
            ->collapse()
            ->unique('id');
    }

    public function isReviewed() {
        return $this->is_reviewed === 1;
    }

    public function isNoReviewed() {
        return $this->is_reviewed === 0;
    }

    public function isNonReviewed() {
        return is_null($this->is_reviewed);
    }
}
