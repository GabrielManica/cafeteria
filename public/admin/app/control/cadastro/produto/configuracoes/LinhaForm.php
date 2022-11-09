<?php

use GX4\Trait\FormTrait\FormTrait;
/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class LinhaForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Linha';
    private static $primaryKey = 'id';
    private static $formName = 'form_Linha';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-grip-vertical fa-fw nav-icon"></i> Linha';

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

        $id->setEditable(FALSE);

        $id->forceUpperCase();
        $nome->forceUpperCase();

        $row1 = $this->form->addFields(
            [ new TLabel('Linha', 'red'),  $nome       ],
            [ new TLabel('ID'),            $id         ],
        );

        $row1->layout = [
            'col-6  col-sm-7',
            'col-6  col-sm-1',
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

        $id_linha       = new THidden('id_linha[]');
        $id_sub_linha   = new THidden('id_sub_linha[]');

        $nome_sub_linha = new TEntry('nome_sub_linha[]');
        $nome_sub_linha->setSize('100%');
        $nome_sub_linha->forceUpperCase();

        $this->fieldlist = new TFieldList;
        $this->fieldlist->generateAria();
        $this->fieldlist->setRemoveAction(new TAction([$this,'onDeleteSubLinha']));
        $this->fieldlist->width = '100%';
        $this->fieldlist->name  = 'my_field_list';
        $this->fieldlist->addField( 'ID',            $id_linha,       ['width' => '0%']   );
        $this->fieldlist->addField( 'ID',            $id_sub_linha,   ['width' => '0%']   );
        $this->fieldlist->addField( 'Sub Linha',     $nome_sub_linha, ['width' => '100%'] );

        $this->form->addField($id_linha);
        $this->form->addField($id_sub_linha);
        $this->form->addField($nome_sub_linha);

        $this->form->addFields( [new TFormSeparator('<b>Sub Linha</b>') ] );
        $this->form->addContent( [$this->fieldlist] );


        // add the form to the page
        parent::add($this->form);
    }

    public static function onDeleteSubLinha($param = null)
    {
        try
        {
            if($param['id_sub_linha'])
            {
                TTransaction::open(self::$database);

                $object_sub_linha = new SubLinha($param['id_sub_linha']);
                $object_sub_linha->delete();

                TTransaction::close();

                TToast::show('success', "Registro excluido com sucesso!", 'top right', 'far:check-circle' );
            }
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage(), null, 'Erro ao editar registro');
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
                $object        = new self::$activeRecord($key);

                $this->form->setData($object);

                $field_sub_linha = SubLinha::where('linha_id', '=', $key)->load();
                $this->fieldlist->addHeader();
                if ($field_sub_linha)
                {
                    foreach($field_sub_linha  as $sub_linha )
                    {
                        $sub_linha->nome_sub_linha = $sub_linha->nome;
                        $sub_linha->id_linha       = $sub_linha->linha_id;
                        $sub_linha->id_sub_linha   = $sub_linha->id;
                        $this->fieldlist->addDetail($sub_linha);
                    }
                    $this->fieldlist->addCloneAction();
                }
                else
                {
                    $this->fieldlist->addDetail( new stdClass);
                    $this->fieldlist->addCloneAction();
                }

                TTransaction::close();

                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                    $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                    $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                    $this->form->addHeaderAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
                }
            }
            else
            {
                if ($this::isMobile())
                {
                    $this->dropdown_acoes->addAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
                }else{
                    $this->form->addHeaderAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
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

                new TMessage('info', 'Registro excluido com sucesso!', new TAction(['LinhaList', 'onReload']), 'Sucesso');
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

            if($data->id){
                $sub_linha = $this->fieldlist->getPostData();

                if($sub_linha){
                    if($sub_linha[0]->nome_sub_linha != '')
                    {
                        foreach ( $sub_linha as $value) {
                            $object_sub_linha = new SubLinha();

                            $action = array(
                                'id'       => $value->id_sub_linha,
                                'linha_id' => $object->id,
                                'nome'     => $value->nome_sub_linha,
                            );

                            $object_sub_linha->fromArray( $action );
                            $object_sub_linha->store();

                        }
                    }
                }
            }

            $data->id = $object->id;

            $this->form->setData($data);

            $key = $data->id;

            if ($this::isMobile())
            {
                $this->dropdown_acoes->addPostAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), self::$formName, 'fa:ban orange');
                $this->dropdown_acoes->addPostAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), self::$formName, 'fa:trash-alt red');
                $this->dropdown_acoes->addAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
            }else{
                $this->form->addHeaderAction('Cancelar', new TAction([$this, 'onEdit'], ['key' => $key]), 'fa:ban orange');
                $this->form->addHeaderAction('Excluir', new TAction([$this, 'onDelete'], ['key' => $key, "static" => 1]), 'fa:trash-alt red');
                $this->form->addHeaderAction('Voltar', new TAction(['LinhaList', 'onReload']), 'fa:arrow-left black');
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
