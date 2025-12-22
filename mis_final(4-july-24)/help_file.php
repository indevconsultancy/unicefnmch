<?php include_once('includes/config.php'); ?>
<?php define("title", "Help File | MQUAD"); ?>
<?php include_once('includes/header.php'); ?>
<?php include_once('includes/left-sidebar.php'); ?>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="css/help.css" />
</head>

<section id="main-content">
    <div class="wrapper">
        <div class="userGuideWrapper">
            <div class="userGuideMain">
                <div class="userGuideNav">
                    <div>
                        <ul id="right-nav">
                            <li> <a href="#section1" data-target="section1"> MQUAD </a>
                            </li>
                            <li>
                                <a href="#section2" data-target="section2">Introduction </a>
                            </li>
                            <li>
                                <a href="#section3" data-target="section3">Data Collection Form using Microsoft Excel </a>

                            </li>
                            <li>
                                <a href="#section4" data-target="section4">Survey Sheet</a>

                            </li>

                            <li>
                                <a href="#section5" data-target="section5">Choices Sheet</a>

                            </li>
                            <li>
                                <button data-toggle="collapse" data-target="#CodingAndExcerptingSubMenu" class="collapsed">Lookups Sheet</button>
                                <div id="CodingAndExcerptingSubMenu" class="collapse">
                                    <ul>
                                        <li>
                                            <a href="#section6" data-target="section6">Purpose of the lookup sheet</a>
                                        </li>
                                        <li>
                                            <a href="#section7" data-target="section7">How to Use</a>
                                        </li>
                                        <li>
                                            <a href="#section8" data-target="section8">Example</a>
                                        </li>

                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#section9" data-target="section9">Type</a>
                            </li>
                        </ul>
                        <div class="text-center">
                            <p>Download help file for creating form </p>
                            <a href="Questionnaire-sample.xlsx" class="btn btn-primary" style="margin-bottom: 10px;"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
                        </div>
                    </div>

                </div>

                <div class="userGuideContentArea">
                    <div class="header main-top">
                        <div class="headerImage">
                        </div>
                        <div class="headerTitle">
                            <h2>A Guide to Create Data Collection Form</h2>
                        </div>

                    </div>
                    <div>
                        <div class="userGuideArticle">
                            <a id="becoming_familiar_with_Dedoose_ug"></a>
                            <div id="section1" class="container-fluid">

                                <h3>Becoming Familiar with MQUAD</h3>

                                <p>MQUAD is a free, effortless, and reliable web interface, wherein one can conduct or organize surveys in an easy manner and yet gain accurate data. An efficient mobile-based application, that lets research organizations, primarily working in development sectors be it the health of a particular bunch of people, nutrition tracking of a certain bunch, governance, or the status of education in a particular area or district,MQUAD is authentic when it comes to conducting surveys & collecting efficient data through electronic means and thus, presenting it using custom statistical widgets with ease. </p>
                            </div>
                            <div id="section2" class="container-fluid">

                                <h3>About MQUAD</h3>

                                <p>MQUAD is a reliable web interface. It facilitates to conduct or organize surveys with ease and collect accurate data. It allows research organizations, primarily working in development sector (health, nutrition, education, etc.) to conduct surveys and collect data through electronic means. Furthermore, it presents data by using custom statistical widgets. </p>
                                <p>It aims to digitize the process of surveys without compromising security and privacy. It assists in customizable dashboard making module to manage the cases effectively. Interestingly, MQUAD works remotely and internationally as well. </p>
                                <p>MQUAD strengthens monitoring anywhere and everywhere it goes. It is a cloud based and knowledge driven system. </p>
                                <h4>Features of MQUAD</h4>
                                <ul class="disk-bullet">
                                    <li>MQUAD provides comprehensive solutions to store, exchange and access tools, questionnaires and data collected as part of MQUAD or otherwise.</li>
                                    <li>Advanced form management.</li>
                                    <li>It implements algorithm to draw samples without storing data.</li>
                                    <li>It provides a range of data quality parameters including time, GPS, key store, etc.</li>
                                    <li>It is user friendly. It is menu driven, easy to locate functions, rich help files and samples of the excel forms.</li>
                                    <li>It allows data export into multiple forms such as excel, STATA, SPSS and JSON.</li>
                                    <li>It offers support services such as response within 12 hours, demo videos and MQUAD community.</li>
                                </ul>
                            </div>

                            <div id="section3" class="container-fluid">
                                <h3>Data collection form using excel</h3>

                                <p> Steps to design an excel form to upload it on the MQUAD data tool website (https://.mquad.org/) for survey generation:

                                    A basic understanding of excel as well as a good understanding of designing a survey questionnaire is required.

                                    Download sample excel file from the MQUAD website (https://mquad.org/), the sheets are as follows:

                                    Survey-This sheet comprises all the survey questions along with their corresponding type, label, restrictions, coding, and other instructions which are to be interpreted by the MQUAD server, once uploaded.

                                    Choices- This sheet lists all the choices related to select one and select multiple questions written on the survey sheet.

                                    Lookups- This sheet is not necessary for every type of survey. It consists of the collection of datasets to be used in the survey sheet for auto - populated data in the field according to the search parameter.

                                </p>
                                <p>A basic understanding of excel as well as a good understanding of designing a survey questionnaire is required.</p>
                                <h5><strong>Download sample excel file from the MQUAD website (https://mquad.org/), the sheets are as follows:</strong></h5>
                                <ul>
                                    <li><strong>Survey-</strong>This sheet comprises all the survey questions along with their corresponding type, label, restrictions, coding, and other instructions which are to be interpreted by the MQUAD server, once uploaded.</li>
                                </ul>
                                <ul>
                                    <li><strong>Choices-</strong> This sheet lists all the choices related to select one and select multiple questions written on the survey sheet.</li>
                                </ul>
                                <ul>
                                    <li><strong>Lookups- </strong>This sheet is not necessary for every type of survey. It consists of the collection of datasets to be used in the survey sheet for auto - populated data in the field according to the search parameter. </li>
                                </ul>
                                <p><img src="img/help_file/datacollection.png" /></p>

                            </div>

                            <div id="section4" class="container-fluid">
                                <h3>Survey Sheet</h3>

                                <p>Fill the survey sheet with the questions. To start with, type in the column headers, the following keywords:</p>
                                <div class="table-responsive ">
                                    <table>
                                        <tr>
                                            <th>Type</th>
                                            <th>choice_relation</th>
                                            <th>name</th>
                                            <th>lookups</th>
                                            <th>label</th>
                                            <th>preserve</th>
                                            <th>unique_id</th>
                                            <th>default_response</th>


                                        </tr>
                                    </table>
                                    <table>
                                        <tr>

                                            <th>hint</th>
                                            <th>limit</th>
                                            <th>constraint</th>
                                            <th>constraint_message</th>
                                            <th>required</th>
                                            <th>constraint</th>
                                            <th>paradata</th>


                                        </tr>
                                    </table>
                                    <table>
                                        <tr>
                                            <th>appearance</th>
                                            <th>choice_filter</th>
                                            <th>relevant</th>
                                            <th>parameters</th>
                                            <th>repeat_count</th>
                                            <th>read_on</th>
                                            <th>calculation</th>
                                        </tr>
                                    </table>
                                </div>

                                <p><img src="img/help_file/surveysheet.png" /></p>
                                <div class="table1 table-responsive">
                                    <table>
                                        <tr>
                                            <th>Header</th>
                                            <th>Explanation</th>
                                        </tr>
                                        <tr>
                                            <td><strong>Type*</strong></td>
                                            <td>All questions should be categorized in their respective type for MQUAD to recognize them. The <strong>types</strong> column include free text questions, single/ multiple options , number, date, time ,camera, begin repeat, end repeat, begin group, end group, calculate, audio, video, hidden, note, GPS- button, photos and geographical locations.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Choice Relation*</b></td>
                                            <td>For <strong>select one </strong>and <strong>select multiple</strong> type questions, write the <b>list name</b> from the <b>choices sheet</b> for available options to the questions.
                                                All elements under this column must be same as the list name of choices field.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Name</b></td>
                                            <td>Elements in this column are the headers for the responses. The names should be related to the questions. All elements under this column must be unique. It should not contain any spaces. The names can only be in letters, numbers with or without underscore. For example ‘A102’, ‘respondent_name’, ‘district’,'q_1' ,etc.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Lookups</b></td>
                                            <td>Elements in this section are the name of the columns from lookups sheet. Here, value is searched and auto filled for any selective row and the specific column as per the given condition in the <b>lookups sheet</b>.</td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="table2 table-responsive">
                                    <table>
                                        <tr>
                                            <th>Header</th>
                                            <th>Explanation</th>
                                        </tr>
                                        <tr>
                                            <td><b>Label</b></td>
                                            <td>All elements under this column will be shown in the survey form. Basically, this is how the survey questions will appear once the form is successfully deployed. There is no specific format on how you should type in the questions here. You are free to type in your intended questions.

                                                <p><b>For example:</b> What is your name? Date today?, Which of the following is/ are true. </p>

                                                You can use the html tag to control the style of text, font, size, colour and background of the area.

                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Preserve</b></td>
                                            <td>Elements in this column are fixed i.e. <b>"yes"</b> where it is required or left blank if not required. This field is to be used for specific condition where you want to repeat the same value across the next survey.

                                                <p><b>For example:</b> if there is a question "Enter Village Name." The survey is being conducted in the same village for the entire day. Write "Yes" under preserve column.</p>

                                                This field remains editable. It shows the previous response given for the question.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Unique ID</b></td>
                                            <td>You can create a <b>unique ID</b> for each survey linked to the responses along with multiple input fields. Write <b>"yes"</b> to all of those questions which are used as a combination.

                                                <p><b> For example:</b> If you want to create a unique id with combination of state code, district code, village code, house no. Write "yes" in this column for all the above questions. Unique id is the reserve keyword for MQUAD application. </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Default Response</b></td>
                                            <td>Elements in this column are the default response to any question.

                                                <p><b>For example:</b> </br>
                                                    if you want to set the interview type as "household survey" in the data under question. You can set "household survey" in the default response column for the question, interview type. It is editable.</br>
                                                    <b>(type=date,default_response=nowdate):</b> If you select nowdate img/Faq_img/ you can see current date.</br>
                                                    <b>(default_response=#name):</b> If you select #name img/Faq_img/ you can see autofill interviewer name</br>
                                                </p>
                                                <p><img src="img/help_file/default_response.png"></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table3 table-responsive">
                                    <table>
                                        <tr>
                                            <th><b>Header</b></th>
                                            <th><b>Explanation</b></th>
                                        </tr>
                                        <tr>
                                            <td><b>Hint</b></td>
                                            <td>Elements in this column are the hints to describe question and provide some instruction about the question.

                                                <p><b> For example:</b> if you have a question "how old were you on your last birthday?" use hints "age of person in years" or question "enter your age" and hints "age bracket should consist of18 to 70 years"</p>

                                                Any additional comments that can help to explain how the questions should be answered can be added here. It will appear in the pop-up. Click on ‘i’ icon, right to the question text.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Limit</b></td>
                                            <td>Elements in this column are mandatory and numbers only. The value represents the number of responses allowed. .

                                                For example, <br>
                                                <b> select one</b> type question-
                                                Question: “Select Gender.<br>
                                                Options : 1-Male, 2-Female, 3-Other<br>
                                                So the limit should be written "1" for this question<br>
                                                <b>select multiple</b> type question-<br>
                                                <b>Question:</b> What skills do you have? <br>
                                                <b>Options:</b> A-Designing B-Singing C- Arts and craft D-Dancing<br>

                                                So the limit should be written maximum "4" for this question because there can be upto 4 responses.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Constraint</b></td>
                                            <td>If you want to set any condition/restriction for certain questions, here, you can type those constraints. <br>
                                                <b>For example:</b> The number of days in a month should never exceed 31 and should not be lower than 0. The command will be >=0 and <=31.< /br>
                                                    <b>(type=date,constraint=currdate):</b> If you select currdate img/Faq_img/ you can see after current date.</br>
                                                    <b>(type=date,constraint=futuredate):</b> If you select futuredate img/Faq_img/ you can see before current date.</br>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Constraint Message</b></td>
                                            <td>If any value outside the defined range is provided in the constraint column, a constraint message will pop up. You can specify what error messages you want to show under the <b>constraint message</b> column.

                                                <p><b>For Example:</b> if you put the constraint " >=18 | <75" for the question "Age" so the message for the values outside the range should be "Age Should be greater than 18 Years and less than 75" </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Required</b></td>
                                            <td>Elements in this column are fixed. Write <b>"yes”</b></75>, if you want to make the question mandatory.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Para Data</b></td>
                                            <td>Elements in this column are optional though very useful to track the interviewer’s activity by capturing response against each question. This field enables to capture <b>Timestamp (T),</b> <b>GPS (G),</b> <b>Audio (A)</b> and <b>Keystroke (K) </b>for any specific question.

                                                <p>For the use of this field define, short code for all indicators:</p>

                                                If you want to capture Timestamp and GPS, Write "T, G "
                                                If you want to capture Timestamp, GPS and Keystroke, than Write "T, G, K" and "A" to capture the hidden audio for the same.
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table4 table-responsive">
                                    <table>
                                        <tr>
                                            <th><b>Header</b></th>
                                            <th><b>Explanation</b></th>
                                        </tr>
                                        <tr>
                                            <td><b>Appearance</b></td>
                                            <td>This column enables you to determine how your questions will appear on mobile devices. There are multiple commands available to visualize the questions.

                                                <p><b>For example:</b><br>

                                                    If you want to collect some description in the text, it requires multiple lines</br>
                                                    <b>(type= select one, appearance=none):</b> if left blank, option displays as <b>radio type</b></br>
                                                    <b>(type= select one, appearance=dropdown):</b> if not left blank, img/Faq_img/, option display as a <b>dropdown list</b></br>
                                                    <b>(type= begin group, appearance= field list):</b> img/Faq_img/ multiple questions will be displayed on screen</br>
                                                    <b>(type= select one, appearance= multimedia):</b> img/Faq_img/, the image will be displayed as an option
                                                </p></br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Choice Filter</b></td>
                                            <td> The names should be related to the questions. All elements under this column must be unique. It should not contain any space. The names can only be in letters, numbers with or without underscore. For example, ‘A102’, ‘respondent name’, ‘district’,'q_1' etc.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Relevant</b></td>
                                            <td>Elements in this column control the flow of the survey and guide the appearance of the next question. The names should be related to the questions and the comparison value should be the response of the specific question.

                                                It must contain <b>question name,</b> mathematical operator and the response value for creating img/Faq_img/. Multiple condition can be applied through <b>OR, I</b> and <b>&</b> operator.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Parameters</b></td>
                                            <td>Reserved for Feature and Not Yet Applicable</td>
                                        </tr>
                                        <tr>
                                            <td><b>Repeat Count</b></td>
                                            <td>You can fix the number of times the group questions can be repeated.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Read Only</b></td>
                                            <td>This column is used for fixed response. It is not editable if marked "Yes". This is a little bit different from default response where user is allowed to edit this response.

                                                <p><b> For example:</b> if you want to set total number of members in the household by calculation of the number of response of male and female members of the family. Write <b>Yes</b> under the question of total members.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table5 table-responsive">
                                    <table>
                                        <tr>
                                            <th>Header</th>
                                            <th>Explanation</th>
                                        </tr>
                                        <tr>
                                            <td><b>Calculation</b></td>
                                            <td>If any question requires any mathematical operation or any other calculation in the response of a question, MQUAD provides an option to calculate. Under this column, you can type calculation steps that are available within MQUAD.

                                                <p><b>For example:</b> if one wants to calculate the total number of family members by calculating total number of males and females. You can calculate the column and write the name of the question with "+" operator. You can put read-only for calculated total members.</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Media File</b></td>
                                            <td>Elements in this column are the names with extension of the media files such as photos, audio & videos that appear on the question label. With the help of this column, one may create the image based questions.</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                            <div id="section5" class="container-fluid">
                                <h3>Choices Sheet</h3>
                                <p>While filling up the survey sheet with the questions, you will also need to fill up the choices sheet. In the choices sheet, there are three compulsory column headers: list name, name, and label.</p>
                                <p><img src="img/help_file/choisessheet.png" /></p>
                                <div class="table6 table-responsive">
                                    <table>
                                        <tr>
                                            <th>Header</th>
                                            <th>Explanation</th>
                                        </tr>
                                        <tr>
                                            <td><b>List Name</b></td>
                                            <td>The elements in this column should be referred to the name you type in the select one/ multiple of the survey sheet. It will be repeated as many times as options that you provide for that question.

                                                <p><b>For example:</b> when referring to image 1, you can see in row 18, column A, it is written as select multiple "q_7" Preferable time to call. It means respondents can choose to tick multiple options related to "q_7". These options as listed in image 2 are morning, noon, afternoon, evening, none of the above with list name as "q_7".</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Value</b></td>
                                            <td>This is similar to your Name column from the survey sheet. This must be unique for each question. It is related to your choices Labels. You can use letters, numbers and/or underscore for the names of the choices.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Label</b></td>
                                            <td>Same like in the survey sheet, all elements typed in this column will appear on the devices.</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table7 table-responsive">
                                    <table>
                                        <tr>
                                            <th>Header</th>
                                            <th>Explanation</th>
                                        </tr>
                                        <tr>
                                            <td><b>Choice Filter Parent</b></td>
                                            <td>This field indicates the information about dependent options of other question’s response.

                                                For example, If you want to categorize the city name as urban/rural under any dependent district. You can put "Urban" or "Rural" according to the district.</td>
                                        </tr>
                                        <tr>
                                            <td><b>Media File</b></td>
                                            <td>Elements in this column are the names with extension of the images that appear on question label. With the help of this column, one can create image-based options. Another task to be done at the Survey Sheet for the same question is to write "Multimedia" in the appearance column.

                                                <p><b>For Example:</b> See the <b>image 2</b> for "q_4" 2 options <b>Yes</b> and <b>No</b> appear with Image named "Yes.png”, No.png".
                                                <p>

                                                    Media files need to be uploaded from another way as described below.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Constraint</b></td>
                                            <td>It is similar to the <b>survey sheet</b> where <b>options </b>will be controlled through mathematical operator with option name.</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div id="section6" class="container-fluid">
                                <h3>Lookups Sheet </h3>
                                <p>This is the optional sheet which holds the data file where link to the Survey Sheet is required.</p>
                                <p><img src="img/help_file/lookup1.png" /></p>
                                <p><img src="img/help_file/lookup2.png" /></p>
                                <h4>Purpose of the lookups sheet</h4>
                                <ol>
                                    <li>It is used as selected sample survey.</li>
                                    <li>It converges data from other sources using unique identifiers.</li>
                                    <li>It lists out the targeted survey with the help of the filtered column.</li>
                                </ol>
                            </div>
                            <div id="section7" class="container-fluid">
                                <h4>How to Use</h4>
                                <p>Usage of this sheet depends on your data structure and the data filter through columns. Column name should be of the same name as the question name where the responses will be filtered. Data will be auto filled in the question responses from the selected rows of lookups file while using single/multiple situations. </p>
                            </div>
                            <div id="section8" class="container-fluid">
                                <h4>Example</h4><br>
                                <p>In case, you want to fill the data of the household’s basic information in the current survey from the existing baseline survey. Then, put the baseline survey data in the lookups sheet, rename the column as same as the question’s name to auto fill the response. .
                                    In the image- 4, row no 5, 6 & 7, auto fill using the search of PSU number and the house number through unique identifier of row number 4 i.e., <b>“sample fsu number”</b> .
                                    Sample fsu number in the survey sheet marked as <b>“Yes”</b> under lookups column and the same column name of sample fsu number available in the lookups sheet. If the number is matched img/Faq_img/, the filtered row is selected and auto fill the question’s response in row 5, 6 & 7 through the column data of residence, village name & fsu number.</p>
                            </div>


                            <div id="section9" class="container-fluid">
                                <h3>Type</h3>
                                <p>The user needs to select their question type from the drop-down list by clicking on “select questions type”.</p>
                                <p>The question types are available in the following formats for different type of survey.</p>
                                <table>

                                    <tr>
                                        <td><b>Text</b></td>
                                        <td>If the user uses text type question, img/Faq_img/ question will display in text type which will be open field.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Number</b></td>
                                        <td>If the user uses number type, img/Faq_img/ question will be displayed in the number type.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Date</b></td>
                                        <td>If the user uses date type, img/Faq_img/ question will be displayed in the date type question.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Time</b></td>
                                        <td>To create a time question, use time type.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Date time</b></td>
                                        <td>To create a date-time question use date-time type.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Camera</b></td>
                                        <td>If the user uses camera type img/Faq_img/, question will be displayed in the image type.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Select one</b></td>
                                        <td>If the user uses select one and appearance in blank, img/Faq_img/, the option will be displayed in radio button.
                                            If the user uses select one and appearance in dropdown, img/Faq_img/, the option will be displayed in dropdown list.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Select multiple</b></td>
                                        <td>If the user uses select multiple type, the option will be displayed in the checkbox.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Begin repeat & end repeat</b></td>
                                        <td>If the user chooses begin repeat & end repeat type question, img/Faq_img/ you can make any question as a repeated question.</td>
                                    </tr>
                                    <tr>
                                        <td><b>Begin group & End group</b></td>
                                        <td>If the user uses begin group & end group type, img/Faq_img/, you can make any question as a group question.</td>
                                    </tr>

                                    </tr>
                                </table>
                                <p><img src="img/help_file/type1.png" /></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<?php include_once('includes/footer.php'); ?>
<!-- <script src="js/jquery.js"></script>
<script src="js/jquery-ui-1.10.4.min.js"></script>
<script src="js/bootstrap.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.3/jquery.scrollTo.min.js"></script> -->
<!-- nice scroll -->
<!-- <script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jquery.nicescroll.js" type="text/javascript"></script> -->
<!-- <script src="js/scripts.js"></script> -->
<script>
    $(document).ready(function() {
        scrollTo();
        scrollToTop();

        function scrollTo() {
            $('#right-nav li > a').click(function(e) {
                e.preventDefault();
                $('#right-nav li > a').removeClass('active');
                $(this).addClass('active');
                var distanceTopToSection = $('#' + $(this).data('target')).offset().top - 70;
                $('body, html').animate({
                    scrollTop: distanceTopToSection
                }, 'slow');
            });
        }

        function scrollToTop() {
            var backToTop = $('.backToTop');
            var showBackTotop = $(window).height();
            backToTop.hide();

            var children = $(".mainMenu li").children();
            var tab = [];
            for (var i = 0; i < children.length; i++) {
                console.log(children[i]);
                var child = children[i];
                var ahref = $(child).attr('href');
                console.log(ahref);
                tab.push(ahref);
            }
        }
    });
</script>
</body>

</html>