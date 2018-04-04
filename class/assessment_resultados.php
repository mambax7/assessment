<?php
// $Id: assessment_resultados.php,v 1.7 2007/03/24 14:41:41 marcellobrandao Exp $
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
 * assessment_resultados class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class assessment_resultados extends XoopsObject
{
    public $db;

    // constructor

    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->db = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_resultado', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cod_prova', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('uid_aluno', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('data_inicio', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('data_fim', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('resp_certas', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('resp_erradas', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('nota_final', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('nivel', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('obs', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('fechada', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('terminou', XOBJ_DTYPE_INT, null, false, 10);
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_resultados') . ' WHERE cod_resultado=' . $id;
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
    public function getAllassessment_resultadoss($criteria = [], $asobject = false, $sort = 'cod_resultado', $order = 'ASC', $limit = 0, $start = 0)
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
            $sql    = 'SELECT cod_resultado FROM ' . $db->prefix('assessment_resultados') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = $myrow['assessment_resultados_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_resultados') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while (false !== ($myrow = $db->fetchArray($result))) {
                $ret[] = new assessment_resultados($myrow);
            }
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getRespostasCertasAsArray()
    {
        $respostas     = [];
        $respostas     = explode(',', $this->getVar('resp_certas'));
        $par_respostas = [];
        foreach ($respostas as $resposta) {
            $x                    = explode('-', $resposta);
            $par_respostas[$x[0]] = @$x[1];
        }

        return $par_respostas;
    }

    /**
     * @return array
     */
    public function getRespostasErradasAsArray()
    {
        $respostas     = explode(',', $this->getVar('resp_erradas'));
        $par_respostas = [];
        foreach ($respostas as $resposta) {
            $x = explode('-', $resposta);
            if (isset($x[1])) {
                $par_respostas[$x[0]] = $x[1];
            }
        }

        return $par_respostas;
    }

    /**
     * @return array
     */
    public function getRespostasAsArray()
    {
        $erradas = [];
        $certas  = [];
        $erradas = $this->getRespostasErradasAsArray();
        $certas  = $this->getRespostasCertasAsArray();

        $todas = $erradas + $certas;

        return $todas;
    }

    /**
     * @return array
     */
    public function getCodPerguntasAsArray()
    {
        $erradas = [];
        $certas  = [];
        $erradas = $this->getRespostasErradasAsArray();
        $certas  = $this->getRespostasCertasAsArray();

        $todas = $erradas + $certas;

        return array_keys($todas);
    }

    /**
     * @param $respostasCertas
     */
    public function setRespostasCertasAsArray($respostasCertas)
    {
        $x = [];
        foreach ($respostasCertas as $chave => $valor) {
            if (!(null == $chave)) {
                $x[] = $chave . '-' . $valor;
            }
        }

        $y = implode(',', $x);
        $this->setVar('resp_certas', $y);
    }

    /**
     * @param $respostasErradas
     */
    public function setRespostasErradasAsArray($respostasErradas)
    {
        $x = [];
        foreach ($respostasErradas as $chave => $valor) {
            if (!(null == $chave)) {
                $x[] = $chave . '-' . $valor;
            }
        }

        $y = implode(',', $x);
        $this->setVar('resp_erradas', $y);
    }

    /**
     * @param $cod_pergunta
     *
     * @return mixed
     */
    public function getRespostaUsuario($cod_pergunta)
    {
        $respostas = $this->getRespostasAsArray();

        return $respostas[$cod_pergunta];
    }

    /**
     * @return int
     */
    public function contarRespostas()
    {
        $respostas = $this->getRespostasAsArray();
        unset($respostas[null]);

        $qtd = count($respostas);

        return $qtd;
    }
}

// -------------------------------------------------------------------------
// ------------------assessment_resultados user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_resultadoshandler class.
 * This class provides simple mecanisme for assessment_resultados object
 */
class Xoopsassessment_resultadosHandler extends XoopsPersistableObjectHandler
{
    /**
     * create a new assessment_resultados
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object assessment_resultados
     */
    public function &create($isNew = true)
    {
        $assessment_resultados = new assessment_resultados();
        if ($isNew) {
            $assessment_resultados->setNew();
        } else {
            $assessment_resultados->unsetNew();
        }

        return $assessment_resultados;
    }

    /**
     * retrieve a assessment_resultados
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return XoopsObject {@link XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_resultados') . ' WHERE cod_resultado=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $assessment_resultados = new assessment_resultados();
            $assessment_resultados->assignVars($this->db->fetchArray($result));

            return $assessment_resultados;
        }

        return false;
    }

    /**
     * insert a new assessment_resultados in the database
     *
     * @param XoopsObject $assessment_resultados reference to the {@link assessment_resultados} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $assessment_resultados, $force = false)
    {
        global $xoopsConfig;
        if (!$assessment_resultados instanceof \assessment_resultados) {
            return false;
        }
        if (!$assessment_resultados->isDirty()) {
            return true;
        }
        if (!$assessment_resultados->cleanVars()) {
            return false;
        }
        foreach ($assessment_resultados->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        //$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
        $now = 'now()';
        if ($assessment_resultados->isNew()) {
            // ajout/modification d'un assessment_resultados
            $assessment_resultados = new assessment_resultados();
            $format                = 'INSERT INTO %s (cod_resultado, cod_prova, uid_aluno, data_inicio, data_fim, resp_certas, resp_erradas, nota_final, nivel, obs, fechada, terminou)';
            $format                .= 'VALUES (%u, %u, %u, %s, %s, %s, %s, %u, %s, %s, %u, %u)';
            $sql                   = sprintf($format, $this->db->prefix('assessment_resultados'), $cod_resultado, $cod_prova, $uid_aluno, $this->db->quoteString($data_inicio), $now, $now, $this->db->quoteString($resp_certas), $this->db->quoteString($resp_erradas), $nota_final, $this->db->quoteString($nivel), $this->db->quoteString($obs), $fechada, $terminou);
            $force                 = true;
        } else {
            $format = 'UPDATE %s SET ';
            $format .= 'cod_resultado=%u, cod_prova=%u, uid_aluno=%u, data_inicio=%s, data_fim=%s, resp_certas=%s, resp_erradas=%s, nota_final=%u, nivel=%s, obs=%s, fechada=%u, terminou=%u';
            $format .= ' WHERE cod_resultado = %u';
            $sql    = sprintf(
                $format,
                $this->db->prefix('assessment_resultados'),
                $cod_resultado,
                $cod_prova,
                $uid_aluno,
                $this->db->quoteString($data_inicio),
                $now,
                $this->db->quoteString($resp_certas),
                $this->db->quoteString($resp_erradas),
                $nota_final,
                $this->db->quoteString($nivel),
                              $this->db->quoteString($obs),
                $fechada,
                $terminou,
                $cod_resultado
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
        if (empty($cod_resultado)) {
            $cod_resultado = $this->db->getInsertId();
        }
        $assessment_resultados->assignVar('cod_resultado', $cod_resultado);

        return true;
    }

    /**
     * delete a assessment_resultados from the database
     *
     * @param XoopsObject $assessment_resultados reference to the assessment_resultados to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $assessment_resultados, $force = false)
    {
        if (!$assessment_resultados instanceof \assessment_resultados) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_resultado = %u', $this->db->prefix('assessment_resultados'), $assessment_resultados->getVar('cod_resultado'));
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
     * retrieve assessment_resultadoss from the database
     *
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://$assessment_resultados'>$assessment_resultados</a> objects
     *                                   objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_resultados');
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
            $assessment_resultados = new assessment_resultados();
            $assessment_resultados->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $assessment_resultados;
            } else {
                $ret[$myrow['cod_resultado']] = $assessment_resultados;
            }
            unset($assessment_resultados);
        }

        return $ret;
    }

    /**
     * count assessment_resultadoss matching a condition
     *
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of assessment_perguntass
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_resultados');
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
     * delete assessment_resultadoss matching a set of conditions
     *
     * @param CriteriaElement $criteria {@link CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_resultados');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /* cria form de edi��o de resultado
*
* @param string $action caminho para arquivo que ...
* @param object $assessment_perguntas {@link assessment_perguntas}
* @return bool FALSE if deletion failed
*/
    /**
     * @param $resultado
     * @param $prova
     * @param $qtd_perguntas
     * @param $action
     *
     * @return bool
     */
    public function renderFormEditar($resultado, $prova, $qtd_perguntas, $action)
    {
        $cod_prova  = $prova->getVar('cod_prova');
        $titulo     = $prova->getVar('titulo');
        $descricao  = $prova->getVar('descricao');
        $instrucoes = $prova->getVar('instrucoes');
        $tempo      = $prova->getVar('tempo');

        $cod_resultado = $resultado->getVar('cod_resultado');
        $data_inicio   = $resultado->getVar('data_inicio');
        $data_fim      = $resultado->getVar('data_fim');
        $resp_certas   = $resultado->getVar('resp_certas');
        $resp_erradas  = $resultado->getVar('resp_erradas');
        $nota_final    = $resultado->getVar('nota_final');
        $nivel         = $resultado->getVar('nivel');
        $observacoes   = $resultado->getVar('obs');
        $qtd_acertos   = substr_count($resp_certas, ',') + 1;
        $qtd_erros     = substr_count($resp_erradas, ',') + 1;

        $texto_resp_certas = _AM_ASSESSMENT_PERGDETALHES . '<br>';
        $vetor_resp_certas = explode(',', $resp_certas);

        foreach ($vetor_resp_certas as $resp) {
            $detalhe_resp_certa = explode('-', $resp);
            $texto_resp_certas  .= '<a href=main.php?op=ver_detalhe_pergunta&cod_pergunta=' . $detalhe_resp_certa[0] . '&cod_resposta=' . $detalhe_resp_certa[1] . '>' . $detalhe_resp_certa[0] . ' </a> ';
        }
        $texto_resp_erradas = _AM_ASSESSMENT_PERGDETALHES . ' <br>';
        $vetor_resp_erradas = explode(',', $resp_erradas);

        foreach ($vetor_resp_erradas as $resp2) {
            $detalhe_resp_errada = explode('-', $resp2);
            $texto_resp_erradas  .= '<a href=main.php?op=ver_detalhe_pergunta&cod_pergunta=' . $detalhe_resp_errada[0] . '&cod_resposta=' . $detalhe_resp_errada[1] . '>' . $detalhe_resp_errada[0] . ' </a> ';
        }

        if ('' == $vetor_resp_certas[0]) {
            $qtd_acertos = 0;
        }
        if ('' == $vetor_resp_erradas[0]) {
            $qtd_erros = 0;
        }
        $qtd_branco  = $qtd_perguntas - $qtd_acertos - $qtd_erros;
        $nota_sugest = round(100 * $qtd_acertos / $qtd_perguntas, 2);

        $form                    = new \XoopsThemeForm(_AM_ASSESSMENT_EDITAR . ' ' . _AM_ASSESSMENT_RESULTADO, 'form_resultado', $action, 'post', true);
        $campo_resp_certas       = new \XoopsFormLabel(_AM_ASSESSMENT_RESPCERTAS, $texto_resp_certas);
        $campo_resp_erradas      = new \XoopsFormLabel(_AM_ASSESSMENT_RESPERR, $texto_resp_erradas);
        $campo_sugest_nota_final = new \XoopsFormLabel(_AM_ASSESSMENT_SUGESTNOTA, $nota_sugest
                                                                                 . '/100 ('
                                                                                 . _AM_ASSESSMENT_ACERTOU
                                                                                 . ' '
                                                                                 . $qtd_acertos
                                                                                 . ' '
                                                                                 . _AM_ASSESSMENT_ERROU
                                                                                 . ' '
                                                                                 . $qtd_erros
                                                                                 . ' '
                                                                                 . _AM_ASSESSMENT_SEMREPONDER
                                                                                 . ' '
                                                                                 . $qtd_branco
                                                                                 . ' '
                                                                                 . _AM_ASSESSMENT_DEUMTOTALDE
                                                                                 . ' '
                                                                                 . $qtd_perguntas
                                                                                 . ' '
                                                                                 . _AM_ASSESSMENT_PERGUNTAS
                                                                                 . ' )');
        $campo_nota_final        = new \XoopsFormText(_AM_ASSESSMENT_NOTAFINAL, 'campo_nota_final', 6, 10, $nota_final);
        $campo_nivel             = new \XoopsFormText(_AM_ASSESSMENT_NIVEL, 'campo_nivel', 10, 20, $nivel);
        $campo_observacoes       = new \XoopsFormTextArea(_AM_ASSESSMENT_OBS, 'campo_observacoes', $observacoes, 2, 50);
        $campo_cod_resultado     = new \XoopsFormHidden('campo_cod_resultado', $cod_resultado);
        $botao_enviar            = new \XoopsFormButton('', 'botao_submit', _AM_ASSESSMENT_SALVARALTERACOES, 'submit');
        $form->addElement($campo_resp_certas, true);
        $form->addElement($campo_resp_erradas, true);
        $form->addElement($campo_sugest_nota_final);
        $form->addElement($campo_nota_final, true);
        $form->addElement($campo_nivel, true);

        $form->addElement($campo_observacoes, true);
        $form->addElement($campo_cod_resultado, true);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * @param $cod_prova
     *
     * @return int
     */
    public function stats($cod_prova)
    {
        $criteria   = new \Criteria('cod_prova', $cod_prova);
        $qtd_provas = $this->getCount($criteria);
        $ret['qtd'] = $qtd_provas;
        $sql        = 'SELECT max(nota_final),min(nota_final),avg(nota_final) FROM ' . $this->db->prefix('assessment_resultados');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }

        while (false !== (list($max, $min, $media) = $this->db->fetchRow($result))) {
            $ret['max']   = $max;
            $ret['min']   = $min;
            $ret['media'] = $media;
        }

        return $ret;
    }
}
