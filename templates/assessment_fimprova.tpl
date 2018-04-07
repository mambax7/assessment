<div id="breadcrump"><a href="../index.php"><{$nome_modulo}></a> > <a href="../verprova.php?cod_prova=<{$cod_prova}>"><{$titulo}></a></div>
<hr>
<div id="Titulo">
    <fieldset>
        <legend><{$lang_prova}></legend>
        <h2><{$titulo}></h2>

        <p id="descricao"><{$descricao}></p>
    </fieldset>
</div>
<div>
    <fieldset>
        <legend><{$lang_stats}></legend>
        <img align="absmiddle" src="assets/images/text-editor.gif" alt="Checklist" title="checklist"> <{$lang_andamento}><br>
        <img align="absmiddle" src="assets/images/relogio.gif" alt="relógio" title="tempo"> <{$lang_terminou}><br>
        <img align="absmiddle" src="assets/images/relogio.gif" alt="relógio" title="tempo"> <{$lang_temporestante}>
    </fieldset>

</div>
<div>
    <fieldset>
        <legend><{$lang_confirmacao}></legend>
        <h3><{$lang_alerta}></h3>

        <form action="fecharprova.php" method="post">
            <{securityToken}><{*//mb*}>
            <input name="cod_resultado" type="hidden" value="<{$cod_resultado}>">
            <input type=submit value="<{$lang_confirmasim}>">
            <{*<{$campo_token}>*}>
        </form>
        <form action="perguntas.php?" method="get">
            <{securityToken}><{*//mb*}>
            <{*<{$campo_token}>*}>
            <input name="cod_prova" type="hidden" value="<{$cod_prova}>">
            <input name="start" type="hidden" value="0">
            <input type=submit value="<{$lang_confirmanao}>">
        </form>


</div>
<div>
    <{include file='db:system_notification_select.tpl'}>
</div>
<img align="right" src="assets/images/mlogo.png" id="marcello_brandao">
