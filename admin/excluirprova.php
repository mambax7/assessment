<?php
// $Id: excluirprova.php,v 1.5 2007/03/24 14:41:40 marcellobrandao Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://xoops.org>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * excluirprova.php, Excluir a prova
 *
 *
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment/admin
 */
/**
 * Arquivo de cabe�alho da administra��o do Xoops
 */
include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Arquivo que cont�m v�rias fun��es interessantes , principalmente a de
 * criar o cabe�alho do menu com as abinhas
 */
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Inclus�es das classes do m�dulo
 */
include dirname(__DIR__) . '/class/assessment_perguntas.php';
include dirname(__DIR__) . '/class/assessment_provas.php';
include dirname(__DIR__) . '/class/assessment_respostas.php';
include dirname(__DIR__) . '/class/assessment_resultados.php';
include dirname(__DIR__) . '/class/assessment_documentos.php';
require_once dirname(dirname(dirname(__DIR__))) . '/class/criteria.php';

/**
 * Pegando cod_prova do formul�rio e uid do aluno da session
 */
$cod_prova   = $_POST['cod_prova'];
$segunda_vez = $_POST['segunda_vez'];

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
        redirect_header($_SERVER['HTTP_REFERER'], 5, _AM_ASSESSMENT_TOKENEXPIRED);
    }
    /**
     * Cria��o das F�bricas de objetos que vamos precisar
     */
    $fabrica_de_perguntas  = new \Xoopsassessment_perguntasHandler($xoopsDB);
    $fabrica_de_provas     = new \Xoopsassessment_provasHandler($xoopsDB);
    $fabrica_de_respostas  = new \Xoopsassessment_respostasHandler($xoopsDB);
    $fabrica_de_resultados = new \Xoopsassessment_resultadosHandler($xoopsDB);
    $fabrica_de_documentos = new \Xoopsassessment_documentosHandler($xoopsDB);

    /**
     * Cria��o de objetos de crit�rio para passar para as F�bricas
     */
    $criteria = new \Criteria('cod_prova', $cod_prova);

    /**
     * Buscamos na f�brica quantos documentos vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_documentos_apagados = $fabrica_de_documentos->getCount($criteria);
    $fabrica_de_documentos->deleteAll($criteria);
    echo $qtd_documentos_apagados . _AM_ASSESSMENT_DOCUMENTOSPAGADOS . ' <br>';

    /**
     * Buscamos na f�brica quantos resultados vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_resultados_apagados = $fabrica_de_resultados->getCount($criteria);
    $fabrica_de_resultados->deleteAll($criteria);
    echo $qtd_resultados_apagados . _AM_ASSESSMENT_RESULTAPAGADOS . '<br>';

    /**
     * Buscamos na f�brica as perguntas da prova, tiramos delas as respostas
     * exclu�mos as respostas
     */
    $perguntas = $fabrica_de_perguntas->getObjects($criteria);

    foreach ($perguntas as $pergunta) {
        ++$i;
        $cod_pergunta      = $pergunta->getVar('cod_pergunta');
        $criteria_pergunta = new \Criteria('cod_pergunta', $cod_pergunta);
        $fabrica_de_respostas->deleteAll($criteria_pergunta);
        printf(_AM_ASSESSMENT_RESPDAPERG, $i);
        echo '<br>';
    }
    /**
     * Buscamos na f�brica quantos resultados vamos excluir, os exclu�mos
     * e ent�o damos uma mensagem informando quantos apagamos
     */
    $qtd_perguntas_apagadas = $fabrica_de_perguntas->getCount($criteria);
    $fabrica_de_perguntas->deleteAll($criteria);
    echo $qtd_perguntas_apagadas . _AM_ASSESSMENT_PERGUNTASAPAGADAS . '<br>';

    /**
     * Enfim depois de ter exclu�do tudo exclu�moso principal, a prova
     */
    $fabrica_de_provas->deleteAll($criteria);

    redirect_header('index.php', 8, _AM_ASSESSMENT_SUCESSO);

    /**
     * Fun��o que fecha o desenho da adminsitra��o do Xoops
     */
    xoops_cp_footer();
} else {

    /**
     * Fun��o que desenha o cabe�alho da administra��o do Xoops
     */
    xoops_cp_header();

    /**
     * Parametro para a fun��o do xoops que monta a confirma��o
     */
    $hiddens = ['cod_prova' => $cod_prova, 'segunda_vez' => 1];

    /**
     * Fun��o que confirma se o professor deseja mesmo excluir a prova
     */
    xoops_confirm($hiddens, '', _AM_ASSESSMENT_CONFIRMAEXCLUSAOPROVAS, _AM_ASSESSMENT_SIMCONFIRMAEXCLUSAOPROVAS);
    /**
     * Fun��o que fecha o desenho da adminsitra��o do Xoops
     */
    xoops_cp_footer();
}
