<?php
include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
include dirname(__DIR__) . '/class/assessment_perguntas.php';
include dirname(__DIR__) . '/class/assessment_provas.php';
include dirname(__DIR__) . '/class/assessment_respostas.php';
require_once dirname(dirname(dirname(__DIR__))) . '/class/criteria.php';

$cod_prova = $_POST['cod_prova'];

$criteria             = new Criteria('cod_prova', $cod_prova);
$fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
$fabrica_de_provas    = new Xoopsassessment_provasHandler($xoopsDB);
$fabrica_de_respostas = new Xoopsassessment_respostasHandler($xoopsDB);

$perguntas = $fabrica_de_perguntas->getObjects($criteria);

foreach ($perguntas as $pergunta) {
    ++$i;
    $cod_pergunta      = $pergunta->getVar('cod_pergunta');
    $criteria_pergunta = new Criteria('cod_pergunta', $cod_pergunta);
    $fabrica_de_respostas->deleteAll($criteria_pergunta);
    echo 'respostas da pergunta ' . $i . ' apagada';
    $fabrica_de_perguntas->delete($pergunta);
    echo $i . 'pergunta(s) apagadas';
}

$fabrica_de_provas->deleteAll($criteria);

redirect_header('index.php', 45, 'Opera��o realizada com sucesso');
