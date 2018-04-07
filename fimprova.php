<?php
// $Id: fimprova.php,v 1.15 2007/03/24 20:08:53 marcellobrandao Exp $
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
 * fimprova.php, Respons�vel por gerar o formul�rio de encerramento da prova
 *
 * Este arquivo exibe um relat�rio da prova que o aluno est� fazendo e o permite
 * que encerre a prova
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */
/**
 * Definindo arquivo de template da p�gina
 */

use XoopsModules\Assessment;

/**
 * Arquivos de cabe�alho do Xoops para carregar ...
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
$xoopsOption['template_main'] = 'assessment_fimprova.tpl';
include dirname(dirname(__DIR__)) . '/header.php';

/**
 * Inclus�es das classes do m�dulo
 */

/**
 * Definindo arquivo de template da p�gina
 */
$xoopsOption['template_main'] = 'assessment_fimprova.tpl';

/**
 * Pegando cod_resultado do formul�rio e uid do aluno da session
 */
$cod_resultado = $_GET['cod_resultado'];
$uid           = $xoopsUser->getVar('uid');

/**
 * Cria��o das F�bricas de objetos que vamos precisar
 */
$examFactory    = new Assessment\ExamHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);

/**
 * Fabricando o objeto resultado
 */
/** @var \XoopsModules\Assessment\Result $resultado */
$resultado = $resultFactory->get($cod_resultado);
$cod_prova = $resultado->getVar('cod_prova');

/**
 * Create Exam object
 */
/** @var \XoopsModules\Assessment\Exam $prova */
$prova = $examFactory->get($cod_prova);

/**
 * Verificando privil�gios do aluno para esta prova
 */
if (!$prova->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

//Vamos agora pegar o numero de perguntas da prova e quantas o aluno respondeu
//e quantas ele deixou em branco para lembr�-lo e confirmar que ele quer encerrar a prova

/**
 * Cria��o de objetos de crit�rio para passar para as F�bricas
 */
$criteria_prova   = new \Criteria('cod_prova', $cod_prova);
$criteria_usuario = new \Criteria('uid_aluno', $uid);
$criteria_compo   = new \CriteriaCompo($criteria_prova);
$criteria_compo->add($criteria_usuario);

/**
 * Buscando o total de perguntas desta prova na F�brica de Perguntas
 */
$qtd_perguntas = $questionFactory->getCount($criteria_prova);

/**
 * Calculos de tempo restante, tempo gasto e hor�rio do fim da prova
 * obs: cabe passar isso para dentro da classe resultado ou prova
 */
$horaatual            = time();
$data_inicio_segundos = $examFactory->dataMysql2dataUnix($resultado->getVar('data_inicio'));
$tempo_prova          = $prova->getVar('tempo');

$tempo_restante    = $examFactory->converte_segundos(($data_inicio_segundos + $tempo_prova) - $horaatual, 'H');
$tempo_gasto       = $examFactory->converte_segundos($horaatual - $data_inicio_segundos, 'H');
$hora_fim_da_prova = $examFactory->converte_segundos($data_inicio_segundos + $tempo_prova, 'H');

/**
 * Verifica��o de tempo da prova: se estourar o tempo salvar o resultado e
 * avisar o aluno
 */
if ($tempo_restante['segundos'] < 0) {
    $resultado->setVar('terminou', 1);
    $resultado->unsetNew();
    $resultFactory->insert($resultado, true);
    redirect_header('index.php', 15, _MA_ASSESSMENT_ACABOU);
}

/**
 * Se o tempo n�o tiver estourado ainda precisamos do
 * cod_resultado para salvar o resultado se o aluno quiser
 */
$cod_resultado = $resultado->getVar('cod_resultado');

/**
 * Buscando titulo da prova. descricao e quantidade de perguntas respondidas
 */

$qtd_respostas = $resultado->contarRespostas();
$titulo        = $prova->getVar('titulo');
$descricao     = $prova->getVar('descricao');

/**
 * Buscando campo de seguran�a para o formul�rio
 */
$campo_token = $GLOBALS['xoopsSecurity']->getTokenHTML();

//nome do m�dulo
$nome_modulo = $xoopsModule->getVar('name');

/**
 * Atribuindo vari�veis ao template
 * obs: poderia ter sido feito direto na �tapa anterior
 * mas por quest�es de leitura de c�digo separei os dois
 * depois podemos pensar em juntar numa se��o s�
 */
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $titulo);
$xoopsTpl->assign('nome_modulo', $nome_modulo); //Nome do m�dulo para o breadcrump
$xoopsTpl->assign('titulo', $titulo); //T�tulo da prova
$xoopsTpl->assign('descricao', $descricao); //Descri��o da prova
$xoopsTpl->assign('cod_prova', $cod_prova); //Codigo da prova(para voltar para a prova caso desista de terminar a prova agora
$xoopsTpl->assign('qtd_perguntas', $qtd_perguntas); // qtd de perguntas na prova
$xoopsTpl->assign('qtd_respostas', $qtd_respostas); //qtd de respostas na prova
$xoopsTpl->assign('cod_resultado', $cod_resultado); //Para terminar a prova o proximo script precisa deste dado
$xoopsTpl->assign('tempo_gasto', $tempo_gasto); //Quanto tempo desde o inicio da prova
$xoopsTpl->assign('lang_temporestante', sprintf(_MA_ASSESSMENT_TEMPORESTANTECOMPOSTO, $tempo_restante['horas'], $tempo_restante['minutos']));
$xoopsTpl->assign('lang_andamento', sprintf(_MA_ASSESSMENT_ANDAMENTO, $qtd_respostas, $qtd_perguntas));
$xoopsTpl->assign('lang_terminou', sprintf(_MA_ASSESSMENT_TERMINOU, $tempo_gasto['horas'], $tempo_gasto['minutos']));
$xoopsTpl->assign('lang_alerta', _MA_ASSESSMENT_ALERTA);
$xoopsTpl->assign('lang_confirmasim', _MA_ASSESSMENT_CONFIRMASIM);
$xoopsTpl->assign('lang_confirmanao', _MA_ASSESSMENT_CONFIRMANAO);
$xoopsTpl->assign('lang_confirmacao', _MA_ASSESSMENT_CONFIRMACAO);
$xoopsTpl->assign('lang_stats', _MA_ASSESSMENT_STATS);

$xoopsTpl->assign('lang_prova', _MA_ASSESSMENT_PROVA);
$xoopsTpl->assign('campo_token', $campo_token);

/**
 * Inclus�o de arquivo de fechamento da p�gina
 */
include dirname(dirname(__DIR__)) . '/footer.php';
