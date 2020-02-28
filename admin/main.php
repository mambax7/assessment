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
 * index.php, Principal arquivo da administração
 *
 * Este arquivo foi implementado da seguinte forma
 * Primeiro você tem várias funções
 * Depois você tem um case que vai chamar algumas destas funções de acordo com
 * o paramentro $op
 *
 * @author  Marcello Brandão <marcello.brandao@gmail.com>
 * @version 1.0
 * @package assessment
 */

/**
 * Arquivo de cabeçalho da administração do Xoops
 */

use XoopsModules\Assessment;

$currentFile = basename(__FILE__);

require_once __DIR__ . '/admin_header.php';
//require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

/**
 * Função que desenha o cabeçalho da administração do Xoops
 */
xoops_cp_header();

/**
 * Arquivo que contém várias funções interessantes , principalmente a de
 * criar o cabeçalho do menu com as abinhas
 * Verificando Versão do xoops Editor e do Frameworks,
 * não estando corretas mensagem com links para baixar
 * falta: colocando tb o link para o mastop editor
 */

//if ((!@file_exists(XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php"))||(!@file_exists(XOOPS_ROOT_PATH."/class/xoopseditor/xoops_version.php"))) {
//     echo _AM_ASSESSMENT_REQUERIMENTOS;
//
//} else {
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
//   require(XOOPS_ROOT_PATH."/Frameworks/xoops_version.php");
//   require(XOOPS_ROOT_PATH."/class/xoopseditor/xoops_version.php");
//   if ((XOOPS_FRAMEWORKS_VERSION<floatval(1.10))||(XOOPS_FRAMEWORKS_XOOPSEDITOR_VERSION<floatval(1.10))) {
//    echo _AM_ASSESSMENT_REQUERIMENTOS;
//    } else {

/**
 * Criação das Fábricas de objetos que vamos precisar
 */
require_once dirname(dirname(dirname(__DIR__))) . '/class/pagenav.php';

//$myts = \MyTextSanitizer::getInstance();

/**
 * Verificações de segurança e atribuição de variáveis recebidas por get
 */

$op = \Xmf\Request::getString('op', '', 'GET');

//$start    = \Xmf\Request::getInt('start', 0, 'GET');
//$startper = \Xmf\Request::getInt('startper', 0, 'GET');
//$startdoc = \Xmf\Request::getInt('startdoc', 0, 'GET');

$start = $startper = $startdoc = '';
if (\Xmf\Request::hasVar('start', 'GET')) {
    $start = \Xmf\Request::getInt('start', '', 'GET');
}
if (\Xmf\Request::hasVar('startper', 'GET')) {
    $startper = \Xmf\Request::getInt('startper', '', 'GET');
}
if (\Xmf\Request::hasVar('startdoc', 'GET')) {
    $startdoc = \Xmf\Request::getInt('startdoc', '', 'GET');
}

/**
 * Para termos as configs dentro da parte de admin
 */
// global $xoopsModuleConfig;

/**
 * Essa função lista na tabela dentro de uma tabela os titulos das
 * provas com botões para editar a prova, excluir a prova ou ver as
 * respostas dos alunos às provas
 */
