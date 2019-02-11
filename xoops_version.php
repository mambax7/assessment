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
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 * @version      $Id $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once __DIR__ . '/preloads/autoloader.php';

$modversion['version']       = 1.1;
$modversion['module_status'] = 'Beta RC1';
$modversion['release_date']  = '2019/02/10';
$modversion['name']          = _MI_ASSESSMENT_NAME;
$modversion['description']   = _MI_ASSESSMENT_DESC;
$modversion['credits']       = 'Equipe Simcity Brasil';
$modversion['author']        = 'Marcello Brandao - suico';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU GPL 2.0 or later';
$modversion['license_url']   = 'www.gnu.org/licenses/gpl-2.0.html';
$modversion['official']      = 0;
$modversion['image']         = 'assets/images/logo_module.png';
$modversion['dirname']       = basename(__DIR__);

$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16']        = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32']        = 'Frameworks/moduleclasses/icons/32';
//about
$modversion['module_website_url']  = 'www.xoops.org';
$modversion['module_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.9';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = [
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7',
];

$modversion['hasMain']     = 1;
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

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

$modversion['config'][] = [
    'name'        => 'editorpadrao',
    'title'       => '_MI_ASSESSMENT_CONFIG2_TITLE',
    'description' => '_MI_ASSESSMENT_CONFIG2_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtmlext',
    'options'     => array_flip($editorHandler->getList()),
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

/*
 $modversion['config'][] = [
'name' =>  'qtdindex',
'title' =>  '_MI_ASSESSMENT_CONFIG5_TITLE',
'description' =>  '_MI_ASSESSMENT_CONFIG5_DESC',
'formtype' =>  'select',
'valuetype' =>  'int',
'default' =>  5,
'options'     => array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '30' => 30, '50' => 50),
];

                                                                                                                  */

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'assessment_perguntas';
$modversion['tables'][1] = 'assessment_respostas';
$modversion['tables'][2] = 'assessment_provas';
$modversion['tables'][3] = 'assessment_resultados';
$modversion['tables'][4] = 'assessment_documentos';

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
