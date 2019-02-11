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
 * Test class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Exam extends \XoopsObject
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
        $ret         = [];
        $where_query = '';
        if ($criteria && is_array($criteria)) {
            $where_query = ' WHERE';
            foreach ($criteria as $c) {
                $where_query .= " $c AND";
            }
            $where_query = mb_substr($where_query, 0, -4);
        } elseif (!is_array($criteria) && $criteria) {
            $where_query = ' WHERE ' . $criteria;
        }
        if (!$asobject) {
            $sql    = 'SELECT cod_prova FROM ' . $this->db->prefix('assessment_provas') . "$where_query ORDER BY $sort $order";
            $result = $this->db->query($sql, $limit, $start);
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[] = $myrow['assessment_provas_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $this->db->prefix('assessment_provas') . "$where_query ORDER BY $sort $order";
            $result = $this->db->query($sql, $limit, $start);
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[] = new Assessment\Exam($myrow);
            }
        }

        return $ret;
    }

    /**
     * Verifica se aluno pode acessar esta prova
     *
     * @param \XoopsUser $aluno
     *
     * @return bool true se autorizado e false se n�o autorizado
     */
    public function isAutorizado($aluno = null)
    {
        global $xoopsUser, $xoopsDB;
        /** @var \XoopsUser $aluno */
        if (null === $aluno) {
            $aluno = $xoopsUser;
        }
        $acesso    = $this->getVar('acesso', 'n');
        $acesso    = explode(',', $acesso);
        $grupos    = $aluno->getGroups();
        $intersect = array_intersect($acesso, $grupos);
        if (!(count($intersect) > 0)) {
            return false;
        }

        $inicio      = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($inicio) > time()) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se aluno pode acessar esta prova
     *
     * @param mixed $grupos
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

        $inicio      = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($inicio) > time()) {
            return false;
        }

        return true;
    }
}
