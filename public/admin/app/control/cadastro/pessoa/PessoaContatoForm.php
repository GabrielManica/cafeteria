<?php

use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\THidden;
use GX4\Trait\FormTrait\FormTrait;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class PessoaContatoForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'PessoaContato';
    private static $primaryKey = 'id';
    private static $formName = 'form_PessoaContato';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-address-book fa-fw nav-icon"></i> Cadastro de Contatos';

    use FormTrait;

    function __construct($param = null)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $btn = $this->form->addAction('Salvar', new TAction(array($this, 'onSave')), 'fa:save');
        $btn->class = 'btn btn-sm btn-success';

        $id         = new THidden('id');
        $pessoa_id  = new THidden('pessoa_id');
        $nome       = new TEntry('nome');
        $email      = new TEntry('email');
        $telefone   = new TEntry('telefone');
        $celular    = new TEntry('celular');
        $observacao = new TText('observacao');

        $pessoa_id->setValue($param['pessoa_id']);

        $id->setEditable(FALSE);

        $nome->forceUpperCase();

        $telefone->setMask('(99) 9999-9999', true);
        $celular->setMask('(99) 99999-9999', true);

        $row = $this->form->addFields(
            [ new TLabel('Nome Contato', 'red'),  $nome     ],
            [ new TLabel('E-mail'),               $email    ],
            [ new TLabel('Telefone'),             $telefone ],
            [ new TLabel('Celular'),              $celular  ],
        );

        $row->layout = [
            'col-12  col-sm-4',
            'col-12  col-sm-4',
            'col-12  col-sm-2',
            'col-12  col-sm-2',
        ];

        $row = $this->form->addFields(
            [ new TLabel('Observação'),  $observacao     ],
        );

        $row->layout = [
            'col-12  col-sm-12',
        ];

        $row = $this->form->addFields(
            [ $id ],
            [ $pessoa_id ],
        );

        $row->layout = [
            'col-1  col-sm-1',
            'col-1  col-sm-1',
        ];

        $id->setSize('100%');
        $nome->setSize('100%');
        $email->setSize('100%');
        $telefone->setSize('100%');
        $celular->setSize('100%');
        $observacao->setSize('100%', 100);

        $this->datagrid = new TDataGrid;
        // $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        $column_nome       = new TDataGridColumn('nome', "Contato", 'left');
        $column_email      = new TDataGridColumn('email', "E-mail", 'left');
        $column_telefone   = new TDataGridColumn('telefone', "Telefone", 'left');
        $column_celular    = new TDataGridColumn('celular', "Celular", 'left');
        $column_observacao = new TDataGridColumn('observacao', "Observação", 'left');

        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_email->setAction(new TAction([$this, 'onReload']), ['order' => 'email']);
        $column_telefone->setAction(new TAction([$this, 'onReload']), ['order' => 'telefone']);
        $column_celular->setAction(new TAction([$this, 'onReload']), ['order' => 'celular']);

        $column_telefone->setTransformer( function($value, $object, $row) {
            return mask('(##) ####-####', $value);
        });

        $column_celular->setTransformer( function($value, $object, $row) {
            return mask('(##) #####-####', $value);
        });

        $column_observacao->setTransformer( function($value, $object, $row) {
            return nl2br($value);
        });

        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_email);
        $this->datagrid->addColumn($column_telefone);
        $this->datagrid->addColumn($column_celular);
        $this->datagrid->addColumn($column_observacao);

        $action_edit   = new TDataGridAction([$this, 'onEdit'],   ['key' => '{id}', 'pessoa_id' => '{pessoa_id}'] );
        $this->datagrid->addAction($action_edit, 'Editar',   'far:edit blue fa-fw');
        $action_delete   = new TDataGridAction([$this, 'onDelete'],   ['key' => '{id}', 'pessoa_id' => '{pessoa_id}'] );
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
                    $param['order'] = 'nome';
                }

                if (empty($param['direction']))
                {
                    $param['direction'] = 'asc';
                }

                $criteria->setProperties($param); // order, offset
                $criteria->setProperty('limit', $this->limit);

                $criteria->add(new TFilter('pessoa_id', '=', $param['pessoa_id']));

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

            new TMessage('info', 'Registro salvo com sucesso!', new TAction([$this, 'onShow'],['pessoa_id' => $data->pessoa_id]), 'Sucesso');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
}
