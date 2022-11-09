<!DOCTYPE html>
<html lang="pt-BR">

@yield('header')

<body>
    <!-- ##### Header Area Start ##### -->
    @yield('nav')
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
