<h1> categories </h1>
<ul>
    @foreach ($categories as $category)
    @include('child', ['child' => $category])
    @endforeach
</ul>