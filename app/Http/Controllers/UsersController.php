<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\QueryBuilders\UserQueryBuilder;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('company') // without -- n+1 queries
            ->orderBy('last_name') // without indexing slower
//            ->with('logins') // bad decision, because all Login models are uploaded

//            ->withLastLoginAt() // one of the best solution // despite subquery is query, it's executed on mysql and very optimized
//            ->withLastLoginIpAddress() // good decision but there could be dynamic relationship

            ->withLastLogin() // very good solution, because also upload dynamic relationship // can't lazy load dynamic relationship

            ->search2(request('search'))

            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function index2()
    {
        $users = User::query()
            // slower without compound indexes, because ordering like this not use indexes, created separately on last_name / first_name
            ->orderBy('last_name')
            ->orderBy('first_name')

            ->with('company')
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function index3()
    {
        // ordering by belongs-to / has-one relationship

        $users = User::query()
            ->select('users.*')

            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->orderBy('companies.name')

            // order by subquery is much slower
//            ->orderBy(
//                Company::query()->select('name')
//                    ->whereColumn('id', 'users.company_id')
//                    ->orderBy('name')
//                    ->take(1)
//            )
            ->with('company')
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function index4()
    {
        // ordering by has many relationship

        $users = User::query()
            ->with('company') // without -- n+1 queries


            // join approach is slower then subquery approach -- see ->orderByLastLogin()
//            ->select(['users.*'])
//            ->join('logins', 'logins.user_id', '=', 'users.id')
//            ->groupBy('users.id')
//            ->orderByRaw('max(logins.created_at) desc')

            // this approach is faster
            ->orderByLastLogin() // ordering by belongs-to relations

            ->withLastLogin() // very good solution, because also upload dynamic relationship // can't lazy load dynamic relationship
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function index5()
    {
        $users = User::query()
            ->with('company')
            ->when(request('sort') === 'town', function (UserQueryBuilder $query) {
                /** @see \App\Providers\AppServiceProvider::boot() */
                $query->orderByNullsLast('town', request('direction'));

//                $query
//                    ->orderByRaw('town is null')
//                    ->orderBy('town', request('direction'));

            })
            ->orderBy('last_name')
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function index6()
    {
        $users = User::query()
            ->with('company')
            ->whereBirthdayThisWeek()
            ->orderByBirthday()
            // ->orderByUpcomingBirthdays()
            ->orderBy('last_name')
            ->paginate();

        return view('users', ['users' => $users]);
    }
}
