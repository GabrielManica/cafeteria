<?php
/**
 * UnidadeMedida Active Record
 * @author  <your-name-here>
 */
class UnidadeMedida extends TRecord
{
    const TABLENAME = 'unidade_medida';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('sigla');
    }


}
