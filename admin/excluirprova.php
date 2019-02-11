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
 * excluirprova.php, Excluir a prova
 *
 *
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment/admin
 */

use Xmf\Request;
use XoopsModules\Assessment;

/**
 * Arquivo de cabe�alho da administra��o do Xoops
 */
require_once __DIR__ . '/admin_header.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Arquivo que cont�m v�rias fun��es interessantes , principalmente a de
 * criar o cabe�alho do menu com as abinhas
 */
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Inclus�es das classes do m�dulo
 */
//require_once dirname(dirname(dirname(__DIR__))) . '/class/criteria.php';

/**
 * Pegando cod_prova do formul�rio e uid do aluno da session
 */
$cod_prova   = \Xmf\Request::getInt('cod_prova', 0, 'POST');
$segunda_vez = \Xmf\Request::getInt('segunda_vez', 0, 'POST');

/**
 * Ao excluir uma prova voc� precisa excluir as perguntas ligadas � ela, os
 * documentos, as respostas e os resultados portanto � algo super s�rio
 * sem volta. Por isso vamos usar uma confirma��o e confirmando excluir tudo.
 */
if (1 == $segunda_vez) {
    /**
     * Fun��o que desenha o cabe�alho da administra��o do Xoops
     */
    xoops_cp_header();

    /**
     * Security check validating TOKEN
     */
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
    }
    /**
     * Cria��o das F�bricas de objetos que vamos precisar
     */
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);
    $examFactory     = new Assessment\ExamHandler($xoopsDB);
    $answerFactory   = new Assessment\AnswerHandler($xoopsDB);
    $resultFactory   = new Assessment\ResultHandler($xoopsDB);
    $documentFactory = new Assessment\DocumentHandler($xoopsDB);

    /**
     * Cria��o de objetos de crit�rio para passar para as F�bricas
     */
    $criteria = new \Criteria('cod_prova', $cod_prova);

    /**
     * Buscamos na f�brica quantos documentos vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_documentos_apagados = $documentFactory->getCount($criteria);
    $documentFactory->deleteAll($criteria);
    echo $qtd_documentos_apagados . _AM_ASSESSMENT_DOCUMENTOSPAGADOS . ' <br>';

    /**
     * Buscamos na f�brica quantos resultados vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_resultados_apagados = $resultFactory->getCount($criteria);
    $resultFactory->deleteAll($criteria);
    echo $qtd_resultados_apagados . _AM_ASSESSMENT_RESULTAPAGADOS . '<br>';

    /**
     * Buscamos na f�brica as perguntas da prova, tiramos delas as respostas
     * exclu�mos as respostas
     */
    $perguntas = $questionFactory->getObjects($criteria);

    foreach ($perguntas as $pergunta) {
        ++$i;
        $cod_pergunta      = $pergunta->getVar('cod_pergunta');
        $criteria_pergunta = new \Criteria('cod_pergunta', $cod_pergunta);
        $answerFactory->deleteAll($criteria_pergunta);
        printf(_AM_ASSESSMENT_RESPDAPERG, $i);
        echo '<br>';
    }
    /**
     * Buscamos na f�brica quantos resultados vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_perguntas_apagadas = $questionFactory->getCount($criteria);
    $questionFactory->deleteAll($criteria);
    echo $qtd_perguntas_apagadas . _AM_ASSESSMENT_PERGUNTASAPAGADAS . '<br>';

    /**
     * Enfim depois de ter exclu�do tudo exclu�moso principal, a prova
     */
    $examFactory->deleteAll($criteria);

    redirect_header('index.php', 8, _AM_ASSESSMENT_SUCESSO);

    /**
     * Fun��o que fecha o desenho da adminsitra��o do Xoops
     */
    xoops_cp_footer();
} else {
    /**
     * Function that draws the Xoops administration header
     */
    xoops_cp_header();

    /**
     * Parameter for the funnel of the xoops mounting the confirmation
     */
    $hiddens = ['cod_prova' => $cod_prova, 'segunda_vez' => 1];

    /**
     * Function that confirms if the teacher really wants to exclude the proof
     */
    xoops_confirm($hiddens, '', _AM_ASSESSMENT_CONFIRMAEXCLUSAOPROVAS, _AM_ASSESSMENT_SIMCONFIRMAEXCLUSAOPROVAS);
    /**
     * Function that closes the design of the Xoops administration
     */
    xoops_cp_footer();
}
