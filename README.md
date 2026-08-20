# Resumo da correção do arquivo

## Eros de sintaxe

## Erro 1 de sintaxe:
 As tags PHP estavam escritas de forma incorreta. O código usava `<? php` em vez de `<?php`, o que impedia o PHP de interpretar o arquivo.

Correção: todas as tags foram ajustadas para o formato correto.



## Erro 2 de sintaxe: 
Faltavam pontos e vírgulas em certos comandos , como `die(...)`, `bind_param(...)`, `exit` e a query de busca.

Correção: todos os comandos foram finalizados corretamente com `;`.

## Erro 3 de sintaxe: 
O HTML estava com marcação quebrada, como `<! DOCTYPE html>` e `<label>Nome :< /label>`, além do link de excluir com espaço no parâmetro.

Correção: a estrutura HTML foi ajustada e o link passou a ficar em `index.php?excluir=ID` ao invés de `index.php?excluir =ID`.

## Falha de segurança: 

A exclusão era feita por `$_GET`, sem validação e sem confirmação do usuário.

Correção: foi feita validação do ID com `FILTER_VALIDATE_INT` e adicionado `confirm()` antes da exclusão.


## Funcionalidade a ser validada

Também foi ajustada a funcionalidade de edição, que antes existia sem fluxo completo. Agora o arquivo carrega o registro para edição, salva o novo nome e e-mail e retorna para a listagem.

## Prepared Statements
Prepared Statements foram usados em INSERT, UPDATE e DELETE para evitar SQL Injection. O banco recebe os valores separados do comando SQL, o que é o padrão seguro do PHP.


