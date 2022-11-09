@section('script')
    <script src="{{ URL::asset('js/shop/jquery.js') }}"></script>
	<script src="{{ URL::asset('js/shop/bootstrap.min.js') }}"></script>
	<script src="{{ URL::asset('js/shop/jquery.scrollUp.min.js') }}"></script>
	<script src="{{ URL::asset('js/shop/price-range.js') }}"></script>
    <script src="{{ URL::asset('js/shop/jquery.prettyPhoto.js') }}"></script>
    <script src="{{ URL::asset('js/shop/main.js') }}"></script>
    <!-- jQuery (Necessary for All JavaScript Plugins) -->
    <script src="{{ URL::asset('js/jquery/jquery-2.2.4.min.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ URL::asset('js/popper.min.js') }}"></script>
    <!-- Bootstrap js -->
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <!-- Plugins js -->
    <script src="{{ URL::asset('js/plugins.js') }}"></script>
    <!-- Classy Nav js -->
    <script src="{{ URL::asset('js/classy-nav.min.js') }}"></script>
    <!-- Active js -->
    <script src="{{ URL::asset('js/active.js') }}"></script>

    <script src="{{ URL::asset('OwlCarrousel/dist/owl.carousel.min.js') }}"></script>

    <script>
        $("#owl-marcas").owlCarousel({
            loop: true,
            margin: 100,
            autoWidth:true,
            center: true,
            autoplay:true,
            autoplayTimeout:1500,
            margin:10,
            //navigation : false,
            //navigationText : ['<span class="fa-stack"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-chevron-circle-left fa-stack-1x fa-inverse"></i></span>','<span class="fa-stack"><i class="fa fa-circle fa-stack-1x"></i><i class="fa fa-chevron-circle-right fa-stack-1x fa-inverse"></i></span>'],
        });
    </script>
@endsection
