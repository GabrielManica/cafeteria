@extends('partials.main')
@section('content')
    <!-- ##### Welcome Area Start ##### -->

    <section class="welcome_area bg-img background-overlay" style="height: auto;">
        <div class="h-100">
            <div class="h-100 align-items-center">
                <div class="col-12" style="padding-left: 0px;padding-right: 0px;">
                    <section id="slider"><!--slider-->
                        <div>
                            <div>
                                <div class="col-sm-12" style="padding-left: 0px;padding-right: 0px;">
                                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                                        @php($i=0)
                                        <ol class="carousel-indicators">
                                            @foreach($banner as $b)
                                            <li data-target="#slider-carousel" data-slide-to="{{ $i }}" @if ($i === 0) class="active" @endif></li>
                                            @php($i++)
                                            @endforeach
                                        </ol>
                                        @php($i=0)
                                        <div class="carousel-inner">
                                            @foreach($banner as $b)
                                                <div class="item @if ($i === 0) active @endif">
                                                    <a href="@if ($b->link) {{$b->link}} @else # @endif">
                                                    @if ($b->mostrar_so_imagem == 'N')
                                                            <div class="col-sm-6 col-6 banner-item">
                                                                <h1>{{ $b->titulo }}</h1>
                                                                <h2>{{ $b->sub_titulo }}</h2>
                                                                <p>{!! $b->conteudo !!}</p>
                                                                {{--  <button type="button" class="btn btn-default get">Get it now</button>  --}}
                                                            </div>
                                                            <div class="col-sm-6 col-6">
                                                                <img src="admin/{{ $b->imagem }}" class="girl img-responsive" alt="" />
                                                            </div>
                                                        @else
                                                            <div class="col-sm-12 col-12">
                                                                <img src="admin/{{ $b->imagem }}" class="girl img-responsive d-none d-md-block d-lg-block" alt="" />
                                                                <img src="admin/{{ $b->imagem }}" class="girl img-responsive d-md-none d-lg-none" style="height: 200px;" alt="" />
                                                            </div>
                                                        @endif
                                                    </a>
                                                </div>
                                                @php($i++)
                                            @endforeach

                                        </div>

                                        {{--  <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                                            <i class="fa fa-angle-left"></i>
                                        </a>
                                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                                            <i class="fa fa-angle-right"></i>
                                        </a>  --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Welcome Area End ##### -->

    <!-- ##### Top Catagory Area Start ##### -->
    {{--  <div class="top_catagory_area section-padding-80 clearfix">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Single Catagory -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(img/bg-img/bg-2.jpg);">
                        <div class="catagory-content">
                            <a href="#">Clothing</a>
                        </div>
                    </div>
                </div>
                <!-- Single Catagory -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(img/bg-img/bg-3.jpg);">
                        <div class="catagory-content">
                            <a href="#">Shoes</a>
                        </div>
                    </div>
                </div>
                <!-- Single Catagory -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="single_catagory_area d-flex align-items-center justify-content-center bg-img" style="background-image: url(img/bg-img/bg-4.jpg);">
                        <div class="catagory-content">
                            <a href="#">Accessories</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  --}}
    <!-- ##### Top Catagory Area End ##### -->

    <!-- ##### CTA Area Start ##### -->
    {{--  <div class="cta-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="cta-content bg-img background-overlay" style="background-image: url(img/bg-img/bg-5.jpg);">
                        <div class="h-100 d-flex align-items-center justify-content-end">
                            <div class="cta--text">
                                <h6>-60%</h6>
                                <h2>Global Sale</h2>
                                <a href="#" class="btn essence-btn">Buy Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  --}}
    <!-- ##### CTA Area End ##### -->

    <!-- ##### New Arrivals Area Start ##### -->
    <section class="new_arrivals_area section-padding-80 clearfix">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h2>Produtos Destaques</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="popular-products-slides owl-carousel">

                        <!-- Single Product -->
                        @foreach($produto as $p)
                            <a href="/produto/{{ $p->id }}">
                                <div class="single-product-wrapper">
                                    <!-- Product Image -->
                                    <div class="product-img">
                                        <img src="admin/{{$p->foto}}" alt="">
                                        <!-- Hover Thumb -->
                                        {{--  <img class="hover-img" src="img/product-img/product-2.jpg" alt="">  --}}
                                        <!-- Favourite -->
                                        {{--  <div class="product-favourite">
                                            <a href="#" class="favme fa fa-heart"></a>
                                        </div>  --}}
                                    </div>
                                    <!-- Product Description -->
                                    <div class="product-description">
                                        <span>{{$p->linha->nome}} / {{$p->sub_linha->nome}}</span>
                                            <h4 style="color: #FF6AA0">{{$p->nome}}</h4>
                                        <p class="product-price">R$ {{ number_format($p->preco_venda_prazo, 2, ",", ".") }}</p>

                                        <!-- Hover Content -->
                                        {{--  <div class="hover-content">
                                            <!-- Add to Cart -->
                                            <div class="add-to-cart-btn">
                                                <a href="#" class="btn essence-btn">Add to Cart</a>
                                            </div>
                                        </div>  --}}
                                    </div>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ##### New Arrivals Area End ##### -->

    <!-- ##### Brands Area Start ##### -->
    <div id="owl-marcas" class="owl-carousel" style="margin-bottom: 20px;">
        @foreach($fabricante as $f)
            <div class="item2">
                <img src="admin/{{ $f->logo }}" alt="" style="opacity: 1;max-width: 110px;max-height: 150px;">
            </div>
        @endforeach
    </div>
    <!-- ##### Brands Area End ##### -->
@endsection
