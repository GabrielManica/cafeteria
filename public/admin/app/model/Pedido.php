<?php

class Pedido extends TRecord
{
    const TABLENAME  = 'pedido';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    private $cliente;



    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('total_pedido');
        parent::addAttribute('cliente_id');
        parent::addAttribute('data_pedido');

    }

    /**
     * Method set_cliente
     * Sample of usage: $var->cliente = $object;
     * @param $object Instance of Cliente
     */
    public function set_cliente(Cliente $object)
    {
        $this->cliente = $object;
        $this->cliente_id = $object->id;
    }

    /**
     * Method get_cliente
     * Sample of usage: $var->cliente->attribute;
     * @returns Cliente instance
     */
    public function get_cliente()
    {

        // loads the associated object
        if (empty($this->cliente))
            $this->cliente = new Cliente($this->cliente_id);

        // returns the associated object
        return $this->cliente;
    }

    /**
     * Method getPedidoProdutos
     */
    public function getPedidoProdutos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pedido_id', '=', $this->id));
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

        $values = PedidoProduto::where('pedido_id', '=', $this->id)->getIndexedArray('pedido_id','{pedido->id}');
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

        $values = PedidoProduto::where('pedido_id', '=', $this->id)->getIndexedArray('produto_id','{produto->id}');
        return implode(', ', $values);
    }


}
