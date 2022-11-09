@extends('partials.main')
@section('content')
    <!-- ##### Single Product Details Area Start ##### -->
    <section class="single_product_details_area d-flex align-items-center">

        <!-- Single Product Thumb -->
        <div class="single_product_thumb clearfix" style="margin-top: 100px;">
            <div class="product_thumbnail_slides owl-carousel">
                <img src="https://mydreamcloset.com.br/admin/{{$produto->foto}}" alt="" style="height: 500px; width: 500px; margin-left: 40px;">
                <img src="https://mydreamcloset.com.br/admin/{{$produto->foto}}" alt="" style="height: 500px; width: 500px; margin-left: 40px;">
            </div>
        </div>

        <!-- Single Product Description -->
        <div class="single_product_desc clearfix">
            <span>{{$produto->linha->nome}} / {{$produto->sub_linha->nome}}</span>
            <h2 style="color: #FF6AA0">{{$produto->nome}}</h2>
            {{--  <p class="product-price"><span class="old-price">$65.00</span> $49.00</p>  --}}
            <p class="product-price" style="color: black">R$ {{ number_format($produto->preco_venda_prazo, 2, ",", ".") }}</p>
            <p class="product-desc">
                {!! $produto->observacao_site !!}
            </p>
        </div>
    </section>
    <!-- ##### Single Product Details Area End ##### -->
@endsection
