<?php

class Cliente extends TRecord
{
    const TABLENAME  = 'cliente';
    const PRIMARYKEY = 'id';
    const IDPOLICY   =  'serial'; // {max, serial}

    

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('documento');
        parent::addAttribute('celular');
            
    }

    /**
     * Method getEnderecos
     */
    public function getEnderecos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Endereco::getObjects( $criteria );
    }
    /**
     * Method getPedidos
     */
    public function getPedidos()
    {
        $criteria = new TCriteria;
        $criteria->add(new TFilter('cliente_id', '=', $this->id));
        return Pedido::getObjects( $criteria );
    }

    public function set_endereco_cliente_to_string($endereco_cliente_to_string)
    {
        if(is_array($endereco_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $endereco_cliente_to_string)->getIndexedArray('id', 'id');
            $this->endereco_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->endereco_cliente_to_string = $endereco_cliente_to_string;
        }

        $this->vdata['endereco_cliente_to_string'] = $this->endereco_cliente_to_string;
    }

    public function get_endereco_cliente_to_string()
    {
        if(!empty($this->endereco_cliente_to_string))
        {
            return $this->endereco_cliente_to_string;
        }
    
        $values = Endereco::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->id}');
        return implode(', ', $values);
    }

    public function set_pedido_cliente_to_string($pedido_cliente_to_string)
    {
        if(is_array($pedido_cliente_to_string))
        {
            $values = Cliente::where('id', 'in', $pedido_cliente_to_string)->getIndexedArray('id', 'id');
            $this->pedido_cliente_to_string = implode(', ', $values);
        }
        else
        {
            $this->pedido_cliente_to_string = $pedido_cliente_to_string;
        }

        $this->vdata['pedido_cliente_to_string'] = $this->pedido_cliente_to_string;
    }

    public function get_pedido_cliente_to_string()
    {
        if(!empty($this->pedido_cliente_to_string))
        {
            return $this->pedido_cliente_to_string;
        }
    
        $values = Pedido::where('cliente_id', '=', $this->id)->getIndexedArray('cliente_id','{cliente->id}');
        return implode(', ', $values);
    }

    
}

