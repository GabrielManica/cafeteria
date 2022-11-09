<?php
/**
 * PessoaEndereco Active Record
 * @author  <your-name-here>
 */
class PessoaEndereco extends TRecord
{
    const TABLENAME = 'pessoa_endereco';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $endereco_tipo;
    private $cidade;
    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('pessoa_id');
        parent::addAttribute('cidade_id');
        parent::addAttribute('endereco_tipo_id');
        parent::addAttribute('nome');
        parent::addAttribute('principal');
        parent::addAttribute('cep');
        parent::addAttribute('endereco');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('complemento');
        parent::addAttribute('ativo');
        parent::addAttribute('data_desativacao');
    }

    public function get_endereco_tipo()
    {
        // loads the associated object
        if (empty($this->endereco_tipo))
            $this->endereco_tipo = new EnderecoTipo($this->endereco_tipo_id);

        // returns the associated object
        return $this->endereco_tipo;
    }

    public function get_cidade()
    {
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);

        // returns the associated object
        return $this->cidade;
    }
}
