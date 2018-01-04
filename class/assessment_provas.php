<?php
// assessment_provas.php,v 1
// $Id: assessment_provas.php,v 1.11 2007/03/24 20:08:53 marcellobrandao Exp $
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
 * assessment_provas class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class assessment_provas extends XoopsObject
{
    public $db;

    // constructor

    /**
     * @param null $id
     * @return assessment_provas
     */
    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
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
        $db          = XoopsDatabaseFactory::getDatabaseConnection();
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
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = $myrow['assessment_provas_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_provas') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = new assessment_provas($myrow);
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
        $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);
        if ($fabrica_de_provas->dataMysql2dataUnix($inicio) > time()) {
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
        $fabrica_de_provas = new Xoopsassessment_provasHandler($xoopsDB);
        if ($fabrica_de_provas->dataMysql2dataUnix($inicio) > time()) {
            return false;
        }

        return true;
    }
}

// -------------------------------------------------------------------------
// ------------------assessment_provas user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_provashandler class.
 * This class provides simple mecanisme for assessment_provas object
 */
class Xoopsassessment_provasHandler extends XoopsPersistableObjectHandler
{
    /**
     * create a new assessment_provas
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object assessment_provas
     */
    public function &create($isNew = true)
    {
        $assessment_provas = new assessment_provas();
        if ($isNew) {
            $assessment_provas->setNew();
        } //hack consertando
        else {
            $assessment_provas->unsetNew();
        }

        //fim do hack para consertar
        return $assessment_provas;
    }

    /**
     * retrieve a assessment_provas
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return XoopsObject {@link XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_provas') . ' WHERE cod_prova=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $assessment_provas = new assessment_provas();
            $assessment_provas->assignVars($this->db->fetchArray($result));

            return $assessment_provas;
        }

        return false;
    }

    /**
     * insert a new assessment_provas in the database
     *
     * @param XoopsObject $assessment_provas reference to the {@link assessment_provas} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $assessment_provas, $force = false)
    {
        global $xoopsConfig;
        if ('assessment_provas' != get_class($assessment_provas)) {
            return false;
        }
        if (!$assessment_provas->isDirty()) {
            return true;
        }
        if (!$assessment_provas->cleanVars()) {
            return false;
        }
        foreach ($assessment_provas->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($assessment_provas->isNew()) {
            // ajout/modification d'un assessment_provas
            $assessment_provas = new assessment_provas();
            $format            = 'INSERT INTO %s (cod_prova, data_criacao, data_update, titulo, descricao, instrucoes, acesso, tempo, uid_elaboradores, data_inicio, data_fim)';
            $format            .= 'VALUES (%u, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)';
            $sql               = sprintf(
                $format,
                $this->db->prefix('assessment_provas'),
                $cod_prova,
                $this->db->quoteString($data_criacao),
                $this->db->quoteString($data_update),
                $this->db->quoteString($titulo),
                $this->db->quoteString($descricao),
                $this->db->quoteString($instrucoes),
                                         $this->db->quoteString($acesso),
                $this->db->quoteString($tempo),
                $this->db->quoteString($uid_elaboradores),
                $this->db->quoteString($data_inicio),
                $this->db->quoteString($data_fim)
            );
            $force             = true;
        } else {
            $format = 'UPDATE %s SET ';
            $format .= 'cod_prova=%u, data_criacao=%s, data_update=%s, titulo=%s, descricao=%s, instrucoes=%s, acesso=%s, tempo=%s, uid_elaboradores=%s, data_inicio=%s, data_fim=%s';
            $format .= ' WHERE cod_prova = %u';
            $sql    = sprintf(
                $format,
                $this->db->prefix('assessment_provas'),
                $cod_prova,
                $this->db->quoteString($data_criacao),
                $this->db->quoteString($data_update),
                $this->db->quoteString($titulo),
                $this->db->quoteString($descricao),
                $this->db->quoteString($instrucoes),
                              $this->db->quoteString($acesso),
                $this->db->quoteString($tempo),
                $this->db->quoteString($uid_elaboradores),
                $this->db->quoteString($data_inicio),
                $this->db->quoteString($data_fim),
                $cod_prova
            );
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_prova)) {
            $cod_prova = $this->db->getInsertId();
        }
        $assessment_provas->assignVar('cod_prova', $cod_prova);

        return true;
    }

    /**
     * delete a assessment_provas from the database
     *
     * @param XoopsObject $assessment_provas reference to the assessment_provas to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $assessment_provas, $force = false)
    {
        if ('assessment_provas' != get_class($assessment_provas)) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE cod_prova = %u', $this->db->prefix('assessment_provas'), $assessment_provas->getVar('cod_prova'));
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
     * @param $cod_prova
     */
    public function clonarProva($cod_prova)
    {
        $prova = $this->get($cod_prova);

        $prova->setVar('titulo', _AM_ASSESSMENT_CLONE . $prova->getVar('titulo'));
        $prova->setVar('cod_prova', 0);
        $prova->setNew();
        $this->insert($prova);
    }

