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

// -------------------------------------------------------------------------
// ------------------assessment_respostas user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_respostashandler class.
 * This class provides simple mecanisme for assessment_respostas object
 */
class AnswerHandler extends \XoopsPersistableObjectHandler
{
    /**
     * create a new Assessment\Answer
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object assessment_respostas
     */
    public function &create($isNew = true)
    {
        $answer = new Assessment\Answer();
        if ($isNew) {
            $answer->setNew();
        } //hack consertando
        else {
            $answer->unsetNew();
        }

        //fim do hack para consertar
        return $answer;
    }

    /**
     * retrieve a assessment_respostas
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return bool|\XoopsModules\Assessment\Answer <a href='psi_element://XoopsObject'>XoopsObject</a>
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_respostas') . ' WHERE cod_resposta=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $answer = new Assessment\Answer();
            $answer->assignVars($this->db->fetchArray($result));

            return $answer;
        }

        return false;
    }

    /**
     * insert a new Assessment\Answer in the database
     *
     * @param \XoopsObject $answer reference to the {@link assessment_respostas} object
     * @param bool         $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $answer, $force = false)
    {
        global $xoopsConfig;
        if (!$answer instanceof \XoopsModules\Assessment\Answer) {
            return false;
        }
        if (!$answer->isDirty()) {
            return true;
        }
        if (!$answer->cleanVars()) {
            return false;
        }
        foreach ($answer->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($answer->isNew()) {
            // ajout/modification d'un assessment_respostas
            $answer = new Assessment\Answer();
            $format = 'INSERT INTO `%s` (cod_resposta, cod_pergunta, titulo, iscerta, data_criacao, data_update, uid_elaboradores, isativa)';
            $format .= 'VALUES (%u, %u, %s, %u, %s, %s, %s, %u)';
            $sql    = sprintf($format, $this->db->prefix('assessment_respostas'), $cod_resposta, $cod_pergunta, $this->db->quoteString($titulo), $iscerta, $now, $now, $this->db->quoteString($uid_elaboradores), $isativa);
            $force  = true;
        } else {
            $format = 'UPDATE `%s` SET ';
            $format .= 'cod_resposta=%u, cod_pergunta=%u, titulo=%s, iscerta=%u, data_criacao=%s, data_update=%s, uid_elaboradores=%s, isativa=%u';
            $format .= ' WHERE cod_resposta = %u';
            $sql    = sprintf($format, $this->db->prefix('assessment_respostas'), $cod_resposta, $cod_pergunta, $this->db->quoteString($titulo), $iscerta, $now, $now, $this->db->quoteString($uid_elaboradores), $isativa, $cod_resposta);
        }
        if (false !== $force) {
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
        $answer->assignVar('cod_resposta', $cod_resposta);

        return true;
    }

    /**
     * delete a assessment_respostas from the database
     *
     * @param \XoopsObject $answer reference to the assessment_respostas to delete
     * @param bool         $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $answer, $force = false)
    {
        if (!$answer instanceof \XoopsModules\Assessment\Answer) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_resposta = %u', $this->db->prefix('assessment_respostas'), $answer->getVar('cod_resposta'));
        if (false !== $force) {
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
     * @param null|\CriteriaElement|\CriteriaCompo $criteria  {@link \CriteriaElement} conditions to be met
     * @param bool                                 $id_as_key use the UID as key for the array?
     *
     * @param bool                                 $as_object
     * @return array array of <a href='psi_element://$answer'>$answer</a> objects
     *                                                        objects
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_respostas');
        if (null !== $criteria && $criteria instanceof \CriteriaElement) {
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
            $answer = new Assessment\Answer();
            $answer->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $answer;
            } else {
                $ret[$myrow['cod_resposta']] = $answer;
            }
            unset($answer);
        }

        return $ret;
    }

    /**
     * count assessment_respostass matching a condition
     *
     * @param \CriteriaElement $criteria {@link \CriteriaElement} to match
     *
     * @return int count of Questions
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_respostas');
        if (null !== $criteria && $criteria instanceof \CriteriaElement) {
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
     * @param \CriteriaElement $criteria {@link \CriteriaElement}
     *
     * @param bool             $force
     * @param bool             $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_respostas');
        if (null !== $criteria && $criteria instanceof \CriteriaElement) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }
}
