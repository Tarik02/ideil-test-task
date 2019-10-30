@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    @foreach($places as $place)
                        <div class="col-md-6">
                            @include('place.card', compact('place'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
window.__initialState = {
    places: {
        data: {!! json_encode($places->map(function (\App\Models\Place $place) {
            return $place->only('slug', 'name', 'description', 'defaultPhoto') + [
                'url' => route('place.show', ['slug' => $place->slug]),
            ];
        })) !!},
    },
};
</script>
@endpush