function listarprovas()
{
    /**
     * Declaração de variáveis globais
     */ global $xoopsDB, $start, $pathIcon16;
    /** @var \XoopsModules\Assessment\Helper $helper */
    $helper = \XoopsModules\Assessment\Helper::getInstance();

    /**
     * Criação da fábrica de provas
     */
    $examFactory = new Assessment\ExamHandler($xoopsDB);

    /**
     * Criação dos objetos critérios para repassar para a fábrica de provas
     */
    $criteria = new \Criteria('cod_prova');
    $criteria->setLimit($helper->getConfig('qtditens'));
    $criteria->setStart($start);

    /**
     * Contamos quantas provas existem e se nenhuma existir informamos
     */
    $total_items = $examFactory->getCount();
    if (0 == $total_items) {
        echo _AM_ASSESSMENT_SEMPROVAS;
    } else {
        /**
         * Caso exista ao menos uma prova então buscamos esta(s) prova(s)
         * na fábrica
         */
        $vetor_provas = $examFactory->getObjects($criteria);
        /**
         * Abre-se a tabela
         */
        echo "<table class='outer' width='100%'><tr><th colspan='5'>" . _AM_ASSESSMENT_LISTAPROVAS . '</th></tr>';
        /**
         * Loop nas provas montando as linhas das tabelas com os botões
         */
        foreach ($vetor_provas as $exam) {
            $x = '<tr><td>' . $exam->getVar('titulo', 's') . "</td><td width='50'>";

            $x .= '<a href="main.php?op=edit_test&amp;cod_prova=' . $exam->getVar('cod_prova', 's');
            $x .= '"><img src="' . $pathIcon16 . '/edit.png" alt="' . _AM_ASSESSMENT_EDITARPROVAS . '" title="' . _AM_ASSESSMENT_EDITARPROVAS . '"></a><br></td>';
            $x .= '<td width="50"> <form action="clonar.php" method="post">
            <input type="hidden" value="' . $exam->getVar('cod_prova', 's') . '" name="cod_prova" id="cod_prova">
            <input type="image" src="' . $pathIcon16 . '/editcopy.png" alt="' . _AM_ASSESSMENT_CLONE . '" title="' . _AM_ASSESSMENT_CLONE . '">
            </form></td>';
            $x .= '<td width="50"><a href="main.php?op=test_results&amp;cod_prova=' . $exam->getVar('cod_prova', 's') . '"><img src="' . $pathIcon16 . '/view.png" alt="' . _AM_ASSESSMENT_VERRESULT . '" title="' . _AM_ASSESSMENT_VERRESULT . '"style="border-color:#E6E6E6"></a></td>';
            $x .= '<td width="50"><form action="excluirprova.php" method="post">'
                  . $GLOBALS['xoopsSecurity']->getTokenHTML()
                  . '<input type="image" src="'
                  . $pathIcon16
                  . '/delete.png" alt="'
                  . _AM_ASSESSMENT_EXCLUIRPROVAS
                  . '" title="'
                  . _AM_ASSESSMENT_EXCLUIRPROVAS
                  . '"><input type="hidden" value="'
                  . $exam->getVar('cod_prova', 's')
                  . '" name="cod_prova" id="cod_prova"></form></td></tr>';
            echo $x;
        }
        /**
         * Fecha-se a tabela
         */
        echo '</table>';
        /**
         * Criando a barra de navegação caso tenha muitas provas
         */
        $barra_navegacao = new \XoopsPageNav($total_items, $helper->getConfig('qtditens'), $start);
        echo $barra_navegacao->renderImageNav(2);
    }
}

/**
 * Função que exibe uma pergunta com suas respostas e destaca a resposta
 * certa e a resposta que o usuário deu. Ela é acionada de dentro da função
 * editar resultado
 * @param $cod_pergunta
 * @param $cod_resposta
 */
function verDetalhePergunta($cod_pergunta, $cod_resposta)
{
    /**
     * Declaração de variáveis globais
     */ global $xoopsDB, $xoopsUser;

    /**
     * Criação da fábrica de provas
     */
    $answerFactory   = new Assessment\AnswerHandler($xoopsDB);
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);

    /**
     * Criação dos objetos critérios para repassar para a fábrica de provas
     */
    $criteria = new \Criteria('cod_pergunta', $cod_pergunta);

    /**
     * Buscando na fábrica as respostas e a pergunta
     */
    $respostas = $answerFactory->getObjects($criteria);
    $pergunta  = $questionFactory->get($cod_pergunta);

    /**
     * Montando a apresentação da pergunta e das respostas
     */
    echo "<div class='odd outer'><h3>" . _AM_ASSESSMENT_PERGUNTA . ' ' . $pergunta->getVar('titulo') . '</h3><p><ul>';
    foreach ($respostas as $resposta) {
        echo '<li>' . $resposta->getVar('titulo');
        if (1 == $resposta->getVar('iscerta')) { // se for a resposta certa
            echo '<span style="color:#009900;font-weight:bold"> <- ' . _AM_ASSESSMENT_RESPCERTA . ' </span>';
        }
        if ($resposta->getVar('cod_resposta') == $cod_resposta) { //se for a resposta do usuário
            echo ' <span style="font-weight:bold"> <-  ' . _AM_ASSESSMENT_RESPUSR . '  </span> ';
        }
        echo '</li>';
    }
    echo '</ul></div>';
}

