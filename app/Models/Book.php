<?php

namespace App\Models;

use App\Models\Pivots\Checkout;
use App\Models\QueryBuilders\BookQueryBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $author
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \App\Models\QueryBuilders\BookQueryBuilder query()
 */
class Book extends Model
{
    public const TABLE = 'books';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'checkouts')
            ->using(Checkout::class)
            ->withPivot('borrowed_date');
    }

    public function lastCheckout()
    {
        return $this->belongsTo(Checkout::class);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \App\Models\QueryBuilders\BookQueryBuilder<\App\Models\Book>
     */
    public function newEloquentBuilder($query): BookQueryBuilder
    {
        return new BookQueryBuilder($query);
    }
}
