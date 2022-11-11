<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Database\TFilter;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TImage;
use Adianti\Widget\Form\TNumeric;
use Adianti\Database\TTransaction;
use Adianti\Widget\Util\TDropDown;
use GX4\Trait\FormTrait\FormTrait;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TQuestion;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Widget\Form\TCheckButton;
use Adianti\Widget\Form\TImageCropper;
use Adianti\Wrapper\BootstrapFormBuilder;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class ProdutoForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_Produto';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-cube fa-fw nav-icon"></i> Produto';

    use Adianti\Base\AdiantiFileSaveTrait;
    use FormTrait;

    function __construct($param)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $id         = new TEntry('id');
        $nome       = new TEntry('nome');
        $foto       = new TImageCropper('foto');
        $preco_custo                      = new TNumeric('valor', '2', ',', '.' );

        $id->setEditable(FALSE);

        // $preco_custo->setExitAction(new TAction([$this,'onExitValor']));

        $id->forceUpperCase();
        $nome->forceUpperCase();

        $foto->enableFileHandling();
        $foto->setAllowedExtensions(["jpg","jpeg","png","gif"]);
        $foto->setWindowTitle("Upload de Foto");
        $foto->setImagePlaceholder(new TImage("fas:file-upload"));

        $foto->setSize(160, 260);

        $preco_custo->setValue('0,00');
        $preco_custo->setSize('100%');


        $bcontainer_62827f23f7f48 = new BootstrapFormBuilder('bcontainer_62827f23f7f48');
        $this->bcontainer_62827f23f7f48 = $bcontainer_62827f23f7f48;
        $bcontainer_62827f23f7f48->setProperty('style', 'border:none; box-shadow:none;');
        $row = $bcontainer_62827f23f7f48->addFields(
            [ new TLabel('Descrição', 'red'),  $nome       ],
            [ new TLabel('Valor', 'red'),                $preco_custo     ],
            [ new TLabel('ID'),                $id         ],
        );

        $row->layout = [
            'col-12  col-sm-6',
            'col-10  col-sm-5',
            'col-2   col-sm-1',
        ];

        $row1 = $this->form->addFields(
            [ new TLabel("Foto", null, '14px', null, '100%'),$foto],
            [$bcontainer_62827f23f7f48],
        );

        $row1->layout = [
            'col-12  col-sm-2',
            'col-12  col-sm-10',
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
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['ProdutoList', 'onReload']), 'Sucesso');
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

            $this->saveFile($object, $data, 'foto', 'app/fotos/produtos');

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['ProdutoList', 'onReload']), 'fa:arrow-left black');
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
