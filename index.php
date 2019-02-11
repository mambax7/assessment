<?php
// $Id: index.php,v 1.13 2007/03/24 20:08:53 marcellobrandao Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * index.php, Responsible for listing user proofs
 *
 * This file lists user proofs and shows which ones are available
 * for accomplishment, which are being corrected, and which command
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

$GLOBALS['xoopsOption']['template_main'] = 'assessment_index.tpl';
require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH.'/header.php';

$moduleDirName = basename(__DIR__);
/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();


/**
 * Creation of the factories of objects that we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);

/**
 * Find all the tests, all the results of this student and all questions
 */
if (\Xmf\Request::hasVar('start', 'GET')) {
    $start = \Xmf\Request::getString('start', '', 'GET');
}
//$criteria = new \Criteria ('cod_prova');
//$criteria->setLimit(10);
//$criteria->setStart($start);
//$total_items = $examFactory->getCount();

//$vetor_provas = $examFactory->getObjects($criteria);
$vetor_provas = $examFactory->getObjects();
$qtd_provas   = count($vetor_provas);
if (is_object($GLOBALS['xoopsUser'])) {
    $uid = $GLOBALS['xoopsUser']->getVar('uid');

    $criteria_aluno   = new \Criteria('uid_aluno', $uid);
    $vetor_resultados = $resultFactory->getObjects($criteria_aluno);
    $vetor_perguntas  = $questionFactory->getObjects();
    //echo "<pre>";
    //print_r($vetor_resultados);
    $grupos = $GLOBALS['xoopsUser']->getGroups();
    /**
     * loop pass proof by test
     */
    $x = [];
    $i = 0;

    /** @var \XoopsModules\Assessment\Exam $prova */
    foreach ($vetor_provas as $prova) {
        $cod_prova = $prova->getVar('cod_prova');

        if ($prova->isAutorizado2($grupos)) {
            $fim          = $prova->getVar('data_fim', 'n');
            $tempo        = $prova->getVar('tempo', 'n');
            $fimmaistempo = $examFactory->dataMysql2dataUnix($fim) + $tempo;

            if ($fimmaistempo < time()) {
                $x[$i]['naodisponivel'] = 1;
            } else {
                $x[$i]['naodisponivel'] = 0;
            }

            $vetor_resultados_terminou = [];
            foreach ($vetor_resultados as $resultado) {
                if (1 == $resultado->getVar('terminou') && $resultado->getVar('cod_prova') == $cod_prova) {
                    $vetor_resultados_terminou[] = $resultado;
                }
            }
            if (count($vetor_resultados_terminou) > 0) {
                $x[$i]['terminou'] = 1;
                $x[$i]['fechada']  = 0;

                $vetor_resultados_terminou_e_fechada = [];
                foreach ($vetor_resultados_terminou as $resultado) {
                    if (1 == $resultado->getVar('fechada')) {
                        $vetor_resultados_terminou_e_fechada[] = $resultado;
                    }
                }

                if (count($vetor_resultados_terminou_e_fechada) > 0) {
                    $resultado           = $vetor_resultados_terminou_e_fechada[0];
                    $x[$i]['fechada']    = 1;
                    $x[$i]['nota_final'] = $resultado->getVar('nota_final');
                    $x[$i]['nivel']      = $resultado->getVar('nivel');
                }
            }
            $x[$i]['cod_prova'] = $prova->getVar('cod_prova', 's');
            $x[$i]['tit_prova'] = $prova->getVar('titulo', 's');
            $x[$i]['inicio']    = $prova->getVar('data_inicio', 's');
            $x[$i]['fim']       = $prova->getVar('data_fim', 's');

            $vetor_perguntas_por_prova = [];
            foreach ($vetor_perguntas as $pergunta) {
                if ($pergunta->getVar('cod_prova') == $cod_prova) {
                    $vetor_perguntas_por_prova[] = $pergunta;
                }
            }
            $x[$i]['qtd_perguntas'] = count($vetor_perguntas_por_prova);
        }
        ++$i;
    }
}
//$barra_navegacao = new \XoopsPageNav($total_items, $helper->getConfig('qtdindex'), $start);
//$barrinha = $barra_navegacao->renderImageNav(2);
//$xoopsTpl->assign('navegacao', $barrinha );
$provas = $x;
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . _MA_ASSESSMENT_LISTAPROVAS);
$xoopsTpl->assign('nome_modulo', $xoopsModule->getVar('name'));
$xoopsTpl->assign('vetor_provas', $provas);
$xoopsTpl->assign('lang_notafinal', _MA_ASSESSMENT_NOTAFINAL);
$xoopsTpl->assign('lang_listaprovas', _MA_ASSESSMENT_LISTAPROVAS);
$xoopsTpl->assign('lang_perguntas', _MA_ASSESSMENT_PERGUNTAS);
$xoopsTpl->assign('lang_nivel', _MA_ASSESSMENT_NIVEL);
$xoopsTpl->assign('lang_emcorrecao', _MA_ASSESSMENT_EMCORRECAO);
$xoopsTpl->assign('lang_inicio', _MA_ASSESSMENT_INICIO);
$xoopsTpl->assign('lang_fim', _MA_ASSESSMENT_FIM);
$xoopsTpl->assign('lang_tempoencerrado', _MA_ASSESSMENT_TEMPOENCERRADO);
$xoopsTpl->assign('lang_disponibilidade', _MA_ASSESSMENT_DISPONIBILIDADE);

//Close the page with your footer. Inclusion Required
require_once dirname(dirname(__DIR__)) . '/footer.php';
