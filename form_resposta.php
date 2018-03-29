<?php
// $Id: form_resposta.php,v 1.6 2007/03/24 14:41:41 marcellobrandao Exp $
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
 * form_resposta.php, Respons�vel pelo processamento da resposta do usu�rio
 *
 * Este arquivo processa a resposta do aluno ap�s alguns testes de seguran�a
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

/**
 * Arquivos de cabe�alho do Xoops para carregar ...
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
include dirname(dirname(__DIR__)) . '/header.php';

/**
 * Inclus�es das classes do m�dulo
 */
include __DIR__ . '/class/assessment_perguntas.php';
include __DIR__ . '/class/assessment_provas.php';
include __DIR__ . '/class/assessment_respostas.php';
include __DIR__ . '/class/assessment_resultados.php';

/**
 * Passar as vari�veis enviadas por $_POST para vari�veis com o mesmo nome
 * As tr�s vari�veis vindas via POST s�o
 * $cod_pergunta
 * $cod_resposta
 * $start
 */
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        $$k = $v;
    }
}
/**
 * Buscar uid do aluno que est� fazendo a prova
 */
$uid = $xoopsUser->getVar('uid');

/**
 * Se o aluno n�o escolheu nenhuma resposta faz ele voltar para a p�gina
 */
if ('' == $cod_resposta) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, _MA_ASSESSMENT_RESPOSTAEMBRANCO);
}

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, _MA_ASSESSMENT_TOKENEXPIRED);
}

/**
 * Cria��o das F�bricas de objetos que vamos precisar
 */
$fabrica_perguntas  = new \Xoopsassessment_perguntasHandler($xoopsDB);
$fabrica_respostas  = new \Xoopsassessment_respostasHandler($xoopsDB);
$fabrica_resultados = new \Xoopsassessment_resultadosHandler($xoopsDB);

/**
 * Cria��o de objetos de crit�rio para passar para as F�bricas
 */
$criteria_pergunta       = new \Criteria('cod_pergunta', $cod_pergunta);
$criteria_certa          = new \Criteria('iscerta', 1);
$criteria_resposta_certa = new \CriteriaCompo($criteria_pergunta);
$criteria_resposta_certa->add($criteria_certa);

/**
 * Pegando qual seria a resposta certa e em seguida seu c�digo
 */
$respostacerta      = $fabrica_respostas->getObjects($criteria_resposta_certa);
$cod_resposta_certa = $respostacerta[0]->getVar('cod_resposta');

/**
 * Pegando qual seria a prova em seguida seu c�digo
 */
$pergunta  = $fabrica_perguntas->get($cod_pergunta);
$cod_prova = $pergunta->getVar('cod_prova');

/**
 * Criando mais um criterio para cod_prova, um criterio para uid_aluno e
 * um que tenha os dois
 */
$criteria_prova   = new \Criteria('cod_prova', $cod_prova);
$criteria_usuario = new \Criteria('uid_aluno', $uid);
$criteria         = new \CriteriaCompo($criteria_prova);
$criteria->add($criteria_usuario);

/**
 * Determinando quantas perguntas tem a prova
 */
$qtd_perguntas = $fabrica_perguntas->getCount($criteria_prova);

/**
 * Colocando start para apontar para a pr�xima pergunta a menos que seja a
 * �ltima da prova nesse caso ir para a tela de resumo do fim da prova
 * Para o futuro:voltar para a primeira sem resposta
 */
if (($qtd_perguntas - 1) == $start) {
    $start = $start;
} else {
    ++$start;
}

/**
 * Agora vem a parte do cadastro da informa��o
 */
/**
 * buscamos as respostas que ele j� deu e o c�digo da
 * resposta antiga dele para esta pergunta
 */
$resultados          = $fabrica_resultados->getObjects($criteria);
$resultado           = $resultados[0];
$respostascertas     = $resultado->getRespostasCertasAsArray();
$respostaserradas    = $resultado->getRespostasErradasAsArray();
$par_respostas       = $respostascertas + $respostaserradas;
$cod_resposta_antiga = $par_respostas[$cod_pergunta];
/**
 * Se a resposta que ele tinha dado antes n�o � a mesma que ele
 * est� dando agora
 */
if (!($cod_resposta == $cod_resposta_antiga)) {
    /**
     * Tira a resposta antiga dele
     */
    unset($respostascertas[$cod_pergunta], $respostaserradas[$cod_pergunta]);
    /**
     * Verifica se ele acertou ou errou e se ele acertou coloca ela
     * no vetor de respostas certas, se ele errou coloca no de respostas
     * erradas
     */
    if ($cod_resposta_certa == $cod_resposta) {
        $respostascertas[$cod_pergunta] = $cod_resposta;
    } else {
        $respostaserradas[$cod_pergunta] = $cod_resposta;
    }
    /**
     * Redefine as vari�veis de repsosta no objeto resultado
     */
    $resultado->setRespostasCertasAsArray($respostascertas);
    $resultado->setRespostasErradasAsArray($respostaserradas);
    /**
     * Garante-se que ele est� marcado como um objeto que j� existia
     * e manda persistir o objeto e dar uma mensagem de sucesso ao
     * aluno
     */
    $resultado->unsetNew();
    $fabrica_resultados->insert($resultado);
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=' . $start, 2, $message = _MA_ASSESSMENT_SUCESSO);
/**
 * Se a resposta que ele tinha dado antes � a mesma que ele
 * est� dando agora avisa ele que ele j� tinha respondido assim
 * a esta pergunta
 */
} else {
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=' . $start, 2, $message = _MA_ASSESSMENT_RESPOSTA_REPETIDA);
}

/**
 * Inclus�o de arquivo de fechamento da p�gina
 */
include dirname(dirname(__DIR__)) . '/footer.php';
