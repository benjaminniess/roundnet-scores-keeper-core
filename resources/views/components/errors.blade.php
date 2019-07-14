{{-- Return form errors --}}
    @if ($errors->any())

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger my-3" role="alert">{{ $error }}</div>
    @endforeach

    @endif