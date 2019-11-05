<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PlaceRequest;
use App\Http\Resources\Admin\PlaceCollection;
use App\Http\Resources\Admin\PlaceResource;
use App\Models\Place;
use App\Models\PlaceField;
use App\Models\PlacePhoto;
use App\Scopes\VisibleScope;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\Models\Media;

class PlaceController extends Controller
{
    public function index(Request $request)
    {
        $places = Place::paginate(
            (int) $request->get('per_page', 10)
        );

        return PlaceResource::collection($places);
    }

    public function store(Request $request)
    {
        dd($request->file());
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
        \DB::transaction(function () use ($request, $slug) {
            $data = $request->validated();

            /** @var Place $place */
            $place = Place::where('slug', $slug)->firstOrFail();

            $place->fill($data);

            $place->fields()->delete();
            $place->fields()->saveMany(collect($data['fields'])->map(function ($field, $i) {
                return PlaceField::make($field + ['weight' => $i]);
            }));

            $photos = $place->getMedia('photos')->mapWithKeys(function (Media $photo) {
                return [$photo->id => $photo];
            });

            $photoFiles = Arr::wrap($request->file('photos', []));
            $orderCounter = 1;
            collect($data['photos'])->each(
                function ($photo) use ($place, $photos, $photoFiles, &$orderCounter) {
                    if (isset($photo['id'])) {
                        $model = $photos[$photo['id']];
                    } else {
                        $model = $place
                            ->addMedia($photoFiles[$photo['file']])
                            ->toMediaCollection('photos')
                        ;
                    }
                    $model->setCustomProperty('visible', $photo['visible']);
                    $model->order_column = $orderCounter++;

                    $model->save();
                }
            );

            $place->save();
        });

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
