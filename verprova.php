<?php
// $Id: verprova.php,v 1.10 2007/03/24 20:08:54 marcellobrandao Exp $
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
 * verprova.php, Respons�vel por gerar o formul�rio de abertura da prova
 *
 * Este arquivo exibe as instru��es da prova que o aluno est� fazendo e o permite
 * que ele crie um resultado para a sua prova
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Arquivos de cabe�alho do Xoops para carregar ...
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
include dirname(dirname(__DIR__)) . '/header.php';

/**
 * Inclus�es das classes do m�dulo
 */

/**
 * Definindo arquivo de template da p�gina
 */
$xoopsOption['template_main'] = 'assessment_verprova.tpl';

/**
 * Pegando cod_prova do formul�rio e uid do aluno da session
 */
$cod_prova = $_GET['cod_prova'];
$uid       = $xoopsUser->getVar('uid');

/**
 * Cria��o das F�bricas de objetos que vamos precisar
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$resultFactory = new Assessment\ResultHandler($xoopsDB);
$questionFactory  = new Assessment\QuestionHandler($xoopsDB);

/**
 * Fabricando o objeto prova
 */
$prova = $examFactory->get($cod_prova);

/**
 * Verificando privil�gios do aluno para esta prova
 */
if (!$prova->isAutorizado()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Verificando prova j� expirou
 */
$fim          = $prova->getVar('data_fim', 'n');
$tempo        = $prova->getVar('tempo', 'n');
$fimmaistempo = $examFactory->dataMysql2dataUnix($fim) + $tempo;

if ($fimmaistempo < time()) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_PROIBIDO);
}

/**
 * Cria��o de objetos de crit�rio para passar para as F�bricas
 */
$criteria_prova     = new \Criteria('cod_prova', $cod_prova);
$criteria_aluno     = new \Criteria('uid_aluno', $uid);
$criteria_terminou  = new \Criteria('terminou', 1);
$criteria_resultado = new \CriteriaCompo($criteria_aluno);
$criteria_resultado->add($criteria_prova);
$criteria_resultado->add($criteria_terminou);

/**
 * Verificando se aluno j� tinha terminado a prova antes em caso positivo
 * informa atraves de mensagem
 */
if ($resultFactory->getCount($criteria_resultado) > 0) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_JATERMINOU);
}

/**
 * Pegando os dados da prova e o campo de seguran�a(TOKEN)
 */
$qtd_perguntas = $questionFactory->getCount($criteria_prova);
$titulo        = $prova->getVar('titulo');
$descricao     = $prova->getVar('descricao');
$instrucoes    = $prova->getVar('instrucoes');
$nome_modulo   = $xoopsModule->getVar('name');
$campo_token   = $GLOBALS['xoopsSecurity']->getTokenHTML();

/**
 * Atribuindo vari�veis ao template
 * obs: poderia ter sido feito direto na �tapa anterior
 * mas por quest�es de leitura de c�digo separei os dois
 * depois podemos pensar em juntar numa se��o s�
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
 * Inclus�o de arquivo de fechamento da p�gina
 */
include dirname(dirname(__DIR__)) . '/footer.php';
