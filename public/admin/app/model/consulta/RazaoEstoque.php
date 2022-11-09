<?php
/**
 * RazaoEstoque Active Record
 * @author  <your-name-here>
 */
class RazaoEstoque extends TRecord
{
    const TABLENAME = 'razao_estoque';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $produto;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('pedido_id');
        parent::addAttribute('tipo');
        parent::addAttribute('estoque_anterior');
        parent::addAttribute('estoque_atual');
        parent::addAttribute('total_quantidade');
        parent::addAttribute('data_hora_inclusao');
        parent::addAttribute('observacao');
    }

    public function get_produto()
    {
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->produto_id);

        // returns the associated object
        return $this->produto;
    }

}
