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
            @if(null !== $firstPhoto = $place->getMedia('photos')->first())
                <img src="{{ $firstPhoto->getUrl('preview') }}" alt="">
            @endif
            {{ $place->description }}
        </p>
    </div>
</div>