/**
 * Função que monta o formulário de edição do resultado(prova feita pelo aluno)
 * tem que arrumar ela para que tenha um parametro $cod_resultado
 */
function editarResultado()
{
    /**
     * Declaração de variáveis globais
     */ global $xoopsDB, $xoopsUser;

    /**
     * Buscando os dados passados via GET
     */
    $cod_resultado = \Xmf\Request::getInt('cod_resultado', null, 'GET');

    /**
     * Criação das fábricas dos objetos que vamos precisar
     */
    $resultFactory   = new Assessment\ResultHandler($xoopsDB);
    $examFactory     = new Assessment\ExamHandler($xoopsDB);
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);

    /**
     * Buscando na fábrica o resultado a ser editado
     */
    $resultado = $resultFactory->get($cod_resultado);
    $cod_prova = $resultado->getVar('cod_prova', 's');
    $uid_aluno = $resultado->getVar('uid_aluno', 's');

    /**
     * Criação dos objetos critéria para repassar para a fábrica de provas
     */
    $criteria_test    = new \Criteria('cod_prova', $cod_prova);
    $criteria_student = new \Criteria('uid_aluno', $uid_aluno);
    $criteria         = new \CriteriaCompo($criteria_test);
    $criteria->add($criteria_student);

    /**
     * Buscando nas fábricas a prova a ser editada e a qtd de perguntas
     */
    $exam = $examFactory->get($cod_prova);
    $qtd  = $questionFactory->getCount($criteria_test);

    /**
     * Mandando a Fabrica gerar um formulário de edição
     */
    $resultFactory->renderFormEditar($resultado, $exam, $qtd, 'editar_resultado.php');
}

/**
 * Function that lists the results and allows you to go to the editing of results
 * have to arrange it so that it has a parameter $ cod_prova
 */
