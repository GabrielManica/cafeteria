<?php

use Adianti\Database\TTransaction;
use GX4\Trait\FormTrait\FormTrait;
use GX4\Trait\ListTrait\ListTrait;
use GX4\Trait\ExportTrait\ExportTrait;

class ConsultaEstoqueList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_search_ConsultaEstoque';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private static $formTitle = '<i class="fas fa-cubes fa-fw nav-icon"></i> Consulta Estoque';
    private $limit = 10;

    use ListTrait;
    use ExportTrait;
    use FormTrait;

    public function __construct()
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle(self::$formTitle);

        $criteria = new TCriteria;
        $criteria->add(new TFilter('id','in','(select pessoa_id from pessoa_categoria where categoria_pessoa_id = 5)'));
        $pessoa_id = new TDBCombo('pessoa_id', 'erp', 'Pessoa', 'id', 'nome','nome asc', $criteria );
        $pessoa_id->setSize('100%');
        $pessoa_id->enableSearch();

        $fabricante_id = new TDBCombo('fabricante_id', 'erp', 'Fabricante', 'id', 'nome','nome asc' );
        $fabricante_id->setSize('100%');
        $fabricante_id->enableSearch();

        $linha_id = new TDBCombo('linha_id', 'erp', 'Linha', 'id', 'nome','nome asc');;
        $linha_id->setSize('100%');
        $linha_id->enableSearch();
        $linha_id->setChangeAction(new TAction([$this,'onChangeLinha'],['static'=>'1']));

        $sub_linha_id = new TCombo('sub_linha_id');
        $sub_linha_id->setSize('100%');
        $sub_linha_id->enableSearch();

        $row = $this->form->addFields(
            [new TLabel('Fornecedor'), $pessoa_id],
        );

        $row->layout = [
            'col-sm-6',
        ];

        $row = $this->form->addFields(
            [new TLabel('Fabricante'), $fabricante_id],
            [new TLabel('Linha'), $linha_id],
            [new TLabel('Sub Linha'), $sub_linha_id],
        );

        $row->layout = [
            'col-sm-4',
            'col-sm-4',
            'col-sm-4',
        ];

        $this->form->setData( TSession::getValue(__CLASS__.'List_filter_data') );

        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';

        // creates a DataGrid
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
        $column_id                               = new TDataGridColumn('id', 'ID', 'center');
        $column_nome                             = new TDataGridColumn('nome', 'Descrição', 'left');
        $column_fornecedor                       = new TDataGridColumn('{fornecedor->nome}', 'Fornecedor', 'left');
        $column_preco_custo                      = new TDataGridColumn('preco_custo', 'Preço Custo', 'center');
        $column_preco_venda                      = new TDataGridColumn('preco_venda', 'Preço Venda', 'center');
        $column_preco_venda_prazo                = new TDataGridColumn('preco_venda_prazo', 'Preço Venda Prazo', 'center');
        $column_estoque                          = new TDataGridColumn('estoque', 'Qtd. Estoque', 'center');

        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_fornecedor );
        $this->datagrid->addColumn( $column_estoque );
        $this->datagrid->addColumn( $column_preco_custo );
        $this->datagrid->addColumn( $column_preco_venda );
        $this->datagrid->addColumn( $column_preco_venda_prazo );

        $column_preco_custo->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_preco_venda->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_preco_venda_prazo->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_fornecedor->setAction(new TAction([$this, 'onReload']), ['order' => 'fornecedor->nome']);
        $column_preco_custo->setAction(new TAction([$this, 'onReload']), ['order' => 'preco_custo']);
        $column_preco_venda->setAction(new TAction([$this, 'onReload']), ['order' => 'preco_venda']);
        $column_preco_venda_prazo->setAction(new TAction([$this, 'onReload']), ['order' => 'preco_venda_prazo']);
        $column_estoque->setAction(new TAction([$this, 'onReload']), ['order' => 'estoque']);

        // $action_edit   = new TDataGridAction(['ProdutoForm', 'onEdit'],   ['key' => '{id}'] );
        // $action_delete = new TDataGridAction([$this, 'onDelete'],   ['key' => '{id}'] );

        // $this->datagrid->addAction($action_edit, 'Edit',   'far:edit blue fa-fw');
        // $this->datagrid->addAction($action_delete, 'Delete', 'far:trash-alt red fa-fw');

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $this->div        = new TElement('div');
        $this->div->class = "row";

        $this->indicator1 = new THtmlRenderer('app/resources/info-box.html');
        $this->indicator2 = new THtmlRenderer('app/resources/info-box.html');
        $this->indicator3 = new THtmlRenderer('app/resources/info-box.html');
        $this->indicator4 = new THtmlRenderer('app/resources/info-box.html');

        $this->div->add( $i1 = TElement::tag('div', $this->indicator1) );
        $this->div->add( $i2 = TElement::tag('div', $this->indicator2) );
        $this->div->add( $i3 = TElement::tag('div', $this->indicator3) );
        $this->div->add( $i4 = TElement::tag('div', $this->indicator4) );

        $i1->class = 'col-sm-6';
        $i2->class = 'col-sm-6';
        $i3->class = 'col-sm-6';
        $i4->class = 'col-sm-6';

        $panel = new TPanelGroup(self::$formTitle);
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $panel->add($this->div);
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'background-color:#fff; justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction([$this, 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('');
        $btnShowCurtainFilters->setImage('fas:filter #000000');
        $this->datagrid_form->addField($btnShowCurtainFilters);

        // $button_atualizar = new TButton('button_button_atualizar');
        // $button_atualizar->setAction(new TAction([$this, 'onRefresh']), "Atualizar");
        // $button_atualizar->addStyleClass('');
        // $button_atualizar->setImage('fas:sync-alt #03a9f4');
        // $this->datagrid_form->addField($button_atualizar);

        // $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        // $button_limpar_filtros->setAction(new TAction([$this, 'onClearFilters']), "Limpar filtros");
        // $button_limpar_filtros->addStyleClass('');
        // $button_limpar_filtros->setImage('fas:eraser #f44336');
        // $this->datagrid_form->addField($button_limpar_filtros);

        // $button_novo = new TButton('button_button_novo');
        // $button_novo->setAction(new TAction(['ProdutoForm', 'onEdit']), "Cadastrar");
        // $button_novo->addStyleClass('');
        // $button_novo->setImage('fas:plus green');
        // $this->datagrid_form->addField($button_novo);

        // $dropdown_button_exportar = new TDropDown("Exportar", 'fas:file-export #4CAF50');
        // $dropdown_button_exportar->setPullSide('right');
        // $dropdown_button_exportar->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        // $dropdown_button_exportar->addPostAction( "CSV", new TAction([$this, 'onExportCsv']), 'datagrid_'.self::$formName, 'fas:file-csv #00b894' );
        // $dropdown_button_exportar->addPostAction( "XLS", new TAction([$this, 'onExportXls']), 'datagrid_'.self::$formName, 'fas:file-excel #4CAF50' );
        // $dropdown_button_exportar->addPostAction( "PDF", new TAction([$this, 'onExportPdf']), 'datagrid_'.self::$formName, 'far:file-pdf #e74c3c' );
        // $dropdown_button_exportar->addPostAction( "XML", new TAction([$this, 'onExportXml']), 'datagrid_'.self::$formName, 'far:file-code #e74c3c' );

        $head_left_actions->add($btnShowCurtainFilters);
        // $head_left_actions->add($button_limpar_filtros);
        // $head_left_actions->add($button_atualizar);
        // $head_left_actions->add($button_novo);

        // $head_right_actions->add($dropdown_button_exportar);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // creates the page container
        $vbox = new TVBox;
        $vbox->style = "width: 100%";
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($panel);

        // add the container inside the page
        parent::add($vbox);
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

    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__ .'List_filter_data', $data);
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->pessoa_id) AND ( (is_scalar($data->pessoa_id) AND $data->pessoa_id !== '') OR (is_array($data->pessoa_id) AND (!empty($data->pessoa_id)) )) )
        {

            $filters[] = new TFilter('fornecedor_id', '=', "{$data->pessoa_id}");// create the filter
        }

        if (isset($data->fabricante_id) AND ( (is_scalar($data->fabricante_id) AND $data->fabricante_id !== '') OR (is_array($data->fabricante_id) AND (!empty($data->fabricante_id)) )) )
        {

            $filters[] = new TFilter('fabricante_id', '=', "{$data->fabricante_id}");// create the filter
        }

        if (isset($data->linha_id) AND ( (is_scalar($data->linha_id) AND $data->linha_id !== '') OR (is_array($data->linha_id) AND (!empty($data->linha_id)) )) )
        {

            $filters[] = new TFilter('linha_id', '=', "{$data->linha_id}");// create the filter
        }

        if (isset($data->sub_linha_id) AND ( (is_scalar($data->sub_linha_id) AND $data->sub_linha_id !== '') OR (is_array($data->sub_linha_id) AND (!empty($data->sub_linha_id)) )) )
        {

            $filters[] = new TFilter('sub_linha_id', '=', "{$data->sub_linha_id}");// create the filter
        }

        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    public function onReload($param = NULL)
    {
        try
        {
            TTransaction::open(self::$database);

            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;
            $criteria2 = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'nome';
            }
            else{
                $order = $param['order'];
                if ($param['order'] == 'fornecedor->nome') {
                    $param['order'] = '(select nome from pessoa where id = fornecedor_id)';
                }
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'asc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter)
                {
                    $criteria->add($filter);
                    $criteria2->add($filter);
                }
            }

            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }

            $objects = $repository->load($criteria, FALSE);
            $objects2 = $repository->load($criteria2, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";
                }
            }

            $total_estoque     = 0;
            $total_custo       = 0;
            $total_venda       = 0;
            $total_venda_prazo = 0;

            if ($objects2)
            {
                foreach ($objects2 as $object)
                {
                    if($object->estoque > 0)
                    {
                        $total_estoque     += $object->estoque;
                        $total_custo       += $object->preco_custo * $object->estoque;
                        $total_venda       += $object->preco_venda * $object->estoque;
                        $total_venda_prazo += $object->preco_venda_prazo * $object->estoque;
                    }
                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            TTransaction::close();
            $this->loaded = true;

            $this->indicator1->enableSection('main', ['title'     => 'Estoque',
                                                        'icon'       => 'cubes',
                                                        'background' => 'green',
                                                        'value'      => _formata_numero($total_estoque) ]);

            $this->indicator2->enableSection('main', ['title'     => 'Custo',
                                                        'icon'       => 'dollar-sign',
                                                        'background' => 'green',
                                                        'value'      => _formata_numero($total_custo, true) ]);

            $this->indicator3->enableSection('main', ['title'     => 'Venda',
                                                        'icon'       => 'dollar-sign',
                                                        'background' => 'green',
                                                        'value'      => _formata_numero($total_venda, true) ]);

            $this->indicator4->enableSection('main', ['title'     => 'Venda Prazo',
                                                        'icon'       => 'dollar-sign',
                                                        'background' => 'green',
                                                        'value'      => _formata_numero($total_venda_prazo, true) ]);

            return $objects;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
