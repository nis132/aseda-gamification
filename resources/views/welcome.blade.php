<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASEDA Generation | Initializing Adventure</title>

    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            /* Background Cerah selaras dengan Login */
            background: linear-gradient(135deg, #f5f7ff 0%, #e0e7ff 100%);
            color: #1e293b;
            text-align: center;
            font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
            overflow: hidden;
        }

        .welcome-container {
            position: relative;
            z-index: 2;
        }

        /* Glow disesuaikan ke Biru Indigo agar tidak terlalu kontras di bg putih */
        .logo-glow {
            font-size: 3.5rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #4338ca; /* Indigo */
            text-shadow: 0 10px 20px rgba(67, 56, 202, 0.1);
            animation: pulse 2s infinite alternate;
        }

        @keyframes pulse {
            from { transform: scale(1); opacity: 0.9; }
            to { transform: scale(1.02); opacity: 1; }
        }

        .status-text {
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 20px;
            color: #6366f1; /* Indigo terang */
            font-weight: 700;
        }

        /* Progress Bar selaras dengan Button Login */
        .progress-box {
            width: 280px;
            height: 8px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            margin: 25px auto;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            /* Menggunakan Biru Elektrik khas tombol login kamu */
            background: linear-gradient(90deg, #007bff, #6366f1);
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
            transition: width 2.2s cubic-bezier(0.45, 0.05, 0.55, 0.95);
        }

        /* Partikel dibuat Biru Lembut */
        .particles {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            background: rgba(99, 102, 241, 0.2);
            border-radius: 50%;
            animation: float 10s infinite linear;
        }

        @keyframes float {
            from { transform: translateY(110vh); opacity: 0; }
            to { transform: translateY(-10vh); opacity: 0.6; }
        }
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="welcome-container">
        <div class="mb-2">
            <i class="fas fa-rocket fa-3x mb-3" style="color: #007bff; filter: drop-shadow(0 5px 15px rgba(0,123,255,0.2));"></i>
        </div>
        <h1 class="fw-bold logo-glow">ASEDA<br><span class="fw-light" style="color: #64748b;">Generation</span></h1>
        
        <p class="status-text" id="statusLabel">Inisialisasi Sistem...</p>

        <div class="progress-box">
            <div class="progress-fill" id="progressBar"></div>
        </div>

        <small class="text-muted" style="font-size: 0.75rem; letter-spacing: 1px; font-weight: 500;">PREPARING YOUR LEARNING REALM</small>
    </div>

    <script>
        const statuses = [
            "Sinkronisasi Data...",
            "Memuat Tantangan...",
            "Menyiapkan Level...",
            "Hampir Sampai..."
        ];
        
        let i = 0;
        const statusLabel = document.getElementById('statusLabel');
        const progressBar = document.getElementById('progressBar');

        const interval = setInterval(() => {
            if(i < statuses.length) {
                statusLabel.innerText = statuses[i];
                i++;
            }
        }, 550);

        setTimeout(() => {
            progressBar.style.width = "100%";
        }, 100);

        setTimeout(function() {
            window.location.href = "{{ route('login') }}";
        }, 2800); // Sedikit lebih lama agar transisi bar terlihat smooth

        const particleContainer = document.getElementById('particles');
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 8 + 4 + 'px';
            p.style.width = size;
            p.style.height = size;
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDelay = Math.random() * 5 + 's';
            p.style.animationDuration = Math.random() * 7 + 5 + 's';
            particleContainer.appendChild(p);
        }
    </script>
</body>
</html>