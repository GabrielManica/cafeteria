<?php

use GX4\Trait\FormTrait\FormTrait;
use GX4\Trait\ListTrait\ListTrait;
use GX4\Trait\ExportTrait\ExportTrait;

class ConsultaPedidoSaidaList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp';
    private static $activeRecord = 'Pedido';
    private static $primaryKey = 'id';
    private static $formName = 'form_search_ConsultaPedidoSaida';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private static $formTitle = '<i class="fas fa-search fa-fw nav-icon"></i> Consulta de Pedidos de Saída';
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

        // create the form fields

        $criteria = new TCriteria;
        $criteria->add(new TFilter('id','in','(select pessoa_id from pessoa_categoria where categoria_pessoa_id = 4)'));
        $pessoa_id = new TDBCombo('pessoa_id', 'erp', 'Pessoa', 'id', 'nome','nome asc', $criteria );
        $pessoa_id->setSize('100%');
        $pessoa_id->enableSearch();

        $data1 = new TDate('data1');
        $data2 = new TDate('data2');

        $data1->setMask('dd/mm/yyyy');
        $data2->setMask('dd/mm/yyyy');
        $data1->setDatabaseMask('yyyy-mm-dd');
        $data2->setDatabaseMask('yyyy-mm-dd');

        $data1->setSize('100%');
        $data2->setSize('100%');


        $row = $this->form->addFields(
            [new TLabel('Cliente'), $pessoa_id],
        );

        $row->layout = [
            'col-sm-8',
        ];

        $row = $this->form->addFields(
            // [new TLabel('Local'), $local_id],
            [new TLabel('Data'), $data1],
            [new TLabel('Até'), $data2],
        );

        $row->layout = [
            // 'col-sm-6',
            'col-sm-3',
            'col-sm-3',
        ];

        $data_incio = mktime(0, 0, 0, date('m') , 1 , date('Y'));

        $data1->setValue(date('d/m/Y',$data_incio));
        $data2->setValue(date('Y-m-t'));

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
        $column_id                     = new TDataGridColumn('id', 'ID', 'center');
        $column_pessoa                 = new TDataGridColumn('{pessoa->nome}', 'Cliente', 'left');
        $column_data_pedido            = new TDataGridColumn('data_pedido', 'Data', 'center');
        $column_total_quantidade       = new TDataGridColumn('total_quantidade', 'Quantidade', 'center');
        $column_total_bruto            = new TDataGridColumn('total_bruto', 'Bruto', 'center');
        $column_total_desconto         = new TDataGridColumn('total_desconto', 'Desconto', 'center');
        $column_total_liquido          = new TDataGridColumn('total_liquido', 'Líquido', 'center');
        $column_total_custo            = new TDataGridColumn('total_custo', 'Custo', 'center');
        $column_total_liquido_desconto = new TDataGridColumn('total_liquido_desconto', 'Líquido Desconto', 'center');

        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_pessoa );
        $this->datagrid->addColumn( $column_data_pedido );
        $this->datagrid->addColumn( $column_total_quantidade );
        $this->datagrid->addColumn( $column_total_bruto );
        $this->datagrid->addColumn( $column_total_desconto );
        $this->datagrid->addColumn( $column_total_liquido );
        $this->datagrid->addColumn( $column_total_custo );
        $this->datagrid->addColumn( $column_total_liquido_desconto );

        $column_total_bruto->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_total_desconto->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_total_liquido->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_total_quantidade->setTransformer( function($value, $object, $row) {
            return _formata_numero($value);
        });

        $column_total_custo->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_total_liquido_desconto->setTransformer( function($value, $object, $row) {
            return _formata_numero($value, true);
        });

        $column_data_pedido->setTransformer( function($value, $object, $row) {
            return _set_format_date($value);
        });

        $column_id->setAction(new TAction([$this, 'onReload']), ['order' => 'id']);

        // $action_edit   = new TDataGridAction(['PedidoSaidaForm', 'onEdit'],   ['key' => '{id}', 'pessoa_id' => '{pessoa_id}'] );
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
        $this->indicator5 = new THtmlRenderer('app/resources/info-box.html');
        $this->indicator6 = new THtmlRenderer('app/resources/info-box.html');

        $this->div->add( $i1 = TElement::tag('div', $this->indicator1) );
        $this->div->add( $i2 = TElement::tag('div', $this->indicator2) );
        $this->div->add( $i3 = TElement::tag('div', $this->indicator3) );
        $this->div->add( $i4 = TElement::tag('div', $this->indicator4) );
        $this->div->add( $i5 = TElement::tag('div', $this->indicator5) );
        $this->div->add( $i6 = TElement::tag('div', $this->indicator6) );

        $i1->class = 'col-sm-4';
        $i2->class = 'col-sm-4';
        $i3->class = 'col-sm-4';
        $i4->class = 'col-sm-4';
        $i5->class = 'col-sm-4';
        $i6->class = 'col-sm-4';

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
        // $button_novo->setAction(new TAction(['PedidoSaidaForm', 'onEdit']), "Cadastrar");
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

    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__ .'List_filter_data', $data);
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);
        TSession::setValue(__CLASS__.'_filters_data1', NULL);
        TSession::setValue(__CLASS__.'_filters_data2', NULL);

        if (isset($data->pessoa_id) AND ( (is_scalar($data->pessoa_id) AND $data->pessoa_id !== '') OR (is_array($data->pessoa_id) AND (!empty($data->pessoa_id)) )) )
        {

            $filters[] = new TFilter('pessoa_id', '=', "{$data->pessoa_id}");// create the filter
        }

        if (isset($data->data1) AND ($data->data1) && isset($data->data2) AND ($data->data2)){
            $filters[] = new TFilter('data_pedido', '>=', "{$data->data1}");

            $filters[] = new TFilter('data_pedido', '<=', "{$data->data2}");
        }
        else
        {
            $data_incio = mktime(0, 0, 0, date('m') , 1 , date('Y'));

            $data->data1 = date('d/m/Y',$data_incio);
            $data->data2 = date('Y-m-t');

            $filters[] = new TFilter('data_pedido', '>=', date('Y-m-d',$data_incio));

            $filters[] = new TFilter('data_pedido', '<=', date('Y-m-t'));
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
                $param['order'] = 'id';
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
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

            $criteria->add(new TFilter('tipo_pedido', '=', "S"));
            $criteria2->add(new TFilter('tipo_pedido', '=', "S"));

            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? [1,2]);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }

            $objects  = $repository->load($criteria, FALSE);
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

            $registros        = 0;
            $pecas            = 0;
            $liquido          = 0;
            $custo            = 0;
            $liquido_desconto = 0;
            $lucro            = 0;

            if ($objects2)
            {
                foreach ($objects2 as $object)
                {
                    $registros++;
                    $pecas            += $object->total_quantidade;
                    $liquido          += $object->total_liquido;
                    $custo            += $object->total_custo;
                    $liquido_desconto += $object->total_liquido_desconto;
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

            $this->indicator1->enableSection('main', ['title'      => 'Qtd. Pedidos',
                                                      'icon'       => 'clipboard-list',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($registros) ]);

            $this->indicator2->enableSection('main', ['title'      => 'Qtd. Peças',
                                                      'icon'       => 'cubes',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($pecas) ]);

            $this->indicator3->enableSection('main', ['title'      => 'Líquido',
                                                      'icon'       => 'dollar-sign',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($liquido, true) ]);

            $this->indicator4->enableSection('main', ['title'      => 'Custo',
                                                      'icon'       => 'dollar-sign',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($custo, true) ]);

            $this->indicator5->enableSection('main', ['title'      => 'Líquido Desconto',
                                                      'icon'       => 'dollar-sign',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($liquido_desconto, true) ]);

            $this->indicator6->enableSection('main', ['title'      => 'Lucro',
                                                      'icon'       => 'dollar-sign',
                                                      'background' => 'green',
                                                      'value'      => _formata_numero($liquido_desconto-$custo, true) ]);

            return $objects;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
