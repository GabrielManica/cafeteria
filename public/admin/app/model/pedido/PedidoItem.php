<?php
/**
 * PedidoItem Active Record
 * @author  <your-name-here>
 */
class PedidoItem extends TRecord
{
    const TABLENAME = 'pedido_item';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}


    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('valor_bruto');
        parent::addAttribute('valor_liquido');
        parent::addAttribute('valor_desconto');
        parent::addAttribute('valor');
        parent::addAttribute('pedido_id');
        parent::addAttribute('produto_nome');
        parent::addAttribute('quantidade');
        parent::addAttribute('data_hora_inclusao');
        parent::addAttribute('data_hora_alteracao');
        parent::addAttribute('referencia_fornecedor');
        parent::addAttribute('valor_custo');
    }


}
