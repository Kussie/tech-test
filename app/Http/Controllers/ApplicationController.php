<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationCollectionResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ApplicationController extends Controller
{
    public function index(ApplicationRequest $request): AnonymousResourceCollection
    {
        $perPage = ($request->has('per_page') ? $request->per_page : 15);

        $applications = Application::query()
            ->when(!empty(request('plan')), function ($query) {
                return $query->whereRelation('plan', 'type', request('plan') );
            })
            ->with(['customer'])
            ->orderBy('updated_at')
            ->paginate($perPage);

        return ApplicationCollectionResource::collection($applications);
    }

    public function show(Request $request, Application $application): ApplicationResource
    {
        $application->load(['plan']);
        return new ApplicationResource($application);
    }
}
