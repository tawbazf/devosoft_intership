<h1>Liste des vidéos</h1>

<ul>
@foreach ($videos as $video)
    <li>
        <strong>{{ $video['title'] }}</strong><br>
        DASH: <a href="{{ $video['manifest_url'] }}">{{ $video['manifest_url'] }}</a><br>
        HLS: <a href="{{ $video['hls_url'] }}">{{ $video['hls_url'] }}</a>
    </li>
@endforeach
</ul>
