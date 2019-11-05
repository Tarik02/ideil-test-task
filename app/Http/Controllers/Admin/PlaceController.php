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
                    'defaultPhoto',
                    'comments' => function (HasMany $builder) {
                        $builder->withoutGlobalScope(VisibleScope::class);
                    },
                    'fields',
                    'photos' => function (HasMany $builder) {
                        $builder->withoutGlobalScope(VisibleScope::class);
                    },
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

            $preservedPhotos = array_flip(array_filter(array_map(function ($photo) {
                return $photo['id'] ?? null;
            }, $data['photos']), function ($id) {
                return $id !== null;
            }));
            $newPhotos = array_filter($data['photos'], function ($photo) {
                return !isset($photo['id']);
            });

            $photos = $place->photos;
            /** @var PlacePhoto $photo */
            foreach ($photos as $photo) {
                if (isset($preservedPhotos[$photo->id])) {
                    $index = $preservedPhotos[$photo->id];
                    $photo->weight = $index;
                    $photo->visible = $data['photos'][$index]['visible'];
                    $photo->save();
                } else {
                    $photo->delete();
                }
            }

            $photoFiles = Arr::wrap($request->file('photos', []));
            foreach ($newPhotos as $index => $newPhoto) {
                /** @var UploadedFile $file */
                $file = $photoFiles[$newPhoto['file']];
                $photo = $place->photos()->make();
//                $photo->place = $place;
                $photo->weight = $index;
                $photo->visible = $data['photos'][$index]['visible'];
                $photo->uploadImage($file, 'original');
                $photo->uploadImage($file, 'preview');
                $photo->save();
            }
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
