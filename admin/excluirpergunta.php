<?php
// $Id: excluirpergunta.php,v 1.5 2007/03/24 14:41:40 marcellobrandao Exp $
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
//require_once dirname(dirname(dirname(__DIR__))) . '/class/criteria.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$cod_pergunta = \Xmf\Request::getString('cod_pergunta', '', 'POST');

$answerFactory = new Assessment\AnswerHandler($xoopsDB);
$criteria      = new \Criteria('cod_pergunta', $cod_pergunta);

if ($answerFactory->deleteAll($criteria)) {
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);
    $pergunta        = $questionFactory->get($cod_pergunta);
    $cod_prova       = $pergunta->getVar('cod_prova');
    if ($questionFactory->delete($pergunta)) {
        redirect_header("main.php?op=editar_prova&amp;cod_prova=$cod_prova", 2, _AM_ASSESSMENT_SUCESSO);
    }
}
