<?php

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xmf\Request;
use XoopsModules\Assessment;

require_once __DIR__ . '/admin_header.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$cod_prova              = \Xmf\Request::getInt('campo_cod_prova', 0, 'POST');
$titulo                 = \Xmf\Request::getString('campo_titulo', '', 'POST');
$ordem                  = \Xmf\Request::getInt('campo_ordem', 0, 'POST');
$tit_resposta_certa     = \Xmf\Request::getString('campo_resposta1', '', 'POST');
$tit_resposta_errada[1] = \Xmf\Request::getString('campo_resposta2', '', 'POST');
$tit_resposta_errada[2] = \Xmf\Request::getString('campo_resposta3', '', 'POST');
$tit_resposta_errada[3] = \Xmf\Request::getString('campo_resposta4', '', 'POST');
$tit_resposta_errada[4] = \Xmf\Request::getString('campo_resposta5', '', 'POST');

$uid_elaborador  = $xoopsUser->getVar('uid');
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$answerFactory   = new Assessment\AnswerHandler($xoopsDB);
$pergunta        = $questionFactory->create();
$pergunta->setVar('cod_prova', $cod_prova);
$pergunta->setVar('titulo', $titulo);
$pergunta->setVar('uid_elaborador', $uid_elaborador);

$pergunta->setVar('ordem', $ordem);
if ($questionFactory->insert($pergunta)) {
    $cod_pergunta = $questionFactory->pegarultimocodigo($xoopsDB);

    $resposta = $answerFactory->create();
    $resposta->setVar('titulo', $tit_resposta_certa);
    $resposta->setVar('cod_pergunta', $cod_pergunta);
    $resposta->setVar('iscerta', 1);
    $vetor_respostas[] = $resposta;

    foreach ($tit_resposta_errada as $tit) {
        $resposta = $answerFactory->create();
        $resposta->setVar('titulo', $tit);
        $resposta->setVar('iscerta', 0);
        $resposta->setVar('cod_pergunta', $cod_pergunta);
        $vetor_respostas[] = $resposta;
    }

    shuffle($vetor_respostas);
    foreach ($vetor_respostas as $resp) {
        $answerFactory->insert($resp);
    }
}

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
