<?php

class Produto extends TRecord
{
    const TABLENAME  = 'produto';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}



    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('valor');
        parent::addAttribute('foto');

    }

    /**
     * Method getPedidoProdutos
     */
    public function getPedidoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('produto_id', '=', $this->id));
        return PedidoProduto::getObjects( $criteria );
    }

    public function set_pedido_produto_pedido_to_string($pedido_produto_pedido_to_string)
    {
        if(is_array($pedido_produto_pedido_to_string))
        {
            $values = Pedido::where('id', 'in', $pedido_produto_pedido_to_string)->getIndexedArray('id', 'id');
            $this->pedido_produto_pedido_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_produto_pedido_to_string = $pedido_produto_pedido_to_string;
        }

        $this->vdata['pedido_produto_pedido_to_string'] = $this->pedido_produto_pedido_to_string;
    }

    public function get_pedido_produto_pedido_to_string()
    {
        if(!empty($this->pedido_produto_pedido_to_string))
        {
            return $this->pedido_produto_pedido_to_string;
        }

        $values = PedidoProduto::where('produto_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
        return implode(', ', $values);
    }

    public function set_pedido_produto_produto_to_string($pedido_produto_produto_to_string)
    {
        if(is_array($pedido_produto_produto_to_string))
        {
            $values = Produto::where('id', 'in', $pedido_produto_produto_to_string)->getIndexedArray('id', 'id');
            $this->pedido_produto_produto_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_produto_produto_to_string = $pedido_produto_produto_to_string;
        }

        $this->vdata['pedido_produto_produto_to_string'] = $this->pedido_produto_produto_to_string;
    }

    public function get_pedido_produto_produto_to_string()
    {
        if(!empty($this->pedido_produto_produto_to_string))
        {
            return $this->pedido_produto_produto_to_string;
        }

        $values = PedidoProduto::where('produto_id', '=', $this->id)->getIndexedArray('produto_id','{produto->id}');
        return implode(', ', $values);
    }


}
