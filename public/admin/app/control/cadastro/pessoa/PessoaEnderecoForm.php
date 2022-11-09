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
    private static $activeRecord = 'PessoaEndereco';
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
        $pessoa_id        = new THidden('pessoa_id');
        $cidade_id        = new THidden('cidade_id');
        $cidade_descricao = new TEntry('cidade_descricao');
        $endereco_tipo_id = new TDBCombo('endereco_tipo_id', 'erp', 'EnderecoTipo', 'id', 'nome', 'nome asc');
        $nome             = new TEntry('nome');
        $principal        = new TCheckButton('principal');
        $cep              = new TEntry('cep');
        $endereco         = new TEntry('endereco');
        $numero           = new TEntry('numero');
        $bairro           = new TEntry('bairro');
        $complemento      = new TEntry('complemento');
        $ativo            = new TCheckButton('ativo');
        $data_desativacao = new TEntry('data_desativacao');

        $pessoa_id->setValue($param['pessoa_id']);

        $id->setEditable(FALSE);
        $endereco_tipo_id->enableSearch();

        $data_desativacao->setEditable(FALSE);
        $endereco->setEditable(FALSE);
        $bairro->setEditable(FALSE);
        $cidade_descricao->setEditable(FALSE);

        $nome->forceUpperCase();
        $endereco->forceUpperCase();
        $numero->forceUpperCase();
        $bairro->forceUpperCase();
        $complemento->forceUpperCase();

        $principal->setIndexValue('S');
        // $principal->setValue('S');
        $principal->setUseSwitch(true, 'blue');

        $cep->setMask('99999-999', true);

        $ativo->setIndexValue('S');
        $ativo->setValue('S');
        $ativo->setUseSwitch(true, 'blue');

        $cep->setExitAction(new TAction([$this,'onExitCep']));

        $row = $this->form->addFields(
            [ new TLabel('Tipo', 'red'),       $endereco_tipo_id  ],
            [ new TLabel('Principal', 'red'),  $principal         ],
            [ new TLabel('Descrição', 'red'),  $nome              ],
            [ new TLabel('Ativo', 'red'),      $ativo             ],
            [ new TLabel('Data Desativação'),  $data_desativacao  ],
        );

        $row->layout = [
            'col-6  col-sm-2',
            'col-6  col-sm-1',
            'col-12  col-sm-6',
            'col-12  col-sm-1',
            'col-12  col-sm-2',
        ];

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
            [ new TLabel('Cidade'),       $cidade_descricao  ],
            [ new TLabel('Complemento'),  $complemento       ],
        );

        $row->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-5',
        ];

        $row = $this->form->addFields(
            [ $cidade_id ],
            [ $id ],
            [ $pessoa_id ],
        );

        $row->layout = [
            'col-1  col-sm-1',
            'col-1  col-sm-1',
            'col-1  col-sm-1',
        ];

        $id->setSize('100%');
        $endereco_tipo_id->setSize('100%');
        $nome->setSize('100%');
        $principal->setSize('100%');
        $cep->setSize('100%');
        $endereco->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $cidade_id->setSize('100%');
        $complemento->setSize('100%');
        $ativo->setSize('100%');
        $data_desativacao->setSize('100%');

        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(320);
        $this->datagrid->datatable = 'true';

        $column_endereco_tipo_id = new TDataGridColumn('{endereco_tipo->nome}', "Tipo", 'left');
        $column_principal        = new TDataGridColumn('principal', "Principal", 'left');
        $column_nome             = new TDataGridColumn('nome', "Descrição", 'left');
        $column_ativo            = new TDataGridColumn('ativo', "Ativo", 'left');
        $column_cep              = new TDataGridColumn('cep', "CEP", 'left');
        $column_endereco         = new TDataGridColumn('endereco', "Endereço", 'left');
        $column_bairro           = new TDataGridColumn('bairro', "Bairro", 'left');

        $column_endereco_tipo_id->setAction(new TAction([$this, 'onReload']), ['order' => 'endereco_tipo_id']);
        $column_principal->setAction(new TAction([$this, 'onReload']), ['order' => 'principal']);
        $column_nome->setAction(new TAction([$this, 'onReload']), ['order' => 'nome']);
        $column_ativo->setAction(new TAction([$this, 'onReload']), ['order' => 'ativo']);
        $column_cep->setAction(new TAction([$this, 'onReload']), ['order' => 'cep']);
        $column_endereco->setAction(new TAction([$this, 'onReload']), ['order' => 'endereco']);
        $column_bairro->setAction(new TAction([$this, 'onReload']), ['order' => 'bairro']);

        $this->datagrid->addColumn($column_endereco_tipo_id);
        $this->datagrid->addColumn($column_principal);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_ativo);
        $this->datagrid->addColumn($column_cep);
        $this->datagrid->addColumn($column_endereco);
        $this->datagrid->addColumn($column_bairro);

        $column_ativo->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? 'Não' : 'Sim';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $column_principal->setTransformer( function($value, $object, $row) {
            $class = ($value=='N') ? 'danger' : 'success';
            $label = ($value=='N') ? 'Não' : 'Sim';
            $div = new TElement('span');
            $div->class="label label-{$class}";
            $div->style="text-shadow:none; font-size:12px; font-weight:lighter";
            $div->add($label);
            return $div;
        });

        $column_cep->setTransformer( function($value, $object, $row) {
            return mask('#####-###', $value);
        });

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
                $object->cidade_descricao = "{$object->cidade->nome} ({$object->cidade->estado->sigla})";
                $object->data_desativacao = _set_format_date($object->data_desativacao);

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

            if($data->principal == '')
            {
                $data->principal = 'N';
            }

            if($data->ativo == '')
            {
                $data->ativo = 'N';
            }

            unset($data->data_desativacao);

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
