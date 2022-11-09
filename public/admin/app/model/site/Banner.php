<?php

class Banner extends TRecord
{
    const TABLENAME = 'banner';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('titulo');
        parent::addAttribute('sub_titulo');
        parent::addAttribute('imagem');
        parent::addAttribute('conteudo');
        parent::addAttribute('ativo');
        parent::addAttribute('mostrar_so_imagem');
        parent::addAttribute('link');
    }


}
