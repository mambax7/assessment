<?php
// $Id: clonar.php,v 1.2 2007/03/24 14:41:40 marcellobrandao Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * index.php, Principal arquivo da administra��o
 *
 * Este arquivo foi implementado da seguinte forma
 * Primeiro voc� tem v�rias fun��es
 * Depois voc� tem um case que vai chamar algumas destas fun��es de acordo com
 * o paramentro $op
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use XoopsModules\Assessment;

/**
 * Arquivo de cabe�alho da administra��o do Xoops
 */
require_once __DIR__ . '/admin_header.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Fun��o que desenha o cabe�alho da administra��o do Xoops
 */
//xoops_cp_header();

/**
 * Fun��o que permite clonar uma prova copiando os seus dados suas perguntas e as respostas destas
 * perguntas
 */
$cod_prova = \Xmf\Request::getString('cod_prova', '', 'POST');
/**
 * Cria��o das f�bricas dos objetos que vamos precisar
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$examFactory->clonarProva($cod_prova);
$cod_prova_clone = $xoopsDB->getInsertId();
$criteria        = new \Criteria('cod_prova', $cod_prova);
$questionFactory->clonarPerguntas($criteria, $cod_prova_clone);
$documentFactory->clonarDocumentos($criteria, $cod_prova_clone);

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova_clone, 2, _AM_ASSESSMENT_SUCESSO);
//fechamento das tags de if l� de cim�o verifica��o se os arquivos do phppp existem
//xoops_cp_footer();
