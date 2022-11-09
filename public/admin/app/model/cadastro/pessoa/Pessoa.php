<?php
/**
 * Pessoa Active Record
 * @author  <your-name-here>
 */
class Pessoa extends TRecord
{
    const TABLENAME = 'pessoa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $grupo_pessoa;
    private $system_user;
    private $categoria_pessoa;
    use SystemChangeLogTrait;
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('grupo_pessoa_id');
        parent::addAttribute('system_user_id');
        parent::addAttribute('nome');
        parent::addAttribute('documento');
        parent::addAttribute('login');
        parent::addAttribute('email');
        parent::addAttribute('senha');
        parent::addAttribute('observacao');
        parent::addAttribute('ativo');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('data_desativacao');
        parent::addAttribute('data_hora_criacao');
        parent::addAttribute('data_hora_alteracao');
        parent::addAttribute('data_hora_exclusao');
    }

    public function get_grupo_pessoa()
    {
        // loads the associated object
        if (empty($this->grupo_pessoa))
            $this->grupo_pessoa = new GrupoPessoa($this->grupo_pessoa_id);

        // returns the associated object
        return $this->grupo_pessoa;
    }

    public function get_system_user()
    {
        // loads the associated object
        if (empty($this->system_user))
            $this->system_user = new SystemUsers($this->system_user_id);

        // returns the associated object
        return $this->system_user;
    }

    public function get_categoria_pessoa()
    {
        if (empty($this->categoria_pessoa))
            $this->categoria_pessoa = PessoaCategoria::where('pessoa_id', '=', $this->id)->load();

        $categorias = [];

        foreach ($this->categoria_pessoa as $value) {
            $categorias[]=$value->categoria->nome;
        }

        $this->categoria_pessoa = implode(',', $categorias);
        // returns the associated object
        return $this->categoria_pessoa;
    }

    public function get_idade()
    {
        $idade = 0;
        $data_nascimento = date('Y-m-d', strtotime($this->data_nascimento));
        list($anoNasc, $mesNasc, $diaNasc) = explode('-', $data_nascimento);

           $idade      = date("Y") - $anoNasc;
           if (date("m") < $mesNasc){
               $idade -= 1;
           } elseif ((date("m") == $mesNasc) && (date("d") <= $diaNasc) ){
               $idade -= 1;
           }

           return $idade.' anos';
    }

}
