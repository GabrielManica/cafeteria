<?php

use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\THidden;
use GX4\Trait\FormTrait\FormTrait;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class PessoaEnderecoForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Endereco';
    private static $primaryKey = 'id';
    private static $formName = 'form_PessoaEndereco';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-map-marked-alt fa-fw nav-icon"></i> Cadastro de Endereços';

    use FormTrait;

    function __construct($param = null)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $btn = $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save');
        $btn->class = 'btn btn-sm btn-success';

        $id               = new THidden('id');
        $cliente_id        = new THidden('cliente_id');
        $cidade_id        = new THidden('cidade_id');
        $cidade = new TEntry('cidade');
        $nome             = new TEntry('nome');
        $cep              = new TEntry('cep');
        $endereco         = new TEntry('logradouro');
        $numero           = new TEntry('numero');
        $bairro           = new TEntry('bairro');
        $complemento      = new TEntry('complemento');

        $cliente_id->setValue(isset($param['pessoa_id']) ? $param['pessoa_id']:'');

        $id->setEditable(FALSE);

        // $endereco->setEditable(FALSE);
        // $bairro->setEditable(FALSE);
        // $cidade_descricao->setEditable(FALSE);

        $nome->forceUpperCase();
        $endereco->forceUpperCase();
        $numero->forceUpperCase();
        $bairro->forceUpperCase();
        $complemento->forceUpperCase();

        $cep->setMask('99999-999', true);

        // $cep->setExitAction(new TAction([$this,'onExitCep']));


        $row = $this->form->addFields(
            [ new TLabel('CEP', 'red'),  $cep      ],
            [ new TLabel('Endereço'),    $endereco ],
            [ new TLabel('Número'),      $numero   ],
            [ new TLabel('Bairro'),      $bairro   ],
        );

        $row->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-5',
            'col-6  col-sm-2',
            'col-6  col-sm-3',
        ];

        $row = $this->form->addFields(
            [ new TLabel('Cidade'),       $cidade  ],
            [ new TLabel('Complemento'),  $complemento       ],
        );

        $row->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-5',
        ];

        $row = $this->form->addFields(
            [ $cidade_id ],
            [ $id ],
            [ $cliente_id ],
        );

        $row->layout = [
            'col-1  col-sm-1',
            'col-1  col-sm-1',
            'col-1  col-sm-1',
        ];

        $id->setSize('100%');
        $nome->setSize('100%');
        $cep->setSize('100%');
        $endereco->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $cidade_id->setSize('100%');
        $complemento->setSize('100%');

        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        $column_cep              = new TDataGridColumn('cep', "CEP", 'left');
        $column_endereco         = new TDataGridColumn('logradouro', "Endereço", 'left');
        $column_bairro           = new TDataGridColumn('bairro', "Bairro", 'left');

        $column_cep->setAction(new TAction([$this, 'onReload']), ['order' => 'cep']);
        $column_endereco->setAction(new TAction([$this, 'onReload']), ['order' => 'endereco']);
        $column_bairro->setAction(new TAction([$this, 'onReload']), ['order' => 'bairro']);

        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_bairro);


        $action_edit   = new TDataGridAction([$this, 'onEdit'],   ['key' => '{id}', 'pessoa_id' => '{cliente_id}'] );
        $this->datagrid->addAction($action_edit, 'Editar',   'far:edit blue fa-fw');
        $action_delete   = new TDataGridAction([$this, 'onDelete'],   ['key' => '{id}', 'pessoa_id' => '{cliente_id}'] );
        $this->datagrid->addAction($action_delete, 'Delete', 'far:trash-alt red fa-fw');
        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup;
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->addFooter($this->pageNavigation);

        $container = new TVBox;
        $container->style = 'width: 100%';

        $container->add($this->form);
        $container->add($panel);

        // add the form to the page
        parent::add($container);
    }

    public static function onExitCep($param = null)
    {
        try
        {
            //code here
            TTransaction::open(self::$database);

            $object = new stdClass();

            if($param['cep'] != '')
            {
                $cep = CEPService::get($param['cep']);

                if($cep)
                {
                    $object->cidade_id        = $cep->cidade_id;
                    $object->cidade_descricao = "{$cep->cidade} ({$cep->sigla})";
                    $object->endereco         = $cep->rua;
                    $object->bairro           = $cep->bairro;
                }
                else
                {
                    $object->cidade_id        = "";
                    $object->cidade_descricao = "";
                    $object->endereco         = "";
                    $object->bairro           = "";

                    TToast::show('error', 'Cep não localizado!', 'top right', 'far:check-circle' );
                }
            }
            else
            {
                $object->cidade_id        = "";
                $object->cidade_descricao = "";
                $object->endereco         = "";
                $object->bairro           = "";
            }

            TForm::sendData(self::$formName, $object);

            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }

    public function onReload( $param = null )
    {
        try
        {
            if($param['pessoa_id'])
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

                $criteria->add(new TFilter('cliente_id', '=', $param['pessoa_id']));

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
                $object                   = new self::$activeRecord($key);

                $this->form->setData($object);

                $this->onReload($param);
                TTransaction::close();
            }
            else
            {

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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction([$this, 'onShow'],['pessoa_id' => $param['pessoa_id']]), 'Sucesso');
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

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            TTransaction::close();

            new TMessage('info', 'Registro salvo com sucesso!', new TAction([$this, 'onShow'],['pessoa_id' => $data->cliente_id]), 'Sucesso');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
}
