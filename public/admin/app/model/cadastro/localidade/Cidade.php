<?php
/**
 * Cidade Active Record
 * @author  <your-name-here>
 */
class Cidade extends TRecord
{
    const TABLENAME = 'cidade';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}


    private $estado;
    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('estado_id');
        parent::addAttribute('nome');
        parent::addAttribute('codigo_ibge');
    }

    public function get_estado()
    {
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);

        // returns the associated object
        return $this->estado;
    }
}
