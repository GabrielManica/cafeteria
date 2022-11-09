<?php

class Estado extends TRecord
{
    const TABLENAME = 'estado';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    use SystemChangeLogTrait;

    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);

        parent::addAttribute('nome');
        parent::addAttribute('sigla');
        parent::addAttribute('codigo_ibge');
    }
}