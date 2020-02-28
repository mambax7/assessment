<div id="help-template" class="outer">
    <{include file=$smarty.const._MI_ASSESSMENT_HELP_HEADER}>

    <h4 class="odd">TUTORIAL</h4> <br>

    <h2>1. Introduction:</h2>

    <p>The assessment module offers both teacher and student a pleasant and intuitive interface for taking tests.
        The student finds in the ease of a navigation bar that marks the questions that he has already answered
        the security he seeks in the realization of the test. The possibility of returning to a question and even
        changing your previous answer is an unprecedented usability for this module.
        On the teacher's side, small details such as the functionality that allows you to clone an existing exam to avoid
        having to rework it to re-type it characterize the uniqueness of this tool.
        We hope you enjoy this tool and that your questions are answered in this manual.</p>

    <p align="right">Marcello Brand&atilde;o </p>
    <ol>
        <li>Introduction</li>
        <li>Installing the module
            <ol type="a">
                <li>Where to download</li>
                <li>Dependencies</li>
                <li>Installing</li>
            </ol>
        </li>
        <li> Configuring for your needs</li>
        <li> Creating a Test
            <ol type="a">
                <li>Registering the test data itself</li>
                <li> Registering the exam questions</li>
                <li> Registering the test documentsa</li>
                <li> Making test available to users</li>
            </ol>
        <li> Editing a Test
            <ol type="a">
                <li> Editing a question</li>
                <li> Editing a document</li>
            </ol>
        <li>Correcting a Test (editing result)</li>
        <li> Taking a Test
            <ol type="a">
                <li> Checking availability</li>
                <li>Reading instructions and test details and starting the test</li>
                <li> Answering the questions</li>
                <li>Checking the progress of the test and ending the test</li>
                <li>Checking Test results</li>
            </ol>
        <li>Credits</li>
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

    <h2 align="left">2. Installing the module</h2>

    <h3 align="left">2.a Where to download</h3>

    <p align="left">The first thing to do is download the module from one of the XOOPS support sites. <br><br>
    </p>

    <h3 align="left">2.b Dependencies:</h3>

    <p align="left"> There are no external dependencies
    </p>

    <h3 align="left">2.c Installation</h3>

    <p align="left">Place the assessment folder inside / modules /, the Frameworks folder (with a capital letter) at the root of the site and the xoopseditor folder inside / class /. Log in as an administrator on the site and go to the area reserved for the administrator and in the system admin module choose the modules option. <br>
        Two lists of modules will appear. The first is for modules already installed, the second is for modules not yet installed. It is in this second list that the assessment module will be. click the install button as shown below.<br><br>
        <img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install1.png" alt="Click on the install icon" width="459" height="233"><br><br></p>

    <p align="left">Now if everything went well a confirmation screen will appear on the screen looking like the image below:<br><br></p>
    <p>
    </p>


    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install2.png" alt="Confirmation of the desire to install this module" width="420" height="175"><br><br></p>

    <p align="left">Again if everything went well, a screen with the log of everything that was accomplished will be presented. Something like this:<br><br></p>

    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/install3.png" alt="Installation text log" width="400" height="180"><br><br><br>
        Your module has been successfully installed and you can go to create your first test or provide an example test on the capitals of the world that have already been installed.<br><br></p>

    <h2 align="left">3. Configuring for your needs</h2>

    <p align="left">Assessment allows you to adjust it for different purposes and according to these you must adjust some parameters. To change the parameters of the module simply go to the preferences menu of the assessment module. The page below will open. Configure it according to your needs.<br><br></p>

    <p align="left"><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/preferencias.png" alt="Preferences screen" width="816" height="490"><br><br></p>

    <p align="left">Item 1: In the question screen you have a navigation bar that allows you to navigate between the different questions in the exam by checking those that the student has already answered. How many items will appear in that list before breaking the line is defined in this item. This allows you to use Assessment in any resolution.</p>

    <p align="left">Item 2: The editor of the documents can be either a simple editor or it can be an elaborate editor that allows inserting even videos from youtube. I recommend that you use the koivi editor as this is recommended by the xoops.org team, but I also suggest that you try the mastoppublish that is attached in the extra folder because it has more features and is done by a Brazilian (topet05)</p>

    <p align="left">Item 3.  When the student finishes taking the test he may either have to wait for a green light from the teacher stating that the result is already out, or he may have the result immediately. Everything will depend on your business case. Sometimes it is interesting that the student does not know his grade until a specific date later, other times he needs to know it immediately. Let's see what's best for you..</p>

    <p align="left">Item 4. In the administration part there are several lists that display questions, documents, proofs and results. Here you define how many items should appear per page. I recommend the use of 5, because it doesn't weigh on the server.</p>

    <p align="left">Item 5. This is known to xoops users. Here you define whether or not you want students to be able to choose to be informed that the result of their test came out</p>

    <p align="left">This is also known to xoopers. In this configuration item you choose which events are enabled. In the case of our module so far only one event exists so it doesn't make much sense, but maybe in the next version.</p>

    <h2 align="left"> 4. Creating a Test </h2>

    <p>The first step to be taken to create an interaction between the student and the teacher is to create the test. Without a test created, there is no way for the student to have a result, which seems obvious. </p>

    <h3>4.a Registering the test data itself</h3>

    <p>To understand how the module works, some terms must be understood. The test consists of questions, documents, results and attributes. The first thing to do to create the test is to register its own attributes. See the registration screen and its explanation:<br><br></p>

    <p><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/telaprova1.png" alt="Main attributes registration screen" width="808" height="598"><br><br></p>

    <p>Item 1: A direct link to the student's part, where the student finds the Test.<br><br></p>

    <p>Item 2: A link to the current page where you create, edit, clone and delete tests, in addition to being able to consult the results of a given test.<br><br></p>

    <p>Item 3: A link to the screen that allows access to the results of all the confused tests.<br><br></p>

    <p>Item 4: A link to the screen that allows access to the documents of all the mixed evidence<br><br></p>

    <p>Item 5: A link to the settings page seen in topic 3 of this tutorial<br><br></p>

    <p>Item 6: A text that serves to place you in the amdinsitration part, it indicates where you are. (breadcrumps)<br><br></p>

    <p>Item 7:  Link to edit the test. In addition to editing the main attributes of the exam you have access to the screen to edit and register both documents and questions.<br><br></p>

    <p>Item 8: Link that allows you to "clone" a test. Cloning an exam means copying all the documents and all your questions in addition to the exam attributes. It should be noted that the assignment of documents to questions is not copied and must be performed manually.<br><br></p>

    <p>Item 9: Displays a screen with all the results of that particular test and some statistical data such as the highest score, average score ...<br><br></p>

    <p>Item 10: Delete the test. This operation requires confirmation as it deletes, in addition to the test attributes, all questions, answers, documents and results related to that test.<br><br></p>

    <p>Item 11: Title of the test. Field for defining a title for the race. ex: GEOGRAPHY TEST<br><br></p>

    <p>Item 12: Fields for registration of the race description. Perhaps information about the matter being checked.<br><br></p>

    <p>Item 13: Field for registering test instructions. I suggest that you put all the rules here (test time, whether it is with or without consultation etc ...)<br><br></p>

    <p>Item 14: Test time in seconds. After the student starts the exam, how much time does he have before she closes herself?<br><br></p>

    <p>Items 15 and 16:  These fields allow you to define the day and time for the start and end of the test availability. Before the student is unable to take the test and then neither. Click the button to see the calendar.<br><br></p>

    <p>Item 17: Groups that can take the test. While preparing the test do not place any groups or change the previous option so that the test is not available to the student. The risk is that the student can take the test before it is ready. <br><br></p>

    <p>Item 18: Button to send the data of the attributes of the tests. <br><br></p>

    <h3>4.b Registering the Test questions </h3>

    <p>After you have registered the basic exam data, you can register the exam questions. For this, on the screen that opens after sending the data of the test attributes (you can also reach this window through the item edit test in the window explained in the previous step) Click on the link for question registration or scroll the scroll bar on the side of your browser to the question registration form. This form has 7 fields as shown in the figure below. Fill them out and send your question. Repeat the operation as many times as necessary. Here I think that it is only worth highlighting the order field, which is important in the sense that it allows defining the order in which the questions will be presented in the test. <br><br></p>

    <h3>4.c Registering the Test documents</h3>

    <p>The documents that you will register in the module should serve as a reference for some questions. Questions, for example of text interpretation, require such functionality. To access the form for sending this information, click on send documents or scroll to the right of your browser to find it. The form displays in addition to its basic fields (details below) a field where you can choose with the left mouse button and the ctrl key pressin at the same time the questions that will present this question before. <br><br><br><br></p>

    <p><img src="<{$xoops_url}>/modules/<{$smarty.const._MI_ASSESSMENT_DIRNAME}>/language/<{$smarty.const._MI_ASSESSMENT_LANGUAGE}>/help/telaprova2.png" alt="Test editing window" width="813" height="2193"><br><br><br><br></p>

    <p>Itens 1 to 6: See topic 4.a of this tutorial.<br><br></p>

    <p>Item 7: Link to question registration form tab <br><br></p>

    <p>Item 8: Link to the document registration form tab<br><br></p>

    <p>Item 9: Form to edit basic attribute data, questions refer to topic 4.a<br><br></p>

    <p>Item 10: List of questions for the test displayed <br><br></p>

    <p>Item 11: Link to be able to edit question data<br><br></p>

    <p>Item 12:  Link to exclude a question from the exam (associated answers are also excluded)<br><br></p>

    <p>Items 13,14,15: Similar to items 10,11,12 only for documents<br><br></p>

    <p>Item 16: Question navigation bar<br><br></p>

    <p>Item 17: There is no such item :D<br><br></p>

    <p>Item 18: Field for ordering the question. This field must be an integer. The lower the question will come in the test and the higher, the later the question will come in the test.<br><br></p>

    <p>Item 19: Question title field: ex: What is the capital of Italy<br><br></p>

    <p>Item 20: Correct answer<br><br></p>

    <p>Item 21: Incorrect answers<br><br></p>

    <p>Item 22: Button to register the question<br><br></p>

    <p>Item 23: Document title<br><br></p>

    <p>Item 24: toolbar of the text editor<br><br></p>

    <p>Item 25: Text of the test. You can copy the text from the word or from the internet and paste here in this editor that it expects the formatting<br><br></p>

    <p>Item 26: If it is an internet text the source of the text<br><br></p>

    <p>Item 27: What questions should the text of the document present before them? Use ctrl while clicking on questions to choose more than one. To deselect a click again (always with the ctrl pressed at the same time) <br><br></p>

    <p>Item 28: Button to register the document <br><br></p>

    <h3> 4.d Making test available to users</h3>

    <p>Now just remember to update the test's attributes to release it to users. Set the date and or the groups and that's it!<br><br></p>

    <p>5 continues in another version ....<br><br></p>

    <h3>8 Credits<br><br></h3>

    <p>The credits for this tutorial go to Marcello Brandão. <br><br></p>

    <p>&nbsp;</p>

    <p align="left"><br>
    </p>

    <p align="left"><br>
    </p>

</div>
