<?php
/**
 * SubLinha Active Record
 * @author  <your-name-here>
 */
class SubLinha extends TRecord
{
    const TABLENAME = 'sub_linha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $linha;
    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('linha_id');
        parent::addAttribute('nome');
    }

    public function get_linha()
    {
        // loads the associated object
        if (empty($this->linha))
            $this->linha = new Linha($this->linha_id);

        // returns the associated object
        return $this->linha;
    }

}
