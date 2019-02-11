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
 * questions.php, Responsible for displaying the question at once
 * This file prepares the form with the question and if there is a
 * document for it it also displays it
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Xoops header files to load ...
 */
$GLOBALS['xoopsOption']['template_main'] = 'assessment_perguntas.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH.'/header.php';


/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

/**
 * Inclusions of module classes
 */

//require_once __DIR__. '/class/navegacao.php'; // class derived from xoops pagenav to display the questions that have already been answered

/**
 * Inclusion of navigation bar class
 */
require_once dirname(dirname(__DIR__)) . '/class/pagenav.php';

/**
 * Picking cod_prova and start from GET and student uid from session
 * the start   the possibility that the proof is, the question
 *
 */
$cod_prova = \Xmf\Request::getInt('cod_prova', '', 'GET');
$uid       = $xoopsUser->getVar('uid');
$start     = \Xmf\Request::getInt('start', 0, 'GET');

/**
 * Creation of the factories of objects that we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);
$answerFactory   = new Assessment\AnswerHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$documentFactory = new Assessment\DocumentHandler($xoopsDB);

/**
 * Searching the factory for proof that this question belongs to
 */
$prova = $examFactory->get($cod_prova);

/**
 * Verifying Student Privileges for this Test
 */
/** @var \XoopsModules\Assessment\Exam $prova */
if (!$prova->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Verifying proof has expired
 */
$fim          = $prova->getVar('data_fim', 'n');
$tempo        = $prova->getVar('tempo', 'n');
$fimmaistempo = $examFactory->dataMysql2dataUnix($fim) + $tempo;

if ($fimmaistempo < time()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Checking if student had already finished the test before in case of a positive
 * inform by message
 */
$criteria_prova = new \Criteria('cod_prova', $cod_prova);
$criteria_prova->setOrder('ASC');
$criteria_prova->setSort('ordem');
$criteria_aluno     = new \Criteria('uid_aluno', $uid);
$criteria_terminou  = new \Criteria('terminou', 1);
$criteria_resultado = new \CriteriaCompo($criteria_aluno);
$criteria_resultado->add($criteria_prova);
$criteria_resultado->add($criteria_terminou);
if ($resultFactory->getCount($criteria_resultado) > 0) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_JATERMINOU);
}
/**
 * Checking if the test already has registered questions if it does not
 * does not allow the student to have access
 */
$qtd_perguntas = $questionFactory->getCount($criteria_prova);
if ($qtd_perguntas < 1) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROVAVAZIA);
}

/**
 * Creating Criteria Objects to Move to Factories
 */
$criteria_compo = new \CriteriaCompo($criteria_prova);
$criteria_compo->add($criteria_aluno);

/**
 * Finding in the factory the amount of results of this test for this student
 * to immediately check if there is already this object, not existing
 * play back to the introduction. The learner can not skip the functional test
 */
$qtd_resultados = $resultFactory->getCount($criteria_compo);
if ($qtd_resultados < 1) {
    redirect_header('verprova.php?cod_prova=' . $cod_prova, 5, 'You can not skip the start test part');
}

/**
 * Searching for the object of this student's test in the factory
 */
$resultados = $resultFactory->getObjects($criteria_compo);
/** @var \XoopsModules\Assessment\Result $resultado */
$resultado = $resultados[0];

/**
 * Calculations of remaining time, time spent and time of the end of the test
 * obs: it is necessary to pass this into the class result or proof
 */
$horaatual = time() - 18000;
//echo $horaatual . 'horaatual </br>';
$serverXX = abs((int)($GLOBALS['xoopsConfig']['server_TZ'] * 3600.0));
//echo $serverXX . 'server TZ </br>';
//echo 'Local: ' . date('r') . 'n<br>GMT: ' . gmdate('r') . '<br>';


    $data_inicio_segundos = $examFactory->dataMysql2dataUnix($resultado->getVar('data_inicio'));
    //echo $data_inicio_segundos . 'data_inicio_segundos </br>';

$tempo_prova = $prova->getVar('tempo');
//echo $tempo_prova . 'tempo_prova   </br>';

$tempo_restante = $examFactory->convertSeconds(($data_inicio_segundos + $tempo_prova) - $horaatual, 'H');
//echo $tempo_restante . 'tempo_restante </br>';
//var_dump($tempo_restante);

$tempo_gasto = $examFactory->convertSeconds($horaatual - $data_inicio_segundos, 'H');
//echo $tempo_gasto . 'tempo_gasto  </br>';
//var_dump($$tempo_gasto);

$hora_fim_da_prova = $examFactory->convertSeconds($data_inicio_segundos + $tempo_prova, 'H');
//echo $hora_fim_da_prova . 'hora_fim_da_prova  </br>';
//var_dump($hora_fim_da_prova);

