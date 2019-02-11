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

use XoopsModules\Assessment;

//require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Answer class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Answer extends \XoopsObject
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
        $this->initVar('cod_resposta', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cod_pergunta', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('titulo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('iscerta', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('data_criacao', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('data_update', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('uid_elaboradores', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('isativa', XOBJ_DTYPE_INT, null, false, 10);
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_respostas') . ' WHERE cod_resposta=' . $id;
        $myrow = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($myrow);
        if (!$myrow) {
            $this->setNew();
        }
    }

}
