<?php
/**
 * Linha Active Record
 * @author  <your-name-here>
 */
class Linha extends TRecord
{
    const TABLENAME = 'linha';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $sub_linha;
    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }

    public function get_sub_linha()
    {
        if (empty($this->sub_linha))
            $this->sub_linha = SubLinha::where('linha_id', '=', $this->id)->load();

        $sub_linha = [];

        foreach ($this->sub_linha as $value) {
            $sub_linha[]=$value->nome;
        }

        $this->sub_linha = implode(', ', $sub_linha);
        // returns the associated object
        return $this->sub_linha;
    }
}
