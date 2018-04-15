<?php

use XoopsModules\Assessment;

include dirname(dirname(__DIR__)) . '/mainfile.php';
include dirname(dirname(__DIR__)) . '/header.php';

/** @var Assessment\Helper $helper */
$helper = Assessment\Helper::getInstance();

//aqui come�a o conte�do principal
//echo('uiohaiufuihfaui<br>');
//echo ($helper->getConfig('ploft'));
$GLOBALS['xoopsOption']['template_main'] = 'meumodulo_lista.tpl';
$xoopsTpl->assign('valor1', $helper->getConfig('ploft'));

$sql = 'SELECT teste_id,teste_nome FROM ' . $xoopsDB->prefix('meumodulo_tabela1');
$rs  = $xoopsDB->query($sql);

$i = 1;
while (false !== (list($id, $nome) = $xoopsDB->fetchRow($rs))) {
    $vetorresultados[$i]['id']   = $id;
    $vetorresultados[$i]['nome'] = $nome;
    ++$i;
}

$xoopsTpl->assign('valor2', $vetorresultados);

//Fecha a p�gina com seu rodap�. Inclus�o Obrigat�ria
include dirname(dirname(__DIR__)) . '/footer.php';