/**
 * Test time check: if the time burst saves the result and
 * warns the student with the possibility of giving the grade direct to the student if so
 * is defined in the administration
 */
if ($tempo_restante['segundos'] < 0) {
    $resultado->setVar('terminou', 1);
    $resultado->unsetNew();
    if (1 == $helper->getConfig('notadireta')) {
        $resultado->setVar('fechada', 1);
    }
    $resultFactory->insert($resultado, true);
    redirect_header('index.php', 5, _MA_ASSESSMENT_ACABOU);
}

//title search test
$titulo_prova = $prova->getVar('titulo', 's');

/**
 * searching the questions of the test, then separating
 * our question and finally picking the code of it that we will need
 */
$perguntas    = $questionFactory->getObjects($criteria_prova);
$pergunta     = $perguntas[$start];
$cod_pergunta = $pergunta->getVar('cod_pergunta');

/**
 * looking for documents to be displayed before the question
 */
$documentos = $documentFactory->getDocumentosProvaPergunta($cod_prova, $cod_pergunta);

/**
 * Creating Criteria Objects to Move to Factories
 */
$criteria_pergunta = new \Criteria('cod_pergunta', $cod_pergunta);

/**
 * looking for the answers to be displayed
 */
$respostas = $answerFactory->getObjects($criteria_pergunta);

/**
 * search for a previous answer to this question if
 * the student has already answered it and the number of questions he
 * has already answered
 */
$cod_resposta_anterior = $resultado->getRespostaUsuario($cod_pergunta);
$qtd_respostas         = $resultado->contarRespostas();

$cod_resultado = $resultado->getVar('cod_resultado');

/**
 * Let's get the codes of the questions answered and the codes of the questions
 * to pass as parameters to the navigation bar that marks the questions that have already been answered in red.
 */
$cod_perguntas_respondidas = $resultado->getCodPerguntasAsArray();
$cod_perguntas             = $questionFactory->getCodObjects($criteria_prova);
$navegacao                 = new Assessment\TestNavigator($qtd_perguntas, 1, $start, 'start', 'cod_prova=' . $cod_prova);
$barra_navegacao           = $navegacao->renderImageNav($cod_perguntas, $cod_perguntas_respondidas, $helper->getConfig('qtdmenu'));

//Assembling the Form
$formulario = $questionFactory->renderFormResponder('form_resposta.php', $pergunta, $respostas, $cod_resposta_anterior);
$formulario->assign($xoopsTpl);

//sanitizing
// missing an if not to give a notice
foreach ($documentos as $doc) {
    //    $doc['documento'] = text_filter($doc['documento'],true);
    $doc['documento'] = $doc['documento'];
}
//module name
$nome_modulo = $xoopsModule->getVar('name');

//Reviewing Variables for the Template
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $titulo_prova);
$xoopsTpl->assign('nome_modulo', $nome_modulo);
$xoopsTpl->assign('documentos', $documentos);
$xoopsTpl->assign('barra_navegacao', $barra_navegacao);
$xoopsTpl->assign('start', $start);
$xoopsTpl->assign('titulo', $titulo_prova);
$xoopsTpl->assign('cod_prova', $cod_prova);
$xoopsTpl->assign('cod_resultado', $cod_resultado);
$xoopsTpl->assign('qtd_perguntas', $qtd_perguntas);
$xoopsTpl->assign('qtd_respostas', $qtd_respostas);
$xoopsTpl->assign('lang_andamento', sprintf(_MA_ASSESSMENT_ANDAMENTO, $qtd_respostas, $qtd_perguntas));
$xoopsTpl->assign('lang_encerrar', _MA_ASSESSMENT_ENCERRARPROVA);
$xoopsTpl->assign('lang_temporestante', sprintf(_MA_ASSESSMENT_TEMPORESTANTECOMPOSTO, $tempo_restante['horas'], $tempo_restante['minutos']));
$xoopsTpl->assign('lang_textosapoio', _MA_ASSESSMENT_TEXTOSAPOIO);
$xoopsTpl->assign('lang_pergunta', _MA_ASSESSMENT_PERGUNTA);
$xoopsTpl->assign('lang_respostas', _MA_ASSESSMENT_RESPOSTAS);
$xoopsTpl->assign('lang_legenda', _MA_ASSESSMENT_LEGENDA);
$xoopsTpl->assign('lang_jaresp', _MA_ASSESSMENT_JARESP);
$xoopsTpl->assign('lang_naoresp', _MA_ASSESSMENT_NAORESP);
$xoopsTpl->assign('lang_iconjaresp', _MA_ASSESSMENT_ICONJARESP);
$xoopsTpl->assign('lang_iconnaoresp', _MA_ASSESSMENT_ICONNAORESP);
$xoopsTpl->assign('tempo_restante', $tempo_restante);

//Close the page with your footer. Inclusion Required
require_once dirname(dirname(__DIR__)) . '/footer.php';
