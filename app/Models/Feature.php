<?php

namespace App\Models;

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
}
