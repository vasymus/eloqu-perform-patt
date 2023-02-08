<?php

namespace App\Models\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class PostQueryBuilder extends Builder
{
    public function addSelectMatchTitleOrBodyAsScore(string $search)
    {
        return $this->selectRaw('match(title, body) against(? in boolean mode) as score', [$search]);
    }

    public function whereMatchTitleOrBody(string $search)
    {
        return $this->whereRaw('match(title, body) against(? in boolean mode)', [$search]);
    }

    public function whereLikeTitleOrBody(string $search)
    {
        return $this->where(function(PostQueryBuilder $q) use ($search) {
            $q
                ->where('title', 'like', "%$search%")
                ->orWhere('body', 'like', "%$search%");
        });
    }
}
