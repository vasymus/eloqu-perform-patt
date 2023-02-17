<?php

namespace App\Models\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class StoreQueryBuilder extends Builder
{
    /**
     * @param float[] $coordinates
     *
     * @return $this
     */
    public function selectDistanceTo(array $coordinates)
    {
        if (is_null($this->getQuery()->columns)) {
            $this->select('*');
        }

        return $this->selectRaw('ST_Distance(
                location,
                ST_SRID(Point(?, ?), 4326)
            ) as distance', $coordinates);
    }

    /**
     * @param float[] $coordinates
     * @param int $distance
     *
     * @return $this
     */
    public function withinDistanceTo(array $coordinates, int $distance)
    {
        return $this->whereRaw('ST_Distance(
                location,
                ST_SRID(Point(?, ?), 4326)
            ) <= ?', [...$coordinates, $distance]);
    }

    /**
     * @param float[] $coordinates
     * @param string $direction
     *
     * @return $this
     */
    public function orderByDistanceTo(array $coordinates, string $direction = 'asc')
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        return $this->orderByRaw('ST_Distance(
                location,
                ST_SRID(Point(?, ?), 4326)
            ) '.$direction, $coordinates);
    }
}
