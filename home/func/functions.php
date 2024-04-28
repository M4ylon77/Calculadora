<?php
// function fatoracao($valor) {
//     $fatores = array();
//     while ($valor % 2 == 0) {
//         $fatores[] = 2;
//         $valor = $valor / 2;
//     }
//     for ($i = 3; $i <= sqrt($valor); $i += 2) {
//         while ($valor % $i == 0) {
//             $fatores[] = $i;
//             $valor = $valor / $i;
//         }
//     }
//     if ($valor > 2) {
//         $fatores[] = $valor;
//     }
//     return implode(' * ', $fatores);
// }
function  potencia($valor){
if(explode('^', $valor)){

if(preg_match('/[*-+\/]/', $valor)){
    return "Para executar a potência não pode haver outro operador!";
            
}else{
    $valorPotencial = 1;
    $numeros = explode('^', $valor);
    
    $primeiroNum = doubleval($numeros[0]);
    $segundoNum = doubleval($numeros[1]);
    
    
    
    for($i = 0 ; $i < $segundoNum ; $i++){
    
        $valorPotencial = $valorPotencial * $primeiroNum;
    
    }
    
    return $valorPotencial;
}

}

// var_dump($valorPotencial);

}

function fatorial($valor){
$resultado = 1;
if(is_numeric($valor)){
    while($valor > 0 ){

        $resultado = $resultado * $valor;
    
        $valor--;
    }
    
}elseif(!is_numeric($valor) || $valor === ""){
    $resultado = "Valor para fatorial invalido";
}

return $resultado;

}

?>