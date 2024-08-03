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
        <div class="header-container">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Italianos Forever Logo">
            </div>
            <nav class="elementor-nav-menu--main elementor-nav-menu__container elementor-nav-menu--layout-horizontal">
                <ul id="menu-1-6411232" class="elementor-nav-menu" data-smartmenus-id="17226655878646082">
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-14">
                        <a href="#sobre" class="elementor-item elementor-item-anchor">Sobre Nós</a>
                    </li>
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-15">
                        <a href="#cidadania" class="elementor-item elementor-item-anchor">Cidadania Italiana</a>
                    </li>
                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-16">
                        <a href="#contato" class="elementor-item elementor-item-anchor">Contato</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="content">
            <div class="text">
                <h1>Reconheça a sua <span>cidadania italiana</span></h1>
                <p>Se você é descendente de italianos é seu direito ser reconhecido</p>
                <a href="#" class="button">Saiba Mais</a>
            </div>
            <div class="image">
                <img src="{{ asset('images/coliseum.png') }}" alt="Coliseum">
            </div>
        </section>
    </main>
</body>
</html>
