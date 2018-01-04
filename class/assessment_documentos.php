<?php
// $Id: assessment_documentos.php,v 1.10 2007/03/24 17:50:52 marcellobrandao Exp $
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

require_once XOOPS_ROOT_PATH . '/kernel/object.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.php';
require_once XOOPS_ROOT_PATH . '/class/xoopseditor/xoopseditor.inc.php';
//require_once XOOPS_ROOT_PATH."/class/xoopseditor/mastoppublish/formmpublishtextarea.php";
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formselecteditor.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/formeditor.php';
//require_once XOOPS_ROOT_PATH."/Frameworks/art/functions.sanitizer.php";
//require_once XOOPS_ROOT_PATH."/Frameworks/xoops22/class/xoopsform/xoopsformloader.php";

require_once __DIR__ . '/assessment_perguntas.php';

/**
 * assessment_documentos class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class assessment_documentos extends XoopsObject
{
    public $db;

    // constructor

    /**
     * @param null $id
     * @return assessment_documentos
     */
    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();
        $this->initVar('cod_documento', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('titulo', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('tipo', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cod_prova', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('cods_perguntas', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('documento', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('uid_elaborador', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('fonte', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('html', XOBJ_DTYPE_INT, null, false, 10);
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
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos') . ' WHERE cod_documento=' . $id;
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
    public function getAllassessment_documentoss($criteria = [], $asobject = false, $sort = 'cod_documento', $order = 'ASC', $limit = 0, $start = 0)
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
            $sql    = 'SELECT cod_documento FROM ' . $db->prefix('assessment_documentos') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = $myrow['assessment_documentos_id'];
            }
        } else {
            $sql    = 'SELECT * FROM ' . $db->prefix('assessment_documentos') . "$where_query ORDER BY $sort $order";
            $result = $db->query($sql, $limit, $start);
            while ($myrow = $db->fetchArray($result)) {
                $ret[] = new assessment_documentos($myrow);
            }
        }

        return $ret;
    }
}

// -------------------------------------------------------------------------
// ------------------assessment_documentos user handler class -------------------
// -------------------------------------------------------------------------

/**
 * assessment_documentoshandler class.
 * This class provides simple mecanisme for assessment_documentos object
 */
class Xoopsassessment_documentosHandler extends XoopsPersistableObjectHandler
{
    /**
     * create a new assessment_documentos
     *
     * @param bool $isNew flag the new objects as "new"?
     *
     * @return object assessment_documentos
     */
    public function &create($isNew = true)
    {
        $assessment_documentos = new assessment_documentos();
        if ($isNew) {
            $assessment_documentos->setNew();
        } else {
            $assessment_documentos->unsetNew();
        }

        return $assessment_documentos;
    }

    /**
     * retrieve a assessment_documentos
     *
     * @param  mixed $id     ID
     * @param  array $fields fields to fetch
     * @return XoopsObject {@link XoopsObject}
     */
    public function get($id = null, $fields = null)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos') . ' WHERE cod_documento=' . $id;
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $numrows = $this->db->getRowsNum($result);
        if (1 == $numrows) {
            $assessment_documentos = new assessment_documentos();
            $assessment_documentos->assignVars($this->db->fetchArray($result));

            return $assessment_documentos;
        }

