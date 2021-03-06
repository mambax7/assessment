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

include dirname(__DIR__) . '/preloads/autoloader.php';
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
//require_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';

require_once dirname(dirname(dirname(__DIR__))) . '/class/xoopsformloader.php';
require_once dirname(__DIR__) . '/include/common.php';

//$moduleDirName = basename(dirname(__DIR__));
/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();
//$utility = new \XoopsModules\Assessment\Utility();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = \Xmf\Module\Admin::getInstance();

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('common');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$language   = empty($GLOBALS['xoopsConfig']['language']) ? 'english' : $GLOBALS['xoopsConfig']['language'];
$helpfolder = XOOPS_URL . "/modules/{$moduleDirName}/language/{$language}/help";
$GLOBALS['xoopsTpl']->assign('helpfolder', $helpfolder);

// set language
if (file_exists(XOOPS_ROOT_PATH . "/modules/{$moduleDirName}/language/" . $GLOBALS ['xoopsConfig']['language'] . '/help/tutorial.tpl')) {
    $GLOBALS['xoopsTpl']->assign('xoops_language', $GLOBALS ['xoopsConfig'] ['language']);
} else {
    $GLOBALS['xoopsTpl']->assign('xoops_language', 'english');
}
