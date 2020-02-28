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
 * prepergunta.php, Responsible for processing the test opening
 *
 * This file processes the opening of the test creating a result for the student
 * for the first time. This result will be updated during the user's responses and in the administration when the student is receiving his grade
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use Xmf\Request;
use XoopsModules\Assessment;

require __DIR__ . '/header.php';

/**
 * Taking form_programs of the form and uid of the session student
 */
$cod_prova = \Xmf\Request::getInt('cod_prova', '', 'POST');
$uid       = $xoopsUser->getVar('uid');

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _MA_ASSESSMENT_TOKENEXPIRED);
}

/**
 * Creation of the Results Factory (factory project pattern with DAO)
 */
$resultFactory = new Assessment\ResultHandler($xoopsDB);

/**
 * Creation of the criteria for the factory to produce the objects
 */
$criteria_test    = new \Criteria('cod_prova', $cod_prova);
$criteria_student = new \Criteria('uid_aluno', $uid);
$criteria         = new \CriteriaCompo($criteria_test);
$criteria->add($criteria_student);

/**
 * Checks whether the result has already been created before and if
 * creates the result, if it informs that the test is in progress j
 */
if ($resultFactory->getCount($criteria) < 1) {
    $resultado = $resultFactory->create();
    $resultado->setVar('uid_aluno', $uid);
    $resultado->setVar('cod_prova', $cod_prova);
    $resultFactory->insert($resultado);
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=0', 8, _MA_ASSESSMENT_CONTAGEMSTART);
} else {
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=0', 8, _MA_ASSESSMENT_PROVAEMANDAMENTO);
}

/**
 * Including page closing file
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
