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

// -------------------------------------------------------------------------
// ------------------Result user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_resultadoshandler class.
 * This class provides simple mecanisme for Result object
 */
class ResultHandler extends \XoopsPersistableObjectHandler
{
    /**
     * create a new Assessment\Result
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object Result
     */
    public function &create($isNew = true)
    {
        $result = new Assessment\Result();
        if ($isNew) {
            $result->setNew();
        } else {
            $result->unsetNew();
        }

        return $result;
    }

    /**
     * retrieve a Result
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return bool|\XoopsObject {@link \XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_resultados') . ' WHERE cod_resultado=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $result = new Assessment\Result();
            $result->assignVars($this->db->fetchArray($result));

            return $result;
        }

        return false;
    }

    /**
     * insert a new Assessment\Result in the database
     *
     * @param \XoopsObject $result reference to the {@link Result} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $result, $force = false)
    {
        global $xoopsConfig;
        if (!$result instanceof \XoopsModules\Assessment\Result) {
            return false;
        }
        if (!$result->isDirty()) {
            return true;
        }
        if (!$result->cleanVars()) {
            return false;
        }
        foreach ($result->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        //$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
        $now = 'now()';
        if ($result->isNew()) {
            // ajout/modification d'un Result
            $result = new Assessment\Result();
            $format                = 'INSERT INTO `%s` (cod_resultado, cod_prova, uid_aluno, data_inicio, data_fim, resp_certas, resp_erradas, nota_final, nivel, obs, fechada, terminou)';
            $format                .= 'VALUES (%u, %u, %u, %s, %s, %s, %s, %u, %s, %s, %u, %u)';
            $sql                   = sprintf($format, $this->db->prefix('assessment_resultados'), $cod_resultado, $cod_prova, $uid_aluno, $this->db->quoteString($data_inicio), $now, $now, $this->db->quoteString($resp_certas), $this->db->quoteString($resp_erradas), $nota_final, $this->db->quoteString($nivel), $this->db->quoteString($obs), $fechada, $terminou);
            $force                 = true;
        } else {
            $format = 'UPDATE `%s` SET ';
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
        $result->assignVar('cod_resultado', $cod_resultado);

        return true;
    }

    /**
     * delete a Result from the database
     *
     * @param \XoopsObject $result reference to the Result to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $result, $force = false)
    {
        if (!$result instanceof \XoopsModules\Assessment\Result) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_resultado = %u', $this->db->prefix('assessment_resultados'), $result->getVar('cod_resultado'));
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
     * @param \CriteriaElement $criteria {@link \CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://$result'>$result</a> objects
     *                                   objects
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_resultados');
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
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
            $result = new Assessment\Result();
            $result->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = $result;
            } else {
                $ret[$myrow['cod_resultado']] = $result;
            }
            unset($result);
        }

        return $ret;
    }

    /**
     * count assessment_resultadoss matching a condition
     *
     * @param \CriteriaElement $criteria {@link \CriteriaElement} to match
     *
     * @return int count of Questions
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_resultados');
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
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
     * @param \CriteriaElement $criteria {@link \CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_resultados');
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
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
* @param object $question {@link Question}
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
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
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
