<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Italianos Forever</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link to Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- Link to Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Link to Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <img class="passaporto" src="{{ asset('images/passaporto.png') }}" alt="passaporto">
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

        <!-- "Cards" Section -->
        <section class="cards-section">
            <div class="cards-container">
                <div class="cards-content">
                    <div class="cards-icon">
                        <img src="{{ asset('images/globo.png') }}" alt="Icon">
                    </div>
                    <div class="cards-heading-container">
                        <h2 class="cards-title">
                            Circule com facilidade
                        </h2>
                        <p>em vários países do mundo</p>
                    </div>
                </div>
                <div class="cards-content">
                    <div class="cards-icon">
                        <img src="{{ asset('images/capello.png') }}" alt="Icon">
                    </div>
                    <div class="cards-heading-container">
                        <h2 class="cards-title">
                            Acesse as melhores universidades
                        </h2>
                        <p>com menor investimento</p>
                    </div>
                </div>
                <div class="cards-content">
                    <div class="cards-icon">
                        <img src="{{ asset('images/mani.png') }}" alt="Icon">
                    </div>
                    <div class="cards-heading-container">
                        <h2 class="cards-title">
                            Tenha mais credibilidade
                        </h2>
                        <p>para desenvolver negócios com vários países</p>
                    </div>
                </div>
                <div class="cards-content">
                    <div class="cards-icon">
                        <img src="{{ asset('images/persone.png') }}" alt="Icon">
                    </div>
                    <div class="cards-heading-container">
                        <h2 class="cards-title">
                            Abra sua empresa
                        </h2>
                        <p>de forma rápida e sem complicações</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- "Services" Section -->
        <section class="services-section"></section>
            <div class="services-container">
                <div class="services-content">
                    <div class="services-heading-container">
                        <span class="long-green-line"></span>
                        <h2 class="services-title-placeholder">
                            CONSULTORIA PARA O RECONHECIMENTO DA CIDADANIA ITALIANA  {{-- Placeholder --}}
                        </h2>
                        <span class="short-red-line"></span>
                    </div>
                </div>
                <div class="services-list">
                    <h2 class="services-title">
                        CONSULTORIA PARA O RECONHECIMENTO DA CIDADANIA ITALIANA
                    </h2>
                    <ul>
                        <li>Atendimento personalizado</li>
                        <li>Comunicação continuada do status de cada etapa</li>
                        <li>Consultoria profissional</li>
                        <li>Consultoria na solicitação para o cônjuge</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- "Statua" Section -->
        <section class="statua-section"></section>
            <div class="statua-container">
                <div class="statua-content">
                    <img src="{{ asset('images/statua.png') }}" alt="statua">
                </div>
            </div>
        </section>

        <!-- "Parla" Section -->
        <section class="parla-section"></section>
            <div class="parla-container">
                <div class="parla-content">
                    <span class="long-green-line"></span>
                    <img src="{{ asset('images/parla.png') }}" alt="parla" class="parla-img">
                    <h2 class="parla-title">
                        O QUE DIZEM NOSSOS CLIENTES
                    </h2>
                    <span class="short-red-line"></span>
                </div>
            </div>
        </section>

        <!-- "Testimonial" Section -->
        <section class="testimonial-section">
            <div class="left-testimonial-container">
                <div class="apostrophe-sides">
                    <img src="{{ asset('images/apostrophe.png') }}" alt="apostrophe">
                </div>
                <div class="testimonial-content">
                    <div class="testimonial-title">
                        <h2>Regina Márcia Bertoncini Hennemann e Robson Avila Wolff</h2>
                    </div>
                    <div class="testimonial-text">
                        <p>
                            A empresa nos foi apresentada em 2011 e a partir daí fizemos a cidadania com toda orientação legal e acompanhamento do processo, sempre com profissionalismo e qualidade nos serviços prestados. Agradecemos a Ester e Giovanni, e sua equipe, pela atenção o carisma e carinho que nos tratam até hoje.

                            Regina Márcia Bertoncini Hennemann e Robson Avila Wolff
                        </p>
                    </div>
                </div>
            </div>
            <div class="meaddle-testimonial-container">
                <div class="apostrophe-meaddle">
                    <img src="{{ asset('images/apostrophe.png') }}" alt="apostrophe">
                </div>
                <div class="testimonial-content">
                    <div class="testimonial-title">
                        <h2>Matheus Tudela de Sà</h2>
                    </div>
                    <div class="testimonial-text">
                        <p>Fui assistido em 2009 por Ester e Giovanni durante o mais importante e complexo projeto da minha carreira : a expatriação. Com uma abordagem firme e diligente me garantiram um accesso espontâneo à minha cidadania. Nada melhor do que dispor de profissionais com tradição e maestria.

                        Sales Engineering Manager – Cella Retail Solutions
                        </p>
                    </div>
                </div>
            </div>
            <div class="right-testimonial-container">
                <div class="apostrophe-sides">
                    <img src="{{ asset('images/apostrophe.png') }}" alt="apostrophe">
                </div>
                <div class="testimonial-content">
                    <div class="testimonial-title">
                        <h2>Erika Mendes Correia</h2>
                    </div>
                    <div class="testimonial-text">
                        <p>Profissionais sérios, experientes, dedicados e apaixonados no suporte ao reconhecimento da cidadania italiana. O processo ocorreu tranquilamente de acordo com a descrição dos serviços prestados. Super recomendo!

                        Erika Mendes Correia – Clinical Trial Manager at Global Antibiotic R&D Partnership (GARDP) Genève</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- "Contact" Section -->
        <section class="contact-section" id="contato">
            <div class="container">
                <div class="row">
                    <div class="col-6 contact-title">
                        <h2>Contato</h2>
                    </div>
                    <div class="col-6 contact-form">
                        <form>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="name" placeholder="Seu nome">
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="email" placeholder="Seu e-mail">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="message" placeholder="Sua mensagem"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="file" class="form-label">Anexar Arquivo</label>
                                <input type="file" class="form-control" id="file">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- "Footer" Section -->
        <section class="footer-section">
            <div class=" row footer-container">
                <div class="col-3 footer-content">
                    <h3>Endereço</h3>
                    <span class="separator-line"></span>
                    <p>Via Nazionale 192/C – 40051
                        Altedo di Malalbergo (Bologna)
                    </p>
                </div>
                <div class="col-3 footer-content">
                    <h3>Contato</h3>
                    <span class="separator-line"></span>
                    <div>
                        <ul>
                            <li>
                                <span>
                                    <i class="fas fa-mobile-alt"></i>
                                </span>
                                <span>Fixo: 051 581156 | Fax: 051 565550</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-3 footer-content">
                    <h3>Redes Sociais</h3>
                    <span class="separator-line"></span>
                    <p>Via Nazionale 192/C – 40051
                        Altedo di Malalbergo (Bologna)
                    </p>
                </div>
            </div>
        </section>
    </main>

</body>
</html>
