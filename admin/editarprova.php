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
$examFactory            = new Assessment\ExamHandler($xoopsDB);
$instrucoes             = \Xmf\Request::getString('campo_instrucoes', '', 'POST');
$descricao              = \Xmf\Request::getString('campo_descricao', '', 'POST');
$titulo                 = \Xmf\Request::getString('campo_titulo', '', 'POST');
$cod_prova              = \Xmf\Request::getInt('campo_cod_prova', 0, 'POST');
$acesso                 = \Xmf\Request::getArray('campo_grupo', [], 'POST');
$tempo                  = \Xmf\Request::getInt('campo_tempo', 0, 'POST');
$datahorainicio         = \Xmf\Request::getArray('campo_data_inicio', [], 'POST');
$horainicio             = $examFactory->convertSeconds($datahorainicio['time'], 'H');
$data_hora_inicio_MYSQL = $datahorainicio['date'] . ' ' . $horainicio['horas'] . ':' . $horainicio['minutos'] . ':' . $horainicio['segundos'];
$datahorafim            = \Xmf\Request::getArray('campo_data_fim', [], 'POST');
$horafim                = $examFactory->convertSeconds($datahorafim['time'], 'H');
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

$exam = $examFactory->create(false);
$exam->load($cod_prova);
$exam->setVar('descricao', $descricao);
$exam->setVar('instrucoes', $instrucoes);
$exam->setVar('titulo', $titulo);
$exam->setVar('tempo', $tempo);
$exam->setVar('acesso', implode(',', $acesso));
$exam->setVar('uid_elaboradores', $uid_elaborador);
$exam->setVar('data_inicio', date('Y-m-d H:i:s', strtotime($data_hora_inicio_MYSQL)));
$exam->setVar('data_fim', date('Y-m-d H:i:s', strtotime($data_hora_fim_MYSQL)));
//$exam->unsetNew();

//$exam->setVar("data_update",$agora);
if ($examFactory->insert($exam)) {
    redirect_header('main.php?op=edit_test&amp;cod_prova=' . $cod_prova, 2, _AM_ASSESSMENT_SUCESSO);
}
