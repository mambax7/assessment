<?php
/**
 * @return array
 */
function b_sitemap_assessment()
{
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = \MyTextSanitizer::getInstance();

    global $xoopsConfig;

    /*  if (file_exists(XOOPS_ROOT_PATH . '/modules/xoopspoll/language/' . $xoopsConfig['language'] . '/main.php')) {
            include_once(XOOPS_ROOT_PATH . '/modules/xoopspoll/language/' . $xoopsConfig['language'] . '/main.php');
        }
    */
    $sitemap = [];
    $result  = $db->query('SELECT cod_prova,titulo FROM ' . $db->prefix('assessment_provas') . ' ');
    $i       = 0;
    while ($prova_linha = $db->fetchArray($result)) {
        $sitemap['parent'][$i]['id'] = $prova_linha['cod_prova'];

        $sitemap['parent'][$i]['title'] = $myts->htmlSpecialChars($prova_linha['titulo']);

        $sitemap['parent'][$i]['url'] = 'verprova.php?cod_prova=' . $prova_linha['cod_prova'];
        /*
                $sitemap['parent'][$i]['child'][$i]['id']=$prova_linha["cod_prova"];
                $sitemap['parent'][$i]['child'][$i]['title']="titulo filho";
                $sitemap['parent'][$i]['child'][$i]['image']=2;
                $sitemap['parent'][$i]['child'][$i]['url']="verprova.php?cod_prova=".$prova_linha["cod_prova"];*/
        ++$i;
    }

    return $sitemap;
}