function listarResultados()
{
    /**
     * Declaração de variáveis globais
     */ global $xoopsDB, $xoopsUser, $start, $pathIcon16;
    /** @var \XoopsModules\Assessment\Helper $helper */
    $helper = \XoopsModules\Assessment\Helper::getInstance();

    /**
     * Buscando os dados passados via GET
     */
    $cod_prova = '';
    if (\Xmf\Request::hasVar('cod_prova', 'GET')) {
        $cod_prova = \Xmf\Request::getInt('cod_prova', '', 'GET');
    }

    /**
     * Criação das fábricas dos objetos que vamos precisar
     */
    $examFactory   = new Assessment\ExamHandler($xoopsDB);
    $resultFactory = new Assessment\ResultHandler($xoopsDB);

    /**
     * Criação dos objetos critéria para repassar para a fábrica de provas
     * Vamos limitar para começar do start e buscar 5 na prova de cod_prova
     */
    $criteria_test = new \Criteria('cod_prova', $cod_prova);
    $criteria_test->setLimit($helper->getConfig('qtditens'));
    if (\Xmf\Request::hasVar('start', 'GET')) {
        $criteria_test->setStart(\Xmf\Request::getInt('start', '', 'GET'));
    }

    /**
     * Buscando na fabrica os resultados (só os 5 que serão mostrados)
     */
    $vetor_resultados = $resultFactory->getObjects($criteria_test);

    /**
     * Mudança nos critérios para agora tirar o limiote de começo e de 5
     * assim podemos buscar a quantidade total de resultados para a prova
     * para poder passar para o a barra de navegação
     */
    $criteria_test->setLimit('');
    $criteria_test->setStart(0);
    $total_items = $resultFactory->getCount($criteria_test);

    if (0 == $total_items) { // teste para ver se tem provas se não tiver faz
        echo _AM_ASSESSMENT_SEMRESULT;
    } else {
        $estatisticas = $resultFactory->stats($cod_prova);

        echo "<table class='outer' width='100%'><tr><th colspan='2'>" . _AM_ASSESSMENT_STATS . ' </th></tr>';
        echo '<tr><td class="odd"><img src="../assets/images/stats.png" title="'
             . _AM_ASSESSMENT_STATS
             . '" alt="'
             . _AM_ASSESSMENT_STATS
             . '">'
             . '</td><td class="odd">'
             . _AM_ASSESSMENT_QTDRESULT
             . ':'
             . $estatisticas['qtd']
             . _AM_ASSESSMENT_NOTAMAX
             . $estatisticas['max']
             . _AM_ASSESSMENT_NOTAMIN
             . $estatisticas['min']
             . _AM_ASSESSMENT_MEDIA
             . $estatisticas['media']
             . ' </td></tr>';
        echo '</table>';

        $barra_navegacao = new \XoopsPageNav($total_items, $helper->getConfig('qtditens'), $start, 'start', 'op=' . \Xmf\Request::getString('op', '', 'GET'));
        $exam            = $examFactory->getObjects($criteria_test);
        $titulo          = $exam[0]->getVar('titulo');
        echo "<table class='outer' width='100%'><tr><th colspan='2'>" . _AM_ASSESSMENT_LISTARESULTADOS . '</th></tr>';
        foreach ($vetor_resultados as $resultado) {
            $uid             = $resultado->getVar('uid_aluno', 's');
            $cod_resultado   = $resultado->getVar('cod_resultado', 's');
            $data_fim        = $resultado->getVar('data_fim', 's');
            $uname           = $xoopsUser::getUnameFromId($uid);
            $cod_prova_atual = $resultado->getVar('cod_prova', 's');
            $terminoutexto   = _AM_ASSESSMENT_PROVAANDAMENTO;
            if (1 == $resultado->getVar('terminou')) {
                $terminoutexto = _AM_ASSESSMENT_TERMINADA;
            }
            $x = '<tr><td> '
                 . _AM_ASSESSMENT_NOMEALUNO
                 . ' '
                 . $uname
                 . '<br> '
                 . _AM_ASSESSMENT_DATA
                 . ' <strong>'
                 . $data_fim
                 . '</strong><br>'
                 . _AM_ASSESSMENT_CODPROVA
                 . '<a href="main.php?op=edit_test&cod_prova='
                 . $cod_prova_atual
                 . '">'
                 . $cod_prova_atual
                 . '</a>  '
                 . $terminoutexto
                 . '</td>';
            $x .= '<td width="50"><a href="main.php?op=editar_resultado&amp;cod_resultado=' . $cod_resultado . '"><img src="' . $pathIcon16 . '/view.png" alt=""></a></td>';
            $x .= '</tr>';
            echo $x;
        }
        echo '</table>';
        echo $barra_navegacao->renderImageNav(2);
    }
}

