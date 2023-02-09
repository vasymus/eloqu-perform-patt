<?php

namespace App\Models\QueryBuilders;

use App\Models\Company;
use App\Models\Login;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
        )->filter()->each(function (string $term) {

//            $term = '%'.$term.'%';

            $term = $term.'%'; // mysql do not use index when like starts with %

            $this->where(function (UserQueryBuilder $query) use ($term) {
                $query
                    ->where('first_name', 'like', $term)
                    ->orWhere('last_name', 'like', $term)

//                    ->orWhereHas('company', function ($query) use ($term) {
//                        $query->where('name', 'like', $term);
//                    })

//                    ->orWhere('companies.name', 'like', $term) // if has join -- see join query above

//                    ->orWhereIn('company_id', function(QueryBuilder $query) use($term) {
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

    public function search2(string $terms = null)
    {
        collect(str_getcsv($terms, ' ', '"'))->filter()->each(function(string $term) {
            $term = preg_replace('/[^A-Za-z0-9]/', '', $term);
            $term = $term.'%';

            $this->whereIn('id', function(QueryBuilder $query) use($term) {
                $query->select('id')
                    ->from(function(QueryBuilder $query) use($term) {
                        $query
                            ->select('id')
                            ->from('users')
                            // ->where('first_name', 'like', $term)
//                            ->whereRaw('regexp_replace(first_name, "[^A-Za-z0-9]", "") like ?', [$term])
                            ->where('first_name_normalized', 'like', $term)
//                            ->orWhere('last_name', 'like', $term)
//                            ->orWhereRaw('regexp_replace(last_name, "[^A-Za-z0-9]", "") like ?', [$term])
                            ->orWhere('last_name_normalized', 'like', $term)
                            ->union(
                                $query->newQuery()
                                    ->select('users.id')
                                    ->from('users')
                                    ->join('companies', 'companies.id', '=', 'users.company_id')
//                                    ->where('companies.name', 'like', $term)
//                                    ->whereRaw('regexp_replace(companies.name, "[^A-Za-z0-9]", "") like ?', [$term])
                                    ->where('name_normalized', 'like', $term)
                            );
                    }, 'matches');
            });
        });

        return $this;
    }

    public function orderByLastLogin()
    {
        return $this->orderByDesc(
            Login::query()
                ->select('created_at')
                ->whereColumn('user_id', 'users.id')
                ->latest()
                ->take(1)
        );
    }

    public function orderByBirthday()
    {
        return $this->orderByRaw('date_format(birth_date, "%m-%d")');
    }

    public function orderByUpcomingBirthdays()
    {
        return $this->orderByRaw('
                case
                    when (birth_date + interval (year(?) - year(birth_date)) year) >= ?
                    then (birth_date + interval (year(?) - year(birth_date)) year)
                    else (birth_date + interval (year(?) - year(birth_date)) + 1 year)
                end
            ', [
            array_fill(0, 4, Carbon::now()->startOfWeek()->toDateString()),
        ]);
    }

    public function whereBirthdayThisWeek()
    {
//         Carbon::setTestNow(Carbon::parse('January 1, 2020'));

        $dates = Carbon::now()
            ->startOfWeek()
            ->daysUntil(Carbon::now()->endOfWeek())
            ->map(fn ($date) => $date->format('m-d'));

        $this->whereRaw('date_format(birth_date, "%m-%d") in (?,?,?,?,?,?,?)', iterator_to_array($dates));

        return $this;
    }
}
