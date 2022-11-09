<?php

use GX4\Trait\FormTrait\FormTrait;
/**
 * CategoryForm Registration
 * @author  <your name here>
 */
class ConsultaValoresForm extends TPage
{
    protected $form;
    private static $database = 'erp';
    private static $activeRecord = 'Produto';
    private static $primaryKey = 'id';
    private static $formName = 'form_ConsultaValores';
    private $showMethods = ['onEdit', 'onSave', 'onDelete'];
    private static $formTitle = '<i class="fas fa-dollar-sign fa-fw nav-icon"></i> Consulta de Valores';

    use FormTrait;

    function __construct()
    {
        parent::__construct();

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setFormTitle( self::$formTitle );

        $markup                     = new TEntry('markup');
        $preco_vista                = new TEntry('preco_vista');
        $preco_prazo                = new TEntry('preco_prazo');
        $preco_vista_pronta_entrega = new TEntry('preco_vista_pronta_entrega');
        $preco_prazo_pronta_entrega = new TEntry('preco_prazo_pronta_entrega');
        $valor                      = new TNumeric('valor', '2', ',', '.' );
        $valor->setValue('0,00');
        $valor->setExitAction(new TAction([$this,'onExitValor']));

        $markup->setEditable(false);
        $preco_vista->setEditable(false);
        $preco_prazo->setEditable(false);
        $preco_prazo_pronta_entrega->setEditable(false);
        $preco_vista_pronta_entrega->setEditable(false);

        $row1 = $this->form->addFields(
            [ new TLabel('Valor', 'red'), $valor       ],
            [ new TLabel('Markup'), $markup       ],
            [ new TLabel('Preço à Vista'), $preco_vista       ],
            [ new TLabel('Preço à Prazo'), $preco_prazo       ],
            [ new TLabel('Preço à Vista Pronta Entrega'), $preco_vista_pronta_entrega       ],
            [ new TLabel('Preço à Prazo Pronta Entrega'), $preco_prazo_pronta_entrega       ],
        );

        $row1->layout = [
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
            'col-6  col-sm-3',
        ];

        $valor->setSize('100%');
        $markup->setSize('100%');
        $preco_prazo->setSize('100%');
        $preco_vista->setSize('100%');
        $preco_vista_pronta_entrega->setSize('100%');
        $preco_prazo_pronta_entrega->setSize('100%');

        // add the form to the page
        parent::add($this->form);
    }

    public static function onExitValor($param = null)
    {
        $object = new stdClass();
        $rateio_despesa = (1500/11000)*100;
        $despesa_lucro_rateio = 6.74+30+$rateio_despesa;
        $object->markup = number_format(60, 2, ',', '.') . '%';

        $valor_semformato = str_replace('.','',$param['valor']);
        $valor_semformato = str_replace(',','.',$valor_semformato);

        $ipi = ($valor_semformato*0.03);
        $icms = ($valor_semformato*0.03);
        $estoque = ($valor_semformato*0.02);

        $valor_pronta_entrega = $ipi+$icms+$estoque+$valor_semformato;
        $valor = $ipi+$icms+$estoque+$valor_semformato;

        $valor       = ($valor/60)*100;
        $valor_prazo = $valor + ($valor*0.08);

        $valor_pronta_entrega = ($valor_pronta_entrega/60)*100;
        $valor_pronta_entrega_prazo = $valor_pronta_entrega + ((($valor_pronta_entrega/60)*100)*0.08);

        $object->preco_vista = number_format($valor, 2, ',', '.');
        $object->preco_prazo = number_format($valor_prazo, 2, ',', '.');

        $desconto_pronta_entrega = $valor_pronta_entrega - ($valor_pronta_entrega * 0.10);
        $desconto_pronta_entrega_prazo = $desconto_pronta_entrega + ($desconto_pronta_entrega*0.08);

        $object->preco_vista_pronta_entrega = number_format($desconto_pronta_entrega, 2, ',', '.');
        $object->preco_prazo_pronta_entrega = number_format($desconto_pronta_entrega_prazo, 2, ',', '.');

        TForm::sendData(self::$formName, $object);
    }

    public function onReload($param = null)
    {

    }
}
