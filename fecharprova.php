<?php
// $Id: fecharprova.php,v 1.9 2007/03/24 15:18:54 marcellobrandao Exp $
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
 * fecharprova.php, Respons�vel por processar o formul�rio de encerramento da prova
 *
 * Este arquivo processa os dados da prova do usu�rio e a fecha definitivamente
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

/** @var Assessment\Helper $helper */
$helper = Assessment\Helper::getInstance();

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
    redirect_header($_SERVER['HTTP_REFERER'], 5, _MA_ASSESSMENT_TOKENEXPIRED);
}

/**
 * Cria��o da F�brica de resultados e perguntas (padr�o de projeto factory com DAO)
 */
$resultFactory = new Assessment\ResultHandler($xoopsDB);
$questionFactory  = new Assessment\QuestionHandler($xoopsDB);

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

$qtd_acertos = substr_count($resp_certas, ',') + 1;
$qtd_erros   = substr_count($resp_erradas, ',') + 1;
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
include dirname(dirname(__DIR__)) . '/footer.php';
