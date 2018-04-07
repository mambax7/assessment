<div id="breadcrump"><a href="../index.php"><{$nome_modulo}></a> > <a href="../verprova.php?cod_prova=<{$cod_prova}>"><{$titulo}></a></div>
<hr>
<div id="Titulo">
    <fieldset id="prova">
        <legend><{$lang_prova}></legend>
        <h2><{$titulo}></h2>

        <p id="descricao"><{$descricao}></p>
    </fieldset>
</div>
<div id="instrucoes">
    <fieldset id="instrucoes">
        <legend><{$lang_instrucoes}></legend>

        <p><{$instrucoes}>

        <p>
</div>
<div id="inicio">
    <form action="preperguntas.php" method="post">
        <{securityToken}><{*//mb*}>
        <{*<{$campo_token}>*}>
        <input name="cod_prova" id="cod_prova" type="hidden" value="<{$cod_prova}>">
        <input name="enviar" type="submit" value="<{$lang_comecar}>">
    </form>
</div>
<img align="right" src="assets/images/mlogo.png" id="marcello_brandao">