        return false;
    }

    /**
     * insert a new assessment_documentos in the database
     *
     * @param XoopsObject $assessment_documentos reference to the {@link assessment_documentos} object
     * @param  bool       $force                 flag to force the query execution despite security settings
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $assessment_documentos, $force = false)
    {
        global $xoopsConfig;
        if ('assessment_documentos' != get_class($assessment_documentos)) {
            return false;
        }
        if (!$assessment_documentos->isDirty()) {
            return true;
        }
        if (!$assessment_documentos->cleanVars()) {
            return false;
        }
        foreach ($assessment_documentos->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        $now = 'date_add(now(), interval ' . $xoopsConfig['server_TZ'] . ' hour)';
        if ($assessment_documentos->isNew()) {
            // ajout/modification d'un assessment_documentos
            $assessment_documentos = new assessment_documentos();
            $format                = 'INSERT INTO %s (cod_documento, titulo, tipo, cod_prova, cods_perguntas, documento, uid_elaborador, fonte, html)';
            $format                .= 'VALUES (%u, %s, %u, %u, %s, %s, %u, %s, %u)';
            $sql                   = sprintf($format, $this->db->prefix('assessment_documentos'), $cod_documento, $this->db->quoteString($titulo), $tipo, $cod_prova, $this->db->quoteString($cods_perguntas), $this->db->quoteString($documento), $uid_elaborador, $this->db->quoteString($fonte), $html);
            $force                 = true;
        } else {
            $format = 'UPDATE %s SET ';
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
        $assessment_documentos->assignVar('cod_documento', $cod_documento);

        return true;
    }

    /**
     * delete a assessment_documentos from the database
     *
     * @param XoopsObject $assessment_documentos reference to the assessment_documentos to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $assessment_documentos, $force = false)
    {
        if ('assessment_documentos' != get_class($assessment_documentos)) {
            return false;
        }
        $sql = sprintf('DELETE FROM %s WHERE cod_documento = %u', $this->db->prefix('assessment_documentos'), $assessment_documentos->getVar('cod_documento'));
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
     * @param CriteriaElement $criteria  {@link CriteriaElement} conditions to be met
     * @param bool            $id_as_key use the UID as key for the array?
     *
     * @param bool            $as_object
     * @return array array of <a href='psi_element://$assessment_documentos'>$assessment_documentos</a> objects
     *                                   objects
     */
    public function &getObjects(CriteriaElement $criteria = null, $id_as_key = false, $as_object = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->db->prefix('assessment_documentos');
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
            $assessment_documentos = new assessment_documentos();
            $assessment_documentos->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $assessment_documentos;
            } else {
                $ret[$myrow['cod_documento']] =& $assessment_documentos;
            }
            unset($assessment_documentos);
        }

        return $ret;
    }

    /**
     * retrieve assessment_documentoss from the database
     *
     * @param $cod_prova
     * @param $cod_pergunta
     * @return array array of <a href='psi_element://assessment_documentos'>assessment_documentos</a> objects
     * objects
     * @internal param object $criteria <a href='psi_element://CriteriaElement'>CriteriaElement</a> conditions to be met conditions to be met conditions to be met conditions to be met
     * @internal param bool $id_as_key use the UID as key for the array?
     *
     */
    public function &getDocumentosProvaPergunta($cod_prova, $cod_pergunta)
    {
        $criteria         = new criteria('cod_prova', $cod_prova);
        $cod_documentos   = [];
        $documentos_prova = $this->getObjects($criteria);
        $i                = 0;
        foreach ($documentos_prova as $documento_prova) {
            $cods_perguntas = explode(',', $documento_prova->getVar('cods_perguntas'));
            if (in_array($cod_pergunta, $cods_perguntas)) {
                $documentos[$i]['titulo'] = $documento_prova->getVar('titulo');
                $documentos[$i]['fonte']  = $documento_prova->getVar('fonte');
                /*if ($xoopsModuleConfig['editorpadrao']=="dhtmlext"||$xoopsModuleConfig['editorpadrao']=="textarea") {
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
     * @param CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int count of assessment_documentoss
     */
    public function getCount(CriteriaElement $criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('assessment_documentos');
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
     * delete assessment_documentoss matching a set of conditions
     *
     * @param CriteriaElement $criteria {@link CriteriaElement}
     *
     * @param bool            $force
     * @param bool            $asObject
     * @return bool FALSE if deletion failed
     */
    public function deleteAll(CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        $sql = 'DELETE FROM ' . $this->db->prefix('assessment_documentos');
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
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
    * @param object $assessment_perguntas {@link assessment_perguntas}
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

        $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
        $criteria             = new criteria('cod_prova', $cod_prova);

        $vetor_perguntas = $fabrica_de_perguntas->getObjects($criteria);
        $campo_perguntas = new XoopsFormSelect(_AM_ASSESSMENT_PERGASSOC, 'campo_perguntas', null, 10, true);

        foreach ($vetor_perguntas as $pergunta) {
            $campo_perguntas->addOption($pergunta->getVar('cod_pergunta'), $pergunta->getVar('titulo'));
        }

        $form           = new XoopsThemeForm(_AM_ASSESSMENT_CADASTRAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, 'form_documento', $action, 'post', true);
        $campo_titulo   = new XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', '', 2, 50);
        $campo_fonte    = new XoopsFormText(_AM_ASSESSMENT_FONTE, 'campo_fonte', 35, 20);
        $campo_codprova = new XoopsFormHidden('campo_codprova', $cod_prova);

        $form->setExtra('enctype="multipart/form-data"');

        if (!is_object($GLOBALS['xoopsModule']) || 'assessment' != $GLOBALS['xoopsModule']->getVar('dirname')) {
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
        //$form->addElement(new XoopsFormSelectEditor($form, "editor", $editor, $noHtml = false));

        // options for the editor
        //required configs
        $options['name']  = 'campo_documento';
        $options['value'] = empty($_REQUEST['message']) ? '' : $_REQUEST['message'];
        //optional configs
        $options['rows']   = 25; // default value = 5
        $options['cols']   = 60; // default value = 50
        $options['width']  = '100%'; // default value = 100%
        $options['height'] = '400px'; // default value = 400px

        // "textarea": if the selected editor with name of $editor can not be created, the editor "textarea" will be used
        // if no $onFailure is set, then the first available editor will be used
        // If dohtml is disabled, set $noHtml to true
        $campo_documento = new XoopsFormEditor(_AM_ASSESSMENT_DOCUMENTO, $editor, $options, $nohtml = false, $onfailure = 'textarea');
        $botao_enviar    = new XoopsFormButton(_AM_ASSESSMENT_CADASTRAR, 'botao_submit', _SUBMIT, 'submit');

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

        $fabrica_de_perguntas = new Xoopsassessment_perguntasHandler($xoopsDB);
        $criteria             = new criteria('cod_prova', $cod_prova);

        $vetor_perguntas = $fabrica_de_perguntas->getObjects($criteria);
        $campo_perguntas = new XoopsFormSelect('Perguntas associadas', 'campo_perguntas', $vetor_perguntas_selecionadas, 10, true);

        foreach ($vetor_perguntas as $pergunta) {
            $campo_perguntas->addOption($pergunta->getVar('cod_pergunta'), $pergunta->getVar('titulo'));
        }

        $form               = new XoopsThemeForm(_AM_ASSESSMENT_EDITAR . ' ' . _AM_ASSESSMENT_DOCUMENTO, 'form_documento', $action, 'post', true);
        $campo_titulo       = new XoopsFormTextArea(_AM_ASSESSMENT_TITULO, 'campo_titulo', $titulo, 2, 50);
        $campo_fonte        = new XoopsFormText(_AM_ASSESSMENT_FONTE, 'campo_fonte', 35, 20, $fonte);
        $campo_coddocumento = new XoopsFormHidden('campo_coddocumento', $cod_documento);
        $campo_codprova     = new XoopsFormHidden('campo_codprova', $cod_prova);
        $form->setExtra('enctype="multipart/form-data"');

        $editor = $xoopsModuleConfig['editorpadrao'];

        // Add the editor selection box
        // If dohtml is disabled, set $noHtml = true
        //$form->addElement(new XoopsFormSelectEditor($form, "editor", $editor, $noHtml = false));

        // options for the editor
        //required configs
        $options['name']  = 'campo_documento';
        $options['value'] = empty($_REQUEST['message']) ? $textodocumento : $_REQUEST['message'];
        //optional configs
        $options['rows']   = 25; // default value = 5
        $options['cols']   = 60; // default value = 50
        $options['width']  = '100%'; // default value = 100%
        $options['height'] = '400px'; // default value = 400px

        // "textarea": if the selected editor with name of $editor can not be created, the editor "textarea" will be used
        // if no $onFailure is set, then the first available editor will be used
        // If dohtml is disabled, set $noHtml to true
        $campo_documento = new XoopsFormEditor(_AM_ASSESSMENT_DOCUMENTO, $editor, $options, $nohtml = false, $onfailure = 'textarea');
        $botao_enviar    = new XoopsFormButton(_AM_ASSESSMENT_EDITAR, 'botao_submit', _SUBMIT, 'submit');

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
     * @return int count of assessment_perguntass
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
