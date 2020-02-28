<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */
defined('XOOPS_ROOT_PATH') || exit('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

xoops_loadLanguage('common', $moduleDirName);

$modversion['version']       = 2.01;
$modversion['module_status'] = 'Alpha 1';
$modversion['release_date']  = '2020/02/25';
$modversion['name']          = _MI_ASSESSMENT_NAME;
$modversion['description']   = _MI_ASSESSMENT_DESC;
$modversion['credits']       = 'Equipe Simcity Brasil';
$modversion['author']        = 'Marcello Brandao - suico';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0 or later';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0;
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = $moduleDirName;
$modversion['modicons16']    = 'assets/images/icons/16';
$modversion['modicons32']    = 'assets/images/icons/32';
//about
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.6';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

$modversion['hasMain']     = 1;
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'][0]        = 'assessment_perguntas';
$modversion['tables'][1]        = 'assessment_respostas';
$modversion['tables'][2]        = 'assessment_provas';
$modversion['tables'][3]        = 'assessment_resultados';
$modversion['tables'][4]        = 'assessment_documentos';

// ------------------- Help files ------------------- //
$modversion['helpsection'] = [
    ['name' => _MI_ASSESSMENT_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_ASSESSMENT_TUTORIAL, 'link' => 'page=tutorial'],
    ['name' => _MI_ASSESSMENT_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_ASSESSMENT_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_ASSESSMENT_SUPPORT, 'link' => 'page=support'],
];

$modversion['config'][] = [
    'name'        => 'qtdmenu',
    'title'       => '_MI_ASSESSMENT_CONFIG1_TITLE',
    'description' => '_MI_ASSESSMENT_CONFIG1_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 5,
    'options'     => ['5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30],
];

xoops_load('xoopseditorhandler');
$editorHandler = XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'editorpadrao',
    'title'       => '_MI_ASSESSMENT_CONFIG2_TITLE',
    'description' => '_MI_ASSESSMENT_CONFIG2_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmlext',
    'options'     => $editorList,
];

//'options'     => array('dhtmlext' => 'Extended DHTML Form', 'textarea' => 'Plain Text', 'FCKeditor' => 'FCKeditor', 'tinymce' => 'tinymce', 'koivi' => 'koivi', 'mastoppublish'=>'mastoppublish'),
//'options'     => array('Extended DHTML Form' => 'dhtmlext', 'Plain Text' => 'textarea', 'FCKeditor' => 'FCKeditor', 'tinymce' => 'tinymce', 'koivi' => 'koivi','mastoppublish'=>'mastoppublish'),

$modversion['config'][] = [
    'name'        => 'notadireta',
    'title'       => '_MI_ASSESSMENT_CONFIG3_TITLE',
    'description' => '_MI_ASSESSMENT_CONFIG3_DESC',
    'formtype'    => 'yesno',
];

$modversion['config'][] = [
    'name'        => 'qtditens',
    'title'       => '_MI_ASSESSMENT_CONFIG4_TITLE',
    'description' => '_MI_ASSESSMENT_CONFIG4_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 5,
    'options'     => ['5' => 5, '10' => 10, '15' => 15, '20' => 20, '30' => 30, '50' => 50],
];

//$modversion['config'][] = [
//'name' =>  'qtdindex',
//'title' =>  '_MI_ASSESSMENT_CONFIG5_TITLE',
//'description' =>  '_MI_ASSESSMENT_CONFIG5_DESC',
//'formtype' =>  'select',
//'valuetype' =>  'int',
//'default' =>  5,
//'options'     => ['5' => 5, '10' => 10, '15' => 15, '20' => 20, '30' => 30, '50' => 50],
//];

/*
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/*
 * Show Developer Tools?
 */
$modversion['config'][] = [
    'name'        => 'displayDeveloperTools',
    'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS',
    'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_DEV_TOOLS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

$modversion['templates'][1]['file']        = 'assessment_index.tpl';
$modversion['templates'][1]['description'] = _MI_ASSESSMENT_TPL1_TITLE;
$modversion['templates'][2]['file']        = 'assessment_verprova.tpl';
$modversion['templates'][2]['description'] = _MI_ASSESSMENT_TPL2_TITLE;
$modversion['templates'][3]['file']        = 'assessment_perguntas.tpl';
$modversion['templates'][3]['description'] = _MI_ASSESSMENT_TPL3_TITLE;
$modversion['templates'][4]['file']        = 'assessment_fimprova.tpl';
$modversion['templates'][4]['description'] = _MI_ASSESSMENT_TPL4_TITLE;

$modversion['hasNotification']                               = 1;
$modversion['notification']['category'][1]['name']           = 'prova';
$modversion['notification']['category'][1]['title']          = _MI_ASSESSMENT_PROVA_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_ASSESSMENT_PROVA_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = 'fimprova.php';
$modversion['notification']['category'][1]['item_name']      = 'cod_resultado';
$modversion['notification']['category'][1]['allow_bookmark'] = 0;
$modversion['notification']['event'][1]['name']              = 'prova_corrigida';
$modversion['notification']['event'][1]['category']          = 'prova';
$modversion['notification']['event'][1]['title']             = _MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFY;
$modversion['notification']['event'][1]['caption']           = _MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFYCAP;
$modversion['notification']['event'][1]['description']       = _MI_ASSESSMENT_PROVA_CORRIGIDA_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template']     = 'provas_corrigida_notify';
$modversion['notification']['event'][1]['mail_subject']      = _MI_ASSESSMENT_PROVA_CORRIGIDA_ASSUNTOMAIL;

/*
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'id';
$modversion['comments']['pageName'] = 'detalhe.php';

$modversion['hasSearch'] = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "tabela1_search";

$modversion['blocks'][12]['file'] = "meumodulo_blocks.php";
$modversion['blocks'][12]['name'] = _MI_MEUMODULO_BNAME1;
$modversion['blocks'][12]['description'] = "Mostra o conte�do de duas vari�veis";
$modversion['blocks'][12]['show_func'] = "b_meumodulo_bloco1_show";
$modversion['blocks'][12]['options'] = "texto1|texto2";
$modversion['blocks'][12]['edit_func'] = "b_meumodulo_bloco1_edit";
$modversion['blocks'][12]['template'] = 'meumodulo_block_bloco1.tpl';
*/
