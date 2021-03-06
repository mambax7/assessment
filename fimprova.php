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
 * fimprova.php, Responsible for generating the test closing form
 *
 * This file displays a report of the test that the student is doing and allows him
 * to finish the test
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Xoops head files to load ...
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
$GLOBALS['xoopsOption']['template_main'] = 'assessment_fimprova.tpl';
require_once dirname(dirname(__DIR__)) . '/header.php';

/**
 * Taking cod_result of the form and uid of the session student
 */
$cod_resultado = \Xmf\Request::getInt('cod_resultado', 0, 'GET');
$uid           = $xoopsUser->getVar('uid');

/**
 * Creation of the factories of objects that we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);

/**
 * Making the Result Object
 */
/** @var \XoopsModules\Assessment\Result $resultado */
$resultado = $resultFactory->get($cod_resultado);
$cod_prova = $resultado->getVar('cod_prova');

/**
 * Create Exam object
 */
/** @var \XoopsModules\Assessment\Exam $exam */
$exam = $examFactory->get($cod_prova);

/**
 * Verifying Student Privileges for this Test
 */
if (!$exam->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

// Let's now get the number of test questions and how many the student answered
// and how many he left blank to remind him and confirm that he wants to end the race

/**
 * Creating Criteria Objects to Move to Factories
 */
$criteria_test    = new \Criteria('cod_prova', $cod_prova);
$criteria_usuario = new \Criteria('uid_aluno', $uid);
$criteria_compo   = new \CriteriaCompo($criteria_test);
$criteria_compo->add($criteria_usuario);

/**
 * Seeking the total questions of this test in the Question Factory
 */
$qtd_perguntas = $questionFactory->getCount($criteria_test);

/**
 * Calculations of remaining time, time spent and time of the end of the test
 * obs: it is necessary to pass this into the class result or test
 */
$horaatual        = time();
$dateStartSeconds = $examFactory->dataMysql2dataUnix($resultado->getVar('data_inicio'));
$examTimeLength   = $exam->getVar('tempo');

$timeLeft    = $examFactory->convertSeconds(($dateStartSeconds + $examTimeLength) - $horaatual, 'H');
$timeSpent   = $examFactory->convertSeconds($horaatual - $dateStartSeconds, 'H');
$examEndTime = $examFactory->convertSeconds($dateStartSeconds + $examTimeLength, 'H');

/**
 * Test time check: if the time burst saves the result and warns the student
 */
if ($timeLeft['segundos'] < 0) {
    $resultado->setVar('terminou', 1);
    $resultado->unsetNew();
    $resultFactory->insert($resultado, true);
    redirect_header('index.php', 15, _MA_ASSESSMENT_ACABOU);
}

/**
 * If the time has not burst we still need the cod_result to save the result if the student wants
 */
$cod_resultado = $resultado->getVar('cod_resultado');

/**
 * Seeking title of test. description and number of questions answered
 */
$qtd_respostas = $resultado->contarRespostas();
$titulo        = $exam->getVar('titulo');
$descricao     = $exam->getVar('descricao');

/**
 * Looking for a field of insurance or for form
 */
$campo_token = $GLOBALS['xoopsSecurity']->getTokenHTML();

//module name
$nome_modulo = $xoopsModule->getVar('name');

/**
 * Assigning Variables to the template
 * obs: could have been made direct in the "previous tab" but for code reading issues I separated the two
 * then we can think of joining in a section s
 */
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $titulo);
$xoopsTpl->assign('nome_modulo', $nome_modulo); //Name of the module for breadcrump
$xoopsTpl->assign('titulo', $titulo); //Title of the test
$xoopsTpl->assign('descricao', $descricao); //Description of test
$xoopsTpl->assign('cod_prova', $cod_prova); //Code of the test (to return to the test if you give up finishing the test nowa
$xoopsTpl->assign('qtd_perguntas', $qtd_perguntas); // # of questions in the test
$xoopsTpl->assign('qtd_respostas', $qtd_respostas); //# of answers in the test
$xoopsTpl->assign('cod_resultado', $cod_resultado); //To finish the test the next script needs this data
$xoopsTpl->assign('timeSpent', $timeSpent); //How long since the start of the race
$xoopsTpl->assign('lang_temporestante', sprintf(_MA_ASSESSMENT_TEMPORESTANTECOMPOSTO, $timeLeft['horas'], $timeLeft['minutos']));
$xoopsTpl->assign('lang_andamento', sprintf(_MA_ASSESSMENT_ANDAMENTO, $qtd_respostas, $qtd_perguntas));
$xoopsTpl->assign('lang_terminou', sprintf(_MA_ASSESSMENT_TERMINOU, $timeSpent['horas'], $timeSpent['minutos']));
$xoopsTpl->assign('lang_alerta', _MA_ASSESSMENT_ALERTA);
$xoopsTpl->assign('lang_confirmasim', _MA_ASSESSMENT_CONFIRMASIM);
$xoopsTpl->assign('lang_confirmanao', _MA_ASSESSMENT_CONFIRMANAO);
$xoopsTpl->assign('lang_confirmacao', _MA_ASSESSMENT_CONFIRMACAO);
$xoopsTpl->assign('lang_stats', _MA_ASSESSMENT_STATS);

$xoopsTpl->assign('lang_prova', _MA_ASSESSMENT_PROVA);
$xoopsTpl->assign('campo_token', $campo_token);

/**
 * Including page closing file
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
