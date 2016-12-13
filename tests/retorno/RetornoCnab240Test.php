<?php

namespace Retorno\Tests;

use Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240\Detalhe;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240\DetalheSegmentoT;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240\DetalheSegmentoU;
use Eduardokum\LaravelBoleto\Cnab\Retorno\Cnab240\DetalheSegmentoY;
use Illuminate\Support\Collection;

class RetornoCnab240Test extends \PHPUnit_Framework_TestCase
{
    public function testRetornoSantanderCnab240()
    {
        $retorno = \Eduardokum\LaravelBoleto\Cnab\Retorno\Factory::make(__DIR__ . '/files/cnab240/santander.ret');
        $retorno->processar();

        $this->assertNotNull($retorno->getHeader());
        $this->assertNotNull($retorno->getHeaderLote());
        $this->assertNotNull($retorno->getDetalhes());
        $this->assertNotNull($retorno->getTrailerLote());
        $this->assertNotNull($retorno->getTrailer());

        $this->assertEquals('Banco Santander (Brasil) S.A.', $retorno->getBancoNome());
        $this->assertEquals('033', $retorno->getCodigoBanco());

        $this->assertInstanceOf(Collection::class, $retorno->getDetalhes());

        $this->assertInstanceOf(Detalhe::class, $retorno->getDetalhe(1));

        foreach ($retorno->getDetalhes() as $detalhe) {
            $this->assertInstanceOf(Detalhe::class, $detalhe);
            $this->assertInstanceOf(DetalheSegmentoT::class, $detalhe->getSegmentoT());
            $this->assertInstanceOf(DetalheSegmentoY::class, $detalhe->getSegmentoY());
            $this->assertInstanceOf(DetalheSegmentoU::class, $detalhe->getSegmentoU());
            $this->assertArrayHasKey('numeroDocumento', $detalhe->getSegmentoT()->toArray());
        }
    }

}