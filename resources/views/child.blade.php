<li>{{ $child->id }} | {{ json_decode($child->name)->en }}</li>
@if ($child->recursiveChildren)
    <ul>
        @foreach ($child->recursiveChildren as $subchild)
            @include('child', ['child' => $subchild])
        @endforeach
    </ul>
@endif