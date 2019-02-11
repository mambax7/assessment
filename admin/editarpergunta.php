<?php
// $Id: editarpergunta.php,v 1.6 2007/03/24 14:41:40 marcellobrandao Exp $
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

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}
$titulo                 = \Xmf\Request::getString('campo_titulo', '', 'POST');
$cod_pergunta           = \Xmf\Request::getString('campo_cod_pergunta', '', 'POST');
$tit_resposta_certa     = \Xmf\Request::getString('campo_resposta1', '', 'POST');
$tit_resposta_errada[1] = \Xmf\Request::getString('campo_resposta2', '', 'POST');
$tit_resposta_errada[2] = \Xmf\Request::getString('campo_resposta3', '', 'POST');
$tit_resposta_errada[3] = \Xmf\Request::getString('campo_resposta4', '', 'POST');
$tit_resposta_errada[4] = \Xmf\Request::getString('campo_resposta5', '', 'POST');
$cod_resposta_certa     = \Xmf\Request::getString('campo_cod_resp1', '', 'POST');
$cod_resposta_errada[1] = \Xmf\Request::getString('campo_cod_resp2', '', 'POST');
$cod_resposta_errada[2] = \Xmf\Request::getString('campo_cod_resp3', '', 'POST');
$cod_resposta_errada[3] = \Xmf\Request::getString('campo_cod_resp4', '', 'POST');
$cod_resposta_errada[4] = \Xmf\Request::getString('campo_cod_resp5', '', 'POST');

$ordem = \Xmf\Request::getString('campo_ordem', '', 'POST');

$uid_elaborador  = $xoopsUser->getVar('uid');
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$pergunta        = $questionFactory->create(false);
$pergunta->load($cod_pergunta);
$pergunta->setVar('titulo', $titulo);
$pergunta->setVar('uid_elaboradores', $uid_elaborador);
$pergunta->setVar('ordem', $ordem);
$cod_prova = $pergunta->getVar('cod_prova');

if ($questionFactory->insert($pergunta)) {
    $answerFactory  = new Assessment\AnswerHandler($xoopsDB);
    $resposta_certa = $answerFactory->create(false);
    $resposta_certa->load($cod_resposta_certa);
    $resposta_certa->setVar('titulo', $tit_resposta_certa);
    $resposta_certa->setVar('uid_elaboradores', $uid_elaborador);
    $answerFactory->insert($resposta_certa);
    $i = 1;
    foreach ($cod_resposta_errada as $cod) {
        $resposta_errada = $answerFactory->create(false);
        $resposta_errada->load($cod);
        $resposta_errada->setVar('titulo', $tit_resposta_errada[$i]);
        $resposta_errada->setVar('uid_elaboradores', $uid_elaborador);
        $answerFactory->insert($resposta_errada);
        ++$i;
    }
}

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
