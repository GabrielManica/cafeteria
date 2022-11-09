<?php
/**
 * GrupoPessoa Active Record
 * @author  <your-name-here>
 */
class GrupoPessoa extends TRecord
{
    const TABLENAME = 'grupo_pessoa';
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
    }


}
