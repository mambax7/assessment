<?php
// $Id: editarpergunta.php,v 1.6 2007/03/24 14:41:40 marcellobrandao Exp $
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

use Xmf\Request;
use XoopsModules\Assessment;

include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}
$titulo                 = $_POST['campo_titulo'];
$cod_pergunta           = $_POST['campo_cod_pergunta'];
$tit_resposta_certa     = $_POST['campo_resposta1'];
$tit_resposta_errada[1] = $_POST['campo_resposta2'];
$tit_resposta_errada[2] = $_POST['campo_resposta3'];
$tit_resposta_errada[3] = $_POST['campo_resposta4'];
$tit_resposta_errada[4] = $_POST['campo_resposta5'];
$cod_resposta_certa     = $_POST['campo_cod_resp1'];
$cod_resposta_errada[1] = $_POST['campo_cod_resp2'];
$cod_resposta_errada[2] = $_POST['campo_cod_resp3'];
$cod_resposta_errada[3] = $_POST['campo_cod_resp4'];
$cod_resposta_errada[4] = $_POST['campo_cod_resp5'];

$ordem = $_POST['campo_ordem'];

$uid_elaborador       = $xoopsUser->getVar('uid');
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$pergunta             = $questionFactory->create(false);
$pergunta->load($cod_pergunta);
$pergunta->setVar('titulo', $titulo);
$pergunta->setVar('uid_elaboradores', $uid_elaborador);
$pergunta->setVar('ordem', $ordem);
$cod_prova = $pergunta->getVar('cod_prova');

if ($questionFactory->insert($pergunta)) {
    $answerFactory = new Assessment\AnswerHandler($xoopsDB);
    $resposta_certa       = $answerFactory->create(false);
    $resposta_certa->load($cod_resposta_certa);
    $resposta_certa->setVar('titulo', $tit_resposta_certa);
    $resposta_certa->setVar('uid_elaboradores', $uid_elaborador);
    $answerFactory->insert($resposta_certa);
    $i = 1;
    foreach ($cod_resposta_errada as $cod) {
        $resposta_errada = $answerFactory->create(false);
        $resposta_errada->load($cod);
        $resposta_errada->setVar('titulo', $tit_resposta_errada[$i]);
        $resposta_errada->setVar('uid_elaboradores', $uid_elaborador);
        $answerFactory->insert($resposta_errada);
        ++$i;
    }
}

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
