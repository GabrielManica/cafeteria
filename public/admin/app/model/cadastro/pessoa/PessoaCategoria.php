<?php
/**
 * PessoaCategoria Active Record
 * @author  <your-name-here>
 */
class PessoaCategoria extends TRecord
{
    const TABLENAME = 'pessoa_categoria';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $categoria;
    use SystemChangeLogTrait;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('categoria_pessoa_id');
    }

    public function get_categoria()
    {
        // loads the associated object
        if (empty($this->categoria))
            $this->categoria = new CategoriaPessoa($this->categoria_pessoa_id);

        // returns the associated object
        return $this->categoria;
    }
}
