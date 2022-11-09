<?php

use Adianti\Database\TFilter;
use GX4\Trait\FormTrait\FormTrait;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class BannerForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Banner';
    private static $primaryKey = 'id';
    private static $formName = 'form_Banner';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-chalkboard fa-fw nav-icon"></i> Banner';

    use Adianti\Base\AdiantiFileSaveTrait;
    use FormTrait;

    function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id                = new TEntry('id');
        $titulo            = new TEntry('titulo');
        $sub_titulo        = new TEntry('sub_titulo');
        $imagem            = new TImageCropper('imagem');
        $ativo             = new TCheckButton('ativo');
        $mostrar_so_imagem = new TCheckButton('mostrar_so_imagem');
        $conteudo          = new THtmlEditor('conteudo');
        $link              = new TEntry('link');

        $ativo->setIndexValue('S');
        $ativo->setValue('S');
        $ativo->setUseSwitch(true, 'blue');

        $mostrar_so_imagem->setIndexValue('S');
        $mostrar_so_imagem->setValue('N');
        $mostrar_so_imagem->setUseSwitch(true, 'blue');

        $id->setEditable(FALSE);

        $id->forceUpperCase();

        $imagem->enableFileHandling();
        $imagem->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $imagem->setWindowTitle("Upload de Banner");
        $imagem->setImagePlaceholder(new TImage("fas:file-upload"));

        $imagem->setSize(180, 260);

        $bcontainer_62827f23f7f48 = new BootstrapFormBuilder('bcontainer_62827f23f7f48');
        $this->bcontainer_62827f23f7f48 = $bcontainer_62827f23f7f48;
        $bcontainer_62827f23f7f48->setProperty('style', 'border:none; box-shadow:none;');
        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Título'),  $titulo       ],
            [ new TLabel('ID'),             $id           ],
        );

        $row->layout = [
            'col-10  col-sm-10',
            'col-2   col-sm-2',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Sub Título'),  $sub_titulo       ],
            [ new TLabel('Ativo', 'red'),       $ativo     ],
        );

        $row->layout = [
            'col-10  col-sm-10',
            'col-2   col-sm-2',
        ];

        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Link'),                   $link                  ],
            [ new TLabel('Só Imagem', 'red'),       $mostrar_so_imagem     ],
        );

        $row->layout = [
            'col-10  col-sm-10',
            'col-2   col-sm-2',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Banner", null, '14px', null, '100%'),$imagem],
            [$bcontainer_62827f23f7f48],
        );

        $row1->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-10',
        ];

        $row = $this->form->addFields(
            [ new TLabel('Conteúdo'),  $conteudo       ],
        );

        $row->layout = [
            'col-12  col-sm-12',
        ];

        $id->setSize('100%');
        $titulo->setSize('100%');
        $sub_titulo->setSize('100%');
        $link->setSize('100%');
        $conteudo->setSize('100%', 400);

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
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['BannerList', 'onReload']), 'Sucesso');
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

            if($data->ativo == '')
            {
                $data->ativo = 'N';
            }

            if($data->mostrar_so_imagem == '')
            {
                $data->mostrar_so_imagem = 'N';
            }

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            $this->saveFile($object, $data, 'imagem', 'app/images/site/banner');

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['BannerList', 'onReload']), 'fa:arrow-left black');
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
