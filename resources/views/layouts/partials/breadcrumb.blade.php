<nav aria-label="breadcrumb" class="my-3">
    <ol class="breadcrumb bg-light p-2 rounded">
        @foreach ($breadcrumbs as $breadcrumb)
            @if (!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>
