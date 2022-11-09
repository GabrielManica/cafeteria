<?php
/**
 * PessoaContato Active Record
 * @author  <your-name-here>
 */
class PessoaContato extends TRecord
{
    const TABLENAME = 'pessoa_contato';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('email');
        parent::addAttribute('nome');
        parent::addAttribute('telefone');
        parent::addAttribute('celular');
        parent::addAttribute('observacao');
    }


}
