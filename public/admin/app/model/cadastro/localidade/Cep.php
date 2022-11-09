<?php
/**
 * Cep Active Record
 * @author  <your-name-here>
 */
class Cep extends TRecord
{
    const TABLENAME = 'cep';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('cidade_id');
        parent::addAttribute('estado_id');
        parent::addAttribute('cep');
        parent::addAttribute('rua');
        parent::addAttribute('cidade');
        parent::addAttribute('bairro');
        parent::addAttribute('codigo_ibge');
        parent::addAttribute('sigla');
    }


}
