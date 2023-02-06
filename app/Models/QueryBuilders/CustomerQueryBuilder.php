<?php

namespace App\Models\QueryBuilders;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class CustomerQueryBuilder extends Builder
{
    public function visibleTo(User $user)
    {
        if (! $user->is_owner) {
            $this->where('sales_rep_id', $user->id);
        }

        return $this;
    }
}
