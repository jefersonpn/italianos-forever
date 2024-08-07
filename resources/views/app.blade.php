<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Italianos Forever</title>
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- Link to Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <div class="header__container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Italianos Forever Logo">
            </div>
            <nav class="nav-menu">
                <ul id="menu-1-6411232" class="nav-menu__list" data-smartmenus-id="17226655878646082">
                    <li class="nav-menu__item menu-item-type-custom menu-item-object-custom menu-item-14">
                        <a href="#sobre" class="nav-menu__link">Sobre Nós</a>
                    </li>
                    <li class="nav-menu__item menu-item-type-custom menu-item-object-custom menu-item-15">
                        <a href="#cidadania" class="nav-menu__link">Cidadania Italiana</a>
                    </li>
                    <li class="nav-menu__item menu-item-type-custom menu-item-object-custom menu-item-16">
                        <a href="#contato" class="nav-menu__link">Contato</a>
                    </li>
                    <li class="nav-menu__item menu-item-type-custom menu-item-object-custom menu-item-16">
                        <a href="#contato" class="nav-menu__link">Login</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Existing Content Section -->
        <section class="content__section">
            <div class="content__overlay">
                <div class="content__text">
                    <img src="{{ asset('images/sello.png') }}" alt="Sello" class="decorative-image">
                    <h1>Reconheça a sua<br><span class="highlighted-text">cidadania italiana</span></h1>
                    <div class="divider"></div>
                    <p>Se você é descendente de italianos<br>é seu direito ser reconhecido</p>
                    <a href="#cidadania" class="button">Saiba Mais</a>
                </div>
                <div class="content__placeholder"></div>
            </div>
        </section>

        <!-- "Sobre Nós" Section -->
        <section class="about-section" id="sobre">
            <div class="about-container">
                <div class="about-content">
                    <div class="about-text">
                        <h2>SOBRE NÓS</h2>
                        <div class="divider"></div>
                        <ul>
                            <li>Temos sede na Itália</li>
                            <li>Mais de 20 anos de experiência</li>
                            <li>Atendemos a mais de 30.000 brasileiros</li>
                            <li>Somos membro do Conselho do Cidadão do Consulado Brasileiro em Milão.</li>
                        </ul>
                        <img src="{{ asset('images/listra.png') }}" alt="Sello">
                    </div>
                </div>
            </div>
        </section>

        <!-- Vantagens Section -->
        <section class="benefits-section" id="cidadania">
            <div class="benefits-container" id="benefits-container">
                <div class="benefits-content" id="benefits-content">
                    <div class="benefits-heading-container" id="benefits-heading-container">
                        <h2 class="benefits-title" id="benefits-title">
                            VANTAGENS DE RECONHECER A SUA CIDADANIA ITALIANA
                        </h2>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
