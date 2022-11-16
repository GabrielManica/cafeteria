@extends('partials.main')
@section('content')
    <!-- ##### Breadcumb Area Start ##### -->
    {{--  <div class="welcome_area bg-img background-overlay d-none d-md-block d-lg-block" style="margin-top: 86px; opacity: 0.7; height: 270px;background-repeat: no-repeat; background-size: 100% 300px; background-image: url({{ URL::asset('images/banner_loja.png') }});">  --}}

    </div>
    <div class="welcome_area bg-img background-overlay d-md-none d-lg-none" style="margin-top: 86px; opacity: 0.7; height: 170px;background-repeat: no-repeat; background-size: 100% 170px; background-image: url({{ URL::asset('images/banner_loja.png') }});">

    </div>
    <!-- ##### Breadcumb Area End ##### -->

    <!-- ##### Shop Grid Area Start ##### -->
    <section class="shop_grid_area section-padding-80" style="padding-top: 120px;">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-2 col-lg-2 d-none d-md-block d-lg-block">
                    <div class="shop_sidebar_area">

                        <!-- ##### Single Widget ##### -->
                        <div class="widget price mb-50">
                            {{--  <!-- Widget Title -->
                            <h6 class="widget-title mb-30">Pesquisar</h6>
                            <!-- Widget Title 2 -->
                            <div class="row">
                                <div class="col-12">
                                    <div>
                                        <form action="{{url('loja/pesquisa')}}" method="get">
                                            <div class="input-group custom-search-form">
                                                @if ($order!='')
                                                    <input type="hidden" id="order" name="order" value="{{$order}}">
                                                @endif
                                                <input type="text" id="pesquisa" name="pesquisa" value="{{$pesquisa}}" class="form-control" placeholder="Pesquisar Produto">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </span>
                                            </div><!-- /input-group -->
                                        </form>
                                    </div>
                                </div>
                            </div>  --}}
                            {{--  <p class="widget-title2 mb-30">Price</p>

                            <div class="widget-desc">
                                <div class="slider-range">
                                    <div data-min="49" data-max="360" data-unit="$" class="slider-range-price ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" data-value-min="49" data-value-max="360" data-label-result="Range:">
                                        <div class="ui-slider-range ui-widget-header ui-corner-all"></div>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                        <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0"></span>
                                    </div>
                                    <div class="range-price">Range: $49.00 - $360.00</div>
                                </div>
                            </div>  --}}
                        </div>

                        <!-- ##### Single Widget ##### -->
                        {{--  <div class="widget catagory mb-50">
                            <!-- Widget Title -->
                            <h6 class="widget-title mb-30">Categorias</h6>

                            <!--  Catagories  -->
                            <div class="catagories-menu">
                                <ul id="menu-content2" class="menu-content collapse show" >
                                    <!-- Single Item -->
                                    <li><a href="/loja" class="sub_linha_active">TODOS</a></li>
                                    @foreach($linhas as $l)
                                        <li data-toggle="collapse" data-target="#{{trim($l->nome)}}" aria-expanded="@if ($linha_id == $l->id) true @else false @endif" class="@if ($linha_id != $l->id) collapsed @endif">
                                            <a href="#" class="rosa">{{trim($l->nome)}}</a>
                                            <ul class="@if ($linha_id == $l->id) sub-menu collapse show @else sub-menu in collapse @endif " id="{{trim($l->nome)}}" style="height: auto;">
                                                <li><a class="@if ($linha_id == $l->id && $sub_linha_id == -1) sub_linha_active @endif " href="/loja/linha/{{$l->id}}/sublinha/-1">TODOS</a></li>
                                                @foreach($sub_linhas as $sl)
                                                    @if($sl->linha_id == $l->id)
                                                        <li><a class="@if ($sub_linha_id == $sl->id) sub_linha_active @endif " href="/loja/linha/{{$l->id}}/sublinha/{{$sl->id}}">{{$sl->nome}}</a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>  --}}



                        <!-- ##### Single Widget ##### -->
                        {{--  <div class="widget color mb-50">
                            <!-- Widget Title 2 -->
                            <p class="widget-title2 mb-30">Color</p>
                            <div class="widget-desc">
                                <ul class="d-flex">
                                    <li><a href="#" class="color1"></a></li>
                                    <li><a href="#" class="color2"></a></li>
                                    <li><a href="#" class="color3"></a></li>
                                    <li><a href="#" class="color4"></a></li>
                                    <li><a href="#" class="color5"></a></li>
                                    <li><a href="#" class="color6"></a></li>
                                    <li><a href="#" class="color7"></a></li>
                                    <li><a href="#" class="color8"></a></li>
                                    <li><a href="#" class="color9"></a></li>
                                    <li><a href="#" class="color10"></a></li>
                                </ul>
                            </div>
                        </div>  --}}

                        <!-- ##### Single Widget ##### -->
                        {{--  <div class="widget brands mb-50">
                            <!-- Widget Title 2 -->
                            <p class="widget-title2 mb-30">Brands</p>
                            <div class="widget-desc">
                                <ul>
                                    <li><a href="#">Asos</a></li>
                                    <li><a href="#">Mango</a></li>
                                    <li><a href="#">River Island</a></li>
                                    <li><a href="#">Topshop</a></li>
                                    <li><a href="#">Zara</a></li>
                                </ul>
                            </div>
                        </div>  --}}
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 d-md-none d-lg-none">
                    <div class="shop_sidebar_area">

                        <!-- ##### Single Widget ##### -->

                        <div class="widget catagory mb-50" style="margin-top: -60px;">
                            <button class="btn btn-primary" id="collapsible" type="button" data-toggle="collapse" data-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                                <i class="fas fa-search"></i> Pesquisar Produtos
                            </button>
                            <div style="">
                                <div class="collapse width" id="collapseWidthExample" style="margin-top: 10px;">
                                    <div class="widget price mb-50" style="margin-bottom: 10px !important;">
                                        <!-- Widget Title -->
                                        <h6 class="widget-title mb-30" style="margin-bottom: 2px !important;">Pesquisar</h6>
                                        <!-- Widget Title 2 -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div>
                                                    <form action="{{url('loja/pesquisa')}}" method="get">
                                                        <div class="input-group custom-search-form">
                                                            @if ($order!='')
                                                                <input type="hidden" id="order" name="order" value="{{$order}}">
                                                            @endif
                                                            <input type="text" id="pesquisa" name="pesquisa" value="{{$pesquisa}}" class="form-control" placeholder="Pesquisar Produto">
                                                            <span class="input-group-btn">
                                                                <button class="btn btn-default" type="button">
                                                                    <i class="fas fa-search"></i>
                                                                </button>
                                                            </span>
                                                        </div><!-- /input-group -->
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Widget Title -->
                                    {{--  <h6 class="widget-title mb-30" style="margin-bottom: 15px !important;">Categorias</h6>  --}}

                                    <!--  Catagories  -->
                                    {{--  <div class="catagories-menu">
                                        <ul id="menu-content2" class="menu-content collapse show" >
                                            <!-- Single Item -->
                                            <li><a href="/loja" class="sub_linha_active">TODOS</a></li>
                                            @foreach($linhas as $l)
                                                <li data-toggle="collapse" data-target="#{{trim($l->nome)}}" aria-expanded="@if ($linha_id == $l->id) true @else false @endif" class="@if ($linha_id != $l->id) collapsed @endif">
                                                    <a href="#" class="rosa">{{trim($l->nome)}}</a>
                                                    <ul class="@if ($linha_id == $l->id) sub-menu collapse show @else sub-menu in collapse @endif " id="{{trim($l->nome)}}" style="height: auto;">
                                                        <li><a class="@if ($linha_id == $l->id && $sub_linha_id == -1) sub_linha_active @endif " href="/loja/linha/{{$l->id}}/sublinha/-1">TODOS</a></li>
                                                        @foreach($sub_linhas as $sl)
                                                            @if($sl->linha_id == $l->id)
                                                                <li><a class="@if ($sub_linha_id == $sl->id) sub_linha_active @endif " href="/loja/linha/{{$l->id}}/sublinha/{{$sl->id}}">{{$sl->nome}}</a></li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>  --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-8 col-lg-9">
                    <div class="shop_grid_product_area">
                        <div class="row">
                            <div class="col-12">
                                <div class="product-topbar d-flex align-items-center justify-content-between">
                                    <!-- Total Products -->
                                    <div class="total-products">
                                        <p><span class="rosa">{{$total_produtos}}</span> Produtos Encontrados</p>
                                    </div>
                                    <!-- Sorting -->
                                    {{--  <div class="product-sorting d-flex">
                                        <p>Ordenar Por:</p>
                                        <form action="" method="get">
                                            @if ($pesquisa!='')
                                                <input type="hidden" id="pesquisa" name="pesquisa" value="{{$pesquisa}}">
                                            @endif
                                            <select name="order" id="sortByselect" onChange="this.form.submit();">
                                                <option value="nome" @if($order == 'nome') selected @endif>Alfabética</option>
                                                <option value="preco_venda_prazo asc" @if($order == 'preco_venda_prazo asc') selected @endif>Menor Valor</option>
                                                <option value="preco_venda_prazo desc" @if($order == 'preco_venda_prazo desc') selected @endif>Maior Valor</option>
                                            </select>
                                            <input type="submit" class="d-none" value="">
                                        </form>
                                    </div>  --}}
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <!-- Single Product -->
                            @foreach($produtos as $p)
                                <a href="/produto/{{ $p->id }}" class="col-12 col-sm-6 col-lg-4">
                                    <div class="single-product-wrapper">
                                        <!-- Product Image -->
                                        <div class="product-img">
                                            <img src="{{ URL::asset('admin') }}/{{$p->foto}}" alt="">
                                            <!-- Hover Thumb -->
                                            {{--  <img class="hover-img" src="img/product-img/product-2.jpg" alt="">  --}}

                                            <!-- Product Badge -->
                                            {{--  <div class="product-badge offer-badge">
                                                <span>-30%</span>
                                            </div>  --}}
                                            <!-- Favourite -->
                                            {{--  <div class="product-favourite">
                                                <a href="#" class="favme fa fa-heart"></a>
                                            </div>  --}}
                                        </div>

                                        <!-- Product Description -->
                                        <div class="product-description">
                                            {{--  <span>{{$p->linha->nome}} / {{$p->sub_linha->nome}}</span>  --}}

                                            <h4 style="color: red">{{$p->nome}}</h4>

                                            <p class="product-price">R$ {{ number_format($p->valor, 2, ",", ".") }}</p>
                                            {{--  <p class="product-price"><span class="old-price">$75.00</span> $55.00</p>  --}}

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
                    <!-- Pagination -->
                    {{--  <nav aria-label="navigation">
                        <ul class="pagination mt-50 mb-70">
                            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-left"></i></a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">...</a></li>
                            <li class="page-item"><a class="page-link" href="#">21</a></li>
                            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </nav>  --}}
                </div>
            </div>
        </div>
    </section>
    <!-- ##### Shop Grid Area End ##### -->
@endsection
