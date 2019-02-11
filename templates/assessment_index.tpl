<div id="breadcrump"><a href="index.php"><{$nome_modulo}></a></div>
<hr>
<div id="Titulo">
    <h2><{$nome_modulo}></h2>
</div>
<table class='outer' width='100%'>
    <tr>
        <th colspan='3'><{$lang_listaprovas}></th>
    </tr>


    <{foreach from=$vetor_provas item=prova}>
        <{if (($prova.fechada == 0) && ($prova.terminou == 1)) }>
        <tr class=<{cycle values="odd,even"
        }>>
            <td><img src=assets/images/prova.png align="left">
                <strong><{$prova.tit_prova}></strong></td>
            <td colspan='2'><{$lang_emcorrecao}></td>
            </tr><{/if}>
        <{if (($prova.fechada == 1) && ($prova.terminou == 1)) }>
            <tr class=<{cycle values="odd,even"
            }>>
                <td width="50%"><img src=assets/images/prova.png align="left">
                    <strong><{$prova.tit_prova}></strong></td>
                <td><{$lang_notafinal}> <{$prova.nota_final}></td>
                <td> <{$lang_nivel}><br>
                    <{$prova.nivel}>
                </td>
            </tr>
        <{/if}>
        <{if (($prova.fechada == 0) && ($prova.terminou == 0) && ($prova.naodisponivel == 1)) }>
            <tr class=<{cycle values="odd,even"
            }>>
                <td><img src=assets/images/prova.png align="left"><strong><{$prova.tit_prova}></strong> <br>
                    <{$lang_tempoencerrado}>
                </td>
                <td><img src="assets/images/text-editor.gif" align="absmiddle"><{$prova.qtd_perguntas}> <{$lang_perguntas}></td>
                <td>
                    <strong><{$lang_disponibilidade}>:</strong><br>
                    <img align="left" src="assets/images/relogio.gif"><{$lang_inicio}>:<{$prova.inicio}> <br>
                    <{$lang_fim}>:<{$prova.fim}>
                </td>
            </tr>
        <{/if}>
        <{if (($prova.fechada == 0) && ($prova.terminou == 0) && ($prova.naodisponivel == 0)) }>
            <tr class=<{cycle values="odd,even"
            }>>
                <td><img src=assets/images/prova.png align="left"><a href=verprova.php?cod_prova=<{$prova.cod_prova}>>
                        <{$prova.tit_prova}></a></td>
                <td><img src="assets/images/text-editor.gif" alt=" " align="absmiddle"><{$prova.qtd_perguntas}> <{$lang_perguntas}><br></td>
                <td><strong><{$lang_disponibilidade}>:</strong><br>
                    <img src="assets/images/relogio.gif" alt=" " align="left"><{$lang_inicio}>:
                    <{$prova.inicio}> <br>
                    <{$lang_fim}>:<{$prova.fim}>
                </td>
            </tr>
        <{/if}>
    <{/foreach}>
</table>
<{$navegacao}>
<img align="right" src="assets/images/mlogo.png" id="marcello_brandao">
