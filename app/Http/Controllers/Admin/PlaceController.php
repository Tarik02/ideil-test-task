<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlaceRequest;
use App\Http\Resources\Admin\PlaceCollection;
use App\Http\Resources\Admin\PlaceResource;
use App\Models\Place;
use App\Scopes\VisibleScope;
use App\Services\PlaceService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /** @var PlaceService */
    private $placeService;

    public function __construct(PlaceService $placeService)
    {
        $this->placeService = $placeService;
    }

    public function index(Request $request)
    {
        $places = Place::paginate(
            (int) $request->get('per_page', 10)
        );

        return PlaceResource::collection($places);
    }

    public function store(PlaceRequest $request)
    {
        /** @var Place $place */
        $place = Place::make();
        $this->placeService->updateFromRequest($request, $place);

        return response()->json(['status' => 'ok']);
    }

    public function show(string $slug)
    {
        return PlaceResource::make(
            Place::where('slug', $slug)
                ->with([
                    'comments' => function (HasMany $builder) {
                        $builder->withoutGlobalScope(VisibleScope::class);
                    },
                    'fields',
                    'media',
                ])
                ->firstOrFail()
        );
    }

    public function update(PlaceRequest $request, string $slug)
    {
        /** @var Place $place */
        $place = Place::where('slug', $slug)->firstOrFail();
        $this->placeService->updateFromRequest($request, $place);

        return response()->json(['status' => 'ok']);
    }

    public function destroy(string $slug)
    {
        /** @var Place $place */
        $place = Place::where('slug', $slug)->firstOrFail();
        $place->delete();

        return response()->json(['status' => 'ok']);
    }
}
