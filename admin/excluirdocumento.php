<?php

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

$cod_documento = \Xmf\Request::getInt('cod_documento', 0, 'POST');
$cod_prova     = \Xmf\Request::getInt('cod_prova', 0, 'POST');

$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$criteria        = new \Criteria('cod_documento', $cod_documento);

if ($documentFactory->deleteAll($criteria)) {
    redirect_header('main.php?op=edit_test&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
}
