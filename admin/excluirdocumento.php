<?php
// $Id: excluirdocumento.php,v 1.5 2007/03/24 14:41:40 marcellobrandao Exp $
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
include dirname(__DIR__) . '/class/assessment_documentos.php';
require_once dirname(dirname(dirname(__DIR__))) . '/class/criteria.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$cod_documento = $_POST['cod_documento'];
$cod_prova     = $_POST['cod_prova'];

$fabrica_de_documentos = new \Xoopsassessment_documentosHandler($xoopsDB);
$criteria              = new \Criteria('cod_documento', $cod_documento);

if ($fabrica_de_documentos->deleteAll($criteria)) {
    redirect_header('main.php?op=editar_prova&cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
}
