<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Colegio San Simón de Ayacucho</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
        }

        .login-box {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }

        .login-header {
            background-color: #000000;
            color: white;
            padding: 40px 30px 30px;
            text-align: center;
            position: relative;
        }

        .logo-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .login-header p {
            font-size: 14px;
            opacity: 0.8;
            font-weight: 400;
        }

        .login-body {
            padding: 40px 35px 35px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            letter-spacing: 0.3px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background-color: #000000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: #333333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            background-color: #ffe6e6;
            color: #cc0000;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            border: 1px solid #ffcccc;
            display: flex;
            align-items: center;
        }

        .error-message:before {
            content: "!";
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background-color: #cc0000;
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            font-weight: bold;
        }

        .info-box {
            background-color: #f8f8f8;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #555;
            line-height: 1.6;
            border-left: 4px solid #000000;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
            color: #000000;
            font-size: 14px;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #888;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e0e0e0;
        }

        .divider span {
            padding: 0 15px;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-body {
                padding: 30px 25px 25px;
            }
            
            .login-header {
                padding: 30px 25px 25px;
            }
            
            .logo-container {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <div class="logo-container">
                    <img src="{{ asset('img/descarga.jpeg') }}" alt="Logo Colegio San Simón de Ayacucho">
                </div>
                <h1>Colegio San Simón</h1>
                <p>Sistema de Administración</p>
            </div>

            <div class="login-body">
                @if ($errors->any())
                    <div class="error-message">
                        {{ $errors->first() }}
                    </div>
                @endif

                

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                            placeholder="tu@email.com"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="••••••••"
                        >
                    </div>

                    <button type="submit" class="btn-login">Iniciar Sesión</button>
                </form>

                <div class="footer-text">
                    &copy; {{ date('Y') }} Colegio San Simón de Ayacucho. Todos los derechos reservados.
                </div>
            </div>
        </div>
    </div>
</body>
</html>