<?php
/**
 * Pedido Active Record
 * @author  <your-name-here>
 */
class Pedido extends TRecord
{
    const TABLENAME = 'pedido';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $pessoa;
    private $forma_pagamento;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('forma_pagamento_id');
        parent::addAttribute('data_pedido');
        parent::addAttribute('total_bruto');
        parent::addAttribute('total_liquido');
        parent::addAttribute('total_desconto');
        parent::addAttribute('tipo_pedido');
        parent::addAttribute('data_hora_inclusao');
        parent::addAttribute('data_hora_alteracao');
        parent::addAttribute('total_quantidade');
        parent::addAttribute('observacao');
        parent::addAttribute('percentual_desconto');
        parent::addAttribute('total_frete');
        parent::addAttribute('pronta_entrega');
        parent::addAttribute('total_custo');
        parent::addAttribute('total_liquido_desconto');
    }

    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);

        // returns the associated object
        return $this->pessoa;
    }

    public function get_forma_pagamento()
    {
        // loads the associated object
        if (empty($this->forma_pagamento))
            $this->forma_pagamento = new FormaPagamento($this->forma_pagamento_id);

        // returns the associated object
        return $this->forma_pagamento;
    }
}
