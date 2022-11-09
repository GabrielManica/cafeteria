<?php
/**
 * Fabricante Active Record
 * @author  <your-name-here>
 */
class Fabricante extends TRecord
{
    const TABLENAME = 'fabricante';
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
        parent::addAttribute('logo');
        parent::addAttribute('preview_site');
    }


}
