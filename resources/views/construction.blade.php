<html class="h-100" lang="pt-BR">
    <head>
        <title>My Dream Closet</title>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="description" content="Melhor loja virtual de roupas de Caxias do Sul, My Dream Closet, meu armário dos sonhos! Venham comprar agora mesmo!">

        <link rel="shortcut icon" href="{{ URL::asset('images/My_Dream_logo_sem_fundo2.png') }}">
        <link rel="apple-touch-icon" href="{{ URL::asset('images/My_Dream_logo_sem_fundo2.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('images/My_Dream_logo_sem_fundo2.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('images/My_Dream_logo_sem_fundo2.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('images/My_Dream_logo_sem_fundo2.png') }}">

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WBW7RMC');</script>
        <!-- End Google Tag Manager -->

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-RM15RN977J"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-RM15RN977J');
        </script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <meta name="robots" content="noindex,nofollow">


        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style>
            .loading {
                display: flex;
                flex-direction: column;
                align-items: center;
                color: #434C56;
            }

            .loading-animation {
                display: flex;
            }

            .loading .glyphicon {
                animation-name: loading-spin;
                animation-timing-function: linear;
                animation-iteration-count: infinite;
            }

            .loading-big {
                font-size: 108px;
                margin-bottom: 15px;
                animation-duration: 3s;
            }

            .loading-small {
                font-size: 60px;
                align-self: flex-end;
                margin-left: -10px;
                animation-duration: 2s;
                animation-direction: reverse;
            }

            .loading-text {
                font-size: 28px;
                font-weight: bold;
            }

            @keyframes loading-spin {
                from {
                transform: rotate(0deg);
                }
                to {
                transform: rotate(360deg);
                }
            }

            #img{
                margin-left: -82px;
                margin-top: -31px;
            }

            @media (max-width: 575.98px) {
                #img{
                    margin-left: -1px;
                    margin-top: -31px;
                }
            }

            @media (max-width: 1199.98px) {
                #img{
                    margin-left: -86px;
                    margin-top: -31px;
                }
            }
            .titulo-principal:after{
                content: '|';
                margin-left: 5px;
                opacity: 1;
                animation: pisca .7s infinite;
               }
               /* Animação aplicada ao content referente a classe *.titulo-principal* resultando num efeito de cursor piscando. */

               @keyframes pisca{
                   0%, 100%{
                       opacity: 1;
                   }
                   50%{
                       opacity: 0;
                   }
               }
        </style>
        <script src="https://unpkg.com/typewriter-effect@latest/dist/core.js" charset="utf-8"></script>
    </head>
    <body class="h-100" style="background-image: url('{{ URL::asset('images/full-shot-woman-checking-wardrobe.jpeg') }}'); background-repeat: no-repeat; background-size: cover;">
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WBW7RMC"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

        <div class="d-flex h-100">
            <div class="offset-md-4 col-md-4 offset-sm-3 col-sm-6 my-auto text-center">
                <div class="shadow-sm rounded border text-dark p-5" style="background: rgba(255,255,255,.4); height: 580px;">
                    {{--  <img src="{{ URL::asset('img/cogs.gif') }}" style="height:150px;">  --}}
                    <div class="loading">
                        <div class="loading-animation">
                        <span style="color: #FF6AA0" class="glyphicon glyphicon-cog loading-big"></span>
                        <span style="color: #FF6AA0" class="glyphicon glyphicon-cog loading-small"></span>
                        </div>
                        {{--  <p class="loading-text">Loading</p>  --}}
                    </div>
                    <h2 class="my-2" >EM CONSTRUÇÃO</h2>
                    <h4 id="tw" class="my-4 titulo-principal">Site em desenvolvimento! Estamos preparando um novo site, com muitas novidades! Volte em Breve!</h4>
                    {{--  <div class="mb-2">Site em desenvolvimento.</div>  --}}
                    {{--  <div>Volte em breve!</div>  --}}
                    <div>
                        <img id="img" src="{{ URL::asset('images/My_Dream_sem_fundo_construcao.png') }}" alt="logo">
                    </div>
                </div>
            </div>
        </div>
        <script>
            function typeWrite(elemento){
                const textoArray = elemento.innerHTML.split('');
                elemento.innerHTML = ' ';
                textoArray.forEach(function(letra, i){

                setTimeout(function(){
                    elemento.innerHTML += letra;
                }, 120 * i)

              });
            }
            const titulo = document.querySelector('.titulo-principal');
            typeWrite(titulo);
        </script>
    </body>
</html>
