<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Database\TFilter;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TImage;
use Adianti\Widget\Form\TNumeric;
use Adianti\Database\TTransaction;
use Adianti\Widget\Util\TDropDown;
use GX4\Trait\FormTrait\FormTrait;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Widget\Form\TCheckButton;
use Adianti\Widget\Form\TImageCropper;
use Adianti\Wrapper\BootstrapFormBuilder;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class ProdutoForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_Produto';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-cube fa-fw nav-icon"></i> Produto';

    use Adianti\Base\AdiantiFileSaveTrait;
    use FormTrait;

    function __construct($param)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id         = new TEntry('id');
        $nome       = new TEntry('nome');
        $referencia_fornecedor       = new TEntry('referencia_fornecedor');
        $foto       = new TImageCropper('foto');

        $criteria = new TCriteria;
        $criteria->add(new TFilter('id','in','(select pessoa_id from pessoa_categoria where categoria_pessoa_id = 5)'));

        $criteria->add( new TFilter('ativo', '=', 'S'), TExpression::OR_OPERATOR );

        if(isset($param['fornecedor_id']))
        {
            $criteria->add( new TFilter('id', '=', "{$param['fornecedor_id']}"),TExpression::OR_OPERATOR );
        }

        $fornecedor                       = new TDBCombo('fornecedor_id', 'erp', 'Pessoa', 'id', 'nome','nome asc', $criteria );
        $linha_id                         = new TDBCombo('linha_id', 'erp', 'Linha', 'id', 'nome','nome asc');
        $sub_linha_id                     = new TCombo('sub_linha_id');
        $fabricante_id                    = new TDBCombo('fabricante_id', 'erp', 'Fabricante', 'id', 'nome','nome asc');
        $unidade_medida_id                = new TDBCombo('unidade_medida_id', 'erp', 'UnidadeMedida', 'id', 'nome','nome asc');
        $preco_custo                      = new TNumeric('preco_custo', '2', ',', '.' );
        $preco_venda                      = new TNumeric('preco_venda', '2', ',', '.' );
        $preco_venda_prazo                = new TNumeric('preco_venda_prazo', '2', ',', '.' );
        $preco_venda_pronta_entrega       = new TNumeric('preco_venda_pronta_entrega', '2', ',', '.' );
        $preco_venda_pronta_entrega_prazo = new TNumeric('preco_venda_pronta_entrega_prazo', '2', ',', '.' );
        $markup                           = new TNumeric('markup', '2', ',', '.' );
        $parcentual_ipi                   = new TNumeric('percentual_ipi', '2', ',', '.' );
        $parcentual_icms                  = new TNumeric('percentual_icms', '2', ',', '.' );
        $estoque                          = new TNumeric('estoque', '0', '', '' );
        $estoque_minimo                   = new TNumeric('estoque_minimo', '0', '', '' );
        $ativo                            = new TCheckButton('ativo');
        $produto_destaque                 = new TCheckButton('produto_destaque');
        $data_desativacao                 = new TEntry('data_desativacao');
        $codigo_barra                     = new TNumeric('codigo_barras', '0', '', '' );
        $observacao                       = new TText('observacao');
        $observacao_site                  = new THtmlEditor('observacao_site');

        $id->setEditable(FALSE);
        $data_desativacao->setEditable(FALSE);
        $data_desativacao->setSize('100%');
        $codigo_barra->setSize('100%');

        // $preco_custo->setExitAction(new TAction([$this,'onExitValor']));

        $id->forceUpperCase();
        $nome->forceUpperCase();
        $referencia_fornecedor->forceUpperCase();

        $foto->enableFileHandling();
        $foto->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $foto->setWindowTitle("Upload de Foto");
        $foto->setImagePlaceholder(new TImage("fas:file-upload"));

        $foto->setSize(160, 260);

        $fornecedor->enableSearch();
        $fornecedor->setSize('100%');

        $linha_id->enableSearch();
        $linha_id->setSize('100%');
        $linha_id->setChangeAction(new TAction([$this,'onChangeLinha'],['static'=>'1']));

        $sub_linha_id->enableSearch();
        $sub_linha_id->setSize('100%');

        $fabricante_id->enableSearch();
        $fabricante_id->setSize('100%');

        $unidade_medida_id->enableSearch();
        $unidade_medida_id->setSize('100%');

        $preco_custo->setValue('0,00');
        $preco_custo->setSize('100%');

        $markup->setValue('0,00');
        $markup->setSize('100%');
        $markup->setEditable(false);

        $parcentual_ipi->setValue('3,00');
        $parcentual_ipi->setSize('100%');

        $parcentual_icms->setValue('3,00');
        $parcentual_icms->setSize('100%');

        $preco_venda_pronta_entrega->setValue('0,00');
        $preco_venda_pronta_entrega->setSize('100%');
        $preco_venda_pronta_entrega->setEditable(false);

        $preco_venda_pronta_entrega_prazo->setValue('0,00');
        $preco_venda_pronta_entrega_prazo->setSize('100%');
        $preco_venda_pronta_entrega_prazo->setEditable(false);

        $preco_venda->setValue('0,00');
        $preco_venda->setSize('100%');
        $preco_venda->setEditable(false);

        $preco_venda_prazo->setValue('0,00');
        $preco_venda_prazo->setSize('100%');
        $preco_venda_prazo->setEditable(false);

        $estoque->setValue('0');
        $estoque->setSize('100%');
        $estoque->setEditable(false);

        $estoque_minimo->setValue('0');
        $estoque_minimo->setSize('100%');

        $ativo->setIndexValue('S');
        $ativo->setValue('S');
        $ativo->setUseSwitch(true, 'blue');

        $produto_destaque->setIndexValue('S');
        $produto_destaque->setValue('N');
        $produto_destaque->setUseSwitch(true, 'blue');

        $bcontainer_62827f23f7f48 = new BootstrapFormBuilder('bcontainer_62827f23f7f48');
        $this->bcontainer_62827f23f7f48 = $bcontainer_62827f23f7f48;
        $bcontainer_62827f23f7f48->setProperty('style', 'border:none; box-shadow:none;');
        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Descrição', 'red'),  $nome       ],
            [ new TLabel('Fornecedor', 'red'), $fornecedor ],
            [ new TLabel('ID'),                $id         ],
        );

        $row->layout = [
            'col-12  col-sm-6',
            'col-10  col-sm-5',
            'col-2   col-sm-1',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Linha', 'red'),             $linha_id          ],
            [ new TLabel('Sub Linha', 'red'),         $sub_linha_id      ],
            [ new TLabel('Fabricante', 'red'),        $fabricante_id     ],
            [ new TLabel('Unidade de Medida', 'red'), $unidade_medida_id ],
        );

        $row->layout = [
            'col-12  col-sm-3',
            'col-12  col-sm-3',
            'col-12  col-sm-3',
            'col-12  col-sm-3',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Preço Custo', 'red'),                $preco_custo     ],
            [ new TLabel('Markup %'),                          $markup          ],
            [ new TLabel('% IPI', 'red'),                      $parcentual_ipi  ],
            [ new TLabel('% ICMS', 'red'),                     $parcentual_icms ],
        );

        $row->layout = [
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Preço Venda', 'red'),                $preco_venda                      ],
            [ new TLabel('Preço Venda Prazo', 'red'),          $preco_venda_prazo                ],
            [ new TLabel('Preço Venda P. Ent.', 'red'),        $preco_venda_pronta_entrega       ],
            [ new TLabel('Preço Venda P. Ent. Prazo', 'red'),  $preco_venda_pronta_entrega_prazo ],
        );

        $row->layout = [
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Estoque'),                    $estoque                          ],
            [ new TLabel('Estoque Mínimo', 'red'),             $estoque_minimo                   ],
            [ new TLabel('Ativo', 'red'),          $ativo            ],
            [ new TLabel('Data Desativação'),      $data_desativacao ],
            [ new TLabel('Destaque', 'red'),      $produto_destaque ],
        );

        $row->layout = [
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-3',
            'col-6  col-sm-2',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Foto", null, '14px', null, '100%'),$foto],
            [$bcontainer_62827f23f7f48],
        );

        $row1->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-10',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Código de Barras", null, '14px', null, '100%'),$codigo_barra],
            [ new TLabel("Referência Fornecedor", 'red', '14px', null, '100%'),$referencia_fornecedor],
        );

        $row1->layout = [
            'col-12  col-sm-6',
            'col-12  col-sm-6',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Observação Interno", null, '14px', null, '100%'),$observacao],
        );

        $row1->layout = [
            'col-12  col-sm-12',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Observação Site", null, '14px', null, '100%'),$observacao_site],
        );

        $row1->layout = [
            'col-12  col-sm-12',
        ];

        $id->setSize('100%');
        $referencia_fornecedor->setSize('100%');
        $nome->setSize('100%');
        $observacao->setSize('100%', 100);
        $observacao_site->setSize('100%', 400);

        if ($this::isMobile())
        {
            $this->dropdown_acoes = new TDropDown('Ações', 'fa:list');
            $this->dropdown_acoes->setButtonClass('btn btn-default waves-effect dropdown-toggle');

            $this->dropdown_acoes->addPostAction('Salvar', new TAction([$this, 'onSave']), self::$formName, 'fa:save green');
            $this->dropdown_acoes->addPostAction('Novo', new TAction([$this, 'onEdit']), self::$formName, 'fa:plus blue');

            $this->form->addHeaderWidget($this->dropdown_acoes);
        } else{
            $this->form->addHeaderAction('Salvar',  new TAction([$this, 'onSave']), 'fa:save green');
            $this->form->addHeaderAction('Novo', new TAction([$this, 'onEdit']), 'fa:plus blue');
        }


        // add the form to the page
        parent::add($this->form);
    }

    public static function onExitValor($param = null)
    {
        $object = new stdClass();
        $rateio_despesa = (1500/7000)*100;
        $despesa_lucro_rateio = 6.74+30+$rateio_despesa;
        $object->markup = number_format(100-$despesa_lucro_rateio, 2, ',', '.');

        $valor_semformato = str_replace('.','',$param['preco_custo']);
        $valor_semformato = str_replace(',','.',$valor_semformato);

        $ipi = ($valor_semformato*0.03);
        $icms = ($valor_semformato*0.03);
        $estoque = ($valor_semformato*0.02);

        $valor_pronta_entrega = $ipi+$icms+$valor_semformato;
        $valor = $ipi+$icms+$estoque+$valor_semformato;

        $valor       = ($valor/(100-$despesa_lucro_rateio))*100;
        $valor_prazo = $valor + ((($valor/(100-$despesa_lucro_rateio))*100)*0.08);

        $valor_pronta_entrega = ($valor_pronta_entrega/(100-$despesa_lucro_rateio))*100;

        $object->preco_venda = number_format($valor, 2, ',', '.');
        $object->preco_venda_prazo = number_format($valor_prazo, 2, ',', '.');

        $desconto_pronta_entrega = $valor_pronta_entrega - ($valor_pronta_entrega * 0.30);
        $desconto_pronta_entrega_prazo = $valor_pronta_entrega + (($valor_pronta_entrega - ($valor_pronta_entrega * 0.30))*0.08);

        $object->preco_venda_pronta_entrega = number_format($desconto_pronta_entrega, 2, ',', '.');
        $object->preco_venda_pronta_entrega_prazo = number_format($desconto_pronta_entrega_prazo, 2, ',', '.');

        TForm::sendData(self::$formName, $object);
    }

    public function onChangeLinha( $param = null)
    {
        try
        {
            TTransaction::open(self::$database);

            if (!empty($param['linha_id']))
            {
                $criteria = TCriteria::create( ['linha_id' => $param['linha_id'] ] );

                TDBCombo::reloadFromModel(self::$formName, 'sub_linha_id', 'erp', 'SubLinha', 'id', 'nome', 'nome asc', $criteria, TRUE);
            }

            TTransaction::close();

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];
                TTransaction::open(self::$database);
                $object        = new self::$activeRecord($key);

                if (!empty($object->linha_id))
                {
                    $criteria = TCriteria::create( ['linha_id' => $object->linha_id ] );

                    TDBCombo::reloadFromModel(self::$formName, 'sub_linha_id', 'erp', 'SubLinha', 'id', 'nome', 'nome asc', $criteria, FALSE);
                }

                $this->form->setData($object);

                TTransaction::close();

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                    $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage(), null, 'Erro ao editar registro');
            TTransaction::rollback();
        }
    }

    public function onDelete($param = null)
    {
        if (isset($param['delete']) && $param['delete'] == 1) {
            try {
                TTransaction::open(self::$database);
                $object = new self::$activeRecord($param['key'], FALSE);
                $object->delete();
                TTransaction::close();

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['ProdutoList', 'onReload']), 'Sucesso');
            } catch (Exception $e) {
                new TMessage('error', '<b style="font-size: 15px;">Não foi possivel excluir!</b><br><br><p style="font-size: 12px;"><b>Erro Banco de Dados </b>='.$e->getMessage().'</p>', null, 'Impossível excluir!' );
            }
        } else {
            $action = new TAction([$this, 'onDelete']);
            $param['delete'] = 1;
            $action->setParameters($param);

            new TQuestion("Deseja excluir o registro?", $action);
        }
    }

    public function onSave( $param )
    {
        try
        {
            TTransaction::open(self::$database);

            $this->form->validate();
            $data = $this->form->getData();

            if($data->produto_destaque == '')
            {
                $data->produto_destaque = 'N';
            }

            if($data->ativo == '')
            {
                $data->ativo = 'N';
            }

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            $this->saveFile($object, $data, 'foto', 'app/fotos/produtos');

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
            }

            TTransaction::close();

            new TMessage('info', 'Registro salvo com sucesso!', new TAction([$this, 'onEdit'],['key'=>$key]), 'Sucesso');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
}