function listarperguntas()
{
    global $xoopsDB, $startper, $pathIcon16;
    /** @var \XoopsModules\Assessment\Helper $helper */
    $helper          = \XoopsModules\Assessment\Helper::getInstance();
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);
    if (\Xmf\Request::hasVar('cod_prova', 'GET')) {
        $cod_prova = \Xmf\Request::getInt('cod_prova', null, 'GET');
    }
    $criteria = new \Criteria('cod_prova', $cod_prova);
    $criteria->setSort('ordem');
    $criteria->setOrder('ASC');
    $criteria->setLimit($helper->getConfig('qtditens'));
    $criteria->setStart($startper);
    $vetor_perguntas = $questionFactory->getObjects($criteria);
    $criteria->setLimit('');
    $criteria->setStart(0);
    $total_items     = $questionFactory->getCount($criteria);
    $barra_navegacao = new \XoopsPageNav($total_items, $helper->getConfig('qtditens'), $startper, 'startper', 'op=' . \Xmf\Request::getString('op', '', 'GET') . '&' . 'cod_prova=' . \Xmf\Request::getInt('cod_prova', null, 'GET'));

    echo "<table class='outer' width='100%'><tr><th colspan=3>" . _AM_ASSESSMENT_LISTAPERGASSOC . '</th></tr>';
    if (null === $vetor_perguntas) {
        echo "<tr><td class='odd'>" . _AM_ASSESSMENT_SEMPERGUNTA . '</td></tr>';
    }
    foreach ($vetor_perguntas as $pergunta) {
        $x = "<tr><td class='odd'>" . $pergunta->getVar('titulo', 's');
        $x .= '</td><td width="50" class="odd"><a href="main.php?op=editar_pergunta&amp;cod_pergunta=' . $pergunta->getVar('cod_pergunta', 's');
        $x .= '"><img src="' . $pathIcon16 . '/edit.png" alt="' . _AM_ASSESSMENT_EDITARPERGUNTAS . '" title="' . _AM_ASSESSMENT_EDITARPERGUNTAS . '"></a></td>';
        $x .= '<td class="odd" width="50"><form action="excluirpergunta.php" method="post">'
              . $GLOBALS['xoopsSecurity']->getTokenHTML()
              . '<input type="image" src="'
              . $pathIcon16
              . '/delete.png" alt="'
              . _AM_ASSESSMENT_EXCLUIRPERGUNTAS
              . '" title="'
              . _AM_ASSESSMENT_EXCLUIRPERGUNTAS
              . '"><input type="hidden" value="'
              . $pergunta->getVar('cod_pergunta', 's')
              . '" name="cod_pergunta" id="cod_pergunta"></form></td></tr>';
        echo $x;
    }
    echo '</table>';
    echo $barra_navegacao->renderImageNav(2);
}

function cadastrarpergunta()
{
    global $xoopsDB;
    $cod_prova   = \Xmf\Request::getInt('cod_prova', null, 'GET');
    $examFactory = new Assessment\ExamHandler($xoopsDB);
    $exam        = $examFactory->get($cod_prova);

    $questionFactory = new Assessment\QuestionHandler($xoopsDB);
    $questionFactory->renderFormCadastrar('cadastropergunta.php', $exam);
}

function cadastrarprova()
{
    global $xoopsDB;
    //    $examFactory = new Assessment\ExamHandler($xoopsDB);
    /** @var \XoopsModules\Assessment\ExamHandler $examFactory */
    $examFactory = \XoopsModules\Assessment\Helper::getInstance()->getHandler('Exam');
    $examFactory->renderFormCadastrar('cadastroprova.php');
}

function editarprova()
{
    global $xoopsDB;
    $cod_prova0 = $_GET['cod_prova'];
    $cod_prova  = \Xmf\Request::getInt('cod_prova', null, 'GET');

    $examFactory = new Assessment\ExamHandler($xoopsDB);
    $exam        = $examFactory->get($cod_prova);
    $examFactory->renderFormEditar('editarprova.php', $exam);
}

function editarpergunta()
{
    global $xoopsDB;
    $cod_pergunta = \Xmf\Request::getInt('cod_pergunta', null, 'GET');
    //    loadModuleAdminMenu(1,"migalhas3");
    $mainAdmin = \Xmf\Module\Admin::getInstance();
    echo $mainAdmin->displayNavigation('main.php?op=editar_pergunta');
    $criteria = new \Criteria('cod_pergunta', $cod_pergunta);
    $criteria->setSort('cod_resposta');
    $criteria->setOrder('ASC');
    $answerFactory   = new Assessment\AnswerHandler($xoopsDB);
    $respostas       = $answerFactory->getObjects($criteria);
    $questionFactory = new Assessment\QuestionHandler($xoopsDB);
    $pergunta        = $questionFactory->get($cod_pergunta);
    $questionFactory->renderFormEditar('editarpergunta.php', $pergunta, $respostas);
}

