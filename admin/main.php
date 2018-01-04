<?php
// $Id: index.php,v 1.22 2007/03/24 17:50:52 marcellobrandao Exp $
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

$currentFile = basename(__FILE__);

require_once __DIR__ . '/admin_header.php';
//include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

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
require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
//   include_once(XOOPS_ROOT_PATH."/Frameworks/xoops_version.php");
//   include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoops_version.php");
//   if ((XOOPS_FRAMEWORKS_VERSION<floatval(1.10))||(XOOPS_FRAMEWORKS_XOOPSEDITOR_VERSION<floatval(1.10))) {
//    echo _AM_ASSESSMENT_REQUERIMENTOS;
//    } else {

/**
 * Criação das Fábricas de objetos que vamos precisar
 */
include dirname(__DIR__) . '/class/assessment_perguntas.php';
include dirname(__DIR__) . '/class/assessment_provas.php';
include dirname(__DIR__) . '/class/assessment_respostas.php';
include dirname(__DIR__) . '/class/assessment_resultados.php';
include dirname(__DIR__) . '/class/assessment_documentos.php';
require_once dirname(dirname(dirname(__DIR__))) . '/class/pagenav.php';

//$myts = \MyTextSanitizer::getInstance();

/**
 * Verificações de segurança e atribuição de variáveis recebidas por get
 */
$op       = isset($_GET['op']) ? $_GET['op'] : '';
$start    = isset($_GET['start']) ? $_GET['start'] : '';
$startper = isset($_GET['startper']) ? $_GET['startper'] : '';
$startdoc = isset($_GET['startdoc']) ? $_GET['startdoc'] : '';

/**
 * Para termos as configs dentro da parte de admin
 */
global $xoopsModuleConfig;

/**
 * Essa função lista na tabela dentro de uma tabela os titulos das
 * provas com botões para editar a prova, excluir a prova ou ver as
 * respostas dos alunos às provas
 */
