@section('carrinho')
    <div class="right-side-cart-area">

        <!-- Cart Button -->
        <div class="cart-button">
            <a href="#" id="rightSideCart"><img src="https://cafeteria.gbsolutions.com.br/img/core-img/bag.svg" alt=""> <span>{{$total_carrinho}}</span></a>
        </div>

        <div class="cart-content d-flex">

            <!-- Cart List Area -->
            <div class="cart-list">
                @if (Session::get('produtos_carinho'))
                    @foreach(Session::get('produtos_carinho') as $p)
                        <div class="single-cart-item">
                            <a href="#" class="product-image">
                                <img src="https://cafeteria.gbsolutions.com.br/admin/{{$p->foto}}" class="cart-thumb" alt="">
                                <!-- Cart Item Desc -->
                                <div class="cart-item-desc">
                                <span class="product-remove"><i class="fa fa-close" aria-hidden="true"></i></span>
                                    <h6>{{$p->nome}}</h6>
                                    <p class="price">R$ {{ number_format($p->valor, 2, ",", ".") }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Cart Summary -->
            <div class="cart-amount-summary">
                <form action="/finalizar" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group" style="margin-top: -90px;">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu Nome">
                    </div>
                    <div class="form-group">
                        <label for="nome">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf" placeholder="Digite seu CPF">
                    </div>
                    <h2>Total</h2>
                    <ul class="summary-table">
                        @if (Session::get('total_valor_carrinho'))
                            <li><span>total:</span> <span>R$ {{ number_format(Session::get('total_valor_carrinho'), 2, ",", ".") }}</span></li>
                        @else
                            <li><span>total:</span><span>R$ 0,00</span></li>
                        @endif
                    </ul>
                    <div class="checkout-btn mt-100">
                        <input type="submit" class="btn btn-success" value="Comprar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- ##### Right Side Cart End ##### -->
@endsection
