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
 * Result class.
 * $this class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 */
class Result extends \XoopsObject
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
     * @return array
     */
    public function getRespostasCertasAsArray()
    {
        $respostas     = [];
        $respostas     = explode(',', $this->getVar('resp_certas'));
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
    public function getRespostasErradasAsArray()
    {
        $respostas     = [];
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
