<?php
// $Id: cadastropergunta.php,v 1.9 2007/03/24 14:41:40 marcellobrandao Exp $
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
include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
include dirname(__DIR__) . '/class/assessment_perguntas.php';
include dirname(__DIR__) . '/class/assessment_provas.php';
include dirname(__DIR__) . '/class/assessment_respostas.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$cod_prova              = $_POST['campo_cod_prova'];
$titulo                 = $_POST['campo_titulo'];
$ordem                  = $_POST['campo_ordem'];
$tit_resposta_certa     = $_POST['campo_resposta1'];
$tit_resposta_errada[1] = $_POST['campo_resposta2'];
$tit_resposta_errada[2] = $_POST['campo_resposta3'];
$tit_resposta_errada[3] = $_POST['campo_resposta4'];
$tit_resposta_errada[4] = $_POST['campo_resposta5'];

$uid_elaborador       = $xoopsUser->getVar('uid');
$fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
$fabrica_de_respostas = new Xoopsassessment_respostasHandler($xoopsDB);
$pergunta             = $fabrica_de_perguntas->create();
$pergunta->setVar('cod_prova', $cod_prova);
$pergunta->setVar('titulo', $titulo);
$pergunta->setVar('uid_elaborador', $uid_elaborador);

$pergunta->setVar('ordem', $ordem);
if ($fabrica_de_perguntas->insert($pergunta)) {
    $cod_pergunta = $fabrica_de_perguntas->pegarultimocodigo($xoopsDB);

    $resposta = $fabrica_de_respostas->create();
    $resposta->setVar('titulo', $tit_resposta_certa);
    $resposta->setVar('cod_pergunta', $cod_pergunta);
    $resposta->setVar('iscerta', 1);
    $vetor_respostas[] = $resposta;

    foreach ($tit_resposta_errada as $tit) {
        $resposta = $fabrica_de_respostas->create();
        $resposta->setVar('titulo', $tit);
        $resposta->setVar('iscerta', 0);
        $resposta->setVar('cod_pergunta', $cod_pergunta);
        $vetor_respostas[] = $resposta;
    }

    shuffle($vetor_respostas);
    foreach ($vetor_respostas as $resp) {
        $fabrica_de_respostas->insert($resp);
    }
}

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
