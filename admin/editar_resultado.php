<?php
// $Id: editar_resultado.php,v 1.8 2007/03/24 14:44:38 marcellobrandao Exp $
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
include dirname(__DIR__) . '/class/assessment_resultados.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header($_SERVER['HTTP_REFERER'], 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$nota_final    = $_POST['campo_nota_final'];
$nivel         = $_POST['campo_nivel'];
$obs           = $_POST['campo_observacoes'];
$cod_resultado = $_POST['campo_cod_resultado'];

$fabrica_de_resultados = new \Xoopsassessment_resultadosHandler($xoopsDB);
$resultado             = $fabrica_de_resultados->get($cod_resultado);
$resultado->setVar('nota_final', $nota_final);
$resultado->setVar('nivel', $nivel);
$resultado->setVar('obs', $obs);
$resultado->setVar('terminou', 1);
$resultado->setVar('fechada', 1);

$resultado->unsetNew();
$notificationHandler = xoops_getHandler('notification');
$notificationHandler->triggerEvent('prova', $cod_resultado, 'prova_corrigida');
if ($fabrica_de_resultados->insert($resultado)) {
    redirect_header('index.php', 2, _AM_ASSESSMENT_SUCESSO);
}
