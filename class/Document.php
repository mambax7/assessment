<?php

namespace XoopsModules\Assessment;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
// assessment_documentos.php,v 1
//  ---------------------------------------------------------------- //
//                                             //
// ----------------------------------------------------------------- //

use XoopsModules\Assessment;

//require_once XOOPS_ROOT_PATH . '/kernel/object.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.inc.php';
//require_once XOOPS_ROOT_PATH."/class/xoopseditor/mastoppublish/formmpublishtextarea.php";
//require_once XOOPS_ROOT_PATH . '/class/xoopsform/formselecteditor.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopsform/formeditor.php';
//require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.sanitizer.php";
//require_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsform/xoopsformloader.php";

/**
 * Document class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Document extends \XoopsObject
{
    /** @var \XoopsMySQLDatabase $db */
    public $db;

    // constructor

    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_documento', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('titulo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tipo', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cod_prova', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cods_perguntas', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('documento', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('uid_elaborador', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('fonte', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('html', XOBJ_DTYPE_INT, null, false, 10);
        if (!empty($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        } else {
            $this->setNew();
        }
    }

    /**
     * @param $id
     */
    public function load($id)
    {
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos') . ' WHERE cod_documento=' . $id;
        $myrow = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($myrow);
        if (!$myrow) {
            $this->setNew();
        }
    }

}
