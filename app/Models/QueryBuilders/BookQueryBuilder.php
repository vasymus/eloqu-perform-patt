<?php

namespace App\Models\QueryBuilders;

use App\Models\Pivots\Checkout;
use Illuminate\Database\Eloquent\Builder;

class BookQueryBuilder extends Builder
{
    public function withLastCheckout()
    {
        return $this->addSelect([
            'last_checkout_id' => Checkout::query()
                ->select('checkouts.id')
                ->whereColumn('book_id', 'books.id')
                ->latest('borrowed_date')
                ->limit(1),
        ])->with('lastCheckout');
    }
}
