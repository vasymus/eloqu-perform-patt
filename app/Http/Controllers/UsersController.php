<?php

namespace App\Http\Controllers;

use App\Models\User;

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

            ->search(request('search'))

            ->paginate();

        return view('users', ['users' => $users]);
    }
}