function listarprovas()
{
    /**
     * Declaração de variáveis globais
     */
    global $xoopsDB, $start, $xoopsModuleConfig, $pathIcon16;

    /**
     * Criação da fábrica de provas
     */
    $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);

    /**
     * Criação dos objetos critérios para repassar para a fábrica de provas
     */
    $criteria = new Criteria('cod_prova');
    $criteria->setLimit($xoopsModuleConfig['qtditens']);
    $criteria->setStart($start);

    /**
     * Contamos quantas provas existem e se nenhuma existir informamos
     */
    $total_items = $fabrica_de_provas->getCount();
    if (0 == $total_items) {
        echo _AM_ASSESSMENT_SEMPROVAS;
    } else {
        /**
         * Caso exista ao menos uma prova então buscamos esta(s) prova(s)
         * na fábrica
         */
        $vetor_provas = $fabrica_de_provas->getObjects($criteria);
        /**
         * Abre-se a tabela
         */
        echo "<table class='outer' width='100%'><tr><th colspan='5'>" . _AM_ASSESSMENT_LISTAPROVAS . '</th></tr>';
        /**
         * Loop nas provas montando as linhas das tabelas com os botões
         */
        foreach ($vetor_provas as $prova) {
            $x = '<tr><td>' . $prova->getVar('titulo', 's') . "</td><td width='50'>";

            $x .= '<a href="main.php?op=editar_prova&amp;cod_prova=' . $prova->getVar('cod_prova', 's');
            $x .= '"><img src="' . $pathIcon16 . '/edit.png" alt="' . _AM_ASSESSMENT_EDITARPROVAS . '" title="' . _AM_ASSESSMENT_EDITARPROVAS . '"></a><br></td>';
            $x .= '<td width="50"> <form action="clonar.php" method="post">
            <input type="hidden" value="' . $prova->getVar('cod_prova', 's') . '" name="cod_prova" id="cod_prova">
            <input type="image" src="' . $pathIcon16 . '/editcopy.png" alt="' . _AM_ASSESSMENT_CLONE . '" title="' . _AM_ASSESSMENT_CLONE . '">
            </form></td>';
            $x .= '<td width="50"><a href="main.php?op=resultados_prova&amp;cod_prova=' . $prova->getVar('cod_prova', 's') . '"><img src="' . $pathIcon16 . '/view.png" alt="' . _AM_ASSESSMENT_VERRESULT . '" title="' . _AM_ASSESSMENT_VERRESULT . '"style="border-color:#E6E6E6"></a></td>';
            $x .= '<td width="50"><form action="excluirprova.php" method="post">'
                  . $GLOBALS['xoopsSecurity']->getTokenHTML()
                  . '<input type="image" src="'
                  . $pathIcon16
                  . '/delete.png" alt="'
                  . _AM_ASSESSMENT_EXCLUIRPROVAS
                  . '" title="'
                  . _AM_ASSESSMENT_EXCLUIRPROVAS
                  . '"><input type="hidden" value="'
                  . $prova->getVar('cod_prova', 's')
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
        $barra_navegacao = new XoopsPageNav($total_items, $xoopsModuleConfig['qtditens'], $start);
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
     */
    global $xoopsDB, $xoopsUser;

    /**
     * Criação da fábrica de provas
     */
    $fabrica_de_respostas = new Xoopsassessment_respostasHandler($xoopsDB);
    $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);

    /**
     * Criação dos objetos critérios para repassar para a fábrica de provas
     */
    $criteria = new Criteria('cod_pergunta', $cod_pergunta);

    /**
     * Buscando na fábrica as respostas e a pergunta
     */
    $respostas = $fabrica_de_respostas->getObjects($criteria);
    $pergunta  = $fabrica_de_perguntas->get($cod_pergunta);

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
     */
    global $xoopsDB, $xoopsUser;

    /**
     * Buscando os dados passados via GET
     */
    $cod_resultado = $_GET['cod_resultado'];

    /**
     * Criação das fábricas dos objetos que vamos precisar
     */
    $fabrica_de_resultados = new Xoopsassessment_resultadosHandler($xoopsDB);
    $fabrica_de_provas     = new Xoopsassessment_provasHandler($xoopsDB);
    $fabrica_de_perguntas  = new Xoopsassessment_perguntasHandler($xoopsDB);

    /**
     * Buscando na fábrica o resultado a ser editado
     */
    $resultado = $fabrica_de_resultados->get($cod_resultado);
    $cod_prova = $resultado->getVar('cod_prova', 's');
    $uid_aluno = $resultado->getVar('uid_aluno', 's');

    /**
     * Criação dos objetos critéria para repassar para a fábrica de provas
     */
    $criteria_prova = new Criteria('cod_prova', $cod_prova);
    $criteria_aluno = new Criteria('uid_aluno', $uid_aluno);
    $criteria       = new criteriaCompo($criteria_prova);
    $criteria->add($criteria_aluno);

    /**
     * Buscando nas fábricas a prova a ser editada e a qtd de perguntas
     */
    $prova = $fabrica_de_provas->get($cod_prova);
    $qtd   = $fabrica_de_perguntas->getCount($criteria_prova);

    /**
     * Mandando a Fabrica gerar um formulário de edição
     */
    $fabrica_de_resultados->renderFormEditar($resultado, $prova, $qtd, 'editar_resultado.php');
}

/**
 * Função que lista os resultados e permite que se vá para a edição de recultados
 * tem que arrumar ela para que tenha um parametro $cod_prova
 */
