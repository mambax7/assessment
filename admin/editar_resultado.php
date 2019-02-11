<?php
// $Id: editar_resultado.php,v 1.8 2007/03/24 14:44:38 marcellobrandao Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;
use XoopsModules\Assessment;

require_once __DIR__ . '/admin_header.php';

//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$nota_final    = \Xmf\Request::getString('campo_nota_final', '', 'POST');
$nivel         = \Xmf\Request::getString('campo_nivel', '', 'POST');
$obs           = \Xmf\Request::getString('campo_observacoes', '', 'POST');
$cod_resultado = \Xmf\Request::getString('campo_cod_resultado', '', 'POST');

$resultFactory = new Assessment\ResultHandler($xoopsDB);
$resultado     = $resultFactory->get($cod_resultado);
$resultado->setVar('nota_final', $nota_final);
$resultado->setVar('nivel', $nivel);
$resultado->setVar('obs', $obs);
$resultado->setVar('terminou', 1);
$resultado->setVar('fechada', 1);

$resultado->unsetNew();
/** @var \XoopsNotificationHandler $notificationHandler */
$notificationHandler = xoops_getHandler('notification');
$notificationHandler->triggerEvent('prova', $cod_resultado, 'prova_corrigida');
if ($resultFactory->insert($resultado)) {
    redirect_header('index.php', 2, _AM_ASSESSMENT_SUCESSO);
}
