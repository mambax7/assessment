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
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */

use XoopsModules\Assessment;

require dirname(dirname(__DIR__)) . '/mainfile.php';
//include dirname(dirname(__DIR__)) . '/header.php';
require XOOPS_ROOT_PATH . '/header.php';

$moduleDirName = basename(__DIR__);

/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

$modulePath = XOOPS_ROOT_PATH . '/modules/' . $moduleDirName;
//require __DIR__ . '/config/config.php';

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
    require $GLOBALS['xoops']->path('class/theme.php');
    $GLOBALS['xoTheme'] = new \xos_opal_Theme();
}

//Handlers
//$XXXHandler = xoops_getModuleHandler('XXX', $moduleDirName);

// Load language files
$helper->loadLanguage('main');

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof XoopsTpl)) {
    require $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new XoopsTpl();
}

//if ($publisher->getConfig('seo_url_rewrite') != 'none') {
//    require PUBLISHER_ROOT_PATH . '/include/seo.inc.php';
//}
