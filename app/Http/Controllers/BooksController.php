<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Pivots\Checkout;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class BooksController extends Controller
{
    public function index()
    {
        $books = Book::query()
            // ordering by latest borrowed date via join
//            ->select('books.*')
//            ->join('checkouts', 'checkouts.book_id', '=', 'books.id')
//            ->groupBy('books.id')
//            ->orderByRaw('max(checkouts.borrowed_date) desc')

            // ordering by latest borrowed date via subquery 1
//            ->orderByDesc(
//                Checkout::query()
//                    ->select('borrowed_date')
//                    ->whereColumn('book_id', 'books.id')
//                    ->latest('borrowed_date')
//                    ->take(1)
//            )

            // ordering by latest borrowed date via subquery 2
//            ->orderByDesc(function(Builder $query) {
//                $query
//                    ->select('borrowed_date')
//                    ->from('checkouts')
//                    ->whereColumn('book_id', 'books.id')
//                    ->latest('borrowed_date')
//                    ->take(1);
//            })

            // ordering by name of user who last borrowed book
            ->orderBy(
                User::query()
                    ->select('last_name')
                    ->join('checkouts', 'checkouts.user_id', '=', 'users.id')
                    ->whereColumn('checkouts.book_id', 'books.id')
                    ->latest('checkouts.borrowed_date')
                    ->take(1)
            )
            // as an alternative -- denormalisation -- add `books`.`last_checkout_id` column (for caching) if too many data

            ->withLastCheckout()
            ->with('lastCheckout.user')
            ->paginate();

        return view('books', ['books' => $books]);
    }

    public function index2()
    {
        $books = Book::query()
            ->with('user')
//            ->orderByDesc('user_id') // in this case the one with user_id is not null not ordered by name [not sure why]
            ->orderByRaw('user_id is null') // this is the correct way
            ->orderBy('name')
            ->paginate();

        return view('books', ['books' => $books]);
    }
}
