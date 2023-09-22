<?php

namespace App\Http\Repositories;

use App\Utilities\SQLBuilderUtils;

class DocentesRepository extends BaseRepository
{
    public function __construct($validated) {
        parent::__construct($validated);
    }

    protected function buildSelectClause()
    {
        return $this->query
            ->addSelect(
                SQLBuilderUtils::singleHashSQL(
                    'vs.id_vinculo',
                    $this->peppers[0],
                    'id_vinculo',
                    $size = 8
                ))
            ->addSelect(
                SQLBuilderUtils::doubleHashSQL(
                    'vs.numero_usp',
                    $this->peppers[0],
                    $this->peppers[1],
                    'id_estagiario'
                ))
            ->addSelect('p.nome AS nome_aluno')
            ->addSelect('vs.situacao_atual')
            ->addSelect('vs.data_inicio_vinculo')
            ->addSelect('vs.data_fim_vinculo')
            ->addSelect('vs.cod_ultimo_setor AS codigo_ultimo_setor')
            ->addSelect('vs.nome_ultimo_setor')
            ->addSelect('vs.classe')
            ->addSelect('vs.referencia')
            ->addSelect('vs.tipo_jornada')
            ->addSelect('vs.tipo_ingresso')
            ->addSelect('vs.data_ultima_alteracao_funcional')
            ->addSelect('vs.ultima_ocorrencia')
            ->addSelect('vs.data_inicio_ultima_ocorrencia AS data_ultima_ocorrencia');
    }

    protected function buildFromClause()
    {
        return $this->query
            ->from('vinculos_servidores AS vs')
            ->leftJoin('pessoas AS p', function ($join) {
                $join->on('vs.numero_usp', '=', 'p.numero_usp');
            });
    }

    protected function buildWhereClause($validated)
    {
        $this->query->where('vinculo', 'Docente');

        $directColumns = [
            'id_vinculo' => SQLBuilderUtils::singleHashSQL(
                'vs.id_vinculo', $this->peppers[0], null, $size = 8
            ),
            'id_estagiario' => SQLBuilderUtils::doubleHashSQL(
                'vs.numero_usp', $this->peppers[0], $this->peppers[1],
            ),
            'situacao_atual' => 'vs.situacao_atual',
            'codigo_ultimo_setor' => 'vs.cod_ultimo_setor',
            'nome_ultimo_setor' => 'vs.nome_ultimo_setor',
            'classe' => 'vs.classe',
            'referencia' => 'vs.referencia',
            'tipo_jornada' => 'vs.tipo_jornada',
            'tipo_ingresso' => 'vs.tipo_ingresso',
            'ultima_ocorrencia' => 'vs.ultima_ocorrencia',
        ];

        $yearColumns = [
            'ano_inicio_vinculo' => 'vs.data_inicio_vinculo',
            'ano_fim_vinculo' => 'vs.data_fim_vinculo',
            'ano_ultima_ocorrencia' => 'vs.data_inicio_ultima_ocorrencia',
            'ano_ultima_alteracao_funcional' => 'vs.data_ultima_alteracao_funcional',
        ];

        SQLBuilderUtils::processFilters(
            $this->query, 
            $validated, 
            $directColumns, 
            $yearColumns
        );
    }
}