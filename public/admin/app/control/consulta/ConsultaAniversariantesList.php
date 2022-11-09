<?php

use GX4\Trait\FormTrait\FormTrait;
use GX4\Trait\ListTrait\ListTrait;
use GX4\Trait\ExportTrait\ExportTrait;

class ConsultaAniversariantesList extends TPage
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'erp';
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_search_ConsultaAniversariante';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private static $formTitle = '<i class="fas fa-birthday-cake fa-fw nav-icon"></i> Aniversariantes';
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
        $mes  = new TCombo('mes');
        $mes->enableSearch();
        $mes->setSize('100%');
        $mes->addItems([
            '1' => 'JANEIRO',
            '2' => 'FEVEREIRO',
            '3' => 'MARÇO',
            '4' => 'ABRIL',
            '5' => 'MAIO',
            '6' => 'JUNHO',
            '7' => 'JULHO',
            '8' => 'AGOSTO',
            '9' => 'SETEMBRO',
            '10' => 'OUTUBRO',
            '11' => 'NOVEMBRO',
            '12' => 'DEZEMBRO',
        ]);

        $row = $this->form->addFields(
            [new TLabel('Mês'), $mes],
        );

        $row->layout = [
            'col-sm-8',
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
        $column_id              = new TDataGridColumn('id', 'Pessoa ID', 'center');
        $column_nome            = new TDataGridColumn('nome', 'Cliente', 'center');
        $column_data_nascimento = new TDataGridColumn('data_nascimento', 'Data Nascimento', 'center');
        $column_idade           = new TDataGridColumn('{idade}', 'Idade', 'center');

        $this->datagrid->addColumn( $column_id );
        $this->datagrid->addColumn( $column_nome );
        $this->datagrid->addColumn( $column_data_nascimento );
        $this->datagrid->addColumn( $column_idade );

        $column_data_nascimento->setTransformer( function($value, $object, $row) {
            return _set_format_date($value);
        });

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup(self::$formTitle);
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
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
        // $button_novo->setAction(new TAction(['GrupoPessoaForm', 'onEdit']), "Cadastrar");
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

        if (isset($data->mes) AND ( (is_scalar($data->mes) AND $data->mes !== '') OR (is_array($data->mes) AND (!empty($data->mes)) )) )
        {
            $filters[] = new TFilter("date_part('month', data_nascimento)", '=', "{$data->mes}");// create the filter
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

            if (empty($param['order']))
            {
                $param['order'] = 'id';
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
                }
            }

            $criteria->add(new TFilter('id','in','(select pessoa_id from pessoa_categoria where categoria_pessoa_id = 4)'));

            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }

            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                foreach ($objects as $object)
                {
                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";
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

            return $objects;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }

    public function show()
    {
        // check if the datagrid is already loaded
        if (TSession::getValue(__CLASS__.'_filters'))
        {
            $this->onReload();
        }
        parent::show();
    }
}