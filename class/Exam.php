<?php namespace XoopsModules\Assessment;

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

use XoopsModules\Assessment;

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Test class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Exam extends \XoopsObject
{
    public $db;

    // constructor

    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_prova', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('data_criacao', XOBJ_DTYPE_TXTBOX, '2017-01-01', false);
        $this->initVar('data_update', XOBJ_DTYPE_TXTBOX, '2017-01-01', false);
        $this->initVar('titulo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('descricao', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('instrucoes', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('acesso', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tempo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('uid_elaboradores', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('data_inicio', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('data_fim', XOBJ_DTYPE_TXTBOX, null, false);
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_provas') . ' WHERE cod_prova=' . $id;
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
    public function getAllassessment_provass($criteria = [], $asobject = false, $sort = 'cod_prova', $order = 'ASC', $limit = 0, $start = 0)
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
            $sql    = 'SELECT cod_prova FROM ' . $db->prefix('assessment_provas') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['assessment_provas_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_provas') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new Assessment\Exam($myrow);
            }
        }

        return $ret;
    }

    /**
     * Verifica se aluno pode acessar esta prova
     *
     * @param object member $aluno
     *
     * @return bool true se autorizado e false se n�o autorizado
     */
    public function isAutorizado($aluno = null)
    {
        global $xoopsUser, $xoopsDB;
        if (null == $aluno) {
            $aluno = $xoopsUser;
        }
        $acesso    = $this->getVar('acesso', 'n');
        $acesso    = explode(',', $acesso);
        $grupos    = $aluno->getGroups();
        $intersect = array_intersect($acesso, $grupos);
        if (!(count($intersect) > 0)) {
            return false;
        }

        $inicio            = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($inicio) > time()) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se aluno pode acessar esta prova
     *
     * @param vetorgrupos
     *
     * @return bool true se autorizado e false se n�o autorizado
     */
    public function isAutorizado2($grupos)
    {
        global $xoopsUser, $xoopsDB;

        $acesso    = $this->getVar('acesso', 'n');
        $acesso    = explode(',', $acesso);
        $intersect = array_intersect($acesso, $grupos);
        if (!(count($intersect) > 0)) {
            return false;
        }

        $inicio            = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($inicio) > time()) {
            return false;
        }

        return true;
    }
}