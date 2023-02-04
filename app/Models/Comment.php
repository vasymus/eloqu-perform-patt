<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $feature_id
 * @property int $user_id
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $update_at
 *
 * @see \App\Models\Comment::feature()
 * @property \App\Models\Feature $feature
 *
 * @see \App\Models\Comment::user()
 * @property \App\Models\User $user
 */
class Comment extends Model
{
    public const TABLE = 'comments';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isAuthor()
    {
        return $this->feature->comments->first()->user_id === $this->user_id;
    }
}
