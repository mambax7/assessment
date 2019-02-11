<?php
// $Id: editar_documento.php,v 1.6 2007/03/24 14:41:40 marcellobrandao Exp $
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
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/** @var Assessment\Helper $helper */
$helper = Assessment\Helper::getInstance();
/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$documento_texto = \Xmf\Request::getString('campo_documento', '', 'POST');
$fonte           = \Xmf\Request::getString('campo_fonte', '', 'POST');
$titulo          = \Xmf\Request::getString('campo_titulo', '', 'POST');
$perguntas       = \Xmf\Request::getString('campo_perguntas', '', 'POST');
$cod_documento   = \Xmf\Request::getString('campo_coddocumento', '', 'POST');
$cod_prova       = \Xmf\Request::getString('campo_codprova', '', 'POST');
$uid_elaborador  = $xoopsUser->getVar('uid');
$html            = 1;
if ('dhtmlext' === $helper->getConfig('editorpadrao') || 'textarea' === $helper->getConfig('editorpadrao')) {
    $html = 0;
}

$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$documento       = $documentFactory->create();

$documento->setVar('cods_perguntas', implode(',', $perguntas));
$documento->setVar('titulo', $titulo);
$documento->setVar('cod_documento', $cod_documento);
$documento->setVar('cod_prova', $cod_prova);
$documento->setVar('documento', $documento_texto);
$documento->setVar('tipo', 0);
$documento->setVar('fonte', $fonte);
$documento->setVar('uid_elaborador', $uid_elaborador);
$documento->setVar('html', $html);
$documento->unsetNew();
if ($documentFactory->insert($documento)) {
    redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
}
