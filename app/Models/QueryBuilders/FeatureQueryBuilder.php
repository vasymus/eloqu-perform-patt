<?php

namespace App\Models\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class FeatureQueryBuilder extends Builder
{
    public function orderByStatus(string $direction)
    {
        return $this->orderBy(DB::raw("
            case
                when status = 'Requested' then 1
                when status = 'Planned' then 2
                when status = 'Completed' then 3
            end
        "), $direction);
    }

    public function orderByActivity(string $direction)
    {
        return $this->orderBy(
            DB::raw('-(votes_count + (comments_count * 2))'),
            $direction
        );
    }
}
