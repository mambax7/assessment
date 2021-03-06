<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_ASSESSMENT_HELP_HEADER}>

    <h4 class="odd">TUTORIAL</h4> <br>

    <h2>1. Introdu&ccedil;&atilde;o:</h2>

    <p>O m&oacute;dulo de assessment oferece a ambos professor e aluno, uma interface agrad&aacute;vel e intuitiva para realizar provas. O aluno encontra
        na facilidade de uma barra de navega&ccedil;&atilde;o que marca as perguntas que ele j&aacute; respondeu a seguran&ccedil;a que procura na realiza&ccedil;&atilde;o
        da prova. A possibilidade de retornar em uma pergunta e at&eacute; mesmo alterar a sua resposta pr&eacute;via configura uma usabilidade
        imprecedente a este m&oacute;dulo. Do lado do professor, pequenos detalhes tais como a funcionalidade que permite clonar uma prova existente para
        n&atilde;o ter o retrabalho de redigit&aacute;-la caracterizam a singularidade desta ferramenta. Esperamos que apreciem esta ferramenta e que
        encontram suas d&uacute;vidas esclarecidas neste manual.</p>

    <p align="right">Marcello Brand&atilde;o </p>
    <ol>
        <li>Introdu&ccedil;&atilde;o</li>
        <li>Instalando o m&oacute;dulo
            <ol type="a">
                <li>Onde baixar</li>
                <li>Depend&ecirc;ncias</li>
                <li>Instalando</li>
            </ol>
        </li>
        <li> Configurando para as suas necessidades</li>
        <li> Criando uma prova
            <ol type="a">
                <li>Cadastrando os dados da prova em si</li>
                <li> Cadastrando as perguntas da prova</li>
                <li> Cadastrando os documentos da prova</li>
                <li> Disponibilizando a prova para os usu&aacute;rios</li>
            </ol>
        <li> Editando uma prova
            <ol type="a">
                <li> Editando uma pergunta</li>
                <li> Editando um documento</li>
            </ol>
        <li>Corrigindo uma prova (editando resultado)</li>
        <li> Fazendo uma prova
            <ol type="a">
                <li> Consultando disponibilidade</li>
                <li>Consultando instru&ccedil;&otilde;es e detalhes da prova e iniciando prova</li>
                <li> Respondendo &agrave;s perguntas</li>
                <li>Consultando andamento da prova e encerrando prova</li>
                <li>Consultando resultado da prova</li>
            </ol>
        <li>Cr&eacute;ditos</li>
    </ol>
    </li>
    </li>
    </ol>
    </li>
    </ol>
    </li>
    </ol>
    </li>
    </ol>
    <p align="left">&nbsp;</p>

    <h2 align="left">2. Instalando o m&oacute;dulo</h2>

    <h3 align="left">2.a Onde baixar</h3>

    <p align="left">A primeira coisa a ser realizada &eacute; baixar o m&oacute;dulo em um dos sites de suporte ao xoops. <br>
        Inicialmente estarei colocando pelo menos em 3: xoops.org xoopstotal.com.br e xoops.pr.gov.br .<br>
    </p>

    <h3 align="left">2.b Depend&ecirc;ncias:</h3>

    <p align="left"> Segunda coia a se fazer &eacute; verificar se voc&ecirc; j&aacute; possui na sua m&aacute;quina dois pacotes opcionais do xoops
        que
        considero
        importantes: Frameworks do phppp 1.1 ou superior e class/xoopseditor 1.10 ou superior.
        O primeiro &eacute; respons&aacute;vel por v&aacute;rias fun&ccedil;&otilde;es de seguran&ccedil;a do m&oacute;dulo e umas utilidades que permitem
        dar &quot;uma carinha bonita&quot; &agrave; administra&ccedil;&atilde;o. O segundo Permite usar um editor de textos muito semelhante
        ao word (este tipo de editor &eacute; chamado <abbr title="What you see is what you get" lang="En">WYSIWYG</abbr>). Voc&ecirc; pode baixar eles
        aqui:<br>
        Link para o Frameworks<br>
        Link para o xoopseditor
    </p>

    <h3 align="left">2.c Instalando</h3>

    <p align="left">Coloque a pasta assessment dentro de /modules/, a pasta do Frameworks (sim com mai&uacute;scula) na
        raiz do site e a pasta xoopseditor dentro de /class/. Entre como administrador no site e v&aacute; na &aacute;rea reservada
        ao administrador e no m&oacute;dulo system admin escolha a op&ccedil;&atilde;o modules. <br>
        Duas listas de m&oacute;dulos ir&atilde;o aparecer. A primeira &eacute; a dos m&oacute;dulos j&aacute; instalados, a segunda &eacute; a dos m&oacute;dulos
        ainda n&atilde;o instalados. &Eacute; nesta segunda lista que estar&aacute; o m&oacute;dulo assessment. clique no bot&atilde;o instalar conforme
        imagem abaixo.<br>
        <img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install1.png" alt="Clique na no &iacute;cone de instala&ccedil;&atilde;o" width="459" height="233"></p>

    <p align="left">Agora se tudo correu bem uma tela de confirma&ccedil;&atilde;o ir&aacute; se estamapar na tela parecendo com a imagem abaixo:</p>

    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install2.png" alt="Confirma&ccedil;&atilde;o do desejo de instalar este m&oacute;dulo" width="420" height="175"></p>

    <p align="left">Novamente se tudo correu bem , uma tela com o log de tudo que foi realizado vai se apresentar. Algo como esta abaixo:</p>

    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install3.png" alt="Log de texto da instala&ccedil;&atilde;o" width="400" height="180"><br>
        Pronto o seu m&oacute;dulo foi instalado com sucesso e voc&ecirc; pode ir criar sua primeira prova ou prestar a prova de exemplo sobre as capitais
        do mundo que vem j&aacute; instalada.</p>

    <h2 align="left">3. Configurando para as suas necessidades</h2>

    <p align="left">Assessment permite que voc&ecirc; o ajuste para diversas finalidades e de acordo com estas voc&ecirc; dever&aacute; ajustar alguns
        par&acirc;metros. Para alterar os par&acirc;metros do m&oacute;dulo simplesmente v&aacute; no menu prefer&ecirc;ncias do m&oacute;dulo de
        assessment. A p&aacute;gina abaixo ir&aacute; se abrir. Configure-a conforme suas necessidades.</p>

    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/preferencias.png" alt="Tela de prefer&ecirc;ncias" width="816" height="490"></p>

    <p align="left">Item 1: Na tela de perguntas voc&ecirc; possui uma barra de navega&ccedil;&atilde;o que permite que voc&ecirc; navegue entre as
        diferentes perguntas da prova marcando aquelas que o aluno j&aacute; respondeu. Quantos itens v&atilde;o aparecer nessa lista antes de quebrar a
        linha &eacute; definido neste item. Isto permite que voc&ecirc; use o Assessment em qualquer resolu&ccedil;&atilde;o.</p>

    <p align="left">Item 2: O editor dos documentos pode ser tanto um editor simples como pode ser um editor elaborado que permite inserir at&eacute;
        mesmo videos do youtube. Eu recomendo que usem o editor koivi por ser este o recomendado pela equipe xoops.org, por&eacute;m eu sugiro tamb&eacute;m
        que testem o mastoppublish que vai em anexo dentro da pasta extras por possuir mais funcionalidades e ser feito por um brasileiro (topet05)</p>

    <p align="left">Item 3. Quando o aluno termianr de fazer a prova ele pode tanto ter que aguardar por um sinal verde do professor informando que o
        resultado j&aacute; saiu, como ele pode ter o resultado imediatamente. Tudo vai depender de seu caso de neg&oacute;cio. As vezes &eacute;
        interessante que o aluno s&oacute; saiba de sua nota em uma data espec&iacute;fica mais tarde, outras vezes &eacute; ele precisa saber
        imediatamente. Vamos ver o que &eacute; emlhor no seu caso.</p>

    <p align="left">Item 4. Na parte da administra&ccedil;&atilde;o existem v&aacute;rias listas que exibem perguntas documentos provas e resultados. Aqui
        voc&ecirc; define quantos itens devem aparecer por p&aacute;gina. Recomendo o uso de 5 mesmo, por n&atilde;o pesar para o servidor.</p>

    <p align="left">Item 5. Esta &eacute; conhecida dos usu&aacute;rios xoops. Aqui voc&ecirc; define se quer ou n&atilde;o que os alunos possam escolher
        de serem comunicados de que o resultado de sua prova saiu.</p>

    <p align="left">Item 6. Esta tamb&eacute;m &eacute; conhecida dos xoopers. Neste item de configura&ccedil;&atilde;o voc&ecirc; escolhe quais eventos
        est&atilde;o habilitados. No caso de nosso m&oacute;dulo por enquanto apenas um evento existe portanto n&atilde;o tem muito sentido , mas quem
        sabe na pr&oacute;xima vers&atilde;o.</p>

    <h2 align="left"> 4. Criando uma prova </h2>

    <p>O primeiro passo a ser tomado para se criar uma intera&ccedil;&atilde;o entre o aluno e o professor &eacute; a cria&ccedil;&atilde;o da prova. Sem
        prova criada n&atilde;o h&aacute; como o aluno ter um resultado o que parece &oacute;bvio. </p>

    <h3>4.a Cadastrando os dados da prova em si</h3>

    <p>Para se entender como o m&oacute;dulo funciona h&aacute; de se entender alguns termos . A prova &eacute; formada por perguntas, documentos,
        resultados e atributos pr&oacute;prios. A primeira coisa a se fazer para criar a prova &eacute; cadastrar os atributos pr&oacute;prios desta. Veja
        a tela de cadastro e sua explica&ccedil;&atilde;o:</p>

    <p><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/telaprova1.png" alt="Tela de cadastro dos atributos principais da prova" width="808" height="598"></p>

    <p>Item 1 : Uma liga&ccedil;&atilde;o direta com a parte do aluno , onde o aluno encontra as provas.</p>

    <p>Item 2: Uma liga&ccedil;&atilde;o para a p&aacute;gina atual onde se cria, edita, clona e exclui provas, al&eacute;m de poder consultar os
        resultados de uma determianda prova.</p>

    <p>Item 3: Uma liga&ccedil;&atilde;o para a tela que permite acessar os resultados de todas as provas confundidas.</p>

    <p>Item 4: Uma liga&ccedil;&atilde;o para a tela que permite acessar os documentos de todas as provas confundidas</p>

    <p>Item 5: Uma liga&ccedil;&atilde;o para a p&aacute;gina de configura&ccedil;&otilde;es vista no t&oacute;pico 3 deste tutorial</p>

    <p>Item 6: Um texto que serve para voc&ecirc; se situar na parte de amdinsitra&ccedil;&atilde;o , ele indica onde voc&ecirc; est&aacute;.
        (breadcrumps)</p>

    <p>Item 7: Link que permite Editar a prova. Al&eacute;m de editar os atributos principais da prova voc&ecirc; tem acesso &Agrave;s tela de editar e
        cadastrar tanto documentos quanto perguntas.</p>

    <p>Item 8: Link que permite &quot;clonar&quot; uma prova. Clonar uma prova significa copiar al&eacute;m dos atributos da prova, todos os seus
        documentos e todas as suas perguntas. Cabe ressaltar que a atribui&ccedil;&atilde;o dos documetos &agrave;s perguntas n&atilde;o &eacute; copiada
        e deve ser realizada manualmente.</p>

    <p>Item 9: Exibe uma tela com todos os resultados daquela prova em especial e alguns dados estat&iacute;sticos como nota mais alta , nota m&eacute;dia...</p>

    <p>Item 10: Excluir a prova. Esta opera&ccedil;&atilde;o exige uma confirma&ccedil;&atilde;o pois ela apaga al&eacute;m dos atributos da prova , todas
        as perguntas , respostas , documentos e resultados relacionados &agrave;quela prova.</p>

    <p>Item 11: T&iacute;tulo da prova. Campo para que se defina um t&iacute;tulo para a prova. ex: PROVA DE GEOGRAFIA</p>

    <p>Item 12: Campos para cadastro da descri&ccedil;&atilde;o da prova. Informa&ccedil;&otilde;es talvez sobre a mat&eacute;ria que est&aacute; sendo
        verificada.</p>

    <p>Item 13: Campo para cadastrar intru&ccedil;&otilde;es da prova. Sugiro que se coloquem todas as regras aqui (tempo de prova, se prova &eacute; com
        ou sem consulta etc...)</p>

    <p>Item 14: Tempo da prova em segundos. Depois que o aluno come&ccedil;ar a prova quanto tempo ele tem antes de ela se fechar sozinha?</p>

    <p>Itens 15 e 16: Esses campos permitem definir dia e hora de inicio e de fim da dosponibiliza&ccedil;&atilde;o da prova. Antes o aluno n&atilde;o
        consegue fazer a prova e depois tamb&eacute;m n&atilde;o. Clique no bot&atilde;o para ver o calend&aacute;rio.</p>

    <p>Item 17: Grupos que poder&atilde;o fazer a prova. Enquanto estiver preparando a prova n&atilde;o coloque nenhum grupo ou ent&atilde;o altere a op&ccedil;&atilde;o
        anterior para que a prova n&atilde;o fique dispon&iacute;vel para o aluno. O risco &eacute; o aluno poder fazer a prova antes de ela estar
        pronta. </p>

    <p>Item 18: Bot&atilde;o para enviar os dados dos atributos das provas. </p>

    <h3>4.b Cadastrando as perguntas da prova </h3>

    <p>Depois de ter cadastrado os dados b&aacute;sicos da prova voc&ecirc; poder&aacute; cadastrar as perguntas da prova. Para isso na tela que se abre
        ap&oacute;s envio dos dados dos atributos da prova (pode chegar nessa janela tamb&eacute;m atrav&eacute;s do item editar prova na janela explicada
        no passo anterior) Clique no link para cadastro de pergunta ou role a barra de rolagem na lateral de seu browser at&eacute; o formul&aacute;rio de
        cadastro de pergunta. Este formul&aacute;rio possui 7 campos conforme figura abaixo. Preencha-os e envie sua pergunta. Repita a opera&ccedil;&atilde;o
        quantas vezes for necess&aacute;rio. Aqui acho que s&oacute; cabe destacar o campo ordem que &eacute; importante no sentido em que ele permite que
        se defina a ordem em que as quest&otilde;es ser&atilde;o apresentadas na prova. </p>

    <h3>4.c Cadastrando os documentos da prova</h3>

    <p>Os documentos que voc&ecirc; ir&aacute; cadastrar no m&oacute;dulo deve servir de refer&ecirc;ncia para algumas perguntas. Quest&otilde;es por
        exemplo de interpreta&ccedil;&atilde;o de texto , requerem uma funcionalidade assim. Para se acessar o formul&aacute;rio de envio dessas informa&ccedil;&otilde;es
        clique em enviar documentos ou role a barra de rolagem a direita de seu browser para encontr&aacute;-lo. O formul&aacute;rio exibe al&eacute;m de
        seus campos b&aacute;sicos(detalhamento abaixo) um campo onde voc&ecirc; pode escolher com o bot&atilde;o esquerdo do mouse e a tecla ctrl
        pressinados ao mesmo tempo as perguntas que v&atilde;o apresentar esta pergunta antes. </p>

    <p><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/telaprova2.png" alt="Janela de edi&ccedil;&atilde;o das provas" width="813" height="2193"></p>

    <p>Itens 1 &agrave; 6: Ver t&oacute;pico 4.a deste tutorial.</p>

    <p>Item 7: Link para &acirc;ncora do formul&aacute;rio de cadastro de perguntas </p>

    <p>Item 8: Link para &acirc;ncora do formul&aacute;rio de cadastro de documentos</p>

    <p>Item 9: Formul&aacute;rio de editar dados b&aacute;sicos dos atributos , d&uacute;vidas se reportar ao t&oacute;pico 4.a</p>

    <p>Item 10: Lista de perguntas da prova exibida </p>

    <p>Item 11. Link para poder editar os dados de uma pergunta</p>

    <p>Item 12: Link para excluir uma pergunta da prova (as respostas associdas tamb&eacute;m s&atilde;o exclu&iacute;das)</p>

    <p>Itens 13,14,15: Semelhantes aos itens 10,11,12 s&oacute; que para documentos</p>

    <p>Item 16: Barra de navega&ccedil;&atilde;o das perguntas</p>

    <p>Item 17: N&atilde;o existe este item :D</p>

    <p>Item 18: Campo para ordem da pergunta. Este campo deve ser um n&uacute;mero inteiro. Quanto mais baixo antes a pergunta vir&aacute; na prova e
        quanto mais alto, mais tarde a pergunta vir&aacute; na prova.</p>

    <p>Item 19: Campo de t&iacute;tulo da pergunta: ex: Qual a capital da Italia</p>

    <p>Item 20: Resposta correta</p>

    <p>Item 21: Respostas incorretas</p>

    <p>Item 22: Bot&atilde;o para cadastrar a pergunta</p>

    <p>Item 23: T&iacute;tulo do documento</p>

    <p>Item 24: barra de ferramento do editor de texto</p>

    <p>Item 25: Texto da prova. Pode-se copiar o texto do word ou da internet e colar aqui neste editor que ele quarda a frmata&ccedil;&atilde;o (no caso
        do koivi)</p>

    <p>Item 26: Se for um texto da internet a fonte do texto</p>

    <p>Item 27: Quais perguntas devem apresentar o texto do documento antes delas? Use o ctrl ao mesmo tempo que clica nas perguntas para escolher mais de
        uma . Para deselecionar uma clicque novamente nela (sempre com o ctrl apertado ao mesmo tempo) </p>

    <p>Item 28: Bot&atilde;o para cadastrar o documento </p>

    <h3> 4.d Disponibilizando a prova para os usu&aacute;rios</h3>

    <p>Agora basta lembrar de atualizar os atributos da prova para liberar ela para os usu&aacute;rios. Acerte a data e ou os grupos e pronto!</p>

    <p>5 continua em outra vers&atilde;o....</p>

    <p>8 Cr&eacute;ditos</p>

    <p>Os cr&eacute;ditos deste tutorial v&atilde;o para Marcello Brand&atilde;o. Se quiser ajudar terminando este tutorial entre em contato com ele no
        xoops.org (suico) ou no xoopstotal (marcellobr). </p>

    <p>&nbsp;</p>

    <p align="left"><br>
    </p>

    <p align="left"><br>
    </p>

</div>
