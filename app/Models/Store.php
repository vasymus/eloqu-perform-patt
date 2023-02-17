<?php

namespace App\Models;

use App\Models\QueryBuilders\StoreQueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $postal
 *
 * @method static \App\Models\QueryBuilders\StoreQueryBuilder query()
 */
class Store extends Model
{
    public const TABLE = 'stores';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \App\Models\QueryBuilders\StoreQueryBuilder<\App\Models\Store>
     */
    public function newEloquentBuilder($query): StoreQueryBuilder
    {
        return new StoreQueryBuilder($query);
    }
}
