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
 * fecharprova.php, Respons�vel por processar o formul�rio de encerramento da prova
 *
 * Este arquivo processa os dados da prova do usu�rio e a fecha definitivamente
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use Xmf\Request;
use XoopsModules\Assessment;

/**
 * Arquivos de cabe�alho do Xoops para carregar ...
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require_once dirname(dirname(__DIR__)) . '/header.php';

/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

/**
 * Inclus�es das classes do m�dulo
 */

/**
 * Pegando cod_prova do formul�rio
 */
$cod_resultado = \Xmf\Request::getInt('cod_resultado', 0, 'POST');

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _MA_ASSESSMENT_TOKENEXPIRED);
}

/**
 * Cria��o da F�brica de resultados e perguntas (padr�o de projeto factory com DAO)
 */
$resultFactory   = new Assessment\ResultHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);

/**
 * Buscando na F�brica o resultado (padr�o de projeto factory com DAO)
 */
$resultado = $resultFactory->create(false);
$resultado = $resultFactory->get($cod_resultado);

/**
 * Calculando a nota do individuo
 */
$resp_certas  = $resultado->getVar('resp_certas');
$resp_erradas = $resultado->getVar('resp_erradas');
$cod_prova    = $resultado->getVar('cod_prova');

$criteria      = new \Criteria('cod_prova', $cod_prova);
$qtd_perguntas = $questionFactory->getCount($criteria);

$qtd_acertos = mb_substr_count($resp_certas, ',') + 1;
$qtd_erros   = mb_substr_count($resp_erradas, ',') + 1;
if ('' == $resp_certas[0]) {
    $qtd_acertos = 0;
}
if ('' == $resp_erradas[0]) {
    $qtd_erros = 0;
}
$nota_sugest = round(100 * $qtd_acertos / $qtd_perguntas, 2);

/**
 * Update the result so that the exam is unavailable to the student or
 * if it's so defined in the preferences, exit the result immediately
 */
$resultado->setVar('nota_final', $nota_sugest);
$resultado->setVar('terminou', 1);
if (1 == $helper->getConfig('notadireta')) {
    $resultado->setVar('fechada', 1);
}
$resultado->unsetNew();

/**
 * Update the result and give a success message
 */
if ($resultFactory->insert($resultado)) {
    redirect_header('index.php', 5, _MA_ASSESSMENT_CONGRATULATIONS);
}

/**
 * Include a footer
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
