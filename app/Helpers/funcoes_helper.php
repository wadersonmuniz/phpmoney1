<?php 

/**
 * Retorna o nome do mês segundo seu número.
 *
 * @param [type] $numero_mes
 * @return void
 */
function nomeMes($numero_mes) {
    switch ($numero_mes) {
        case '01':
            $mes= 'Janeiro';
        break;
        case '02':
            $mes= 'Fevereiro';
        break;
        case '03':
            $mes= 'Março';
        break;
        case '04':
            $mes= 'Abril';
        break;
        case '05':
            $mes= 'Maio';
        break;
        case '06':
            $mes= 'Junho';
        break;
        case '07':
            $mes= 'Julho';
        break;
        case '08':
            $mes= 'Agosto';
        break;
        case '09':
            $mes= 'Setembro';
        break;
        case '10':
            $mes= 'Outubro';
        break;
        case '11':
            $mes= 'Novembro';
        break;
        case '12':
            $mes= 'Dezembro';
        break;
    }
    return strtoupper($mes);
}

/**
 * Converte uma data formato americano para BR
 * se o segundo parametro também for true retorna a hora
 *
 * @param [type] $data
 * @param boolean $mostrar_hora
 * @return void
 */
function toDataBr($data, $mostrar_hora = false){
    return $mostrar_hora ? date('d/m/Y H:i:s', strtotime($data)) : date('d/m/Y', strtotime($data));
}

/**
 * Retorna uma array de anos para ser usada dentro de um formDropDown
 * O ano inicia através do parametro informado
 *
 * @param array|null $params
 * @return void
 */
function comboAnos(array $params = null){
    $ano_inicial = $params['ano_inicial'];
    $ano_final = date("Y");

    $result = [];
    while($ano_inicial <= $ano_final){
        $result += [
            $ano_inicial => $ano_inicial
        ];
        $ano_inicial++;
    }

    return $result;
}