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

require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

/**
 * Class TestNavigator
 */
class TestNavigator extends \XoopsPageNav
{
    /**
     * Create navigation with images
     *
     * @param int     $cod_perguntas
     * @param         $cod_perguntas_respondidas
     * @param integer $offset
     *
     * @return string
     */
    public function renderImageNav($cod_perguntas = null, $cod_perguntas_respondidas = null, $offset = 1)
    {
        global $xoopsModule;
        $vazio     = [];
        $largura   = 50 * $offset;
        $perguntas = @array_merge($perguntas, $vazio);
        if ($this->total < $this->perpage) {
            return null;
        }
        $total_pages = ceil($this->total / $this->perpage);

        if ($total_pages > 1) {
            $ret = '<table align="center" style="width:' . $largura . 'px;"><tr>';

            $prev = $this->current - $this->perpage;
            if ($prev >= 0) {
                $ret .= '<td align="right"><a href="' . $this->url . $prev . $this->extra . '">
                <img src="assets/images/anterior.gif" border="0" alt="' . _MA_ASSESSMENT_ANTERIOR . '" title="' . _MA_ASSESSMENT_ANTERIOR . '"></a></td> ';
            }
            $ret  .= '<td align="center"><table><tr>';
            $next = $this->current + $this->perpage;

            $counter      = 1;
            $current_page = (int)floor(($this->current + $this->perpage) / $this->perpage);
            while ($counter <= $total_pages) {
                $cod_pergunta_atual = $cod_perguntas[$counter - 1];

                if (in_array($cod_pergunta_atual, $cod_perguntas_respondidas)) {
                    $ret .= '<td  valign="center" style="height=20px; text-align:center; background:url(assets/images/feita.jpg); background-repeat:no-repeat;background-position:center;">
                    <a rel="sad" href="' . $this->url . (($counter - 1) * $this->perpage) . $this->extra . '">' . $counter . '</a></td>';
                } else {
                    $ret .= '<td  valign="center" style="height=20px; text-align:center; background:url(assets/images/naofeita.jpg); background-repeat:no-repeat;background-position:center;">
                    <a href="' . $this->url . (($counter - 1) * $this->perpage) . $this->extra . '">' . $counter . '</a></td>';
                }

                if ((0 == ($counter % $offset)) & $counter != $total_pages - 1) {
                    $ret .= '</tr><tr>';
                }
                ++$counter;
            }
            $ret .= '</tr></table></td>';
            if ($this->total > $next) {
                $ret .= '<td valign="middle"><a href="' . $this->url . $next . $this->extra . '"><img src="assets/images/proximo.gif" border="0" alt="' . _MA_ASSESSMENT_PROXIMO . '" title="' . _MA_ASSESSMENT_PROXIMO . '"></a></td>';
            }

            $ret .= '</tr></table>';
        }

        return $ret;
    }
}
