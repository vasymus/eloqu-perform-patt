<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\QueryBuilders\FeatureQueryBuilder;
use Illuminate\Database\Query\Builder;

class FeaturesController extends Controller
{
    public function index()
    {
        // individuals queries -- not very good approach
//        $requested = Feature::query()->where('status', 'Requested')->count();
//        $planned = Feature::query()->where('status', 'Planned')->count();
//        $completed = Feature::query()->where('status', 'Completed')->count();

        // good approach instead of individuals queries
        $statuses = Feature::query()
            ->toBase()
            ->selectRaw("count(case when status = 'Requested' then 1 end) as requested")
            ->selectRaw("count(case when status = 'Planned' then 1 end) as planned")
            ->selectRaw("count(case when status = 'Completed' then 1 end) as completed")
            ->first();

        $features = Feature::query()
            ->withCount('comments')
            ->paginate();

        return view('features', [
            'statuses' => $statuses,
            'features' => $features,
        ]);
    }

    public function index2()
    {
        $features = Feature::query()
            ->withCount('comments', 'votes')
            ->when(request('sort'), function (FeatureQueryBuilder $query, $sort) {
                switch ($sort) {
                    case 'title': return $query->orderBy('title', request('direction'));
                    case 'status': return $query->orderByStatus(request('direction'));
                    case 'activity': return $query->orderByActivity(request('direction'));
                }
            })
            ->latest()
            ->paginate();

        return view('features2', ['features' => $features]);
    }

    public function show(Feature $feature)
    {
        $feature->load('comments.user');
        $feature->comments->each->setRelation('feature', $feature); // manual set relation so not to make additional load

        return view('feature', ['feature' => $feature]);
    }
}