function listDocuments()
{
    /**
     * Listar variáveis globais
     */ global $xoopsDB, $start, $startdoc, $pathIcon16;
    /** @var \XoopsModules\Assessment\Helper $helper */
    $helper = \XoopsModules\Assessment\Helper::getInstance();

    /**
     * Buscando os dados passados via GET
     */
    $cod_prova = '';
    if (\Xmf\Request::hasVar('cod_prova', 'GET')) {
        $cod_prova = \Xmf\Request::getInt('cod_prova', '', 'GET');
    }

    /**
     * Montando os criterios para buscar o total de documentos para montar a barra de navegacao
     */
    $criteria = new \Criteria('cod_prova', $cod_prova);
    $criteria->setLimit('');
    $criteria->setStart(0);
    $documentFactory = new Assessment\DocumentHandler($xoopsDB);
    $total_items     = $documentFactory->getCount($criteria);
    if (0 == $total_items) {
        echo _AM_ASSESSMENT_SEMDOCUMENTO;
    } else {
        /**
         * Montando os criterios para buscar somente os documentos desta página
         */
        $criteria->setLimit($helper->getConfig('qtditens'));
        $criteria->setStart($startdoc);

        $vetor_documentos = $documentFactory->getObjects($criteria);

        $barra_navegacao = new \XoopsPageNav($total_items, $helper->getConfig('qtditens'), $startdoc, 'startdoc', 'op=' . \Xmf\Request::getString('op', '', 'GET') . '&' . 'cod_prova=' . $cod_prova);

        echo "<table class='outer' width='100%'><tr><th colspan='3'>" . _AM_ASSESSMENT_LISTADOC . '</th></tr>';
        foreach ($vetor_documentos as $documento) {
            $x = "<tr><td class='odd'>" . $documento->getVar('titulo', 's') . "</td><td class='odd' width='50'>";

            $x .= '<a href="main.php?op=editar_documento&amp;cod_documento=' . $documento->getVar('cod_documento', 's');
            $x .= '"><img src="' . $pathIcon16 . '/edit.png" alt="' . _AM_ASSESSMENT_EDITARDOC . '" title="' . _AM_ASSESSMENT_EDITARDOC . '"></a><br></td>';
            //$x.= '<td class="odd" width="50"><a href="main.php?op=test_results&amp;cod_documento='.$documento->getVar("cod_documento", "s").'"><img src="../assets/images/detalhe.gif" alt="Ver Resultados" style="border-color:#E6E6E6"></a></td>';
            $x .= '<td class="odd" width="50"><form action="excluirdocumento.php" method="post">'
                  . $GLOBALS['xoopsSecurity']->getTokenHTML()
                  . '<input type="image" src="'
                  . $pathIcon16
                  . '/delete.png" alt="'
                  . _AM_ASSESSMENT_EXCLUIRDOC
                  . '"  title="'
                  . _AM_ASSESSMENT_EXCLUIRDOC
                  . '"><input type="hidden" value="'
                  . $documento->getVar('cod_documento', 's')
                  . '" name="cod_documento" id="cod_documento"><input type="hidden" value="'
                  . $documento->getVar('cod_prova', 's')
                  . '" name="cod_prova" id="cod_prova"></form></td></tr>';
            echo $x;
        }
        echo '</table>';
        echo $barra_navegacao->renderImageNav(2);
    }
}

function registerDocument()
{
    /**
     * Buscando os dados passados via GET
     */ global $xoopsDB;
    $cod_prova = '';
    if (\Xmf\Request::hasVar('cod_prova', 'GET')) {
        $cod_prova = \Xmf\Request::getInt('cod_prova', 0, 'GET');
    }

    if ('' == $cod_prova) {
        echo _AM_ASSESSMENT_INSTRUCOESNOVODOC;
    } else {
        $documentFactory = new Assessment\DocumentHandler($xoopsDB);
        $documentFactory->renderFormCadastrar('cadastrardocumento.php', $cod_prova);
    }
}

