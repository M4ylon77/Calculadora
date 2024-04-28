<?php
session_start();
include "./home/func/functions.php";

$valor = "";
$btnMemoria = true;
$vlresMemoria = "";
$erro = "";

if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = array();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["valor"])) {
        $valor = $_GET["valor"];
        $erro = $_GET["erro"];
    }

if(isset($_GET["limpar"])){
    $valorLimpar = $_GET["limpar"];
    if(!$valorLimpar){
        unset($_SESSION["historico"]);
        
    }
    $_SESSION['historico'] = array();
}



 if (isset($_GET["botao"])) {
    $valorBotao = $_GET["botao"];
    $erro = "";
    $ultimoCaractere = substr($valor, -1);
    $primeiroCaractere = substr($valor, 0, 1);
    
    // e jaz aqui a nossa verificação dos operadores ! para não serem adicionado mais de uma vez !
    $verificaçãodOperador = in_array($ultimoCaractere, ['+', '-', '*', '/','.','^']);
    $operadoresNaoPermitidosPrimeiro = ['*', '/', '^','.'];
    if($valor === "INF"){
        $valor = "";
    }
    if (is_numeric($valorBotao)) {
        $valor .= $valorBotao;
    } elseif (in_array($valorBotao, ['+', '-', '*', '/','.',"^"])) {

        if (!($valor === "" && in_array($valorBotao, $operadoresNaoPermitidosPrimeiro))) {

        if(!$verificaçãodOperador){
            
            if(preg_match('/[\*\-+\/]/', $valor)){

                if($valorBotao == '^'){
                    
                    $erro = "Operador já selecionado não pode Executar potência";
                }
                else{
                    $valor .= $valorBotao;
                } 
            }
            else{
                $valor .= $valorBotao;
            }
            
        }
        else{
            $erro = "Operador já selecionado";
        }
        
        }
        
        } elseif ($valorBotao === "=") {
            //precisamos da verificação de divisão por zero!
            if($valor === '-' || $valor === '+' ||  $verificaçãodOperador || $valor === ""){
                $erro = "Valor invalido";
            }else{
                if (preg_match('/\/0/', $valor)) {
                    $erro = "não é possivel dividir por zero";
                  
                 }elseif(strpos($valor, '^') == true){
                
                        
                        $valorResult = potencia($valor);

                        if($valorResult != "Para executar a potência não pode haver outro operador!"){
                            $_SESSION['historico'][] = $valor . ' = ' . $valorResult;
                            $valor = potencia($valor);
                        }else{
                            $erro = $valorResult;
                         

                        }

                 }
                 else{
    
                     $resultado = eval("return $valor;");
                
                     if ($resultado !== false) {
                        
                        
                            $_SESSION['historico'][] = $valor . ' = ' . $resultado;
                            $valor = $resultado;
                     }              
                 }
            }

            
        } elseif ($valorBotao === "C") {

            $valor = "";
            // $_SESSION["valorMemoria"] = ""; \\ sequisessemos resetar o valor da memoria seria apenas descomentar essa linhha
                                                // numa calculadora original o C não remove o valor da memoria !    

              
        }elseif($valorBotao === "M"){

        //    $valor_armazenado = $_GET["valor-armazenado"];
           if($valor != null){

               $_SESSION["valorMemoria"] = $valor;
           }else{
            $erro = "Não possui valor para salvar na memoria";
           }    
            // if($valor_armazenado === ""){

            //     $_SESSION["valorMemoria"] = $valor;
         
            //     // echo "true";
    
            // }elseif($valor_armazenado != ""){

            //     $valor .= $_SESSION["valorMemoria"];
            //     // $_SESSION["valorMemoria"] = ""; eu mudei um pouco o escopo para parecer mais proficional!
            //     // echo "false";
            // }

        }elseif($valorBotao === "MR"){
            if(isset($_SESSION["valorMemoria"])){

                $valor .= $_SESSION["valorMemoria"];

            }else{
                $erro = "Não possui valor salvo na memoria";
            }
        }
        elseif($valorBotao === "-M"){
          if(isset($_SESSION['valorMemoria'])){
            unset($_SESSION['valorMemoria']);
          }else{
            $erro = "Nâo possui valor salvo na memoria";
          }
        }
        elseif($valorBotao === "N!"){
           
                $resultado = fatorial($valor);

                if($resultado === "Valor para fatorial invalido"){
                
                    $erro = $resultado;
                    $valor = "";

                }else{

                    $_SESSION['historico'][] = $valor   . ' = ' . $resultado;

                    $valor = $resultado;
                    
      
        } 
        }elseif($valorBotao === '->'){
        
            if(strlen($valor) > 0){
                // $erro = "teste";
                $valor = substr($valor, 0, -1);

            }else{
                $erro = "Não possue valores!";
            }

        }
      

      
        // elseif($valorBotao === "N!"){
        //     if($valor === ""){
        //         $valor = fatoracao(1);
        //     }else{

                
        //         $resultado = fatoracao($valor);

        //         $_SESSION['historico'][] =   $valor. ' = ' .$resultado;

        //         $valor = $resultado;

        //     } 
            
        // }


    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link rel="stylesheet" href="./home/assets/style.css">
</head>
<body>
<h1>Calculadora</h1>
<form action="#" method="GET">
    <div id="conteiner_principal">
        <div id="conteiner_1">
            <div id="conteiner_1_child_1">
                <div id="conteiner_1_child_1_1">
                    <input type="text" name="valor" value="<?=$valor?>" placeholder="Selecione o valor pelo botão" readonly>
                </div>
            </div>
            <input name="erro" id="erro"  value="<?=$erro?>" type="text" readonly>
            <div id="body_calculadora">
                <div id="body_calculadora_1">

                
            <div id="conteiner_1_child_2" >
                <div id="conteiner_1_child_2_1">
            <input type="hidden" style="width:100%; box-shadow: 0 4px 8px black;
            "  type="text" name="valor" placeholder="Digite o valor" value="<?=$valor?>" >
                <button id="btn"   type="submit" name="botao" value="+">+</button>
                <button id="btn"    type="submit" name="botao" value="-">-</button>
                <button  id="btn" type="submit" name="botao" value="*">*</button>
                <button  id="btn" type="submit" name="botao" value="/">/</button>
                <button  id="btn" type="submit" name="botao" value="M">+M</button>
                <button id="btn" type="submit" name="botao" value="-M">-M</button>
                <button id="btn" type="submit" name="botao" value="MR">MR</button>
                <button  id="btn" type="submit" name="botao" value="N!">N!</button>
                <button  id="btn" type="submit" name="botao" value="^">x^y</button>
                <button id="btn_back"  type="submit" name="botao" value="->">&rarr;</button>
                <button id="btn_c"    type="submit" name="botao" value="C">C</button>
                
            </div>
            </div>
        
                    <div id="conteiner_principal_body">
                        <div id="conteiner_principal_1">
                            <?php
                            for($i = 0; $i < 10; $i++) {
                                echo "<button  style='width:50px; height:40px;' type='submit' name='botao' value='{$i}'>{$i}</button>";
                            }
                            ?>
                            <button type="submit" name="botao" value=".">.</button>
                            <button type="submit" name="botao" value="=">=</button>
                            <input placeholder="Salvo na memoria" name="valor-armazenado" value="<?php echo isset($_SESSION['valorMemoria']) ? $_SESSION['valorMemoria'] : ''; ?>" readonly>
                            
                            
                     
                </div>
            </div> 
            </div>
            </div>
        </div>
        <div id="conteiner_2">
                        <h2>Historico</h2>
                        <button id="limpar" name="limpar" type="submit"  >Limpar</button>
                        <ul >
                            <?php
                            foreach($_SESSION["historico"] as $operacoes){

                            
                            ?>
                                
                                <li><?=$operacoes?></li>
                                
                                
                                <?php
                            }
                            ?>
                            

                        </ul>
            

        </div>

    </div>
</form>
</body>
</html>