    /**
     * retrieve assessment_provass from the database
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://assessment_perguntas'>assessment_perguntas</a> objects
     *                                   objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_provas');
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
        while ($myrow = $this->db->fetchArray($result)) {
            $assessment_provas = new assessment_provas();
            $assessment_provas->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $assessment_provas;
            } else {
                $ret[$myrow['cod_prova']] = $assessment_provas;
            }
            unset($assessment_provas);
        }

        return $ret;
    }

    /**
     * count assessment_provass matching a condition
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of assessment_provass
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_provas');
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
     * delete assessment_provass matching a set of conditions
     *
     * @param CriteriaElement $criteria {@link CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_provas');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * cria form de inser��o e edi��o de pergunta
     *
     * @param string $action caminho para arquivo que ...
     * @return bool FALSE if deletion failed
     * @internal param object $assessment_perguntas <a href='psi_element://assessment_perguntas'>assessment_perguntas</a>
     *
     */
    public function renderFormCadastrar($action)
    {
        $form             = new XoopsThemeForm(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_PROVA, 'form_prova', $action, 'post', true);
        $campo_titulo     = new XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', '', 2, 50);
        $campo_descricao  = new XoopsFormTextArea(_AM_ASSESSMENT_DESCRICAO, 'campo_descricao', '', 2, 50);
        $campo_instrucoes = new XoopsFormTextArea(_AM_ASSESSMENT_INSTRUCOES, 'campo_instrucoes', '', 2, 50);
        $campo_tempo      = new XoopsFormText(_AM_ASSESSMENT_TEMPO, 'campo_tempo', 10, 20);

        $campo_acesso      = new XoopsFormSelectGroup(_AM_ASSESSMENT_GRUPOSACESSO, 'campo_grupo', false, null, 4, true);
        $botao_enviar      = new XoopsFormButton(_AM_ASSESSMENT_CADASTRAR, 'botao_submit', _SUBMIT, 'submit');
        $campo_data_inicio = new XoopsFormDateTime(_AM_ASSESSMENT_DATA_INICIO, 'campo_data_inicio');
        $campo_data_fim    = new XoopsFormDateTime(_AM_ASSESSMENT_DATA_FIM, 'campo_data_fim');
        $form->addElement($campo_titulo, true);
        $form->addElement($campo_descricao, true);
        $form->addElement($campo_instrucoes, true);
        $form->addElement($campo_tempo, true);

        $form->addElement($campo_data_inicio, true);
        $form->addElement($campo_data_fim, true);
        $form->addElement($campo_acesso, true);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * cria form de inser��o e edi��o de pergunta
     *
     * @param string $action caminho para arquivo que ...
     * @param        $prova
     * @return bool FALSE if deletion failed
     * @internal param object $assessment_perguntas <a href='psi_element://assessment_perguntas'>assessment_perguntas</a>
     *
     */
    public function renderFormEditar($action, $prova)
    {
        $cod_prova  = $prova->getVar('cod_prova');
        $titulo     = $prova->getVar('titulo');
        $descricao  = $prova->getVar('descricao');
        $instrucoes = $prova->getVar('instrucoes');
        $acessos    = explode(',', $prova->getVar('acesso'));
        $tempo      = $prova->getVar('tempo');
        $inicio     = $this->dataMysql2dataUnix($prova->getVar('data_inicio'));
        $fim        = $this->dataMysql2dataUnix($prova->getVar('data_fim'));

        $form              = new XoopsThemeForm(_AM_ASSESSMENT_EDITAR . ' ' . _AM_ASSESSMENT_PROVA, 'form_prova', $action, 'post', true);
        $campo_titulo      = new XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', $titulo, 2, 50);
        $campo_descricao   = new XoopsFormTextArea(_AM_ASSESSMENT_DESCRICAO, 'campo_descricao', $descricao, 2, 50);
        $campo_instrucoes  = new XoopsFormTextArea(_AM_ASSESSMENT_INSTRUCOES, 'campo_instrucoes', $instrucoes, 2, 50);
        $campo_tempo       = new XoopsFormText(_AM_ASSESSMENT_TEMPO, 'campo_tempo', 10, 20, $tempo);
        $campo_acesso      = new XoopsFormSelectGroup(_AM_ASSESSMENT_GRUPOSACESSO, 'campo_grupo', false, $acessos, 4, true);
        $campo_cod_prova   = new XoopsFormHidden('campo_cod_prova', $cod_prova);
        $campo_data_inicio = new XoopsFormDateTime(_AM_ASSESSMENT_DATA_INICIO, 'campo_data_inicio', null, $inicio);
        $campo_data_fim    = new XoopsFormDateTime(_AM_ASSESSMENT_DATA_FIM, 'campo_data_fim', null, $fim);
        $botao_enviar      = new XoopsFormButton('', 'botao_submit', _AM_ASSESSMENT_SALVARALTERACOES, 'submit');
        $form->addElement($campo_titulo, true);
        $form->addElement($campo_descricao, true);
        $form->addElement($campo_instrucoes, true);
        $form->addElement($campo_cod_prova, true);

        $form->addElement($campo_tempo, true);
        $form->addElement($campo_data_inicio, true);
        $form->addElement($campo_data_fim, true);
        $form->addElement($campo_acesso, true);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * @param $db
     *
     * @return mixed
     */
    public function pegarultimocodigo(&$db)
    {
        return $db->getInsertId();
    }

    /**
     * @param $dataMYSQL
     *
     * @return int
     */
    public function dataMysql2dataUnix($dataMYSQL)
    {
        $d  = @explode(' ', $dataMYSQL, 2);
        $t  = @explode(':', $d[1], 3);
        $d  = @explode('-', $d[0], 3);
        $ts = @mktime($t[0], $t[1], $t[2], $d[1], $d[2], $d[0]);

        return $ts;
    }

    /**
     * @param        $total_segundos
     * @param string $inicio
     *
     * @return mixed
     */
    public function converte_segundos($total_segundos, $inicio = 'Y')
    {
        /**
         * @autor: Carlos H. Reche
         * @data : 11/08/2004
         */

        $comecou = false;

        if ('Y' == $inicio) {
            $array['anos']  = floor($total_segundos / (60 * 60 * 24 * _AM_ASSESSMENT_DAYS_PER_MONTH * 12));
            $total_segundos = ($total_segundos % (60 * 60 * 24 * _AM_ASSESSMENT_DAYS_PER_MONTH * 12));
            $comecou        = true;
        }
        if (('m' == $inicio) || (true === $comecou)) {
            $array['meses'] = floor($total_segundos / (60 * 60 * 24 * _AM_ASSESSMENT_DAYS_PER_MONTH));
            $total_segundos = ($total_segundos % (60 * 60 * 24 * _AM_ASSESSMENT_DAYS_PER_MONTH));
            $comecou        = true;
        }
        if (('d' == $inicio) || (true === $comecou)) {
            $array['dias']  = floor($total_segundos / (60 * 60 * 24));
            $total_segundos = ($total_segundos % (60 * 60 * 24));
            $comecou        = true;
        }
        if (('H' == $inicio) || (true === $comecou)) {
            $array['horas'] = floor($total_segundos / (60 * 60));
            $total_segundos = ($total_segundos % (60 * 60));
            $comecou        = true;
        }
        if (('i' == $inicio) || (true === $comecou)) {
            $array['minutos'] = floor($total_segundos / 60);
            $total_segundos   = ($total_segundos % 60);
            $comecou          = true;
        }
        $array['segundos'] = $total_segundos;

        return $array;
    }
}
