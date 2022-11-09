<?php

class CEPService
{
    public static function get($cep)
    {
        $cep = str_replace(['-','.'], ['', ''], $cep);

        $dadosCep = CEPCacheService::get($cep);

        if($dadosCep)
        {
            return $dadosCep;
        }

        try
        {
            $cep = preg_replace('/[^0-9]/', '', $cep);
            $url = 'https://viacep.com.br/ws/' . $cep . '/json/';
            $content = @file_get_contents($url);
            $dadosCep = json_decode($content);
        }
        catch (Exception $e)
        {
            return null;
        }

        if(isset($dadosCep->erro))
        {
            return null;
        }

        $dadosCep->rua = $dadosCep->logradouro;
        $dadosCep->cep = $cep;

        $dadosCep->cidade = $dadosCep->localidade;

        $cidade = Cidade::where('codigo_ibge', '=', $dadosCep->ibge)->first();
        $estado = Estado::where('codigo_ibge', '=', substr($dadosCep->ibge,0,2))->first();

        if ($cidade)
        {
            $dadosCep->cidade_id = $cidade->id;
            $dadosCep->estado_id = $cidade->estado_id;
        }
        else // se não achar a cidade/estado aproveitamos para salvar
        {
            if (!$estado)
            {
                $estado = new Estado;
                $estado->sigla = $dadosCep->uf;
                $estado->nome = $dadosCep->estado;
                $estado->codigo_ibge = $dadosCep->estado_cod_ibge;
                $estado->store();
            }

            $cidade = new Cidade;
            $cidade->nome = strtoupper($dadosCep->localidade);
            $cidade->codigo_ibge = $dadosCep->ibge;
            $cidade->estado_id = $estado->id;
            $cidade->store();

            $dadosCep->cidade_id = $cidade->id;
            $dadosCep->estado_id = $cidade->estado_id;
        }

        CEPCacheService::save($dadosCep);

        $dadosCep = CEPCacheService::get($cep);

        return $dadosCep;
    }
}