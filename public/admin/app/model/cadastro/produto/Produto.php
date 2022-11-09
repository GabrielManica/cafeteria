<?php
/**
 * Produto Active Record
 * @author  <your-name-here>
 */
class Produto extends TRecord
{
    const TABLENAME = 'produto';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}

    private $fabricante;
    private $linha;
    private $sub_linha;
    private $fornecedor;
    private $unidade_medida;

    use SystemChangeLogTrait;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('fabricante_id');
        parent::addAttribute('linha_id');
        parent::addAttribute('sub_linha_id');
        parent::addAttribute('fornecedor_id');
        parent::addAttribute('unidade_medida_id');
        parent::addAttribute('nome');
        parent::addAttribute('codigo_barras');
        parent::addAttribute('preco_custo');
        parent::addAttribute('preco_venda');
        parent::addAttribute('peso_liquido');
        parent::addAttribute('peso_bruto');
        parent::addAttribute('largura');
        parent::addAttribute('altura');
        parent::addAttribute('volume');
        parent::addAttribute('estoque');
        parent::addAttribute('estoque_minimo');
        parent::addAttribute('observacao');
        parent::addAttribute('foto');
        parent::addAttribute('ativo');
        parent::addAttribute('data_desativacao');
        parent::addAttribute('data_hora_criacao');
        parent::addAttribute('data_hora_alteracao');
        parent::addAttribute('data_hora_exclusao');
        parent::addAttribute('preco_venda_pronta_entrega');
        parent::addAttribute('markup');
        parent::addAttribute('preco_venda_prazo');
        parent::addAttribute('preco_venda_pronta_entrega_prazo');
        parent::addAttribute('percentual_ipi');
        parent::addAttribute('percentual_icms');
        parent::addAttribute('referencia_fornecedor');
        parent::addAttribute('observacao_site');
        parent::addAttribute('produto_destaque');
    }

    public function get_fabricante()
    {
        // loads the associated object
        if (empty($this->fabricante))
            $this->fabricante = new Fabricante($this->fabricante_id);

        // returns the associated object
        return $this->fabricante;
    }

    public function get_linha()
    {
        // loads the associated object
        if (empty($this->linha))
            $this->linha = new Linha($this->linha_id);

        // returns the associated object
        return $this->linha;
    }

    public function get_sub_linha()
    {
        // loads the associated object
        if (empty($this->sub_linha))
            $this->sub_linha = new SubLinha($this->sub_linha_id);

        // returns the associated object
        return $this->sub_linha;
    }

    public function get_unidade_medida()
    {
        // loads the associated object
        if (empty($this->unidade_medida))
            $this->unidade_medida = new UnidadeMedida($this->unidade_medida_id);

        // returns the associated object
        return $this->unidade_medida;
    }

    public function get_fornecedor()
    {
        // loads the associated object
        if (empty($this->fornecedor))
            $this->fornecedor = new Pessoa($this->fornecedor_id);

        // returns the associated object
        return $this->fornecedor;
    }

}
