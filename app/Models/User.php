<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\QueryBuilders\UserQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property int $company_id
 * @property string $first_name
 * @property string $last_name
 * @property int|null $gender
 * @property string|null $photo
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $update_at
 *
 * @see \App\Models\User::getNameAttribute()
 * @property string $name
 *
 * @see \App\Models\User::company()
 * @property \App\Models\Company $company
 *
 * @see \App\Models\User::posts()
 * @property \App\Models\Post[]|\Illuminate\Database\Eloquent\Collection $posts
 *
 * @see \App\Models\User::logins()
 * @property \App\Models\Login[]|\Illuminate\Database\Eloquent\Collection $logins
 *
 * @see \App\Models\QueryBuilders\UserQueryBuilder::withLastLoginAt()
 * @property \Illuminate\Support\Carbon|null $last_login_at
 *
 * @see \App\Models\QueryBuilders\UserQueryBuilder::withLastLoginIpAddress()
 * @property string|null $last_login_ip_address
 *
 * @see \App\Models\QueryBuilders\UserQueryBuilder::withLastLogin()
 * @see \App\Models\User::lastLogin()
 * @property \App\Models\Login|null $lastLogin
 *
 * @method static \App\Models\QueryBuilders\UserQueryBuilder query()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const TABLE = 'users';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getNameAttribute(): string
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    /**
     * $this->hasOne(Login::class)->latest() is not working properly [memory usages or n+1 issue]
     * $this->hasOne(Login::class)->latest()->limit(1) is not working properly [retrieved only for one User model]
     *
     * @see \App\Models\QueryBuilders\UserQueryBuilder::withLastLogin()
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastLogin()
    {
        return $this->belongsTo(Login::class, 'last_login_id', 'id');
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     *
     * @return \App\Models\QueryBuilders\UserQueryBuilder<\App\Models\User>
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }
}
