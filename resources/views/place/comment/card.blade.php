@php
    /** @var \App\Models\PlaceComment $comment */
@endphp

<div class="card">
    <div class="card-body">
        <h6 class="card-title">
            Коментар залишив(ла) {{ $comment->author->name }}. Дата: {{ $comment->created_at }}
        </h6>

        <p class="card-text">{{ $comment->text }}</p>
    </div>
</div>
