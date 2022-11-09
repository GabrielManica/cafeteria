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
    private static $activeRecord = 'Pessoa';
    private static $primaryKey = 'id';
    private static $formName = 'form_Pessoa';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-user fa-fw nav-icon"></i> Pessoa';

    use FormTrait;

    function __construct($param = null)
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );
        $this->form->setFieldSizes('100%');

        $id               = new TEntry('id');
        $nome             = new TEntry('nome');
        $documento        = new TEntry('documento');
        $login            = new TEntry('login');
        $email            = new TEntry('email');
        $senha            = new TEntry('senha');
        $observacao       = new TText('observacao');
        $ativo            = new TCheckButton('ativo');
        $data_nascimento  = new TDate('data_nascimento');
        $data_desativacao = new TEntry('data_desativacao');
        $grupo_pessoa_id  = new TDBCombo('grupo_pessoa_id', 'erp', 'GrupoPessoa', 'id', 'nome', 'nome asc');
        $categoria        = new TDBCheckGroup('categoria', 'erp', 'CategoriaPessoa', 'id', '{nome}','nome asc'  );

        $categoria->setLayout('horizontal');
        $categoria->setUseButton();
        // $categoria->setBreakItems(4);
        // $categoria->setSize('100%');

        $id->setEditable(FALSE);
        $data_desativacao->setEditable(FALSE);

        $id->forceUpperCase();
        $nome->forceUpperCase();
        $documento->cpf_cnpj = 'true';

        $ativo->setIndexValue('S');
        $ativo->setValue('S');
        $ativo->setUseSwitch(true, 'blue');

        $grupo_pessoa_id->enableSearch();

        $data_nascimento->setDatabaseMask('yyyy-mm-dd');
        $data_nascimento->setMask('dd/mm/yyyy');

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
            [ new TLabel('Ativo', 'red'),      $ativo            ],
            [ new TLabel('Data Desativação'),  $data_desativacao ],
            [ new TLabel('Grupo', 'red'),      $grupo_pessoa_id  ],
            [ new TLabel('Data Nascimento'),   $data_nascimento  ],
        );

        $row2->layout = [
            'col-6  col-sm-1',
            'col-6  col-sm-2',
            'col-12 col-sm-3',
            'col-12 col-sm-2',
        ];

        $row3 = $this->form->addFields(
            [ new TLabel('Categoria Pessoa'), $categoria ],
        );

        $row3->layout = [
            'col-12  col-sm-12',
        ];

        $row3 = $this->form->addFields(
            [ new TLabel('Observação Pessoa'), $observacao ],
        );

        $row3->layout = [
            'col-12  col-sm-12',
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

        $observacao->setSize('100%', 100);


        if (isset($param['key']))
        {
            $this->form->appendPage("Endereços");
            $page_endereco = new TElement('div');
            $page_endereco->id = 'page_endereco';

            $page_endereco->style = 'width: 100%';

            $row_page_endereco = $this->form->addFields([$page_endereco]);
            $row_page_endereco->layout = ['col-12'];

            $this->form->appendPage("Contatos");
            $page_contato = new TElement('div');
            $page_contato->id = 'page_contato';

            $page_contato->style = 'width: 100%';

            $row_page_contato = $this->form->addFields([$page_contato]);
            $row_page_contato->layout = ['col-12'];

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
        else if($param['current_page'] == 2) {
            TScript::create("
                    __adianti_load_page('index.php?class=PessoaContatoForm&method=onShow&target_container=page_contato&register_state=false&pessoa_id={$param['key']}');
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
                $object->data_desativacao = _set_format_date($object->data_desativacao);

                $object->categoria = PessoaCategoria::where('pessoa_id', '=', $object->id)->getIndexedArray('categoria_pessoa_id', 'categoria_pessoa_id');

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

            if($data->ativo == '')
            {
                $data->ativo = 'N';
            }

            unset($data->data_desativacao);

            $data->login = explode(' ', $data->nome)[0];

            if($data->id != '')
            {
                $count = Pessoa::where('login', 'ilike', "%{$data->login}%")->where('id','not in', "NOESC:({$data->id})")->count();
            }
            else
            {
                $count = Pessoa::where('login', 'ilike', "%{$data->login}%")->count();
            }

            if($count>0)
            {
                $data->login .= $count;
            }

            if (!$data->categoria)
            {
                throw new Exception('Categoria da Pessoa deve ser informado!');
            }

            $data->senha = substr($data->documento, 0, 4);
            $data->email = 'teste@teste.com.br';

            $object = new self::$activeRecord;
            $object->fromArray( (array) $data );
            $object->store();

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            $repository = PessoaCategoria::where('pessoa_id', '=', $object->id);
            $repository->delete();

            if ($data->categoria)
            {
                foreach ($data->categoria as $categoria)
                {
                    $pessoa_categoria = new PessoaCategoria;

                    $pessoa_categoria->categoria_pessoa_id = $categoria;
                    $pessoa_categoria->pessoa_id           = $object->id;
                    $pessoa_categoria->store();
                }
            }

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
