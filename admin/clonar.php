<?php
// $Id: clonar.php,v 1.2 2007/03/24 14:41:40 marcellobrandao Exp $
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
include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Fun��o que desenha o cabe�alho da administra��o do Xoops
 */
//xoops_cp_header();

/**
 * Fun��o que permite clonar uma prova copiando os seus dados suas perguntas e as respostas destas
 * perguntas
 */

$cod_prova = $_POST['cod_prova'];
/**
 * Cria��o das f�bricas dos objetos que vamos precisar
 */
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$questionFactory  = new Assessment\QuestionHandler($xoopsDB);
$documentFactory = new Assessment\DocumentHandler($xoopsDB);
$examFactory->clonarProva($cod_prova);
$cod_prova_clone = $xoopsDB->getInsertId();
$criteria        = new \Criteria('cod_prova', $cod_prova);
$questionFactory->clonarPerguntas($criteria, $cod_prova_clone);
$documentFactory->clonarDocumentos($criteria, $cod_prova_clone);

redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova_clone, 2, _AM_ASSESSMENT_SUCESSO);
//fechamento das tags de if l� de cim�o verifica��o se os arquivos do phppp existem
//xoops_cp_footer();