function listarResultados()
{
    /**
     * Declaração de variáveis globais
     */
    global $xoopsDB, $xoopsUser, $start, $xoopsModuleConfig, $pathIcon16;

    /**
     * Buscando os dados passados via GET
     */
    $cod_prova = isset($_GET['$cod_prova']) ? $_GET['$cod_prova'] : '';

    /**
     * Criação das fábricas dos objetos que vamos precisar
     */
    $fabrica_de_provas     = new Xoopsassessment_provasHandler($xoopsDB);
    $fabrica_de_resultados = new Xoopsassessment_resultadosHandler($xoopsDB);

    /**
     * Criação dos objetos critéria para repassar para a fábrica de provas
     * Vamos limitar para começar do start e buscar 5 na prova de cod_prova
     */
    $criteria_prova = new Criteria('cod_prova', $cod_prova);
    $criteria_prova->setLimit($xoopsModuleConfig['qtditens']);
    if (isset($_GET['start'])) {
        $criteria_prova->setStart($_GET['start']);
    }

    /**
     * Buscando na fabrica os resultados (só os 5 que serão mostrados)
     */
    $vetor_resultados = $fabrica_de_resultados->getObjects($criteria_prova);

    /**
     * Mudança nos critérios para agora tirar o limiote de começo e de 5
     * assim podemos buscar a quantidade total de resultados para a prova
     * para poder passar para o a barra de navegação
     */
    $criteria_prova->setLimit('');
    $criteria_prova->setStart(0);
    $total_items = $fabrica_de_resultados->getCount($criteria_prova);

    if (0 == $total_items) { // teste para ver se tem provas se não tiver faz
        echo _AM_ASSESSMENT_SEMRESULT;
    } else {
        $estatisticas = $fabrica_de_resultados->stats($cod_prova);

        echo "<table class='outer' width='100%'><tr><th colspan='2'>" . _AM_ASSESSMENT_STATS . ' </th></tr>';
        echo '<tr><td class="odd"><img src="../assets/images/stats.png" title="'
             . _AM_ASSESSMENT_STATS
             . '" alt="'
             . _AM_ASSESSMENT_STATS
             . '">'
             . '</td<td class="odd">'
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

        $barra_navegacao = new XoopsPageNav($total_items, $xoopsModuleConfig['qtditens'], $start, 'start', 'op=' . $_GET['op']);
        $prova           = $fabrica_de_provas->getObjects($criteria_prova);
        $titulo          = $prova[0]->getVar('titulo');
        echo "<table class='outer' width='100%'><tr><th colspan='2'>" . _AM_ASSESSMENT_LISTARESULTADOS . '</th></tr>';
        foreach ($vetor_resultados as $resultado) {
            $uid             = $resultado->getVar('uid_aluno', 's');
            $cod_resultado   = $resultado->getVar('cod_resultado', 's');
            $data_fim        = $resultado->getVar('data_fim', 's');
            $uname           = $xoopsUser->getUnameFromId($uid);
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
                 . '<a href="main.php?op=editar_prova&cod_prova='
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
    global $xoopsDB, $startper, $xoopsModuleConfig, $pathIcon16;
    $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
    $cod_prova            = $_GET['cod_prova'];
    $criteria             = new Criteria('cod_prova', $cod_prova);
    $criteria->setSort('ordem');
    $criteria->setOrder('ASC');
    $criteria->setLimit($xoopsModuleConfig['qtditens']);
    $criteria->setStart($startper);
    $vetor_perguntas = $fabrica_de_perguntas->getObjects($criteria);
    $criteria->setLimit('');
    $criteria->setStart(0);
    $total_items     = $fabrica_de_perguntas->getCount($criteria);
    $barra_navegacao = new XoopsPageNav($total_items, $xoopsModuleConfig['qtditens'], $startper, 'startper', 'op=' . $_GET['op'] . '&' . 'cod_prova=' . $_GET['cod_prova']);

    echo "<table class='outer' width='100%'><tr><th colspan=3>" . _AM_ASSESSMENT_LISTAPERGASSOC . '</th></tr>';
    if (null == $vetor_perguntas) {
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
    $cod_prova         = $_GET['cod_prova'];
    $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);
    $prova             = $fabrica_de_provas->get($cod_prova);

    $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
    $fabrica_de_perguntas->renderFormCadastrar('cadastropergunta.php', $prova);
}

function cadastrarprova()
{
    global $xoopsDB;
    $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);
    $fabrica_de_provas->renderFormCadastrar('cadastroprova.php');
}

function editarprova()
{
    global $xoopsDB;
    $cod_prova = $_GET['cod_prova'];

    $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);
    $prova             = $fabrica_de_provas->get($cod_prova);
    $fabrica_de_provas->renderFormEditar('editarprova.php', $prova);
}

