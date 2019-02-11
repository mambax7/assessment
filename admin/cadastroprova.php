<?php
// $Id: cadastroprova.php,v 1.9 2007/03/24 20:08:53 marcellobrandao Exp $
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

//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_TOKENEXPIRED);
}

$examFactory = new Assessment\ExamHandler($xoopsDB);

$instrucoes             = \Xmf\Request::getString('campo_instrucoes', '', 'POST');
$descricao              = \Xmf\Request::getString('campo_descricao', '', 'POST');
$titulo                 = \Xmf\Request::getString('campo_titulo', '', 'POST');
$acesso                 = \Xmf\Request::getString('campo_grupo', '', 'POST');
$tempo                  = \Xmf\Request::getString('campo_tempo', '', 'POST');
$datahorainicio         = \Xmf\Request::getString('campo_data_inicio', '', 'POST');
$horainicio             = $examFactory->converte_segundos($datahorainicio['time'], 'H');
$data_hora_inicio_MYSQL = $datahorainicio['date'] . ' ' . $horainicio['horas'] . ':' . $horainicio['minutos'] . ':' . $horainicio['segundos'];
$datahorafim            = \Xmf\Request::getString('campo_data_fim', '', 'POST');
$horafim                = $examFactory->converte_segundos($datahorafim['time'], 'H');
$data_hora_fim_MYSQL    = $datahorafim['date'] . ' ' . $horafim['horas'] . ':' . $horafim['minutos'] . ':' . $horainicio['segundos'];

$data_hora_inicio_UNIX = $examFactory->dataMysql2dataUnix($data_hora_inicio_MYSQL);
$data_hora_fim_UNIX    = $examFactory->dataMysql2dataUnix($data_hora_fim_MYSQL);

if ($data_hora_inicio_UNIX > $data_hora_fim_UNIX) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _AM_ASSESSMENT_DATAINICIOMAIORQFIM);
}
/*if (!(is_int($tempo) && $tempo>0 )) {

 redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, $tempo." n�o � um n�mero inteiro. ");
    }*/

$uid_elaborador = $xoopsUser->getVar('uid');

$prova = $examFactory->create();

$prova->setVar('acesso', implode(',', $acesso));
$prova->setVar('descricao', $descricao);
$prova->setVar('instrucoes', $instrucoes);
$prova->setVar('titulo', $titulo);
$prova->setVar('tempo', $tempo);
$prova->setVar('uid_elaboradores', $uid_elaborador);
$prova->setVar('data_inicio', date('Y-m-d H:i:s', strtotime($data_hora_inicio_MYSQL)));
$prova->setVar('data_fim', date('Y-m-d H:i:s', strtotime($data_hora_fim_MYSQL)));
if ($examFactory->insert($prova)) {
    $cod_prova = $examFactory->pegarultimocodigo($xoopsDB);

    redirect_header("main.php?op=editar_prova&cod_prova=$cod_prova", 2, _AM_ASSESSMENT_SUCESSO);
}
