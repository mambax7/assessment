<?php
// $Id: index.php,v 1.13 2007/03/24 20:08:53 marcellobrandao Exp $
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
 * index.php, Respons�vel por listar as provas do usu�rio
 *
 * Este arquivo lista as provas do usu�rio e exibe quais est�o dispon�veis
 * para realiza��o, quais est�o sendo corrigidas e quais
 *commad
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Arquivos de cabe�alho do Xoops para carregar ...
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'assessment_index.tpl';
include dirname(dirname(__DIR__)) . '/header.php';

$moduleDirName = basename(__DIR__);
$helper = Assessment\Helper::getInstance();

/**
 * Inclus�es das classes do m�dulo
 */

/**
 * Definindo arquivo de template da p�gina
 */

/**
 * Cria��o das F�bricas de objetos que vamos precisar
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory  = new Assessment\QuestionHandler($xoopsDB);
$resultFactory = new Assessment\ResultHandler($xoopsDB);

/**
 * Buscar todas as provas, todos os resultados deste aluno e e todas as perguntas
 */
if (isset($_GET['start'])) {
    $start = $_GET['start'];
}
//$criteria = new \Criteria ('cod_prova');
//$criteria->setLimit(10);
//$criteria->setStart($start);
//$total_items = $examFactory->getCount();

//$vetor_provas = $examFactory->getObjects($criteria);
$vetor_provas     = $examFactory->getObjects();
$qtd_provas       = count($vetor_provas);
if (is_object($GLOBALS['xoopsUser'])) {
    $uid = $GLOBALS['xoopsUser']->getVar('uid');

    $criteria_aluno   = new \Criteria('uid_aluno', $uid);
    $vetor_resultados = $resultFactory->getObjects($criteria_aluno);
    $vetor_perguntas  = $questionFactory->getObjects();
    //echo "<pre>";
    //print_r($vetor_resultados);
    $grupos = $GLOBALS['xoopsUser']->getGroups();
    /**
     * loop passar prova por prova
     */
    $x = [];
    $i = 0;
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
                if (1 == $resultado->getVar('terminou') & $resultado->getVar('cod_prova') == $cod_prova) {
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

//Fecha a p�gina com seu rodap�. Inclus�o Obrigat�ria
include dirname(dirname(__DIR__)) . '/footer.php';
