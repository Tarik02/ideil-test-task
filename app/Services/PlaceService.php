<?php

namespace App\Services;

use App\Http\Requests\Admin\PlaceRequest;
use App\Models\Place;
use App\Models\PlaceField;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\Models\Media;

class PlaceService
{
    public function updateFromRequest(PlaceRequest $request, Place $place)
    {
        \DB::transaction(function () use ($request, $place) {
            $data = $request->validated();

            $place->fill($data);
            $place->save();

            $place->fields()->delete();
            $place->fields()->saveMany(collect($data['fields'])->map(function ($field, $i) {
                return PlaceField::make($field + ['weight' => $i]);
            }));

            $photos = $place->getMedia('photos')->mapWithKeys(function (Media $photo) {
                return [$photo->id => $photo];
            });

            $photoFiles = Arr::wrap($request->file('file', []));
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
        });
    }
}
