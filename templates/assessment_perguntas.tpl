<div id="breadcrump"><a href="index.php"><{$nome_modulo}></a> <a href="verprova.php?cod_prova=<{$cod_prova}>"><{$titulo}></a> > <a
            href="perguntas.php?cod_prova=<{$cod_prova}>&start=<{$start}>"><{$lang_pergunta}> <{$start+1}></a></div>
<hr>
<div><h3><{$titulo}></h3>

    <p><img align="absmiddle" src="assets/images/text-editor.gif" alt="Checklist" title="checklist"> <{$lang_andamento}> <img align="absmiddle"
                                                                                                                       src="assets/images/relogio.gif"
                                                                                                                       alt="rel�gio" title="tempo">
        <{$lang_temporestante}></p>
</div>
<{if isset($documentos)}>
    <fieldset id="documentos">
        <legend><{$lang_textosapoio}></legend>
        <{foreach from=$documentos item=documento}>
            <h2><{$documento.titulo}></h2>
            <i><{$documento.fonte}></i>
            <div>
                <{$documento.documento}>
            </div>
        <{/foreach}>
    </fieldset>
<{/if}>
<fieldset id="pergunta">
    <legend><{$lang_pergunta}>:</legend>
    <{$form_resposta.title}>
</fieldset>
<form action="<{$form_resposta.action}>" method="<{$form_resposta.method}>" name="<{$form_resposta.name}>" <{$form_resposta.extra}> >
    <fieldset>
        <legend><{$lang_respostas}></legend>
        <{foreach from=$form_resposta.elements item=campo }>
            <{$campo.body}>
        <{/foreach}>
    </fieldset>
</form>

<{$form_resposta.javascript}>
<div id="barranavegacao">
    <{$barra_navegacao}>
</div>
<div id="fimprova" style="text-align:center"><a href="fimprova.php?cod_prova=<{$cod_prova}>&cod_resultado=<{$cod_resultado}>"><{$lang_encerrar}></a>
</div>
<div id=legenda>
    <h3><{$lang_legenda}></h3>
    <img src="assets/images/feita.jpg" alt="<{$lang_iconjaresp}>"> <{$lang_jaresp}><br>
    <img src="assets/images/naofeita.jpg" alt="<{$lang_iconnaoresp}>"> <{$lang_naoresp}>

</div>
<img align="right" src="assets/images/mlogo.png" id="marcello_brandao">