function editarDocumento()
{
    global $xoopsDB;
    $cod_documento = \Xmf\Request::getInt('cod_documento', null, 'GET');

    $documentFactory = new Assessment\DocumentHandler($xoopsDB);
    $documentFactory->renderFormEditar('editar_documento.php', $cod_documento);
}

function seloqualidade()
{
    echo '<img align="right" src="../assets/images/mlogo.png" id="marcello_brandao">';
}

switch ($op) {
    case 'keep_documents':
        //            loadModuleAdminMenu(3,"-> "._AM_ASSESSMENT_DOCUMENTO);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=keep_documents');
        listDocuments();
        registerDocument();
        seloqualidade();
        break;
    case 'keep_test':
        //            loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=keep_test');

        //        $mainAdmin = \Xmf\Module\Admin::getInstance();
        //        echo $mainAdmin->displayNavigation('main.php?op=keep_test');
        //        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_PERGUNTA, '#cadastrar_pergunta', 'add');
        //        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_DOCUMENTO, '#cadastrar_documento', 'add');
        //    $mainAdmin->addItemButton(_MI_ASSESSMENT_ADMENU1, "{$currentFile}?op==keep_test", 'list');
        //        echo $mainAdmin->displayButton('left');

        listarprovas();
        cadastrarprova();
        seloqualidade();

        break;
    case 'keep_results':
        //            loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_RESULTADO);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=keep_results');
        listarResultados();
        seloqualidade();
        break;
    case 'test_results':
        //          loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_RESULTPROVA);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=keep_test');
        listarResultados();
        seloqualidade();
        break;
    case 'see_detail_question':
        //            loadModuleAdminMenu(2,_AM_ASSESSMENT_RESPALUNO);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=see_detail_question');
        verDetalhePergunta(\Xmf\Request::getInt('cod_pergunta', null, 'GET'), \Xmf\Request::getInt('cod_resposta', null, 'GET'));
        seloqualidade();
        break;
    case 'edit_test':
        //            loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA." - "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php?op=keep_test');
        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_PERGUNTA, '#cadastrar_pergunta', 'add');
        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, '#cadastrar_documento', 'add');
        $mainAdmin->addItemButton(_MI_ASSESSMENT_ADMENU1, "{$currentFile}?op==keep_test", 'list');

        echo $mainAdmin->displayButton('left');

        //        echo "<a href=#cadastrar_pergunta>" . _AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_PERGUNTA . "</a> | <a href=#cadastrar_documento>" . _AM_ASSESSMENT_CADASTRAR
        //            . " " . _AM_ASSESSMENT_DOCUMENTO . "</a>";
        editarprova();
        echo "<table class='outer' width='100%'><tr><td valign=top width='50%'>";
        listarperguntas();
        echo "</td><td valign=top width='50%'>";
        listDocuments();
        echo "</td></tr><tr><td colspan='2'>";
        echo '<br><br><a name="cadastrar_pergunta">';
        cadastrarpergunta();
        echo "</td></tr><tr><td colspan='2'>";
        echo '<br><br><a name="cadastrar_documento">';
        registerDocument();
        echo '</td></tr></table>';
        seloqualidade();
        break;
    case 'editar_resultado':
        //            loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_PROVA." "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php');
        editarResultado();
        seloqualidade();
        break;
    case 'editar_documento':
        //            loadModuleAdminMenu(3,"-> "._AM_ASSESSMENT_DOCUMENTO." "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php');
        editarDocumento();
        seloqualidade();
        break;
    case 'editar_pergunta':
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php');
        editarpergunta();
        seloqualidade();
        break;
    case 'default':

    default:
        //        loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA);
        $mainAdmin = \Xmf\Module\Admin::getInstance();
        echo $mainAdmin->displayNavigation('main.php');
        listarprovas();
        cadastrarprova();
        seloqualidade();
        break;
}

//    }
//}

//fechamento das tags de if lá de cimão verificação se os arquivos do phppp existem
require_once __DIR__ . '/admin_footer.php';
