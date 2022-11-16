<!DOCTYPE html>
<html lang="pt-BR">

@yield('header')

<body>
    @if (Session::get('mensagem'))
        @php
            $mensagem = Session::get('mensagem');
            Session::put('mensagem', null);
        @endphp

        <script>
            Swal.fire({
                allowOutsideClick: false,
                allowEscapeKey: false,
                title: 'Sucesso!',
                html: "{{$mensagem['mensagem']}}",
                icon: "{{$mensagem['type']}}",
                confirmButtonText: 'OK',
                confirmButtonColor: '#236BB0'
            });
        </script>
    @endif
    <!-- ##### Header Area Start ##### -->
    @yield('nav')
    @yield('carrinho')
    <!-- ##### Header Area End ##### -->

    <!-- ##### Right Side Cart Area ##### -->
    <div class="cart-bg-overlay"></div>

    @yield('content')

    <!-- ##### Footer Area Start ##### -->
    @yield('footer')
    <!-- ##### Footer Area End ##### -->

    @yield('script')


</body>

</html>
