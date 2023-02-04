<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $name_normalized
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $update_at
 *
 * @see \App\Models\Company::users()
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection $users
 */
class Company extends Model
{
    public const TABLE = 'companies';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
