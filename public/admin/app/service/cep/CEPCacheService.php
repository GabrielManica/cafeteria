<?php
class CEPCacheService
{
    use Util\Trait\Gerais;

    public static function get($cep)
    {
        return Cep::where('cep', '=', $cep)->first();
    }

    public static function save($cepInfo)
    {
        $cepCache = new Cep();

        $cepCache->codigo_ibge = $cepInfo->ibge;
        $cepCache->rua         = self::trata(strtoupper($cepInfo->rua));
        $cepCache->cidade      = self::trata(strtoupper($cepInfo->cidade));
        $cepCache->bairro      = self::trata(strtoupper($cepInfo->bairro));
        $cepCache->sigla       = strtoupper($cepInfo->uf);
        $cepCache->cep         = $cepInfo->cep;
        $cepCache->cidade_id   = $cepInfo->cidade_id;
        $cepCache->estado_id   = $cepInfo->estado_id;

        $cepCache->store();
    }
}