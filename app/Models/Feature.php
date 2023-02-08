<?php

namespace App\Models;

use App\Models\QueryBuilders\FeatureQueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $update_at
 *
 * @see \App\Models\Feature::comments()
 * @property \App\Models\Comment[]|\Illuminate\Database\Eloquent\Collection $comments
 *
 * @method static \App\Models\QueryBuilders\FeatureQueryBuilder query()
 */
class Feature extends Model
{
    public const TABLE = 'features';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \App\Models\QueryBuilders\FeatureQueryBuilder<\App\Models\Feature>
     */
    public function newEloquentBuilder($query): FeatureQueryBuilder
    {
        return new FeatureQueryBuilder($query);
    }
}
