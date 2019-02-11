<?php
// $Id: verprova.php,v 1.10 2007/03/24 20:08:54 marcellobrandao Exp $
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
require XOOPS_ROOT_PATH.'/header.php';


/**
 * Taking form_programs of the form and uid of the session student
 */
$cod_prova = $_GET['cod_prova'];
$uid       = $xoopsUser->getVar('uid');

/**
 * Creation of the object factories that we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);

/**
 * Create Exam object
 */
/** @var \XoopsModules\Assessment\Exam $prova */
$prova = $examFactory->get($cod_prova);

/**
 *  Verifying Student Privileges for this Exam
 */
if (!$prova->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Verifying Exam has expired
 */
$fim          = $prova->getVar('data_fim', 'n');
$tempo        = $prova->getVar('tempo', 'n');
$fimmaistempo = $examFactory->dataMysql2dataUnix($fim) + $tempo;

if ($fimmaistempo < time()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Creating of Criteria objects to be passed to object factories
 */
$criteria_prova     = new \Criteria('cod_prova', $cod_prova);
$criteria_aluno     = new \Criteria('uid_aluno', $uid);
$criteria_terminou  = new \Criteria('terminou', 1);
$criteria_resultado = new \CriteriaCompo($criteria_aluno);
$criteria_resultado->add($criteria_prova);
$criteria_resultado->add($criteria_terminou);

/**
 * Checking if student had already finished the test before in case of a positive inform by message
 */
if ($resultFactory->getCount($criteria_resultado) > 0) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_JATERMINOU);
}

/**
 * Taking the test data and the safety field(TOKEN)
 */
$qtd_perguntas = $questionFactory->getCount($criteria_prova);
$titulo        = $prova->getVar('titulo');
$descricao     = $prova->getVar('descricao');
$instrucoes    = $prova->getVar('instrucoes');
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
$xoopsTpl->assign('lang_instrucoes', _MA_ASSESSMENT_INSTRUCOES);
$xoopsTpl->assign('lang_comecar', _MA_ASSESSMENT_COMECAR);
$xoopsTpl->assign('lang_prova', _MA_ASSESSMENT_PROVA);

/**
 * Including page closing file
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
