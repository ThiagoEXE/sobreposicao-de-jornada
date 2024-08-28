<?php

$dados = [
    [
        'Matricula' => '1',
        'Data' => '14/05/2024',
        'Inicio' => '05:05',
        'Fim' => '12:25',
        'Linha' => '123',
        'Pos' => 'X',
        'Veiculo' => '0001234'
    ],
    [
        'Matricula' => '2',
        'Data' => '14/05/2024',
        'Inicio' => '09:05',
        'Fim' => '10:25',
        'Linha' => '123',
        'Pos' => 'X',
        'Veiculo' => '0001234'
    ],
    [
        'Matricula' => '4',
        'Data' => '14/05/2024',
        'Inicio' => '10:00',
        'Fim' => '12:26',
        'Linha' => '123',
        'Pos' => 'Y',
        'Veiculo' => '0001234'
    ],
    [
        'Matricula' => '5',
        'Data' => '14/05/2024',
        'Inicio' => '15:25',
        'Fim' => '23:26',
        'Linha' => '123',
        'Pos' => 'Y',
        'Veiculo' => '0001234'
    ],
    [
        'Matricula' => '6',
        'Data' => '14/05/2024',
        'Inicio' => '11:25',
        'Fim' => '12:26',
        'Linha' => '1234',
        'Pos' => 'Z',
        'Veiculo' => '000123'
    ],
    // Adicione mais registros conforme necessário
];

function gerarLog(array $p_reg_atual, array $p_reg_ant, $nomeArquivo = 'Log.txt') {
        // Abre o arquivo para escrita (cria o arquivo se não existir)
        $arquivo = fopen($nomeArquivo, 'a');
    
        if ($arquivo === false) {
            echo "Erro ao abrir o arquivo para escrita.";
            return;
        }
    
        // Escreve o cabeçalho do log
        fwrite($arquivo, "========================================\n");
        fwrite($arquivo, "Sobreposição de Jornada\n");
        
        // Percorre o array de dados e escreve cada registro no arquivo
        foreach ($p_reg_atual as $registro_atual) {
            fwrite($arquivo, "Matricula: " . $registro_atual['Matricula'] . "\n");
            fwrite($arquivo, "Data: " . $registro_atual['Data'] . "\n");
            fwrite($arquivo, "Inicio: " . $registro_atual['Inicio'] . "\n");
            fwrite($arquivo, "Fim: " . $registro_atual['Fim'] . "\n");
            fwrite($arquivo, "Linha: " . $registro_atual['Linha'] . "\n");
            fwrite($arquivo, "Pos: " . $registro_atual['Pos'] . "\n");
            fwrite($arquivo, "Veiculo: " . $registro_atual['Veiculo'] . "\n");
            fwrite($arquivo, "----------------------------------------\n");
        }

        fwrite($arquivo, "Registro Comparado\n");
        foreach ($p_reg_ant as $registro_anterior) {
            fwrite($arquivo, "Matricula: " . $registro_anterior['Matricula'] . "\n");
            fwrite($arquivo, "Data: " . $registro_anterior['Data'] . "\n");
            fwrite($arquivo, "Inicio: " . $registro_anterior['Inicio'] . "\n");
            fwrite($arquivo, "Fim: " . $registro_anterior['Fim'] . "\n");
            fwrite($arquivo, "Linha: " . $registro_anterior['Linha'] . "\n");
            fwrite($arquivo, "Pos: " . $registro_anterior['Pos'] . "\n");
            fwrite($arquivo, "Veiculo: " . $registro_anterior['Veiculo'] . "\n");
            
        }
    
        // Fecha o arquivo
        fclose($arquivo);
    
        echo "Log gerado com sucesso no arquivo $nomeArquivo.";

}

function converteParaTimestamp($data, $hora)
{
    // Converte a data de DD/MM/YYYY para YYYY-MM-DD
    $partesData = explode('/', $data);
    $dataFormatada = $partesData[2] . '-' . $partesData[1] . '-' . $partesData[0];
    echo "Data: $dataFormatada, Hora: $hora" . PHP_EOL;

    return strtotime("$dataFormatada $hora");
}

function verificaSobreposicao($dados)
{
    $registroAnterior = null;
    $dados_compilados = [];

    foreach ($dados as $key => $registroAtual) {

        if ($registroAnterior !== null) {

            if ($registroAtual['Veiculo'] === $registroAnterior['Veiculo']) {

                $inicioAtual = converteParaTimestamp($registroAtual['Data'], $registroAtual['Inicio']);
                $fimAtual    = converteParaTimestamp($registroAtual['Data'], $registroAtual['Fim']);

                $inicioAnterior = converteParaTimestamp($registroAnterior['Data'], $registroAnterior['Inicio']);
                $fimAnterior    = converteParaTimestamp($registroAnterior['Data'], $registroAnterior['Fim']);

               /* echo "Data de inicio Atual: $inicioAtual" . PHP_EOL;
                echo "Data de inicio Anterior: $inicioAnterior" . PHP_EOL;
                echo "Data de Fim Anterior: $fimAnterior" . PHP_EOL;*/

                if ($inicioAtual >= $inicioAnterior && $fimAtual <= $fimAnterior) {
                   /* echo "Sobreposição encontrada entre os registros: \n";
                    
                    echo "Registro Atual \n";
                    print_r($registroAtual);

                    echo "Registro Anterior\n";
                    print_r($registroAnterior);*/
                   // unset($dados[$key]);
                   /**
                    * Chama a função de log passando o registro atual
                    */
                    $reg_atual = [$registroAtual];
                    $reg_ant = [$registroAnterior];
                    gerarLog($reg_atual, $reg_ant);
                    /*\|*-*|/*/
                } else if ($inicioAtual >= $inicioAnterior && $inicioAtual <= $fimAnterior) {
                  /*  echo "Encavalamento encontrado entro os registros: \n";
                    
                    echo "Registro Atual \n";
                    print_r($registroAtual);
                   
                    echo "Registro Anterior\n";
                    print_r($registroAnterior);*/

                    $registroAtual['Inicio'] = $registroAnterior['Fim'];
                    $registroAnterior = $registroAtual;

                    $dados_compilados[] = $registroAtual;
                } else {
                    /*echo "*******Registro Atual Sem sobreposições ou encavalamento******* \n";
                    print_r($registroAtual);*/
                    $registroAnterior = $registroAtual;
                    $dados_compilados[] = $registroAtual;
                }
            } else {
                /*echo "Registro Atual Veiculo diferente \n";
                print_r($registroAtual);*/
                $registroAnterior = $registroAtual;
                $dados_compilados[] = $registroAtual;
            }

        } else{
            $registroAnterior = $registroAtual;
            $dados_compilados[] = $registroAtual;
        }
    }

    echo "Dados Anteriores";
    print_r($dados);
    echo "Dados Compilados";
    print_r($dados_compilados);
}

verificaSobreposicao($dados);

