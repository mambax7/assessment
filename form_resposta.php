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
 * form_resposta.php, Responsible for processing the user response
 *
 * This file processes the student's response after some security tests
 *
 * @author  Marcello Brandao <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

use Xmf\Request;
use XoopsModules\Assessment;

/**
 * Xoops head files to load...
 */

require __DIR__ . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

/**
 * Pass the variables sent by $ _POST to variables with the same name
 * The three variables coming via POST are
 *
 * $cod_pergunta
 * $cod_resposta
 * $start
 */
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}
/**
 * Find uid from the student taking the test
 */
$uid = $xoopsUser->getVar('uid');

/**
 * If the student did not choose any answer he returns to the page
 */
if ('' == $cod_resposta) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _MA_ASSESSMENT_RESPOSTAEMBRANCO);
}

/**
 * Security check validating TOKEN
 */
if (!$GLOBALS['xoopsSecurity']->check()) {
    redirect_header(Request::getString('HTTP_REFERER', '', 'SERVER'), 5, _MA_ASSESSMENT_TOKENEXPIRED);
}

/**
 * Creation of the factories of objects that we will need
 */
$questionFactory = new Assessment\QuestionHandler($xoopsDB);
$answerFactory   = new Assessment\AnswerHandler($xoopsDB);
$resultFactory   = new Assessment\ResultHandler($xoopsDB);

/**
 * Creating Criteria Objects to Move to Factories
 */
$criteria_pergunta       = new \Criteria('cod_pergunta', $cod_pergunta);
$criteria_certa          = new \Criteria('iscerta', 1);
$criteria_resposta_certa = new \CriteriaCompo($criteria_pergunta);
$criteria_resposta_certa->add($criteria_certa);

/**
 * Taking what would be the right answer and then your code
 */
$respostacerta      = $answerFactory->getObjects($criteria_resposta_certa);
$cod_resposta_certa = $respostacerta[0]->getVar('cod_resposta');

/**
 * Picking up what would prove next your code
 */
$pergunta  = $questionFactory->get($cod_pergunta);
$cod_prova = $pergunta->getVar('cod_prova');

/**
 * Creating one more criterion for cod_prova, one criterion for uid_aluno and one that has both
 */
$criteria_test    = new \Criteria('cod_prova', $cod_prova);
$criteria_usuario = new \Criteria('uid_aluno', $uid);
$criteria         = new \CriteriaCompo($criteria_test);
$criteria->add($criteria_usuario);

/**
 * Determining how many questions the test has
 */
$qtd_perguntas = $questionFactory->getCount($criteria_test);

/**
 * Placing start to point to the next question unless it is the last
 * of the test in this case go to the summary screen of the end of the test
 * For the future: go back to the first one without an answer
 */
if (($qtd_perguntas - 1) == $start) {
    $start = $start;
} else {
    ++$start;
}

/**
 * Now comes the part of the information register
 */
/**
 * we look for the answers he has already given and the code of his
 * ancient answer to this question
 */
$resultados = $resultFactory->getObjects($criteria);
/** @var \XoopsModules\Assessment\Result $resultado */
$resultado           = $resultados[0];
$respostascertas     = $resultado->getRespostasCertasAsArray();
$respostaserradas    = $resultado->getRespostasErradasAsArray();
$par_respostas       = $respostascertas + $respostaserradas;
$cod_resposta_antiga = $par_respostas[$cod_pergunta];
/**
 * If the answer he had given before is not the same as what he is giving now
 */
if (!($cod_resposta == $cod_resposta_antiga)) {
    /**
     * Take his old answer
     */
    unset($respostascertas[$cod_pergunta], $respostaserradas[$cod_pergunta]);
    /**
     * Check if he got it right or wrong and if he got it, put it
     * in the right answer vector, if he missed, put it in the wrong answer
     */
    if ($cod_resposta_certa == $cod_resposta) {
        $respostascertas[$cod_pergunta] = $cod_resposta;
    } else {
        $respostaserradas[$cod_pergunta] = $cod_resposta;
    }
    /**
     * Redefine response variables in the result object
     */
    $resultado->setRespostasCertasAsArray($respostascertas);
    $resultado->setRespostasErradasAsArray($respostaserradas);
    /**
     * It is guaranteed that it is marked as an object that already existed
     * and has the object persist and give a message of success to the student
     */
    $resultado->unsetNew();
    $resultFactory->insert($resultado);
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=' . $start, 2, $message = _MA_ASSESSMENT_SUCESSO);
    /**
     * If the answer he had given earlier is the same one he is giving now,
     * he warns him that he has already answered this question
     */
} else {
    redirect_header('perguntas.php?cod_prova=' . $cod_prova . '&start=' . $start, 2, $message = _MA_ASSESSMENT_RESPOSTA_REPETIDA);
}

/**
 * Including page closing file
 */
require_once dirname(dirname(__DIR__)) . '/footer.php';
