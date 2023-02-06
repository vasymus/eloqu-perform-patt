<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomersController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = User::query()->where('first_name', 'Sarah')->where('last_name', 'Seller')->first();
        Auth::login($user);

        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        $customers = Customer::query()
            ->visibleTo($authUser)
            ->with('salesRep')
            ->orderBy('name')
            ->paginate();

        return view('customers', ['customers' => $customers]);
    }
}
