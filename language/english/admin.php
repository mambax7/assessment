<?php
// $Id: admin.php,v 1.9 2007/03/24 20:09:11 marcellobrandao Exp $
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
//index.php

//define("_AM_ASSESSMENT_CONGRATULATIONS", "Congratulations your test has been successfully saved");

//excluirprova.php
define('_AM_ASSESSMENT_CONFIRMAEXCLUSAOPROVAS', "Deleting this exam you will also be deleting:
       :<br><br>Its questions<br>The answers of those questions<br>
       The documents which show up before the questions<br>The results of the students who had already done the exam<br><br>Are you sure that's what you want to do?<br>");

define('_AM_ASSESSMENT_SIMCONFIRMAEXCLUSAOPROVAS', 'Ok I understood that I will delete more than a single exam and yes I AM SURE');
define('_AM_ASSESSMENT_PERGUNTASAPAGADAS', 'question(s) deleted');
define('_AM_ASSESSMENT_RESPDAPERG', 'Answers of question %s deleted');
define('_AM_ASSESSMENT_RESULTAPAGADOS', 'result(s) deleted');
define('_AM_ASSESSMENT_DOCUMENTOSPAGADOS', 'document(s) deleted');

//index.php
define('_AM_ASSESSMENT_REQUERIMENTOS', "You need to install these packages in order to make this module work properly:<br><br>
   <a href='http://dev.xoops.org/modules/xfmod/project/showfiles.php?group_id=1357'>
   Frameworks v 1.1 or newer</a><br>
   <br>
   <a href='http://dev.xoops.org/modules/xfmod/project/showfiles.php?group_id=1155'>
   Xoops editor 1.10 or newer</a><br>");
define('_AM_ASSESSMENT_SEMPROVAS', 'No exams have been submitted, yet');
define('_AM_ASSESSMENT_EDITARPROVAS', 'Edit exams');
define('_AM_ASSESSMENT_EXCLUIRPROVAS', 'Delete exams');
define('_AM_ASSESSMENT_VERRESULT', 'View results');
define('_AM_ASSESSMENT_LISTAPROVAS', 'Exams list:');
define('_AM_ASSESSMENT_PERGUNTA', 'Question:');
define('_AM_ASSESSMENT_RESPCERTA', 'Right answer');
define('_AM_ASSESSMENT_RESPUSR', "Student's answer");
define('_AM_ASSESSMENT_SEMRESULT', 'No result submitted yet');
define('_AM_ASSESSMENT_QTDRESULT', 'Number of results');
define('_AM_ASSESSMENT_NOTAMAX', ' Best mark:');
define('_AM_ASSESSMENT_NOTAMIN', ' Worst mark:');
define('_AM_ASSESSMENT_MEDIA', ' Mean:');
define('_AM_ASSESSMENT_LISTARESULTADOS', 'Results list:');
define('_AM_ASSESSMENT_PROVAANDAMENTO', 'Exam still going on');
define('_AM_ASSESSMENT_TERMINADA', 'Exam finished');
define('_AM_ASSESSMENT_NOMEALUNO', 'Name of the student:');
define('_AM_ASSESSMENT_DATA', 'Date:');
define('_AM_ASSESSMENT_CODPROVA', "Exam's id:");
define('_AM_ASSESSMENT_STATS', 'Stats:');
define('_AM_ASSESSMENT_LISTAPERGASSOC', 'List of questions associated to this exam:');
define('_AM_ASSESSMENT_SEMPERGUNTA', 'No questions submitted to this exam yet');
define('_AM_ASSESSMENT_EDITARPERGUNTAS', 'Edit questions');
define('_AM_ASSESSMENT_EXCLUIRPERGUNTAS', 'Delete questions');
define('_AM_ASSESSMENT_SEMDOCUMENTO', 'No documents submitted to this exam yet ');
define('_AM_ASSESSMENT_LISTADOC', "Document's List:");
define('_AM_ASSESSMENT_EDITARDOC', 'Edit Document');
define('_AM_ASSESSMENT_EXCLUIRDOC', 'Delete document');
define('_AM_ASSESSMENT_INSTRUCOESNOVODOC', "To submit a new document go to the exam tab and select 'edit exam'");
define('_AM_ASSESSMENT_PROVA', 'Exam');
define('_AM_ASSESSMENT_DOCUMENTO', 'Document');
define('_AM_ASSESSMENT_RESULTADO', 'Result');
define('_AM_ASSESSMENT_RESULTPROVA', 'Result per exam');
define('_AM_ASSESSMENT_RESPALUNO', "Student's answer");
define('_AM_ASSESSMENT_EDITAR', 'Edit');
define('_AM_ASSESSMENT_CADASTRAR', 'Submit');
define('_AM_ASSESSMENT_ACERTOU', 'Correct: ');
define('_AM_ASSESSMENT_ERROU', 'Wrong:');
define('_AM_ASSESSMENT_SEMREPONDER', 'Blank:');
define('_AM_ASSESSMENT_DEUMTOTALDE', 'out of a total of');
define('_AM_ASSESSMENT_PERGUNTAS', 'questions');

//assessment_documentos.php
define('_AM_ASSESSMENT_PERGASSOC', 'Associated questions');
define('_AM_ASSESSMENT_TITULO', 'Title');
define('_AM_ASSESSMENT_FONTE', 'Source');

//Question.php
define('_AM_ASSESSMENT_RESPOSTA', 'Answer');
define('_AM_ASSESSMENT_RESPCORRETA', 'Right answer');
define('_AM_ASSESSMENT_SALVARALTERACOES', 'Save changes');

//Test.php
define('_AM_ASSESSMENT_DESCRICAO', 'Description');
define('_AM_ASSESSMENT_INSTRUCOES', 'Instructions');
define('_AM_ASSESSMENT_TEMPO', 'Time in seconds');
define('_AM_ASSESSMENT_GRUPOSACESSO', 'Groups with access');
define('_AM_ASSESSMENT_DATA_INICIO', 'Availability date');
define('_AM_ASSESSMENT_DATA_FIM', 'Unavailability date');

//Result
define('_AM_ASSESSMENT_PERGDETALHES', 'Click on the ID of the question to see more details');
define('_AM_ASSESSMENT_RESPCERTAS', 'Correct answers');
define('_AM_ASSESSMENT_RESPERR', 'Wrong answers');
define('_AM_ASSESSMENT_SUGESTNOTA', 'Grade suggestion');
define('_AM_ASSESSMENT_NOTAFINAL', 'Final grade');
define('_AM_ASSESSMENT_NIVEL', 'Level');
define('_AM_ASSESSMENT_OBS', 'Observations');

//cadastro_prova.php
define('_AM_ASSESSMENT_DATAINICIOMAIORQFIM', 'Unavailability date has been set to a date before the availability date');
//geral
define('_AM_ASSESSMENT_SUCESSO', 'Action succeed');
define('_AM_ASSESSMENT_TOKENEXPIRED', 'Your token has expired , try it again');
define('_AM_ASSESSMENT_ORDEM', 'Order');

//clone.php
define('_AM_ASSESSMENT_CLONE', '[Clone] ');

define('_AM_ASSESSMENT_DAYS_PER_MONTH', (((365 * 3) + 366) / 4) / 12);
