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

//require_once  dirname(__DIR__) . '/include/common.php';
/** @var \XoopsModules\Assessment\Helper $helper */
$helper = \XoopsModules\Assessment\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _MI_ASSESSMENT_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png',
];

$adminmenu[] = [
    'title' => _MI_ASSESSMENT_ADMENU1,
    'link'  => 'admin/main.php?op=manter_provas',
    'icon'  => $pathIcon32 . '/manage.png',
];

$adminmenu[] = [
    'title' => _MI_ASSESSMENT_ADMENU2,
    'link'  => 'admin/main.php?op=manter_resultados',
    'icon'  => $pathIcon32 . '/button_ok.png',
];

$adminmenu[] = [
    'title' => _MI_ASSESSMENT_ADMENU3,
    'link'  => 'admin/main.php?op=manter_documentos',
    'icon'  => $pathIcon32 . '/content.png',
];

$adminmenu[] = [
    'title' => _MI_ASSESSMENT_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png',
];
