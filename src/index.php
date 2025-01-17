<?php
use GrammiCore\AutoScriptLexerBuilder;
use GrammiCore\Engine\ErrorHandling\LanguageGrammarInterpreterError;
use GrammiCore\Engine\ReservedClass\InterpreterThrowable;
use GrammiCore\GrammarInterpreter\LanguageGrammarInterpreter;
use GrammiCore\GrammarInterpreter\Strategies\AssignmentExpressionInterpreterStrategy;
use GrammiCore\GrammarInterpreter\Strategies\CallExpressionInterpreterStrategy;
use GrammiCore\GrammarParser\LanguageGrammarParser;
use GrammiCore\GrammarParser\Tree\AssignmentExpressionNode;
use GrammiCore\GrammarParser\Tree\IdentifierNode;
use GrammiCore\GrammarParser\Tree\MemberExpressionNode;
use GrammiCore\GrammarParser\Tree\UnsetAssignmentNode;

header('Content-Type: application/json');
require '../vendor/autoload.php';

set_exception_handler(function($x) {
    print_r($x);
});

// $source = "

// var teste = [
//     'pessoa' => [
//         'nome' => 'gustavo',
//         'endereco' => [
//             'rua' => 'Estrada'
//         ]  
//     ]
// ];

// var nome = teste['pessoa']['endereco']['rua'] + 'Teste';

// print(nome);

// ";
// $source = "

// var teste = [
//     'pessoa' => [
//         'nome' => 'gustavo',
//         'idade' => 21,
//         'endereco' => [
//             'rua' => 'Estrada',
//             'teste' => [
//                 'aaa' => 'bbb'
//             ]
//         ]  
//     ]
// ];

// var idadeFutura = teste['pessoa']['idade'] + 10;

// print(idadeFutura);
// ";

// $source = "
// var array = range(1, 5);
// for(var i = 0; i < count(array); i + 1) {
//     print(i);
// }
// ";
// $source = "
// var i = 1;
// i = i++;
// print(i);
// ";
// $source = "
// var array = range(1, 5);
// for(var i = 0; i <= count(array); i++) {
//         print(i);
//     }
//     ";
// $source = "
// var i = 'aaaa';
// i = i++;
// print(i);
// ";

// $source = "
//     var array = range(1, 100);
//     for(var i = 0; i < count(array); i++) {
//         print(i);
//         print(', ');
//     }
// ";
// $source = "
//     var array = range(1, 100);
//     for(var i = 0; i < count(array); i++) {
//         print(i);
//         print(', ');
//     }
// ";

// $source = "

//     var array = range(1, 5);

//     array[0] = 'Teste';

//     print(array);
// ";

// $source = "

//     var teste = [
//         'pessoa' => [
//             'nome' => 'gustavo',
//             'idade' => 21,
//             'endereco' => [
//                 'rua' => 'Estrada',
//                 'teste' => [
//                     'aaa' => 'bbb'
//                 ]
//             ]  
//         ]
//     ];

//     teste['pessoa']['teste'] = 'Teste' + 'AAA';

//     print(teste);
// ";

// $source = "
//     var teste = [
//         'pessoa' => [
//             'nome' => 'gustavo',
//             'idade' => 21,
//             'endereco' => [
//                 'rua' => 'Estrada',
//                 'teste' => [
//                     'aaa' => 'bbb'
//                 ]
//             ]  
//         ]
//     ];
//     print(x);
// ";
// $source = "
//     var teste = [
//         'pessoa' => [
//             'nome' => 'gustavo',
//             'idade' => 21,
//             'endereco' => [
//                 'rua' => 'Estrada',
//                 'teste' => [
//                     'aaa' => 'bbb'
//                 ]
//             ]  
//         ]
//     ];
//     print(teste);
// ";
// $source = "
//     var x = 0;
//     if(!x) {
//         print('TRUE');
//     }
// ";
// $source = "
//     var array = [
//         'a' => [
//             'b' => [
//                 'c' => 1
//             ]
//         ]
//     ];
//     if(!!array['a']['b']['c']) {
//         print('TRUE');
//     }
// ";

// $source = "
//     var teste = [
//         ''
//     ];

//     function teste() {
//         return [ 1, 2 ];
//     }

//     print(count(teste()));
// ";
// $source = "
//     class Pessoa {

//         public prop nome = 'Não especificado';
//         private prop idade;
//         prop endereco = [
//             'rua' => 'Teste',
//             'bairro' => [
//                 'nome' => 'Centro'
//             ]
//         ];

//         public method getNome() {
//             return this.nome;
//         }

//         public method getEndereco() {
//             return this.endereco;
//         }

//         public method setNome(nome) {
//             this.nome = nome;
//             return this;
//         }

//     }

// ";
// $source = "
//     class Pai {

//         private prop nome;

//         public method getNome() {
//             return this.nome;
//         }

//         public method setNome(nome) {
//             this.nome = nome;
//             return this;
//         }

//     }

//     class Filha extends Pai {

//         private prop cpf;

//         public method getCpf() {
//             return this.cpf;
//         }

//         public method setCpf(cpf) {
//             this.cpf = cpf;
//             return this;
//         }

//     }

//     class Neto extends Filha {

//         private prop cnpj;

//         public method getCnpj() {
//             return this.cnpj;
//         }

//         public method setCnpj(cnpj) {
//             this.cnpj = cnpj;
//             return this;
//         }

//         public method getNome() {
//             return super.getNome();
//         }

//         public method setNome(nome) {
//             super.setNome(nome);
//             return this;
//         }

//     }

//     var neto = new Neto();
//     neto.setNome('Teste');
//     print(neto.getNome());
// ";
// $source = "

//     class Estatica {

//         public static prop nome = 'Teste';

//         public method getNome() {
//             return this.nome;
//         }

//     }

//     var x = new Estatica();

//     print(x.getNome());
// ";

// $source = "
//     throw new Error('Teste');
//     // ex.setMessage('Teste');
//     // var mensagem = ex.getMessage();
//     // print(mensagem);
// ";

// $source = "
//     try {
//         var x = 1 + 1;
//         if(x == 1) {
//             throw new Error('Erro de teste');
//         }
//     }
//     catch(ex) {
//         print('Teste');
//     }
// ";

$source = "

function somar(l, r) {
    return l + r;
}

var x = somar(1, 1);

print(x);

";


$oAutoScriptLexer = AutoScriptLexerBuilder::build();
$aTokens = $oAutoScriptLexer->tokenize($source);
$oParser = new LanguageGrammarParser($aTokens);
$output = $aTokens;
$oAst = $oParser->parse();
$output = $oAst;
$oInterpreter = new LanguageGrammarInterpreter();
$oInterpreter->addReservedFunction('count', function($aArgs, $oInterpreter) {
    $iArgs = count($aArgs);
    if($iArgs == 0) {
        $oInterpreter->throwError('count requires at least 1 parameter');
    }
    list($oFirstArg) = $aArgs;
    $xFirstArgValue = $oFirstArg->getProperty('value');
    return count($xFirstArgValue);
});
$oInterpreter->addReservedClass('Error', InterpreterThrowable::class);
try {
    $oInterpreter->run($oAst);
} catch (LanguageGrammarInterpreterError $oError) {
    //throw $th;
}
$output = $oInterpreter->getIO(); 
print_r($output);