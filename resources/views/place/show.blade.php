@php
    /** @var \App\Models\Place $place */
    /** @var string|null $likeState */
@endphp

@extends('layouts.app')

@section('title', $place->name)

@section('content')
    <div class="container" id="show-place">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h5 class="title">
                    {{ $place->name }}
                </h5>

                <p class="text">{{ $place->description }}</p>

                @unless(empty($place->fields))
                    <h6>Додаткова інформація:</h6>
                    <table class="table">
                        @foreach($place->fields as $field)
                            <tr>
                                <td>{{ $field->key }}</td>
                                <td>{{ $field->value }}</td>
                            </tr>
                        @endforeach
                    </table>
                @endunless

                <h6>Галерея:</h6>
                @foreach($place->getMedia('photos') as $photo)
                    @if(!$photo->getCustomProperty('visible', true))
                        @continue
                    @endif
                    <img src="{{ $photo->getUrl('preview') }}" alt="">
                @endforeach

                <p class="text">
                    Оцінка: {{ $place->mark }}/10
                    <br>
                    Кількість лайків: {{ $place->likes_count }}
                    <br>
                    Кількість дизлайків: {{ $place->dislikes_count }}
                    <br>
                    <a
                        href="{{ route('place.like', ['slug' => $place->slug, 'value' => 1]) }}"
                        class="btn {!! $likeState === 'like' ? 'btn-primary' : 'btn-outline-primary' !!}"
                    >Like</a>
                    <a
                        href="{{ route('place.like', ['slug' => $place->slug, 'value' => -1]) }}"
                        class="btn {!! $likeState === 'dislike' ? 'btn-primary' : 'btn-outline-primary' !!}"
                    >Dislike</a>
                </p>

                <div class="comments-container">
                    @forelse($place->comments as $comment)
                        @include('place.comment.card', compact('comment'))
                    @empty
                        Коментарів немає
                    @endforelse
                </div>

                {{-- Add comment button in Vue --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
window.__initialState = {!! json_encode([
    'place' => $place->jsonSerialize() + [
        'photos' => $place->getMedia('photos')
            ->filter(function ($photo) {
                return $photo->getCustomProperty('visible', true);
            })
            ->map(function ($photo) {
                return [
                    'preview' => $photo->getUrl('preview'),
                    'original' => $photo->getUrl(),
                ];
            }),
    ],
    'like_state' => $likeState,
]) !!};
</script>
@endpush
