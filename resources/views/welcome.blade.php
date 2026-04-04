<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEDA Generation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .hero-video {
            position: relative;
            height: 100vh;
        }

        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background: rgba(0,0,0,0.3);
        }

        .btn-masuk {
            position: absolute;
            bottom: 50px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="hero-video">
    <video autoplay muted playsinline id="introVideo">
        <source src="{{ asset('video/aseda.mp4') }}" type="video/mp4">
    </video>

    <div class="overlay"></div>

    <div class="btn-masuk">
        <a href="{{ route('login') }}" class="btn btn-light px-4 py-2">
            Masuk
        </a>
    </div>
</div>

<script>
    // Auto redirect setelah video selesai
    const video = document.getElementById('introVideo');

    video.onended = function() {
        window.location.href = "{{ route('login') }}";
    }
</script>

</body>
</html>