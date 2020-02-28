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
     * Checks whether a student can access this exam
     *
     * @param \XoopsUser $aluno
     *
     * @return bool true if authorized and false if not authorized
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

        $start       = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($start) > time()) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se aluno pode acessar esta prova
     *
     * @param mixed $grupos
     *
     * @return bool true if authorized and false if not authorized
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

        $start       = $this->getVar('data_inicio', 'n');
        $examFactory = new Assessment\ExamHandler($xoopsDB);
        if ($examFactory->dataMysql2dataUnix($start) > time()) {
            return false;
        }

        return true;
    }
}
