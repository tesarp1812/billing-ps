@extends('app')

@section('content')
    @php
        $bootstrap = [
            'user' => auth()->user(),
            'appName' => config('app.name', 'PS Billing POS'),
        ];
    @endphp

    <script>
        window.PSBILLING_BOOTSTRAP = {!! json_encode($bootstrap, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    </script>

    <div id="app"></div>
@endsection
