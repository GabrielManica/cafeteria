<?php

use Adianti\Control\TAction;
use Adianti\Database\TTransaction;
use Adianti\Widget\Form\TPassword;
use GX4\Trait\FormTrait\FormTrait;
use Adianti\Widget\Wrapper\TDBUniqueSearch;

/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class PessoaForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Cliente';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pessoa';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-user fa-fw nav-icon"></i> Cliente';

    use FormTrait;

    function __construct($param = null)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );
        $this->form->setFieldSizes('100%');

        $id        = new TEntry('id');
        $nome      = new TEntry('nome');
        $documento = new TEntry('documento');
        $celular   = new TEntry('celular');

        $id->setEditable(FALSE);

        $id->forceUpperCase();
        $nome->forceUpperCase();
        $documento->cpf_cnpj = 'true';

        $this->form->appendPage("Dados Cadastrais");

        if (isset($param['key']) && $param['key']) {
            $this->form->setTabAction(new TAction([$this, 'onTabClick'], ['key'=> $param['key']]));
            $this->form->addFields([new THidden('current_tab')]);
            $this->form->setTabFunction("$('[name=current_tab]').val($(this).attr('data-current_page'));");
        }

        $row1 = $this->form->addFields(
            [ new TLabel('Nome', 'red'),      $nome      ],
            [ new TLabel('CPF/CNPJ', 'red'),  $documento ],
            [ new TLabel('ID'),               $id        ],
        );

        $row1->layout = [
            'col-12 col-sm-6',
            'col-10  col-sm-5',
            'col-2  col-sm-1',
        ];

        $row2 = $this->form->addFields(
            [ new TLabel('Celular', 'red'),      $celular            ],
        );

        $row2->layout = [
            'col-6  col-sm-6',
        ];


        // $this->form->addFields( [new TFormSeparator('<b>Dados de Login</b>') ] );
        // $row2 = $this->form->addFields(
        //     [ new TLabel('Login', 'red'),  $login ],
        //     [ new TLabel('Senha', 'red'),  $senha ],
        //     [ new TLabel('E-mail', 'red'), $email ],
        // );

        // $row2->layout = [
        //     'col-6  col-sm-6',
        //     'col-6  col-sm-3',
        //     'col-6  col-sm-3',
        // ];


        if (isset($param['key']))
        {
            $this->form->appendPage("Endereços");
            $page_endereco = new TElement('div');
            $page_endereco->id = 'page_endereco';

            $page_endereco->style = 'width: 100%';

            $row_page_endereco = $this->form->addFields([$page_endereco]);
            $row_page_endereco->layout = ['col-12'];
        }

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

    public static function onTabClick($param) {
        if($param['current_page'] == 1) {
            TScript::create("
                    __adianti_load_page('index.php?class=PessoaEnderecoForm&method=onShow&target_container=page_endereco&register_state=false&pessoa_id={$param['key']}');
            ");
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

                $object        = new self::$activeRecord($key);

                $this->form->setData($object);
                TTransaction::close();

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                    $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['PessoaList', 'onReload']), 'Sucesso');
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

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['PessoaList', 'onReload']), 'fa:arrow-left black');
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
