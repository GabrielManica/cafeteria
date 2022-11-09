<?php

use GX4\Trait\FormTrait\FormTrait;
/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class PedidoSaidaForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Pedido';
    private static $primaryKey = 'id';
    private static $formName = 'form_PedidoSaida';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-clipboard-list fa-fw nav-icon"></i> Pedido de Saída';

    use FormTrait;

    function __construct($param)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id          = new TEntry('id');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id','in','(select pessoa_id from pessoa_categoria where categoria_pessoa_id = 4)'));
        $criteria->add( new TFilter('ativo', '=', 'S') );

        if(isset($param['pessoa_id']) && $param['pessoa_id'] != '')
        {
            $criteria->add( new TFilter('id', '=', "{$param['pessoa_id']}"),TExpression::OR_OPERATOR );
        }

        $pessoa_id           = new TDBCombo('pessoa_id', 'erp', 'Pessoa', 'id', 'nome','nome asc', $criteria );
        $forma_pagamento_id  = new TDBCombo('forma_pagamento_id', 'erp', 'FormaPagamento', 'id', 'descricao' );
        $data_pedido         = new TDate('data_pedido');
        $percentual_desconto = new TNumeric('percentual_desconto', '2', ',', '.' );
        $total_bruto         = new TNumeric('total_bruto', '2', ',', '.' );
        $total_desconto      = new TNumeric('total_desconto', '2', ',', '.' );
        $total_frete         = new TNumeric('total_frete', '2', ',', '.' );
        $total_liquido       = new TNumeric('total_liquido', '2', ',', '.' );
        $total_quantidade    = new TNumeric('total_quantidade', '0', '', '' );
        $observacao          = new TText('observacao');
        $pronta_entrega      = new TCombo('pronta_entrega');

        $id->setEditable(FALSE);

        $id->forceUpperCase();
        $pessoa_id->enableSearch();
        $forma_pagamento_id->enableSearch();

        $data_pedido->setDatabaseMask('yyyy-mm-dd');
        $data_pedido->setMask('dd/mm/yyyy');

        $total_bruto->setValue('0,00');
        $total_bruto->setSize('100%');
        $total_bruto->setEditable(FALSE);

        $total_desconto->setValue('0,00');
        $total_desconto->setSize('100%');
        $total_desconto->setEditable(FALSE);

        $total_liquido->setValue('0,00');
        $total_liquido->setSize('100%');
        $total_liquido->setEditable(FALSE);

        $total_quantidade->setValue('0,00');
        $total_quantidade->setSize('100%');
        $total_quantidade->setEditable(FALSE);

        $percentual_desconto->setValue('0,00');
        $percentual_desconto->setSize('100%');

        $total_frete->setValue('0,00');
        $total_frete->setSize('100%');

        $pronta_entrega->setDefaultOption(false);
        $pronta_entrega->setValue('N');
        $pronta_entrega->setSize('100%');
        $pronta_entrega->enableSearch();
        $pronta_entrega->addItems(['N'=>'Não', 'S'=>'Sim']);

        $observacao->setSize('100%', 100);

        $row1 = $this->form->addFields(
            [ new TLabel('Cliente', 'red'),         $pessoa_id          ],
            [ new TLabel('Pronta Entrega', 'red'),  $pronta_entrega     ],
            [ new TLabel('Forma Pagamento', 'red'), $forma_pagamento_id ],
            [ new TLabel('Data', 'red'),            $data_pedido        ],
            [ new TLabel('ID'),                     $id                 ],
        );

        $row1->layout = [
            'col-12  col-sm-4',
            'col-6   col-sm-2',
            'col-6   col-sm-3',
            'col-6   col-sm-2',
            'col-6   col-sm-1',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel('% Desconto'),       $percentual_desconto  ],
            [ new TLabel('Total Bruto'),      $total_bruto          ],
            [ new TLabel('Total Desconto'),   $total_desconto       ],
            [ new TLabel('Total Frete'),      $total_frete          ],
            [ new TLabel('Total Líquido'),    $total_liquido        ],
            [ new TLabel('Total Quantidade'), $total_quantidade     ],
        );

        $row1->layout = [
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
        ];

        $row3 = $this->form->addFields(
            [ new TLabel('Observação'), $observacao ],
        );

        $row3->layout = [
            'col-12  col-sm-12',
        ];

        $id->setSize('100%');
        $pessoa_id->setSize('100%');
        $forma_pagamento_id->setSize('100%');
        $data_pedido->setSize('100%');

        $this->form->addFields( [new TFormSeparator('<b>Adicionar Item no Pedido</b>') ] );

        $criteria = new TCriteria();
        $criteria->add(new TFilter('ativo', '=', 'S'));
        $criteria->add(new TFilter('estoque', '>', '0'));
        $produto_id    = new TDBCombo('produto_id', 'erp', 'Produto', 'id', '{id} - {referencia_fornecedor} - {nome} - {codigo_barras}', 'nome asc', $criteria );
        $quantidade    = new TNumeric('quantidade', '0', '', '' );
        $valor         = new TNumeric('valor', '2', ',', '.' );
        // $valor_bruto   = new TNumeric('valor_bruto', '2', ',', '.' );
        $valor_liquido = new TNumeric('valor_liquido', '2', ',', '.' );

        $produto_id->enableSearch();
        $produto_id->setSize('100%');

        $quantidade->setSize('100%');

        $valor->setValue('0,00');
        $valor->setSize('100%');
        // $valor->setEditable(FALSE);

        // $valor_bruto->setValue('0,00');
        // $valor_bruto->setSize('100%');
        // $valor_bruto->setEditable(FALSE);

        $valor_liquido->setValue('0,00');
        $valor_liquido->setSize('100%');
        $valor_liquido->setEditable(FALSE);

        $produto_id->setChangeAction(new TAction([__CLASS__, 'onChangeProduto']));
        $quantidade->setExitAction(new TAction([__CLASS__, 'onExitQuantidade']));
        $valor->setExitAction(new TAction([__CLASS__, 'onExitQuantidade']));

        $button_inserir = new TButton('button_inserir');
        $button_inserir->setAction(new TAction([__CLASS__, 'onInserirItem'], ['static' => '1']), "Gravar");
        $button_inserir->class = "btn btn-success";
        $button_inserir->setImage('fas:check');
        $button_inserir->style="width: 100%";

        $row1 = $this->form->addFields(
            [ new TLabel('Produto', 'red'),    $produto_id    ],
            [ new TLabel('Quantidade', 'red'), $quantidade    ],
            [ new TLabel('Valor'),             $valor         ],
            // [ new TLabel('Bruto'),             $valor_bruto   ],
            [ new TLabel('Total'),            $valor_liquido  ],
            [ new TLabel('&nbsp;'),           $button_inserir ],
        );

        $row1->layout = [
            'col-12  col-sm-4',
            'col-12  col-sm-2',
            'col-12  col-sm-2',
            // 'col-12  col-sm-2',
            'col-12  col-sm-2',
            'col-12  col-sm-2 btnInserirItem',
        ];

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

        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        // creates the datagrid columns
        $column_referencia_fornecedor = new TDataGridColumn('referencia_fornecedor', 'Referência Fornecedor', 'center');
        $column_produto_id            = new TDataGridColumn('produto_id', 'Produto ID', 'center');
        $column_produto_nome          = new TDataGridColumn('produto_nome', 'Produto', 'left');
        $column_valor                 = new TDataGridColumn('valor', 'Valor', 'center');
        $column_quantidade            = new TDataGridColumn('quantidade', 'Quantidade', 'center');
        $column_valor_liquido         = new TDataGridColumn('valor_liquido', 'Total', 'center');

        $this->datagrid->addColumn( $column_referencia_fornecedor );
        $this->datagrid->addColumn( $column_produto_id );
        $this->datagrid->addColumn( $column_produto_nome );
        $this->datagrid->addColumn( $column_valor );
        $this->datagrid->addColumn( $column_quantidade );
        $this->datagrid->addColumn( $column_valor_liquido );

        $column_valor->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_valor_liquido->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        // $action_edit   = new TDataGridAction(['GrupoPessoaForm', 'onEdit'],   ['key' => '{id}'] );
        $action_delete = new TDataGridAction([$this, 'onDeleteItem'],   ['key' => '{id}'] );

        // $this->datagrid->addAction($action_edit, 'Edit',   'far:edit blue fa-fw');
        $this->datagrid->addAction($action_delete, 'Delete', 'far:trash-alt red fa-fw');

        // create the datagrid model
        $this->datagrid->createModel();

        $panel = new TPanelGroup('<i class="fas fa-clipboard-list fa-fw nav-icon"></i> Itens Pedido de Saída');
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $vbox = new TVBox;
        $vbox->style = "width: 100%";
        $vbox->add($this->form);
        $vbox->add($panel);

        // add the form to the page
        parent::add($vbox);
    }

    public static function onDeleteItem($param = null)
    {
        if (isset($param['delete']) && $param['delete'] == 1) {
            try {
                TTransaction::open(self::$database);
                $object = new PedidoItem($param['key']);

                $pedido_id = $object->pedido_id;

                $mensagem_success = 'Registro excluido com sucesso!';

                $mensagem_error = 'Impossível excluir o registro!';

                $object->delete();
                TTransaction::close();

                new TMessage('info', $mensagem_success, new TAction(['PedidoSaidaForm', 'onEdit'],['key'=>$pedido_id]), 'Sucesso');
            } catch (Exception $e) {
                new TMessage('error', '<b style="font-size: 15px;">'.$mensagem_error.'</b><br><br><p style="font-size: 12px;"><b>Erro Banco de Dados </b>='.$e->getMessage().'</p>', null, 'Impossível excluir!' );
            }
        } else {
            $action = new TAction(['PedidoSaidaForm', 'onDeleteItem']);
            $param['delete'] = 1;
            $param['static'] = 1;
            $action->setParameters($param);

            $mesage = 'Deseja excluir o registro?';

            new TQuestion($mesage, $action);
        }
    }

    public function onInserirItem($param = null)
    {
        try
        {
           TTransaction::open(self::$database);

           if(isset($param['produto_id']) && $param['produto_id'] == '')
           {
               throw new Exception('Produto é obrigatório!');
           }
           if(isset($param['quantidade']) && $param['quantidade'] == '')
           {
               throw new Exception('Quantidade é obrigatório!');
           }
           if(isset($param['quantidade']) && $param['quantidade'] == '0')
           {
               throw new Exception('Quantidade é obrigatório!');
           }

           $produto = new Produto($param['produto_id']);

           if($param['quantidade'] > $produto->estoque)
           {
                throw new Exception('Quantidade informada maior que no estoque! Quantidade em estoque: '. $produto->estoque);
           }

            if(isset($param['id']) && $param['id'] != '')
            {
                $object = new Pedido($param['id']);
            }
            else
            {
                if(isset($param['pessoa_id']) && $param['pessoa_id'] == '')
                {
                    throw new Exception('Cliente é obrigatório!');
                }
                if(isset($param['forma_pagamento_id']) && $param['forma_pagamento_id'] == '')
                {
                    throw new Exception('Forma Pagamento é obrigatório!');
                }
                if(isset($param['data_pedido']) && $param['data_pedido'] == '')
                {
                    throw new Exception('Data é obrigatório!');
                }

                $param['tipo_pedido']         = 'S';
                $param['percentual_desconto'] = _remove_mask_numeric($param['percentual_desconto']);
                $param['total_bruto']         = _remove_mask_numeric($param['total_bruto']);
                $param['total_desconto']      = _remove_mask_numeric($param['total_desconto']);
                $param['total_frete']         = _remove_mask_numeric($param['total_frete']);
                $param['total_liquido']       = _remove_mask_numeric($param['total_liquido']);
                $param['total_quantidade']    = _remove_mask_numeric($param['total_quantidade']);
                $param['data_pedido']         = _set_db_date($param['data_pedido']);

                $object = new self::$activeRecord;
                $object->fromArray( (array) $param );
                $object->store();
            }

            $pedido_item = new PedidoItem();
            $pedido_item->produto_id    = $param['produto_id'];
            $pedido_item->valor_bruto   = _remove_mask_numeric($param['valor'])*_remove_mask_numeric($param['quantidade']);
            $pedido_item->valor_liquido = _remove_mask_numeric($param['valor'])*_remove_mask_numeric($param['quantidade']);
            $pedido_item->pedido_id     = $object->id;
            $pedido_item->quantidade    = _remove_mask_numeric($param['quantidade']);
            $pedido_item->valor         = _remove_mask_numeric($param['valor']);
            $pedido_item->store();

            $produto = new Produto($param['produto_id']);

            if($produto->estoque <= $produto->estoque_minimo)
            {
                TToast::show('info', "Estoque atual do Produto {$produto->nome} abaixo do estoque mínimo {$produto->estoque_minimo}. Quantidade em estoque: {$produto->estoque}", 'bottom center');
            }

            TScript::create("window.setTimeout(function(){
                __adianti_load_page('index.php?class=PedidoSaidaForm&method=onEdit&register_state=false&key={$object->id}');
            },200);");

           TTransaction::close();
        }
        catch (Exception $e)
        {
           new TMessage('error', $e->getMessage());
           TTransaction::rollback();
        }
    }

    public static function onChangeProduto($param = null)
    {
        try
        {
            TTransaction::open(self::$database);

            $object = new stdClass();

            if(isset($param['produto_id']) && $param['produto_id'] != '')
            {
                $produto = new Produto($param['produto_id']);
                $forma_pagamento = new FormaPagamento($param['forma_pagamento_id']);

                if($param['pronta_entrega'] == 'N' || $param['pronta_entrega'] == '')
                {
                    $object->valor         = _formata_numero($produto->preco_venda);

                    if($forma_pagamento->parcelas > 1)
                    {
                        $object->valor         = _formata_numero($produto->preco_venda_prazo);
                    }
                }
                else
                {
                    $object->valor         = _formata_numero($produto->preco_venda_pronta_entrega);

                    if($forma_pagamento->parcelas > 1)
                    {
                        $object->valor         = _formata_numero($produto->preco_venda_pronta_entrega_prazo);
                    }
                }
            }
            else
            {
                $object->valor         = '0,00';
            }

            TForm::sendData(self::$formName, $object);

           TTransaction::close();
        }
        catch (Exception $e)
        {
           new TMessage('error', $e->getMessage());
           TTransaction::rollback();
        }
    }

    public static function onExitQuantidade($param = null)
    {
        try
        {
            TTransaction::open(self::$database);

            $object = new stdClass();

            if((isset($param['produto_id']) && $param['produto_id'] != '') && (isset($param['quantidade']) && $param['quantidade'] != '' && $param['quantidade'] != '0'))
            {
                $valor = str_replace('.','',$param['valor']);
                $valor = str_replace(',','.',$valor);

                $object->valor_liquido = _formata_numero($valor * $param['quantidade']);
            }
            else
            {
                $object->valor_liquido = '0,00';
            }

            TForm::sendData(self::$formName, $object);

           TTransaction::close();
        }
        catch (Exception $e)
        {
           new TMessage('error', $e->getMessage());
           TTransaction::rollback();
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

                $this->form->setData($object);

                $repository = new TRepository('PedidoItem');
                $criteria = new TCriteria;
                $criteria->add(new TFilter('pedido_id','=',$object->id));
                $params['order'] = 'id desc';
                $criteria->setProperties($params); // order, offset

                $itens = $repository->load($criteria, FALSE);

                $this->datagrid->clear();
                if ($itens)
                {
                    foreach ($itens as $object)
                    {
                        $row = $this->datagrid->addItem($object);
                        $row->id = "row_{$object->id}";
                    }
                }

                TTransaction::close();

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                    $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                $object = new stdClass();
                $object->data_pedido = date("Y-m-d");
                $this->form->setData($object);

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['PedidoSaidaList', 'onReload']), 'Sucesso');
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

            $data->tipo_pedido = 'S';

            if(isset($data->pessoa_id) && $data->pessoa_id == '')
            {
                throw new Exception('Cliente é obrigatório!');
            }
            if(isset($data->forma_pagamento_id) && $data->forma_pagamento_id == '')
            {
                throw new Exception('Forma Pagamento é obrigatório!');
            }
            if(isset($data->data_pedido) && $data->data_pedido == '')
            {
                throw new Exception('Data é obrigatório!');
            }

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['PedidoSaidaList', 'onReload']), 'fa:arrow-left black');
            }

            TTransaction::close();

            new TMessage('info', 'Registro salvo com sucesso!', new TAction([__CLASS__, 'onEdit'],['key'=>$key]), 'Sucesso');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
}
