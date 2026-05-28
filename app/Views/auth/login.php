<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sírvalo Pues · Acceso al Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0e1a 0%, #0f1423 50%, #13192e 100%);
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* Encabezado Hero */
        .hero-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-header .badge {
            background: linear-gradient(135deg, #f5a623, #e67e22);
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            display: inline-block;
        }

        .hero-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .hero-header h1 span {
            color: #f5a623;
        }

        .hero-header .hero-desc {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            max-width: 600px;
            margin: 0 auto 1rem auto;
            line-height: 1.6;
        }

        .hero-features {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .hero-features .feature {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            backdrop-filter: blur(5px);
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .hero-features .feature i {
            color: #f5a623;
            font-size: 1rem;
        }

        /* Contenedor principal */
        .login-container {
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
        }

        /* Tarjeta principal */
        .login-card {
            background: rgba(18, 24, 38, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 2rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Panel izquierdo - Historias */
        .stories-panel {
            background: linear-gradient(135deg, rgba(10, 14, 26, 0.95) 0%, rgba(20, 26, 46, 0.95) 100%);
            padding: 2.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Logo original en el panel izquierdo */
        .logo-original {
            margin-bottom: 2rem;
            text-align: center;
        }
        
        .logo-original img {
            max-height: 80px;
            width: auto;
            border-radius: 15px;
        }

        .stories-panel h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .stories-panel h2 span {
            color: #f5a623;
        }

        .stories-panel .lead {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* Historias */
        .story-item {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .story-item:hover {
            background: rgba(245, 166, 35, 0.08);
            border-color: rgba(245, 166, 35, 0.3);
            transform: translateX(5px);
        }

        .story-icon {
            width: 45px;
            height: 45px;
            background: rgba(245, 166, 35, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .story-icon i {
            font-size: 1.3rem;
            color: #f5a623;
        }

        .story-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: white;
            margin-bottom: 0.25rem;
        }

        .story-content p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
            line-height: 1.4;
        }

        .story-content small {
            color: rgba(245, 166, 35, 0.7);
            font-size: 0.7rem;
            display: inline-block;
            margin-top: 0.3rem;
        }

        /* Panel derecho - Login */
        .login-panel {
            padding: 2.5rem;
        }

        /* Logo original en el panel derecho */
        .login-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .login-logo img {
            max-height: 70px;
            width: auto;
            border-radius: 12px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 1.8rem;
        }

        .login-header h3 {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.8rem;
        }

        /* Formulario */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.9rem;
            z-index: 1;
        }

        .input-group-custom input {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.75rem;
            color: white;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: #f5a623;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2);
        }

        .input-group-custom input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Botón amarillo */
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #f5a623, #e67e22);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
            cursor: pointer;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(245, 166, 35, 0.4);
            background: linear-gradient(135deg, #f5b334, #f5a623);
        }

        .security-note {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.7rem;
        }

        .security-note i {
            margin-right: 0.3rem;
        }

        /* Alertas */
        .alert-custom {
            background: rgba(231, 74, 59, 0.15);
            border: 1px solid rgba(231, 74, 59, 0.3);
            border-radius: 0.75rem;
            color: #f1aeb5;
            font-size: 0.8rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }

        /* Animaciones */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .stories-panel {
                display: none;
            }
            .login-panel {
                width: 100%;
            }
            .login-card {
                max-width: 450px;
                margin: 0 auto;
            }
            .hero-header h1 {
                font-size: 1.8rem;
            }
            .hero-features {
                gap: 0.8rem;
            }
            .hero-features .feature {
                font-size: 0.7rem;
                padding: 0.3rem 0.8rem;
            }
        }

        @media (max-width: 576px) {
            body {
                padding: 1rem;
            }
            .login-panel {
                padding: 1.5rem;
            }
            .hero-header {
                margin-bottom: 1rem;
            }
            .hero-header .hero-desc {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<!-- ════════════════════════════════════════
     ENCABEZADO DE LA FONDA
════════════════════════════════════════ -->
<div class="hero-header">
    <div class="badge">🍖 Tradición Colombiana desde 2023</div>
    <h1>Sírvalo <span>Pues</span></h1>
    <div class="hero-desc">
        La fonda más auténtica de la ciudad, donde el chicharrón al barril, los licores nacionales 
        y la mejor atención se unen para darte una experiencia inolvidable.
    </div>
    <div class="hero-features">
        <div class="feature"><i class="fas fa-drumstick-bite"></i> Chicharrón al barril</div>
        <div class="feature"><i class="fas fa-music"></i> Música en vivo</div>
        <div class="feature"><i class="fas fa-smile"></i> Atención de primera</div>
        <div class="feature"><i class="fas fa-glass-cheers"></i> Licores nacionales</div>
        <div class="feature"><i class="fas fa-calendar-alt"></i> Abierto fines de semana</div>
    </div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="row g-0">
            
            <!-- Panel Izquierdo: Historias + Logo -->
            <div class="col-lg-6">
                <div class="stories-panel">
                    <div class="logo-original">
                        <img src="<?= base_url('/img/sirvalo.png') ?>" alt="Sírvalo Pues">
                    </div>
                    
                    <h2>Más que una fonda,<br><span>una experiencia inolvidable</span></h2>
                    <p class="lead">Descubre por qué nuestros clientes regresan una y otra vez</p>
                    
                    <!-- Historias -->
                    <div class="story-item">
                        <div class="story-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="story-content">
                            <h4>🎵 La noche que sonó mi canción</h4>
                            <p>"Llegué con mis amigos, pedí mi canción favorita y en 5 minutos estaba sonando. ¡El ambiente es único!"</p>
                            <small>— Carlos M.</small>
                        </div>
                    </div>
                    
                    <div class="story-item">
                        <div class="story-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="story-content">
                            <h4>🍽️ El mejor chicharrón de la ciudad</h4>
                            <p>"La comida es espectacular, el chicharrón al barril es mi favorito. ¡Volveré cada semana!"</p>
                            <small>— Ana R.</small>
                        </div>
                    </div>
                    
                    <div class="story-item">
                        <div class="story-icon">
                            <i class="fas fa-glass-cheers"></i>
                        </div>
                        <div class="story-content">
                            <h4>🥃 Licores nacionales de primera</h4>
                            <p>"El aguardiente y el ron son de la mejor calidad. El lugar perfecto para celebrar con los amigos."</p>
                            <small>— Javier P.</small>
                        </div>
                    </div>
                    
                    <div class="story-item">
                        <div class="story-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="story-content">
                            <h4>🏆 5 años siendo el mejor lugar</h4>
                            <p>"El servicio es excepcional, el personal te hace sentir como en casa. ¡100% recomendado!"</p>
                            <small>— Laura G.</small>
                        </div>
                    </div>
                    
                    <div class="story-item">
                        <div class="story-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="story-content">
                            <h4>🎉 El mejor plan para fin de semana</h4>
                            <p>"Música, buen ambiente, comida deliciosa y atención increíble. ¡Sírvalo Pues es mi lugar favorito!"</p>
                            <small>— Sergio D.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel Derecho: Login -->
            <div class="col-lg-6">
                <div class="login-panel">
                    <div class="login-logo">
                        <img src="<?= base_url('/img/sirvalo.png') ?>" alt="Sírvalo Pues">
                    </div>
                    
                    <div class="login-header">
                        <h3>Bienvenido de vuelta</h3>
                        <p>Ingresa a tu cuenta para gestionar el negocio</p>
                    </div>
                    
                    <?php if(session()->getFlashdata('msg')): ?>
                    <div class="alert-custom">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
                    </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('/auth/authenticate') ?>" method="post">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user me-1"></i> Usuario</label>
                            <div class="input-group-custom">
                                <i class="fas fa-user"></i>
                                <input type="text" name="username" placeholder="admin@ejemplo.com" required autofocus>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-lock me-1"></i> Contraseña</label>
                            <div class="input-group-custom">
                                <i class="fas fa-key"></i>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-login">
                            <i class="fas fa-sign-in-alt me-2"></i> Ingresar al sistema
                        </button>
                    </form>
                    
                    <div class="security-note">
                        <i class="fas fa-shield-alt"></i> Área restringida · Solo personal autorizado
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>