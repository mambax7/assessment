<?php
// $Id: excluirdocumento.php,v 1.5 2007/03/24 14:41:40 marcellobrandao Exp $
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

$cod_documento = \Xmf\Request::getString('cod_documento', '', 'POST');
$cod_prova     = \Xmf\Request::getString('cod_prova', '', 'POST');

$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$criteria        = new \Criteria('cod_documento', $cod_documento);

if ($documentFactory->deleteAll($criteria)) {
    redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
}
