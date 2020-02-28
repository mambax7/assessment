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
// Author: Marcello Brandao                                            //
// ----------------------------------------------------------------- //

use XoopsModules\Assessment;

//require_once XOOPS_ROOT_PATH . '/kernel/object.php';
//require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
//require("../class/assessment_respostas.php");

// -------------------------------------------------------------------------
// ------------------assessment_perguntas user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_perguntashandler class.
 * This class provides simple mechanism for Question object
 */
class QuestionHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @var Helper
     */
    public $helper;
    public $userIsAdmin;

    /**
     * @param \XoopsDatabase                       $db
     * @param null|\XoopsModules\Assessment\Helper $helper
     */
    public function __construct(\XoopsDatabase $db = null, \XoopsModules\Assessment\Helper $helper = null)
    {
        /** @var \XoopsModules\Assessment\Helper $this->helper */
        if (null === $helper) {
            $this->helper = \XoopsModules\Assessment\Helper::getInstance();
        } else {
            $this->helper = $helper;
        }
        $userIsAdmin = $this->helper->isUserAdmin();
        parent::__construct($db, 'assessment_perguntas', Question::class, 'cod_pergunta', 'cod_pergunta');
    }

    /**
     * create a new Assessment\Question
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object Question
     */
    public function create($isNew = true)
    {
        $question = new Assessment\Question();
        if ($isNew) {
            $question->setNew();
        } //hack consertando
        else {
            $question->unsetNew();
        }

        //fim do hack para consertar
        return $question;
    }

    /**
     * retrieve a assessment_perguntas
     *
     * @param mixed $id     ID
     * @param array $fields fields to fetch
     * @return bool|\XoopsModules\Assessment\Question <a href='psi_element://XoopsObject'>XoopsObject</a>
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_perguntas') . ' WHERE cod_pergunta=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $question = new Assessment\Question();
            $question->assignVars($this->db->fetchArray($result));

            return $question;
        }

        return false;
    }

    /**
     * insert a new Assessment\Question in the database
     *
     * @param \XoopsObject $question reference to the {@link assessment_perguntas} object
     * @param bool         $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $question, $force = false)
    {
        global $xoopsConfig;
        if (!$question instanceof \XoopsModules\Assessment\Question) {
            return false;
        }
        if (!$question->isDirty()) {
            return true;
        }
        if (!$question->cleanVars()) {
            return false;
        }
        foreach ($question->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($question->isNew()) {
            // ajout/modification d'un assessment_perguntas
            $question = new Assessment\Question();
            $format   = 'INSERT INTO `%s` (cod_pergunta, cod_prova, titulo, data_criacao, data_update, uid_elaborador,ordem)';
            $format   .= 'VALUES (%u, %u, %s, %s, %s, %s, %u)';
            $sql      = sprintf($format, $this->db->prefix('assessment_perguntas'), $cod_pergunta, $cod_prova, $this->db->quoteString($titulo), $now, $now, $this->db->quoteString($uid_elaborador), $ordem);
            $force    = true;
        } else {
            $format = 'UPDATE `%s` SET ';
            $format .= 'cod_pergunta=%u, cod_prova=%u, titulo=%s, data_criacao=%s, data_update=%s, uid_elaborador=%s, ordem=%u';
            $format .= ' WHERE cod_pergunta = %u';
            $sql    = sprintf($format, $this->db->prefix('assessment_perguntas'), $cod_pergunta, $cod_prova, $this->db->quoteString($titulo), $now, $now, $this->db->quoteString($uid_elaborador), $ordem, $cod_pergunta);
        }
        if ($force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_pergunta)) {
            $cod_pergunta = $this->db->getInsertId();
        }
        $question->assignVar('cod_pergunta', $cod_pergunta);

        return true;
    }

    /**
     * delete a assessment_perguntas from the database
     *
     * @param \XoopsObject $question reference to the assessment_perguntas to delete
     * @param bool         $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $question, $force = false)
    {
        if (!$question instanceof \XoopsModules\Assessment\Question) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_pergunta = %u', $this->db->prefix('assessment_perguntas'), $question->getVar('cod_pergunta'));
        if ($force) {
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
     * retrieve assessment_perguntas from the database
     *
     * @param \CriteriaElement|\Criteria $criteria            {@link \CriteriaElement} conditions to be met
     * @param bool                       $id_as_key           use the UID as key for the array?
     *
     * @param bool                       $as_object
     * @return array array of <a href='psi_element://Question'>Question</a> objects
     *                                                        objects
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_perguntas');
        if ($criteria instanceof \Criteria) {
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
            $question = new Assessment\Question();
            $question->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $question;
            } else {
                $ret[$myrow['cod_pergunta']] = $question;
            }
            unset($question);
        }

        return $ret;
    }

    /**
     * retrieve Question from the database
     *
     * @param \Criteria $criteria  {@link \Criteria} conditions to be met
     * @param bool      $id_as_key use the UID as key for the array?
     *
     * @return array array of {@link Question} objects
     */
    public function &getCodObjects(\Criteria $criteria = null, $id_as_key = false)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT cod_pergunta FROM ' . $this->db->prefix('assessment_perguntas');
        if ($criteria !== null) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $ret[] = $myrow['cod_pergunta'];
        }

        return $ret;
    }

    /**
     * count assessment_perguntass matching a condition
     *
     * @param \CriteriaElement|\CriteriaCompo $criteria {@link \CriteriaElement} to match
     *
     * @return int count of Questions
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_perguntas');
        if ($criteria instanceof \CriteriaElement) {
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
     * @param array  $criteria
     * @param bool   $asobject
     * @param string $sort
     * @param string $order
     * @param int    $limit
     * @param int    $start
     *
     * @return array
     */
    public function getAll2($criteria = [], $asobject = false, $sort = 'cod_pergunta', $order = 'ASC', $limit = 0, $start = 0)
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
            $sql    = 'SELECT cod_pergunta FROM ' . $this->db->prefix('assessment_perguntas') . "$where_query ORDER BY $sort $order";
            $result = $this->db->query($sql, $limit, $start);
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[] = $myrow['assessment_perguntas_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $this->db->prefix('assessment_perguntas') . "$where_query ORDER BY $sort $order";
            $result = $this->db->query($sql, $limit, $start);
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[] = new Assessment\Question($myrow);
            }
        }

        return $ret;
    }

    /**
     * delete Questions matching a set of conditions
     *
     * @param \CriteriaElement|\Criteria $criteria {@link \CriteriaElement}
     *
     * @param bool                       $force
     * @param bool                       $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_perguntas');
        if ($criteria instanceof \Criteria) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * create form of insertion and question edit
     *
     * @param string               $action caminho para arquivo que ...
     * @param Assessment\Exam|null $exam
     * @return bool FALSE if failed
     * @internal param object $assessment_prova <a href='psi_element://assessment_pprova'>assessment_pprova</a>
     */
    public function renderFormCadastrar($action, $exam = null)
    {
        $form              = new \XoopsThemeForm(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_PERGUNTA, 'form_pergunta', $action, 'post', true);
        $campo_titulo      = new \XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', '', 2, 50);
        $campo_ordem       = new \XoopsFormText(_AM_ASSESSMENT_ORDEM, 'campo_ordem', 3, 3, '0');
        $cod_prova         = $exam->getVar('cod_prova');
        $titulo_prova      = $exam->getVar('titulo');
        $campo_prova_label = new \XoopsFormLabel(_AM_ASSESSMENT_PROVA, $titulo_prova);
        $campo_prova_valor = new \XoopsFormHidden('campo_cod_prova', $cod_prova);
        $campo_resposta1   = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . ' 1 <br>(' . _AM_ASSESSMENT_RESPCERTAS . ')', 'campo_resposta1', '', 2, 50);
        $campo_resposta1->setExtra('style="background-color:#ECFFEC"');
        $campo_resposta2 = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . ' 2 - (' . _AM_ASSESSMENT_RESPERR . ')', 'campo_resposta2', '', 2, 50);
        $campo_resposta2->setExtra('style="background-color:#FFF0F0"');
        $campo_resposta3 = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . ' 3 - (' . _AM_ASSESSMENT_RESPERR . ')', 'campo_resposta3', '', 2, 50);
        $campo_resposta3->setExtra('style="background-color:#FFF0F0"');
        $campo_resposta4 = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . ' 4 - (' . _AM_ASSESSMENT_RESPERR . ')', 'campo_resposta4', '', 2, 50);
        $campo_resposta4->setExtra('style="background-color:#FFF0F0"');
        $campo_resposta5 = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . ' 5 - (' . _AM_ASSESSMENT_RESPERR . ')', 'campo_resposta5', '', 2, 50);
        $campo_resposta5->setExtra('style="background-color:#FFF0F0"');
        $botao_enviar = new \XoopsFormButton(_AM_ASSESSMENT_CADASTRAR, 'botao_submit', _SUBMIT, 'submit');
        $form->addElement($campo_prova_label);
        $form->addElement($campo_prova_valor);
        $form->addElement($campo_ordem, true);
        $form->addElement($campo_titulo, true);
        $form->addElement($campo_resposta1, true);
        $form->addElement($campo_resposta2, true);
        $form->addElement($campo_resposta3, true);
        $form->addElement($campo_resposta4, true);
        $form->addElement($campo_resposta5, true);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * @param       $action
     * @param       $pergunta
     * @param array $respostas
     *
     * @return bool
     */
    public function renderFormEditar($action, $pergunta, $respostas = [])
    {
        $cod_prova    = $pergunta->getVar('cod_prova');
        $titulo       = $pergunta->getVar('titulo');
        $cod_pergunta = $pergunta->getVar('cod_pergunta');
        $ordem        = $pergunta->getVar('ordem');

        $form = new \XoopsThemeForm(_AM_ASSESSMENT_EDITAR . ' ' . _AM_ASSESSMENT_PERGUNTA, 'form_pergunta', $action, 'post', true);

        $campo_ordem = new \XoopsFormText(_AM_ASSESSMENT_ORDEM, 'campo_ordem', 3, 3, $ordem);
        $form->addElement($campo_ordem, true);
        $campo_titulo = new \XoopsFormTextArea(_AM_ASSESSMENT_PERGUNTA, 'campo_titulo', $titulo, 2, 50);
        $form->addElement($campo_titulo, true);
        $botao_enviar       = new \XoopsFormButton('', 'botao_submit', _AM_ASSESSMENT_SALVARALTERACOES, 'submit');
        $campo_cod_pergunta = new \XoopsFormHidden('campo_cod_pergunta', $cod_pergunta);
        $campo_prova_valor  = new \XoopsFormHidden('campo_cod_prova', $cod_prova);

        $i = 1;
        foreach ($respostas as $resposta) {
            $titulo_resposta            = $resposta->getVar('titulo');
            $cod_resposta               = $resposta->getVar('cod_resposta');
            $nome_campo_titulo_resposta = 'campo_resposta' . $i;
            if (1 == $resposta->getVar('iscerta')) {
                $resposta_correta = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPCORRETA . $i, $nome_campo_titulo_resposta, $titulo_resposta, 2, 50);
                $resposta_correta->setExtra('style="background-color:#ECFFEC"');
                $cod_resposta_correta = new \XoopsFormHidden('campo_cod_resp1', $cod_resposta);
                $form->addElement($cod_resposta_correta, true);
                $form->addElement($resposta_correta, true);
            } else {
                $vetor_respostas_erradas[$i] = new \XoopsFormTextArea(_AM_ASSESSMENT_RESPOSTA . $i, $nome_campo_titulo_resposta, $titulo_resposta, 2, 50);
                $vetor_respostas_erradas[$i]->setExtra('style="background-color:#FFF0F0"');
                $vetor_cod_respostas_erradas[$i] = new \XoopsFormHidden('campo_cod_resp' . $i, $cod_resposta);
                $form->addElement($vetor_respostas_erradas[$i], true);
                $form->addElement($vetor_cod_respostas_erradas[$i], true);
            }
            ++$i;
        }

        $form->addElement($campo_prova_valor, true);
        $form->addElement($campo_cod_pergunta, true);

        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * @param       $action
     * @param       $pergunta
     * @param array $respostas
     * @param int   $param_cod_resposta
     *
     * @return \XoopsThemeForm
     */
    public function renderFormResponder($action, $pergunta, $respostas = [], $param_cod_resposta = 0)
    {
        global $_GET;
        $start        = \Xmf\Request::getInt('start', 0, 'GET');
        $cod_prova    = $pergunta->getVar('cod_prova');
        $titulo       = $pergunta->getVar('titulo');
        $cod_pergunta = $pergunta->getVar('cod_pergunta');
        $form         = new \XoopsThemeForm((string)$titulo, 'form_resposta', $action, 'post', true);

        $botao_enviar       = new \XoopsFormButton('', 'botao_submit', _SUBMIT, 'submit');
        $campo_cod_pergunta = new \XoopsFormHidden('cod_pergunta', $cod_pergunta);
        $campo_start        = new \XoopsFormHidden('start', $start);
        $campo_respostas    = new \XoopsFormRadio(_MA_ASSESSMENT_RESPOSTA, 'cod_resposta', '', '<br>');
        shuffle($respostas);

        $campo_respostas->setValue($param_cod_resposta);
        foreach ($respostas as $resposta) {
            $titulo_resposta = $resposta->getVar('titulo');
            $cod_resposta    = $resposta->getVar('cod_resposta');
            $campo_respostas->addOption($cod_resposta, $titulo_resposta . '<br>');
        }
        //$form->addElement($campo_prova_valor,true);
        $form->addElement($campo_cod_pergunta);
        $form->addElement($campo_respostas, true);
        $form->addElement($campo_start);

        $form->addElement($botao_enviar);

        //$form->display();
        return $form;
    }

    /**
     * @param \XoopsMySQLDatabase|null $db
     *
     * @return mixed
     */
    public function pegarultimocodigo(\XoopsMySQLDatabase $db = null)
    {
        if (null !== $db) {
            return $db->getInsertId();
        }
    }

    /**
     * Copy the questions and save them! Clone test
     *
     * @param \Criteria $criteria {@link \Criteria} to match
     *
     * @param           $cod_prova
     */
    public function clonarPerguntas(\Criteria $criteria, $cod_prova)
    {
        global $xoopsDB;
        $answerFactory = new Assessment\AnswerHandler($xoopsDB);
        $perguntas     = $this->getObjects($criteria);
        foreach ($perguntas as $pergunta) {
            $cod_pergunta = $pergunta->getVar('cod_pergunta');
            $pergunta->setVar('cod_prova', $cod_prova);
            $pergunta->setVar('cod_pergunta', 0);
            $pergunta->setNew();
            $this->insert($pergunta);
            $cod_pergunta_clone = $xoopsDB->getInsertId();

            $criteria_pergunta = new \Criteria('cod_pergunta', $cod_pergunta);
            $respostas         = $answerFactory->getObjects($criteria_pergunta);

            foreach ($respostas as $resposta) {
                $resposta->setVar('cod_pergunta', $cod_pergunta_clone);
                $resposta->setVar('cod_resposta', 0);
                $resposta->setNew();
                $answerFactory->insert($resposta);
            }
        }
    }
}
