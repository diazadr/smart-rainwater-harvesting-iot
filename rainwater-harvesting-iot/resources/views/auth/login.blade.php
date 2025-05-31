<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Smart Rainwater Harvesting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #3b82f6;
            --dark-blue: #1e40af;
            --light-blue: #dbeafe;
            --iot-green: #10b981;
            --iot-teal: #0d9488;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }

        .login-card {
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
            max-width: 420px;
            width: 100%;
        }

        .login-header {
            background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }

        .login-body {
            padding: 2rem;
            background-color: white;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-blue), var(--dark-blue));
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }

        .iot-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: white;
        }

        .water-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 20px;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z" fill="%23ffffff" opacity=".25"/><path d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z" fill="%23ffffff" opacity=".5"/><path d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z" fill="%23ffffff"/></svg>');
            background-size: cover;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-blue);
        }

        .sensor-animation {
            position: absolute;
            width: 100px;
            height: 100px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: pulse 4s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.8); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 0.3; }
            100% { transform: scale(0.8); opacity: 0.7; }
        }

        .sensor-1 {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .sensor-2 {
            bottom: 15%;
            right: 10%;
            animation-delay: 1s;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="login-card">
                        <div class="login-header position-relative">
                            <div class="sensor-animation sensor-1"></div>
                            <div class="sensor-animation sensor-2"></div>
                            <i class="fas fa-tint iot-icon"></i>
                            <h2 class="mb-1">Smart Rainwater Harvesting</h2>
                            <p class="mb-0">Sistem Monitoring dan Kontrol IoT</p>
                            <div class="water-wave"></div>
                        </div>
                        <div class="login-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif

                            <form action="/login" method="post">
                                @csrf
                                @method('POST')

                                <div class="mb-4 input-icon">
                                    <i class="fas fa-envelope"></i>
                                    <input type="email" class="form-control" id="email" placeholder="Alamat Email" name="email"
                                        value="{{ old('email') }}" required>
                                </div>

                                <div class="mb-4 input-icon">
                                    <i class="fas fa-lock"></i>
                                    <input type="password" class="form-control" id="password" placeholder="Kata Sandi" name="password"
                                        required>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-login">
                                        <i class="fas fa-sign-in-alt me-2"></i>Masuk
                                    </button>
                                </div>

                                <div class="text-center">
                                   <a href="{{ route('register') }}" class="text-decoration-none text-muted small">
    <i class="fas fa-question-circle me-1"></i>Tidak mempunyai akun?
</a>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4 text-muted small">
                        <p>© 2025 Smart Rainwater Harvesting. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
