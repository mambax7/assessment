<?php

require __DIR__ . '/header.php';

/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

//here begins the main content
//echo('uiohaiufuihfaui<br>');
//echo ($helper->getConfig('ploft'));
$GLOBALS['xoopsOption']['template_main'] = 'meumodulo_lista.tpl';
$xoopsTpl->assign('valor1', $helper->getConfig('ploft'));

$sql = 'SELECT teste_id,teste_nome FROM ' . $xoopsDB->prefix('meumodulo_tabela1');
$rs  = $xoopsDB->query($sql);

$i = 1;
while (list($id, $nome) = $xoopsDB->fetchRow($rs)) {
    $vetorresultados[$i]['id']   = $id;
    $vetorresultados[$i]['nome'] = $nome;
    ++$i;
}

$xoopsTpl->assign('valor2', $vetorresultados);

//Close the page with your footer. Inclusion Required
require_once dirname(dirname(__DIR__)) . '/footer.php';
