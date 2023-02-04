<?php

namespace App\Models\QueryBuilders;

use App\Models\Company;
use App\Models\Login;
use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    public function withLastLoginAt()
    {
        return $this
            ->addSelect([
                'last_login_at' => Login::query()->select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1)
            ])
            ->withCasts(['last_login_at' => 'datetime']);
    }

    public function withLastLoginIpAddress()
    {
        return $this
            ->addSelect([
                'last_login_ip_address' => Login::query()->select('ip_address')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1)
            ]);
    }

    public function withLastLogin()
    {
        return $this
            ->addSelect([
                'last_login_id' => Login::query()->select('id')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1)
            ])
            ->with('lastLogin');
    }

    public function search(string $terms = null)
    {
//        $this->join('companies', 'companies.id', '=', 'users.company_id');

        collect(
            // explode(' ', $terms)
            str_getcsv($terms, ' ', '"') // bill gates "micr corp" --> ['bill', 'gates', 'micr corp']
        )->filter()->each(function ($term) {

//            $term = '%'.$term.'%';

            $term = $term.'%'; // mysql do not use index when like starts with %

            $this->where(function (UserQueryBuilder $query) use ($term) {
                $query
                    ->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)
//                    ->orWhereHas('company', function ($query) use ($term) {
//                        $query->where('name', 'like', $term);
//                    })

//                    ->orWhere('companies.name', 'like', $term)

//                    ->orWhereIn('company_id', function(\Illuminate\Database\Query\Builder $query) use($term) {
//                        $query
//                            ->select('id')
//                            ->from('companies')
//                            ->where('name', 'like', $term);
//                    }) // fast, but there could be faster

                    ->orWhereIn('company_id', Company::query()->where('name', 'like', $term)->pluck('id')) // the fastest way
                ;
            });
        });

        return $this;
    }
}
