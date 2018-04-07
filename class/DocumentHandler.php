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
// assessment_documentos.php,v 1
//  ---------------------------------------------------------------- //
//                                             //
// ----------------------------------------------------------------- //

use Xmf\Request;
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


// -------------------------------------------------------------------------
// ------------------Document user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_documentoshandler class.
 * This class provides simple mecanisme for Document object
 */
class DocumentHandler extends \XoopsPersistableObjectHandler
{
    /**
     * create a new Assessment\Document
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object Document
     */
    public function create($isNew = true)
    {
        $document = new Assessment\Document();
        if ($isNew) {
            $document->setNew();
        } else {
            $document->unsetNew();
        }

        return $document;
    }

    /**
     * retrieve a Document
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return bool|\XoopsModules\Assessment\Document <a href='psi_element://XoopsObject'>XoopsObject</a>
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos') . ' WHERE cod_documento=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $document = new Assessment\Document();
            $document->assignVars($this->db->fetchArray($result));

            return $document;
        }

        return false;
    }

    /**
     * insert a new Assessment\Document in the database
     *
     * @param \XoopsObject $document reference to the {@link assessment_documentos} object
     * @param  bool       $force                 flag to force the query execution despite security settings
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $document, $force = false)
    {
        global $xoopsConfig;
        if (!$document instanceof \XoopsModules\Assessment\Document) {
            return false;
        }
        if (!$document->isDirty()) {
            return true;
        }
        if (!$document->cleanVars()) {
            return false;
        }
        foreach ($document->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($document->isNew()) {
            // ajout/modification d'un Document
            $document = new Assessment\Document();
            $format                = 'INSERT INTO `%s` (cod_documento, titulo, tipo, cod_prova, cods_perguntas, documento, uid_elaborador, fonte, html)';
            $format                .= 'VALUES (%u, %s, %u, %u, %s, %s, %u, %s, %u)';
            $sql                   = sprintf($format, $this->db->prefix('assessment_documentos'), $cod_documento, $this->db->quoteString($titulo), $tipo, $cod_prova, $this->db->quoteString($cods_perguntas), $this->db->quoteString($documento), $uid_elaborador, $this->db->quoteString($fonte), $html);
            $force                 = true;
        } else {
            $format = 'UPDATE `%s` SET ';
            $format .= 'cod_documento=%u, titulo=%s, tipo=%u, cod_prova=%u, cods_perguntas=%s, documento=%s, uid_elaborador=%u, fonte=%s, html=%u';
            $format .= ' WHERE cod_documento = %u';
            $sql    = sprintf($format, $this->db->prefix('assessment_documentos'), $cod_documento, $this->db->quoteString($titulo), $tipo, $cod_prova, $this->db->quoteString($cods_perguntas), $this->db->quoteString($documento), $uid_elaborador, $this->db->quoteString($fonte), $html, $cod_documento);
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (empty($cod_documento)) {
            $cod_documento = $this->db->getInsertId();
        }
        $document->assignVar('cod_documento', $cod_documento);

        return true;
    }

    /**
     * delete a Document from the database
     *
     * @param \XoopsObject $document reference to the Document to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(\XoopsObject $document, $force = false)
    {
        if (!$document instanceof \XoopsModules\Assessment\Document) {
            return false;
        }
        $sql = sprintf('DELETE FROM `%s` WHERE cod_documento = %u', $this->db->prefix('assessment_documentos'), $document->getVar('cod_documento'));
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
     * retrieve assessment_documentoss from the database
     *
     * @param \CriteriaElement $criteria {@link \CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://$document'>$document</a> objects
     *                                   objects
     */
    public function &getObjects(\CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos');
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
            $document = new Assessment\Document();
            $document->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $document;
            } else {
                $ret[$myrow['cod_documento']] =& $document;
            }
            unset($document);
        }

        return $ret;
    }

    /**
     * retrieve assessment_documentoss from the database
     *
     * @param $cod_prova
     * @param $cod_pergunta
     * @return array array of <a href='psi_element://Document'>Document</a> objects
     * objects
     * @internal param object $criteria <a href='psi_element://CriteriaElement'>CriteriaElement</a> conditions to be met conditions to be met conditions to be met conditions to be met
     * @internal param bool $id_as_key use the UID as key for the array?
     *
     */
    public function getDocumentosProvaPergunta($cod_prova, $cod_pergunta)
    {
        $criteria         = new \Criteria('cod_prova', $cod_prova);
        $cod_documentos   = [];
        $documentos_prova = $this->getObjects($criteria);
        $i                = 0;
        foreach ($documentos_prova as $documento_prova) {
            $cods_perguntas = explode(',', $documento_prova->getVar('cods_perguntas'));
            if (in_array($cod_pergunta, $cods_perguntas)) {
                $documentos[$i]['titulo'] = $documento_prova->getVar('titulo');
                $documentos[$i]['fonte']  = $documento_prova->getVar('fonte');
                /*if ($helper->getConfig('editorpadrao')=="dhtmlext"||$helper->getConfig('editorpadrao')=="textarea") {
                $documentos[$i]['documento']= text_filter($documento_prova->getVar('documento',"s"),true);} else {
                $documentos[$i]['documento']= text_filter($documento_prova->getVar('documento',"n"),true);
                    }*/
                if (1 == $documento_prova->getVar('html')) {
                    //$documentos[$i]['documento']= text_filter($documento_prova->getVar('documento',"n"),true);
                    $documentos[$i]['documento'] = $documento_prova->getVar('documento', 'n');
                } else {
                    $documentos[$i]['documento'] = text_filter($documento_prova->getVar('documento', 's'), true);
                }
                ++$i;
            }
        }

        return $documentos;
    }

    /**
     * count assessment_documentoss matching a condition
     *
     * @param \CriteriaElement $criteria {@link \CriteriaElement} to match
     *
     * @return int count of assessment_documentoss
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_documentos');
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
     * delete assessment_documentoss matching a set of conditions
     *
     * @param \CriteriaElement $criteria {@link \CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_documentos');
        if ($criteria !== null && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /* cria form de inser��o e edi��o de pergunta
    *
    * @param string $action caminho para arquivo que ...
    * @param object $question {@link Question}
    * @return bool FALSE if deletion failed
    */
    /**
     * @param $action
     * @param $cod_prova
     *
     * @return bool
     */
    public function renderFormCadastrar($action, $cod_prova)
    {
        global $xoopsDB, $xoopsUser;

        $questionFactory = new Assessment\QuestionHandler($xoopsDB);
        $criteria             = new \Criteria('cod_prova', $cod_prova);

        $vetor_perguntas = $questionFactory->getObjects($criteria);
        $campo_perguntas = new \XoopsFormSelect(_AM_ASSESSMENT_PERGASSOC, 'campo_perguntas', null, 10, true);

        foreach ($vetor_perguntas as $pergunta) {
            $campo_perguntas->addOption($pergunta->getVar('cod_pergunta'), $pergunta->getVar('titulo'));
        }

        $form           = new \XoopsThemeForm(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, 'form_documento', $action, 'post', true);
        $campo_titulo   = new \XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', '', 2, 50);
        $campo_fonte    = new \XoopsFormText(_AM_ASSESSMENT_FONTE, 'campo_fonte', 35, 20);
        $campo_codprova = new \XoopsFormHidden('campo_codprova', $cod_prova);

        $form->setExtra('enctype="multipart/form-data"');

        if (!is_object($GLOBALS['xoopsModule']) || 'assessment' !== $GLOBALS['xoopsModule']->getVar('dirname')) {
            $modhandler    = &xoops_getHandler('module');
            $module        =& $modhandler->getByDirname('assessment');
            $configHandler = &xoops_getHandler('config');
            $moduleConfig  =& $configHandler->getConfigsByCat(0, $module->getVar('mid'));
        } else {
            $moduleConfig =& $GLOBALS['xoopsModuleConfig'];
        }
        $editor = $moduleConfig['editorpadrao'];

        // Add the editor selection box
        // If dohtml is disabled, set $noHtml = true
        //$form->addElement(new \XoopsFormSelectEditor($form, "editor", $editor, $noHtml = false));

        // options for the editor
        //required configs
        $options['name']  = 'campo_documento';
        $options['value'] = Request::getString('message', '');
        //optional configs
        $options['rows']   = 25; // default value = 5
        $options['cols']   = 60; // default value = 50
        $options['width']  = '100%'; // default value = 100%
        $options['height'] = '400px'; // default value = 400px

        // "textarea": if the selected editor with name of $editor can not be created, the editor "textarea" will be used
        // if no $onFailure is set, then the first available editor will be used
        // If dohtml is disabled, set $noHtml to true
        $campo_documento = new \XoopsFormEditor(_AM_ASSESSMENT_DOCUMENTO, $editor, $options, $nohtml = false, $onfailure = 'textarea');
        $botao_enviar    = new \XoopsFormButton(_AM_ASSESSMENT_CADASTRAR, 'botao_submit', _SUBMIT, 'submit');

        $form->addElement($campo_codprova);
        $form->addElement($campo_titulo, true);
        $form->addElement($campo_documento, true);
        $form->addElement($campo_fonte, true);
        $form->addElement($campo_perguntas, true);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * @param $action
     * @param $cod_documento
     *
     * @return bool
     */
    public function renderFormEditar($action, $cod_documento)
    {
        global $xoopsDB, $xoopsUser, $xoopsModuleConfig;

        $documento = $this->get($cod_documento);
        $titulo    = $documento->getVar('titulo', 's');
        //$textodocumento = text_filter($documento->getVar('documento',"f"),true);
        $textodocumento               = $documento->getVar('documento', 'f');
        $fonte                        = $documento->getVar('fonte', 's');
        $uid_elaborador               = $documento->getVar('uid_elaborador', 's');
        $cod_prova                    = $documento->getVar('cod_prova', 's');
        $vetor_perguntas_selecionadas = explode(',', $documento->getVar('cods_perguntas', 's'));

        $questionFactory = new Assessment\QuestionHandler($xoopsDB);
        $criteria             = new \Criteria('cod_prova', $cod_prova);

        $vetor_perguntas = $questionFactory->getObjects($criteria);
        $campo_perguntas = new \XoopsFormSelect('Perguntas associadas', 'campo_perguntas', $vetor_perguntas_selecionadas, 10, true);

        foreach ($vetor_perguntas as $pergunta) {
            $campo_perguntas->addOption($pergunta->getVar('cod_pergunta'), $pergunta->getVar('titulo'));
        }

        $form               = new \XoopsThemeForm(_AM_ASSESSMENT_EDITAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, 'form_documento', $action, 'post', true);
        $campo_titulo       = new \XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', $titulo, 2, 50);
        $campo_fonte        = new \XoopsFormText(_AM_ASSESSMENT_FONTE, 'campo_fonte', 35, 20, $fonte);
        $campo_coddocumento = new \XoopsFormHidden('campo_coddocumento', $cod_documento);
        $campo_codprova     = new \XoopsFormHidden('campo_codprova', $cod_prova);
        $form->setExtra('enctype="multipart/form-data"');

        $editor = $helper->getConfig('editorpadrao');

        // Add the editor selection box
        // If dohtml is disabled, set $noHtml = true
        //$form->addElement(new \XoopsFormSelectEditor($form, "editor", $editor, $noHtml = false));

        // options for the editor
        //required configs
        $options['name']  = 'campo_documento';
        $options['value'] = Request::getString('message', $textodocumento);
        //optional configs
        $options['rows']   = 25; // default value = 5
        $options['cols']   = 60; // default value = 50
        $options['width']  = '100%'; // default value = 100%
        $options['height'] = '400px'; // default value = 400px

        // "textarea": if the selected editor with name of $editor can not be created, the editor "textarea" will be used
        // if no $onFailure is set, then the first available editor will be used
        // If dohtml is disabled, set $noHtml to true
        $campo_documento = new \XoopsFormEditor(_AM_ASSESSMENT_DOCUMENTO, $editor, $options, $nohtml = false, $onfailure = 'textarea');
        $botao_enviar    = new \XoopsFormButton(_AM_ASSESSMENT_EDITAR, 'botao_submit', _SUBMIT, 'submit');

        $form->addElement($campo_coddocumento);
        $form->addElement($campo_titulo, true);
        $form->addElement($campo_documento, true);
        $form->addElement($campo_fonte, true);
        $form->addElement($campo_perguntas, true);
        $form->addElement($campo_codprova);
        $form->addElement($botao_enviar);
        $form->display();

        return true;
    }

    /**
     * Copia os documentos e salva eles ligados � prova clone
     *
     * @param object $criteria {@link CriteriaElement} to match
     *
     * @param        $cod_prova
     * @return void count of Questions
     */
    public function clonarDocumentos($criteria, $cod_prova)
    {
        global $xoopsDB;

        $documentos = $this->getObjects($criteria);
        foreach ($documentos as $documento) {
            $documento->setVar('cod_prova', $cod_prova);
            $documento->setVar('cod_documento', 0);
            $documento->setNew();
            $this->insert($documento);
        }
    }
}
