<?php

namespace App\Models;

use App\Models\QueryBuilders\CustomerQueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sales_rep_id
 * @property string $name
 * @property string $city
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @see \App\Models\Customer::salesRep()
 * @property \App\Models\User $salesRep
 *
 * @method static \App\Models\QueryBuilders\CustomerQueryBuilder query()
 */
class Customer extends Model
{
    public const TABLE = 'customers';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    public function salesRep()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \App\Models\QueryBuilders\CustomerQueryBuilder<\App\Models\Customer>
     */
    public function newEloquentBuilder($query): CustomerQueryBuilder
    {
        return new CustomerQueryBuilder($query);
    }
}
