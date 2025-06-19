@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ $video->title }}</h2>
    <video id="video" width="100%" controls autoplay></video>

    <script src="https://cdn.jsdelivr.net/npm/shaka-player/dist/shaka-player.compiled.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const player = new shaka.Player(document.getElementById('video'));

            shaka.polyfill.installAll();

            player.configure({
                drm: {
                    servers: {
                        'com.widevine.alpha': '{{ $video->license_url }}'
                    }
                }
            });

            try {
                await player.load("{{ $manifest_url }}");
            } catch (e) {
                console.error('Erreur de lecture', e);
            }
        });
    </script>
</div>
@endsection
