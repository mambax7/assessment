<?php

use XoopsModules\Assessment;

require_once __DIR__ . '/admin_header.php';

//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';


$cod_prova = $_POST['cod_prova'];

$criteria        = new \Criteria('cod_prova', $cod_prova);
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$examFactory     = new Assessment\ExamHandler($xoopsDB);
$answerFactory   = new Assessment\AnswerHandler($xoopsDB);

$perguntas = $questionFactory->getObjects($criteria);

foreach ($perguntas as $pergunta) {
    ++$i;
    $cod_pergunta      = $pergunta->getVar('cod_pergunta');
    $criteria_pergunta = new \Criteria('cod_pergunta', $cod_pergunta);
    $answerFactory->deleteAll($criteria_pergunta);
    echo 'respostas da pergunta ' . $i . ' apagada';
    $questionFactory->delete($pergunta);
    echo $i . 'pergunta(s) apagadas';
}

$examFactory->deleteAll($criteria);

redirect_header('index.php', 45, 'Opera��o realizada com sucesso');
