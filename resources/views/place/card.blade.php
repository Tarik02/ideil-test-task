@php
    /** @var \App\Models\Place $place */
@endphp

<div class="card">
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('place.show', ['slug' => $place->slug]) }}">
                {{ $place->name }}
            </a>
        </h5>
        <p class="card-text">
            @if($place->defaultPhoto !== null)
                <img src="{{ $place->defaultPhoto->imageUrl('preview') }}" alt="">
            @endif
            {{ $place->description }}
        </p>
    </div>
</div>
