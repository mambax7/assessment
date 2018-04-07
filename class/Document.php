<?php namespace XoopsModules\Assessment;

// $Id: assessment_documentos.php,v 1.10 2007/03/24 17:50:52 marcellobrandao Exp $
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
// assessment_documentos.php,v 1
//  ---------------------------------------------------------------- //
//                                             //
// ----------------------------------------------------------------- //

use XoopsModules\Assessment;

require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';
require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.inc.php';
//require_once XOOPS_ROOT_PATH."/class/xoopseditor/mastoppublish/formmpublishtextarea.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formselecteditor.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formeditor.php';
//require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.sanitizer.php";
//require_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsform/xoopsformloader.php";
/** @var Assessment\Helper $helper */

$helper = Assessment\Helper::getInstance();

/**
 * Document class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Document extends \XoopsObject
{
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

    /**
     * @param array  $criteria
     * @param bool   $asobject
     * @param string $sort
     * @param string $order
     * @param int    $limit
     * @param int    $start
     *
     * @return array
     */
    public function getAllassessment_documentoss($criteria = [], $asobject = false, $sort = 'cod_documento', $order = 'ASC', $limit = 0, $start = 0)
    {
        $db          = \XoopsDatabaseFactory::getDatabaseConnection();
        $ret         = [];
        $where_query = '';
        if (is_array($criteria) && count($criteria) > 0) {
            $where_query = ' WHERE';
            foreach ($criteria as $c) {
                $where_query .= " $c AND";
            }
            $where_query = substr($where_query, 0, -4);
        } elseif (!is_array($criteria) && $criteria) {
            $where_query = ' WHERE ' . $criteria;
        }
        if (!$asobject) {
            $sql    = 'SELECT cod_documento FROM ' . $db->prefix('assessment_documentos') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['assessment_documentos_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_documentos') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new Assessment\Document($myrow);
            }
        }

        return $ret;
    }
}
