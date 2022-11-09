<?php

use GX4\Trait\FormTrait\FormTrait;
/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class FabricanteForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Fabricante';
    private static $primaryKey = 'id';
    private static $formName = 'form_Fabricante';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-industry fa-fw nav-icon"></i> Fabricante';

    use Adianti\Base\AdiantiFileSaveTrait;
    use FormTrait;

    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id           = new TEntry('id');
        $nome         = new TEntry('nome');
        $logo         = new TImageCropper('logo');
        $preview_site = new TCheckButton('preview_site');

        $preview_site->setIndexValue('S');
        $preview_site->setValue('N');
        $preview_site->setUseSwitch(true, 'blue');

        $logo->enableFileHandling();
        $logo->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $logo->setWindowTitle("Upload de Logo");
        $logo->setImagePlaceholder(new TImage("fas:file-upload"));

        $logo->setSize(180, 260);

        $id->setEditable(FALSE);

        $id->forceUpperCase();
        $nome->forceUpperCase();

        $bcontainer_62827f23f7f48 = new BootstrapFormBuilder('bcontainer_62827f23f7f48');
        $this->bcontainer_62827f23f7f48 = $bcontainer_62827f23f7f48;
        $bcontainer_62827f23f7f48->setProperty('style', 'border:none; box-shadow:none;');
        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Fabricante', 'red'),  $nome           ],
            [ new TLabel('Preview Site', 'red'), $preview_site  ],
            [ new TLabel('ID'),                 $id             ],
        );

        $row->layout = [
            'col-6  col-sm-8',
            'col-3  col-sm-2',
            'col-3  col-sm-2',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Logo", null, '14px', null, '100%'),$logo],
            [$bcontainer_62827f23f7f48],
        );

        $row1->layout = [
            'col-12  col-sm-3',
            'col-12  col-sm-9',
        ];


        $id->setSize('100%');
        $nome->setSize('100%');

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

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                    $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['FabricanteList', 'onReload']), 'Sucesso');
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

            if($data->preview_site == '')
            {
                $data->preview_site = 'N';
            }

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $this->saveFile($object, $data, 'logo', 'app/images/site/fabricante');

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['FabricanteList', 'onReload']), 'fa:arrow-left black');
            }

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
