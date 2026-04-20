<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEDA Generation</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-align: center;
        }

        .loader {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div>
    <h1 class="fw-bold">ASEDA Generation</h1>
    <p class="mb-3">Memuat halaman...</p>

    <div class="spinner-border text-light loader" role="status"></div>
</div>

<script>
    // Redirect sekali saja setelah 2 detik
    setTimeout(function() {
        window.location.href = "{{ route('login') }}";
    }, 2000);
</script>

</body>
</html>