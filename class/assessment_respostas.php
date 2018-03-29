<?php
// $Id: assessment_respostas.php,v 1.2 2007/03/17 03:12:34 marcellobrandao Exp $
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
require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * assessment_respostas class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class assessment_respostas extends XoopsObject
{
    public $db;

    // constructor

    /**
     * @param null $id
     * @return assessment_respostas
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
    public function getAllassessment_respostass($criteria = [], $asobject = false, $sort = 'cod_resposta', $order = 'ASC', $limit = 0, $start = 0)
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
            $sql    = 'SELECT cod_resposta FROM ' . $db->prefix('assessment_respostas') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['assessment_respostas_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_respostas') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new assessment_respostas($myrow);
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------assessment_respostas user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_respostashandler class.
 * This class provides simple mecanisme for assessment_respostas object
 */
class Xoopsassessment_respostasHandler extends XoopsPersistableObjectHandler
{
    /**
     * create a new assessment_respostas
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object assessment_respostas
     */
    public function &create($isNew = true)
    {
        $assessment_respostas = new assessment_respostas();
        if ($isNew) {
            $assessment_respostas->setNew();
        } //hack consertando
        else {
            $assessment_respostas->unsetNew();
        }

        //fim do hack para consertar
        return $assessment_respostas;
    }

    /**
     * retrieve a assessment_respostas
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return XoopsObject {@link XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_respostas') . ' WHERE cod_resposta=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $assessment_respostas = new assessment_respostas();
            $assessment_respostas->assignVars($this->db->fetchArray($result));

            return $assessment_respostas;
        }

        return false;
    }

    /**
     * insert a new assessment_respostas in the database
     *
     * @param XoopsObject $assessment_respostas reference to the {@link assessment_respostas} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $assessment_respostas, $force = false)
    {
        global $xoopsConfig;
        if ('assessment_respostas' !== get_class($assessment_respostas)) {
            return false;
        }
        if (!$assessment_respostas->isDirty()) {
            return true;
        }
        if (!$assessment_respostas->cleanVars()) {
            return false;
        }
        foreach ($assessment_respostas->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($assessment_respostas->isNew()) {
            // ajout/modification d'un assessment_respostas
            $assessment_respostas = new assessment_respostas();
            $format               = 'INSERT INTO %s (cod_resposta, cod_pergunta, titulo, iscerta, data_criacao, data_update, uid_elaboradores, isativa)';
            $format               .= 'VALUES (%u, %u, %s, %u, %s, %s, %s, %u)';
            $sql                  = sprintf($format, $this->db->prefix('assessment_respostas'), $cod_resposta, $cod_pergunta, $this->db->quoteString($titulo), $iscerta, $now, $now, $this->db->quoteString($uid_elaboradores), $isativa);
            $force                = true;
        } else {
            $format = 'UPDATE %s SET ';
            $format .= 'cod_resposta=%u, cod_pergunta=%u, titulo=%s, iscerta=%u, data_criacao=%s, data_update=%s, uid_elaboradores=%s, isativa=%u';
            $format .= ' WHERE cod_resposta = %u';
            $sql    = sprintf($format, $this->db->prefix('assessment_respostas'), $cod_resposta, $cod_pergunta, $this->db->quoteString($titulo), $iscerta, $now, $now, $this->db->quoteString($uid_elaboradores), $isativa, $cod_resposta);
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_resposta)) {
            $cod_resposta = $this->db->getInsertId();
        }
        $assessment_respostas->assignVar('cod_resposta', $cod_resposta);

        return true;
    }

    /**
     * delete a assessment_respostas from the database
     *
     * @param XoopsObject $assessment_respostas reference to the assessment_respostas to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $assessment_respostas, $force = false)
    {
        if ('assessment_respostas' != get_class($assessment_respostas)) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE cod_resposta = %u', $this->db->prefix('assessment_respostas'), $assessment_respostas->getVar('cod_resposta'));
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * retrieve assessment_respostass from the database
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://$assessment_respostas'>$assessment_respostas</a> objects
     *                                   objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_respostas');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $assessment_respostas = new assessment_respostas();
            $assessment_respostas->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $assessment_respostas;
            } else {
                $ret[$myrow['cod_resposta']] = $assessment_respostas;
            }
            unset($assessment_respostas);
        }

        return $ret;
    }

    /**
     * count assessment_respostass matching a condition
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of assessment_perguntass
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_respostas');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($count) = $this->db->fetchRow($result);

        return $count;
    }

    /**
     * delete assessment_respostass matching a set of conditions
     *
     * @param CriteriaElement $criteria {@link CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_respostas');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }
}
