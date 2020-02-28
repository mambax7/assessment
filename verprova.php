<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * verprova.php, Responsible for generating the test opening form
 *
 * This file displays the instructions of the test that the student is doing and allows
 * him to create a result for his test
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Xoops head files to load ...
 */
$GLOBALS['xoopsOption']['template_main'] = 'assessment_verprova.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;
//require __DIR__ . '/config/config.php';

//global $xoopsUser;

/**
 * Taking form_programs of the form and uid of the session student
 */
$cod_prova     = \Xmf\Request::getInt('cod_prova', '', 'GET');
$memberHandler = xoops_getHandler('member');
//$uid       = $xoopsUser->getVar('uid');
$uid = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0;

/**
 * Creation of the object factories that we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);

/**
 * Create Exam object
 */
/** @var \XoopsModules\Assessment\Exam $exam */
$exam = $examFactory->get($cod_prova);

/**
 *  Verifying Student Privileges for this Exam
 */
if (!$exam->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Verifying Exam has expired
 */
$fim          = $exam->getVar('data_fim', 'n');
$tempo        = $exam->getVar('tempo', 'n');
$fimmaistempo = $examFactory->dataMysql2dataUnix($fim) + $tempo;

if ($fimmaistempo < time()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Creating of Criteria objects to be passed to object factories
 */
$criteria_test     = new \Criteria('cod_prova', $cod_prova);
$criteria_student  = new \Criteria('uid_aluno', $uid);
$criteria_finished = new \Criteria('terminou', 1);
$criteria_result   = new \CriteriaCompo($criteria_student);
$criteria_result->add($criteria_test);
$criteria_result->add($criteria_finished);

/**
 * Checking if student had already finished the test before in case of a positive inform by message
 */
if ($resultFactory->getCount($criteria_result) > 0) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_JATERMINOU);
}

/**
 * Taking the test data and the safety field(TOKEN)
 */
$qtd_perguntas = $questionFactory->getCount($criteria_test);
$titulo        = $exam->getVar('titulo');
$descricao     = $exam->getVar('descricao');
$instrucoes    = $exam->getVar('instrucoes');
$nome_modulo   = $xoopsModule->getVar('name');
$campo_token   = $GLOBALS['xoopsSecurity']->getTokenHTML();

/**
 * Assigning Variables to the template
 * obs: could have been made direct in the "previous tab" but for code reading issues I separated the two
 * then we can think of joining in a section s
 */
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $titulo);
$xoopsTpl->assign('campo_token', $campo_token);
$xoopsTpl->assign('nome_modulo', $nome_modulo);
$xoopsTpl->assign('titulo', $titulo);
$xoopsTpl->assign('descricao', $descricao);
$xoopsTpl->assign('instrucoes', $instrucoes);
$xoopsTpl->assign('qtd_perguntas', $qtd_perguntas);
$xoopsTpl->assign('cod_prova', $cod_prova);
$xoopsTpl->assign('lang_instructions', _MA_ASSESSMENT_INSTRUCOES);
$xoopsTpl->assign('lang_comecar', _MA_ASSESSMENT_COMECAR);
$xoopsTpl->assign('lang_prova', _MA_ASSESSMENT_PROVA);

/**
 * Including page closing file
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
