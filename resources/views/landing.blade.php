<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Raport SMK Muhammadiyah 2 Taman</title>
    
    <!-- Google Fonts: Plus Jakarta Sans for premium modern look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }
        
        body {
            font-family: var(--font-family);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("{{ asset('images/landing-bg.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #ffffff;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle overlay to enhance readability and contrast */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.1) 50%, rgba(0, 0, 0, 0.3) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .landing-container {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 1000px;
            padding: 2rem;
        }

        /* Premium Animations */
        .animate-fade-in-up {
            animation: fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo styling */
        .logo-img {
            max-width: 160px;
            height: auto;
            margin-bottom: 2.5rem;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.03);
        }

        /* Typography */
        h1.heading {
            font-size: 2.5rem;
            font-weight: 600; /* semi bold */
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin-bottom: 0.75rem;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            white-space: nowrap;
        }

        p.subheading {
            font-size: 1.25rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
        }

        /* Premium Button */
        .btn-enter {
            background-color: #ffffff;
            color: #0b2545; /* elegant dark navy matching gradient */
            font-size: 1rem;
            font-weight: 600;
            padding: 0.85rem 2.25rem;
            border-radius: 50px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-enter:hover {
            background-color: #ffffff;
            color: #134074;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.25), 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-enter:active {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            h1.heading {
                font-size: 1.75rem;
                white-space: normal;
            }
            h1.heading span {
                display: block;
            }
            p.subheading {
                font-size: 1.05rem;
                margin-bottom: 2rem;
            }
            .logo-img {
                max-width: 130px;
                margin-bottom: 2rem;
            }
            .btn-enter {
                padding: 0.75rem 1.85rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

    <div class="landing-container">
        <!-- Logo -->
        <div class="animate-fade-in-up">
            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Muhammadiyah 2 Taman" class="logo-img">
        </div>
        
        <!-- Headline -->
        <h1 class="heading animate-fade-in-up delay-1">
            <span>Platform E-Raport</span>
            <span>SMK Muhammadiyah 2 Taman</span>
        </h1>
        
        <!-- Subheading -->
        <p class="subheading animate-fade-in-up delay-2">
            Akses cepat ke nilai raport siswa
        </p>
        
        <!-- CTA Button -->
        <div class="animate-fade-in-up delay-3">
            <a href="{{ route('login') }}" class="btn-enter">
                <span>Masuk dengan id SMK</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </a>
        </div>
    </div>

</body>
</html>
