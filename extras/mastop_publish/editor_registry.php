<?php
// $Id: editor_registry.php,v 1.1 2007/02/03 21:11:16 topet05 Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * XOOPS editor registry
 *
 * @author        phppp (D.J.)
 * @copyright     copyright (c) 2005 XOOPS.org
 */
$current_path = __FILE__;
if (DIRECTORY_SEPARATOR !== '/') {
    $current_path = str_replace(mb_strpos($current_path, '\\\\', 2) ? '\\\\' : DIRECTORY_SEPARATOR, '/', $current_path);
}
$root_path = dirname($current_path);

return $config = [
    'name'  => 'mastoppublish',
    //"class"   =>  "XoopsFormTinyeditorTextArea",
    'class' => 'XoopsFormMPublishTextArea',
    'file'  => $root_path . '/formmpublishtextarea.php',
    'title' => 'mastoppublish',
    'order' => 5,
];