function editarpergunta()
{
    global $xoopsDB;
    $cod_pergunta = $_GET['cod_pergunta'];
    //    loadModuleAdminMenu(1,"migalhas3");
    $mainAdmin = new ModuleAdmin();
    echo $mainAdmin->addNavigation('main.php?op=editar_pergunta');
    $criteria = new Criteria('cod_pergunta', $cod_pergunta);
    $criteria->setSort('cod_resposta');
    $criteria->setOrder('ASC');
    $fabrica_de_respostas = new Xoopsassessment_respostasHandler($xoopsDB);
    $respostas            = $fabrica_de_respostas->getObjects($criteria);
    $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
    $pergunta             = $fabrica_de_perguntas->get($cod_pergunta);
    $fabrica_de_perguntas->renderFormEditar('editarpergunta.php', $pergunta, $respostas);
}

function listarDocumentos()
{
    /**
     * Listar variáveis globais
     */
    global $xoopsDB, $start, $startdoc, $xoopsModuleConfig, $pathIcon16;

    /**
     * Buscando os dados passados via GET
     */
    $cod_prova = isset($_GET['cod_prova']) ? $_GET['cod_prova'] : '';

    /**
     * Montando os criterios para buscar o total de documentos para montar a barra de navegacao
     */
    $criteria = new Criteria('cod_prova', $cod_prova);
    $criteria->setLimit('');
    $criteria->setStart(0);
    $fabrica_de_documentos = new Xoopsassessment_documentosHandler($xoopsDB);
    $total_items           = $fabrica_de_documentos->getCount($criteria);
    if (0 == $total_items) {
        echo _AM_ASSESSMENT_SEMDOCUMENTO;
    } else {
        /**
         * Montando os criterios para buscar somente os documentos desta página
         */
        $criteria->setLimit($xoopsModuleConfig['qtditens']);
        $criteria->setStart($startdoc);

        $vetor_documentos = $fabrica_de_documentos->getObjects($criteria);

        $barra_navegacao = new XoopsPageNav($total_items, $xoopsModuleConfig['qtditens'], $startdoc, 'startdoc', 'op=' . $_GET['op'] . '&' . 'cod_prova=' . $cod_prova);

        echo "<table class='outer' width='100%'><tr><th colspan='3'>" . _AM_ASSESSMENT_LISTADOC . '</th></tr>';
        foreach ($vetor_documentos as $documento) {
            $x = "<tr><td class='odd'>" . $documento->getVar('titulo', 's') . "</td><td class='odd' width='50'>";

            $x .= '<a href="main.php?op=editar_documento&amp;cod_documento=' . $documento->getVar('cod_documento', 's');
            $x .= '"><img src="' . $pathIcon16 . '/edit.png" alt="' . _AM_ASSESSMENT_EDITARDOC . '" title="' . _AM_ASSESSMENT_EDITARDOC . '"></a><br></td>';
            //$x.= '<td class="odd" width="50"><a href="main.php?op=resultados_prova&amp;cod_documento='.$documento->getVar("cod_documento", "s").'"><img src="../assets/images/detalhe.gif" alt="Ver Resultados" style="border-color:#E6E6E6"></a></td>';
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

function cadastrarDocumento()
{
    /**
     * Buscando os dados passados via GET
     */
    global $xoopsDB;
    $cod_prova = isset($_GET['cod_prova']) ? $_GET['cod_prova'] : '';

    if ('' == $cod_prova) {
        echo _AM_ASSESSMENT_INSTRUCOESNOVODOC;
    } else {
        $fabrica_de_documentos = new Xoopsassessment_documentosHandler($xoopsDB);
        $fabrica_de_documentos->renderFormCadastrar('cadastrardocumento.php', $cod_prova);
    }
}

function editarDocumento()
{
    global $xoopsDB;
    $cod_documento = $_GET['cod_documento'];

    $fabrica_de_documentos = new Xoopsassessment_documentosHandler($xoopsDB);
    $fabrica_de_documentos->renderFormEditar('editar_documento.php', $cod_documento);
}

function seloqualidade()
{
    echo '<img align="right" src="../assets/images/mlogo.png" id="marcello_brandao">';
}

switch ($op) {

    case 'manter_documentos':
        //            loadModuleAdminMenu(3,"-> "._AM_ASSESSMENT_DOCUMENTO);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=manter_documentos');
        listarDocumentos();
        cadastrarDocumento();
        seloqualidade();
        break;

    case 'manter_provas':
        //            loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=manter_provas');

        //        $mainAdmin = new ModuleAdmin();
        //        echo $mainAdmin->addNavigation('main.php?op=manter_provas');
        //        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_PERGUNTA, '#cadastrar_pergunta', 'add');
        //        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_DOCUMENTO, '#cadastrar_documento', 'add');
        //    $mainAdmin->addItemButton(_MI_ASSESSMENT_ADMENU1, "{$currentFile}?op==manter_provas", 'list');
        //        echo $mainAdmin->renderButton('left');

        listarprovas();
        cadastrarprova();
        seloqualidade();
        break;

    case 'manter_resultados':
        //            loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_RESULTADO);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=manter_resultados');
        listarResultados();
        seloqualidade();
        break;

    case 'resultados_prova':
        //          loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_RESULTPROVA);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=manter_prova');
        listarResultados();
        seloqualidade();
        break;

    case 'ver_detalhe_pergunta':
        //            loadModuleAdminMenu(2,_AM_ASSESSMENT_RESPALUNO);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=ver_detalhe_pergunta');
        verDetalhePergunta($_GET['cod_pergunta'], $_GET['cod_resposta']);
        seloqualidade();
        break;

    case 'editar_prova':
        //            loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA." - "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php?op=manter_provas');
        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_PERGUNTA, '#cadastrar_pergunta', 'add');
        $mainAdmin->addItemButton(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, '#cadastrar_documento', 'add');
        $mainAdmin->addItemButton(_MI_ASSESSMENT_ADMENU1, "{$currentFile}?op==manter_provas", 'list');

        echo $mainAdmin->renderButton('left');

        //        echo "<a href=#cadastrar_pergunta>" . _AM_ASSESSMENT_CADASTRAR . " " . _AM_ASSESSMENT_PERGUNTA . "</a> | <a href=#cadastrar_documento>" . _AM_ASSESSMENT_CADASTRAR
        //            . " " . _AM_ASSESSMENT_DOCUMENTO . "</a>";
        editarprova();
        echo "<table class='outer' width='100%'><tr><td valign=top width='50%'>";
        listarperguntas();
        echo "</td><td valign=top width='50%'>";
        listarDocumentos();
        echo "</td></tr><tr><td colspan='2'>";
        echo '<br><br><a name="cadastrar_pergunta">';
        cadastrarpergunta();
        echo "</td></tr><tr><td colspan='2'>";
        echo '<br><br><a name="cadastrar_documento">';
        cadastrarDocumento();
        echo '</td></tr></table>';
        seloqualidade();
        break;

    case 'editar_resultado':
        //            loadModuleAdminMenu(2,"-> "._AM_ASSESSMENT_PROVA." "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php');
        editarResultado();
        seloqualidade();
        break;

    case 'editar_documento':
        //            loadModuleAdminMenu(3,"-> "._AM_ASSESSMENT_DOCUMENTO." "._AM_ASSESSMENT_EDITAR);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php');
        editarDocumento();
        seloqualidade();
        break;

    case 'editar_pergunta':
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php');
        editarpergunta();
        seloqualidade();
        break;

    case 'default':

    default:
        //        loadModuleAdminMenu(1,"-> "._AM_ASSESSMENT_PROVA);
        $mainAdmin = new ModuleAdmin();
        echo $mainAdmin->addNavigation('main.php');
        listarprovas();
        cadastrarprova();
        seloqualidade();
        break;
}

//    }
//}

//fechamento das tags de if lá de cimão verificação se os arquivos do phppp existem
require_once __DIR__ . '/admin_footer.php';
