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
 * Result class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Result extends \XoopsObject
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
                $ret[] = new Assessment\Result($myrow);
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
            if (!(null === $chave)) {
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
            if (!(null === $chave)) {
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
