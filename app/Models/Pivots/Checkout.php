<?php

namespace App\Models\Pivots;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property \Illuminate\Support\Carbon $borrowed_date
 */
class Checkout extends Pivot
{
    public const TABLE = 'checkouts';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'borrowed_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
