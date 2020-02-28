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
 * index.php, Main administration file
 *
 * This file was implemented as follows
 * First you have several functions
 * Then you have a case that will call some of these functions according to the parameter $op
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Xoops admin header file
 */
require_once __DIR__ . '/admin_header.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Function that draws the header of the Xoops administration
 */
//xoops_cp_header();

/**
 * Function that allows you to clone a test by copying your data, your questions and their answers
 * perguntas
 */
$cod_prova = \Xmf\Request::getInt('cod_prova', '', 'POST');
/**
 * Creating the factories of the objects we will need
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$examFactory->clonarProva($cod_prova);
$cod_prova_clone = $xoopsDB->getInsertId();
$criteria        = new \Criteria('cod_prova', $cod_prova);
$questionFactory->clonarPerguntas($criteria, $cod_prova_clone);
$documentFactory->cloneDocuments($criteria, $cod_prova_clone);

redirect_header('main.php?op=edit_test&cod_prova=' . $cod_prova_clone, 2, _AM_ASSESSMENT_SUCESSO);
//fechamento das tags de if l� de cim�o verifica��o se os arquivos do phppp existem
//xoops_cp_footer();
