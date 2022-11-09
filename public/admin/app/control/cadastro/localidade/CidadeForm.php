<?php

use GX4\Trait\FormTrait\FormTrait;
/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class CidadeForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Cidade';
    private static $primaryKey = 'id';
    private static $formName = 'form_Cidade';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-map-marked-alt fa-fw nav-icon"></i> Cidade';

    use FormTrait;

    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id          = new TEntry('id');
        $nome        = new TEntry('nome');
        $estado_id   = new TDBUniqueSearch('estado_id', 'erp', 'Estado', 'id', 'sigla', 'sigla asc');
        $codigo_ibge = new TEntry('codigo_ibge');

        $id->setEditable(FALSE);
        $nome->setEditable(FALSE);
        $estado_id->setEditable(FALSE);
        $codigo_ibge->setEditable(FALSE);

        $id->forceUpperCase();
        $nome->forceUpperCase();
        $codigo_ibge->forceUpperCase();

        $estado_id->setMinLength(1);

        $row1 = $this->form->addFields(
            [ new TLabel('Nome', 'red'),      $nome        ],
            [ new TLabel('Estado', 'red'),     $estado_id  ],
            [ new TLabel('Cód. IBGE', 'red'), $codigo_ibge ],
            [ new TLabel('ID'),               $id          ],
        );

        $row1->layout = [
            'col-6  col-sm-7',
            'col-6  col-sm-2',
            'col-6  col-sm-2',
            'col-6  col-sm-1',
        ];

        $id->setSize('100%');
        $nome->setSize('100%');
        $estado_id->setSize('100%');
        $codigo_ibge->setSize('100%');

        $this->form->addHeaderAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');

        // if ($this::isMobile())
        // {
        //     $this->dropdown_acoes = new TDropDown('Ações', 'fa:list');
        //     $this->dropdown_acoes->setButtonClass('btn btn-default waves-effect dropdown-toggle');

        //     $this->dropdown_acoes->addPostAction('Salvar', new TAction([$this, 'onSave']), $this->form_name, 'fa:save green');
        //     $this->dropdown_acoes->addPostAction('Novo', new TAction([$this, 'onEdit']), $this->form_name, 'fa:plus blue');

        //     $this->form->addHeaderWidget($this->dropdown_acoes);
        // } else{
        //     $this->form->addHeaderAction('Salvar',  new TAction([$this, 'onSave']), 'fa:save green');
        //     $this->form->addHeaderAction('Novo', new TAction([$this, 'onEdit']), 'fa:plus blue');
        // }


        // add the form to the page
        parent::add($this->form);
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
                TTransaction::close();

                // if ($this::isMobile())
                // {
                //     $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), $this->form_name, 'fa:ban orange');
                //     $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), $this->form_name, 'fa:trash-alt red');
                //     $this->dropdown_acoes->addAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
                // }else{
                //     $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                //     $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                //     $this->form->addHeaderAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
                // }
            }
            else
            {
                // if ($this::isMobile())
                // {
                //     $this->dropdown_acoes->addAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
                // }else{
                //     $this->form->addHeaderAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
                // }
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['CidadeList', 'onReload']), 'Sucesso');
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

            // if ($this::isMobile())
            // {
            //     $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), $this->form_name, 'fa:ban orange');
            //     $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), $this->form_name, 'fa:trash-alt red');
            //     $this->dropdown_acoes->addAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
            // }else{
            //     $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
            //     $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
            //     $this->form->addHeaderAction('Voltar', new TAction(['CidadeList', 'onReload']), 'fa:arrow-left black');
            // }

            TTransaction::close();

            new TMessage('info', 'Registro salvo com sucesso!', null, 'Sucesso');
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            $this->form->setData( $this->form->getData() );
            TTransaction::rollback();
        }
    }
}
