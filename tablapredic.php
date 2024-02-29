<?php

$tabla_predictiva = [
    'REPETITIVA-do' => ['do', 'CUERPO', 'MIENTRAS'],
    'CUERPO-{}' => ['{}'],
    'MIENTRAS-mientras' => ['mientras', 'CONDICIONAL'],
    'CONDICIONAL-(' => ['(', 'EXPRESION', ')'],
    'EXPRESION-range' => ['VALOR', 'OPERADOR', 'V'],
    'VALOR-range' => ['LETRA', 'RESTO'],
    'LETRA-range' => ['range'],
    'RESTO-range' => ['LETRA', 'RESTO'],
    'RESTO-==' => ['epsilon'],
    'RESTO-!=' => ['epsilon'],
    'V-true' => ['true'],
    'V-false' => ['false'],
    'OPERADOR-==' => ['=='],
    'OPERADOR-!=' => ['!='],
];

function claveTabla($parte1, $parte2) {
    return $parte1 . '-' . $parte2;
}

// Función transformadora
function transformador($entrada) {
    $simbolos_procesados = [];
    $entrada_reemplazada = str_replace(['{}', '(', ')', '==', '!='], [' {} ', ' ( ', ' ) ', ' == ', ' != '], $entrada);
    $elementos = explode(' ', $entrada_reemplazada);

    foreach ($elementos as $elemento) {
        if (in_array($elemento, ['do', 'mientras', '{}', '(', ')', '==', '!=', 'true', 'false'])) {
            $simbolos_procesados[] = $elemento;
        } else {
            foreach (str_split($elemento) as $char) {
                $simbolos_procesados[] = 'range'; 
            }
        }
    }
    $simbolos_procesados[] = '$';
    return $simbolos_procesados;
}

// Función analizadora
function analizador($entrada) {
    global $tabla_predictiva;
    $pila = ['$', 'REPETITIVA'];
    $registro = [implode(' ', $pila)];
    $entry_symbols = transformador(trim($entrada));

    while (count($pila) > 0 && count($entry_symbols) > 0) {
        $tope_pila = end($pila);
        $current_symbol = $entry_symbols[0];

        if ($tope_pila == $current_symbol) {
            array_pop($pila);
            array_shift($entry_symbols);
        } elseif (isset($tabla_predictiva[claveTabla($tope_pila, $current_symbol)])) {
            array_pop($pila);
            $elementos_produccion = $tabla_predictiva[claveTabla($tope_pila, $current_symbol)];
            if ($elementos_produccion != ['epsilon']) {
                foreach (array_reverse($elementos_produccion) as $elemento) {
                    $pila[] = $elemento;
                }
            }
        } else {
            return implode("\n", $registro) . "\nError en la entrada cerca de \"$current_symbol\"";
        }
        $registro[] = count($pila) ? implode(' ', $pila) : 'Aceptación';
    }

    return implode("\n", $registro);
}

