<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="jquery.min.js"></script>
  </head>
  <body>
    <script src="d3.v3.min.js" charset="utf-8"></script>
    <script src="jquery-1.11.0.js"></script>
    <script type="text/javascript">

      // Prevent window close
      var hook = true;
      window.onbeforeunload = function() {
        if (hook) {
          return "Are you sure that you want to end this survey? All of your answers will be lost.";
        }
      }
      function unhook() {
        hook=false;
      }
      var bodyHeightone = $(document).height() - 20;
      if (bodyHeightone < 2600) bodyHeightone = 3000;
      var bodyWidth = $(document).width();
      var bodyHeight = $(document).height() - 20;
      if (bodyWidth < 800) bodyWidth = 800;
      if (bodyHeight < 2500) bodyHeight = 750;
      var center = bodyWidth / 2;
      var middle = bodyHeight / 200;

      var textWidth = 800;
      var text_offset_top = 60;
      var title_offset_top = 70;
      var lineHeight = 18;

      var q_window_width = 100,
          q_window_height = 100,
          backdrop_width = 500;

      // left and top values for individual questions
      var question_lnum = center - (textWidth / 2);
      var string_l = question_lnum.toString();
      var string_t = "200px";
      var string_r_t = "45%",
          q_margin_top = 200,
          q_margin_top_str = q_margin_top.toString();

      // bar with boxes for answers
      var boxbar_margin = 10,
          boxbar_label_margin = 3,
          bar_target_height = 100,
          bar_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar4_target_width = ((bodyWidth - (boxbar_margin * 3) - 20) / 4),
          bar5_target_width = ((bodyWidth - (boxbar_margin * 4) - 20) / 5),
          bar6_target_width = ((bodyWidth - (boxbar_margin * 5) - 20) / 6),
          bar_label_height = 25,
          boxbar_offset_x = 10,
          boxbar_offset_y = bodyHeight - bar_target_height - 100;

      var currSlide = 1;
      var numFriends = 0;
      var askedAbout = 0;
      var numAsked = 1;
      var lastAnswered = 0;
      var numOther = 0;
      var checked = false;
      var skipped = false;
      var currNode = null;
      var nodeColor = '#9CD4D4',
          femaleColor = '#FFCCFF';

      var startTime;
      var results = [];

      //--------------------------------
      // Declaration of graph properties
      //--------------------------------

    var bodyselection = d3.select("body");

    var svg = d3.select("body").append("svg")
      .attr("width", bodyWidth)
      .attr("height", bodyHeight)
      .attr("height", bodyHeightone)
      .on("contextmenu", function() {d3.event.preventDefault()});




      var force = d3.layout.force()
        .size([bodyWidth, bodyHeight])
        .nodes([{x:bodyWidth / 2,
                  y:bodyHeight / 2.2,
                  fixed: true,
                  name:"You",
                  id:0,
                  gender:"",
                  age:"",
                  code:"",
                  race:"",
                  religion:"",
                  surveyTime:0,
                  sawStats:false,
                  edu:null,
                  freq:null,
                  enjoy:"",
                  like:"",
                  interest:"",
                  motivation:""}]) // initialize with a single node
        .linkDistance(100)
        .charge(-1500)
        .on("tick", tick);

      var nodes = force.nodes(),
          links = force.links(),
          node = svg.selectAll(".node"),
          link = svg.selectAll(".link");

      //--------------------------------
      // Declaration of slides and boxes
      //--------------------------------

      // Slide 0

      // Catch Internet Explorer users; incompatible browser
      if (isIE()) {
        var slide_0 = d3.select("svg").append("g")
          .attr("id", "slide0");
        slide_0.append("rect")
          .style("fill", "white")
          .attr("x", 0)
          .attr("y", 0)
          .attr("width", bodyWidth)
          .attr("height", bodyHeight);
        slide_0.append("text")
          .attr("class", "lead")
          .text("Your browser is not supported.")
          .attr("x", center - 170)
          .attr("y", title_offset_top);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight)
          .text("Please use a different browser for this survey.")
          .call(wrap, textWidth);
        document.getElementById("Next").style.display="none";
      } else {
        var slide_0 = d3.select("svg").append("g")
          .attr("id", "slide0");
        slide_0.append("rect")
          .style("fill", "white")
          .attr("x", 0)
          .attr("y", 0)
          .attr("width", bodyWidth)
          .attr("height", bodyHeight);
        slide_0.append("text")
          .attr("class", "lead")
          .text("")
          .attr("x", center - 170)
          .attr("y", title_offset_top);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight)
          .text("This is a survey about how social networks are influenced by extroversion and loneliness.")
          .call(wrap, textWidth);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 4)
          .text("It is not possible to move back to an earlier question.")
          .call(wrap, textWidth);
        slide_0.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 8)
          .text("Completing this survey takes 10 to 15 minutes. Please make sure you read the questions carefully and to not leave the page before all questions have been answered.")
          .call(wrap, textWidth);
        }
      var slide_1 = d3.select("svg").append("g")
        .attr("id", "slide1")
      slide_1.append("rect")
        .style("fill", "white")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height",  bodyHeightone );
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight)
        .text("Please read the following slide before continuing. Clicking Next demonstrates you have read and understood the information below.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 4)
        .text("PARTICIPANT INFORMATION.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 8)
        .text("You are being invited to take part in this research study.  Before you decide it is important for you to read this slide so you understand why the study is being carried out and what it will involve.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 3)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 12)
        .text("What is the purpose of this study?")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 16)
        .text("The aim of this research is to understand how extroversion-introversion and loneliness are associated with network layer size and emotional closeness to network members.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 3)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 20)
        .text("Why have I been invited?")
       .call(wrap, textWidth);
     slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 24)
        .text("It is important that we assess as many people as possible and you have indicated that you are interested in taking part in this study, and that you are an adult aged 18 or over and have a reasonable understanding of English.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 3)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 28)
        .text("Do I have to take apart?")
       .call(wrap, textWidth);
      slide_1.append("text")
       .attr("class", "slideText")
       .attr("x", center - textWidth / 2)
       .attr("y", text_offset_top + title_offset_top + lineHeight * 32)
       .text("No. It is up to you whether you would like to take part in the study.  I am giving you this information sheet to help you make that decision.  If you do decide to take part, remember that you can stop being involved in the study whenever you choose, without telling me why.  You are completely free to decide whether or not to take part, or to take part and then leave the study before completion.  If you no longer want to take part, simply close your browser window")
       .call(wrap, textWidth);
      slide_1.append("text")
       .attr("class", "slideText")
       .attr("x", center - textWidth / 3)
       .attr("y", text_offset_top + title_offset_top + lineHeight * 40)
       .text("What will happen if I take part?")
       .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 44)
      .text("You will be asked complete a survey that will last 20-30 minutes which consists of questions about your social network and your perceived emotional closeness to network members. You will also be asked to rate yourself on your personality, specifically how you rate in extroversion. And then finally you will be asked to rate yourself on several statements related to loneliness.")
      .call(wrap, textWidth);
     slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 50)
      .text("What are the possible disadvantages of taking part?")
      .call(wrap, textWidth);
     slide_1.append("text")
     .attr("class", "slideText")
     .attr("x", center - textWidth / 2)
     .attr("y", text_offset_top + title_offset_top + lineHeight * 54)
     .text("Considering the nature of this research, participants may find questions about loneliness disconcerting and may feel uncomfortable answering such questions. However, the research will not gather and personal information about you such as your name, your email address or your IP. Any information given about your social network members will be anonymised during analysis and into randomly generated number codes. However, if you still feel uncomfortable about answering some of fields on this survey you are free to skip them or stop the survey at any time (simply close the browser). If you have completed the research already but have changed your mind on whether you wish to have your information used, you may send the code you inputted at the start of the survey to the researcher (email below) so you can withdraw your information. ")
     .call(wrap, textWidth);
     slide_1.append("text")
     .attr("class", "slideText")
     .attr("x", center - textWidth / 3)
     .attr("y", text_offset_top + title_offset_top + lineHeight * 64)
     .text("Will my taking part in this study be kept confidential and anonymous?")
     .call(wrap, textWidth);
     slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 68)
      .text("Yes.  Your name will not be written on any of the data we collect; the written information you provide will be related to your code name you inputted at the beginning of the survey, not your name. Your name or the names you input in the social networks you describe will not appear in any reports or documents resulting from this study. The data collected from you in this study will be confidential.  Anonymised data from this study will be posted on the open science framework (http://osf.io) in line with current scientific practice. ")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 74)
      .text("How will my data be stored, and how long will it be stored for?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 78)
      .text("All data from the survey will be stored on the University U drive, which is password protected.  All data will be stored in accordance with University guidelines and the Data Protection Act (2018).  No personally identifiable data will be stored. After removing the information on your network members, the data will also be available on the open science framework (http://osf.io) however all this data will be anonymous. Should you not want your anonymized data to become public, then do not take part.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 86)
      .text("What categories of personal data will be collected and processed in this study?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 90)
      .text("This research will be collection your age and gender. However, it will not gather any other personal information aside from information about your social network members that will be anonymised prior to analysis.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 94)
      .text("What is the legal basis for processing personal data?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 98)
      .text("All data collected in this study are processed in line with GDPR Article 6(1) e: processing is necessary for the performance of a task carried out in the public interest.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 102)
      .text("Who are the recipients or categories of recipients of personal data, if any?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 106)
      .text("The researchers Connor Malcolm and Dr. Thomas Pollet at Northumbria university. No other researchers will be involved in the handling of the raw data.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 110)
      .text("What will happen to the results of the study and could personal data collected be used in future research?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 114)
      .text("The general findings might be reported in a scientific journal or presented at a research conference, however the data will be anonymized and you will not be personally identifiable. We can provide you with a summary of the findings from the study if you email the researcher at the address listed below. Anonymized data will be posted on the open science framework in order to comply with the norm of transparency in research. Researchers and academics will have access to this research data through the open science framework (http://osf.io). Should you not want your anonymized data to be shared, then please do not take part.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 122)
      .text("Who is Organizing and Funding the Study?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 126)
      .text("Northumbria University health and Life Sciences department")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 130)
      .text("Who has reviewed this study?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 134)
      .text("The Faculty of Health and Life Sciences Research Ethics Committee at Northumbria University have reviewed the study in order to safeguard your interests and have granted approval to conduct the study.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 138)
      .text("What are my rights as a participant in this study?")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 142)
      .text("You have the rights to the right to be informed, the right of access, the right to rectification, the right to erasure, the right to restrict processing, the right to data portability, the right to object and rights in relation to automated decision making and profiling. You have a right of access to a copy of the information comprised in your personal data (to do so you should submit a Subject Access Request); you have a right in certain circumstances to have inaccurate personal data rectified; and a right to object to decisions being taken by automated means. If dissatisfied with the University’s processing of personal data, you have the right to complain to the Information Commissioner’s Office")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 3)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 152)
      .text("Contact for further information")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 154)
      .text("Researcher email: connor.malcolm@northumbria.ac.uk.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 156)
      .text("Supervisor email: thomas.pollet@northumbria.ac.uk.")
      .call(wrap, textWidth);
      slide_1.append("text")
      .attr("class", "slideText")
      .attr("x", center - textWidth / 2)
      .attr("y", text_offset_top + title_offset_top + lineHeight * 158)
      .text("Name and contact details of the Data Protection Officer at Northumbria University: Duncan James (dp.officer@northumbria.ac.uk).")
      .call(wrap, textWidth);
      slide_1.style("display", "none");

      var slide_2 = d3.select("svg").append("g")
        .attr("id", "slide2");
      slide_2.append("rect")
        .style("fill", "white")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth )
        .attr("height", bodyHeight);
      slide_2.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight)
        .text("If you would like to take part in this study, please read the statement below and click ‘Next’")
        .call(wrap, textWidth);
      slide_2.append("text")
        .attr("class", "slideText")
        .attr("x", center - textWidth / 2)
        .attr("y", text_offset_top + title_offset_top + lineHeight * 4)
        .text("I understand the nature of the study, and what is required from me.  I understand that after I participate I will receive a debrief providing me with information about the study and contact details for the researcher.  I understand I am free to withdraw from the study at any time, without having to give a reason for withdrawing, and without prejudice. I agree to provide information to the investigator and understand that my contribution will remain confidential.   I also consent to the retention of this data under the condition that any subsequent use also be restricted to research projects that have gained ethical approval from Northumbria University.")
        .call(wrap, textWidth);
        slide_2.style("display", "none");



      var slide_4 = d3.select("svg").append("g")
        .attr("id", "slide4");
      slide_4.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("The following questions are about people with whom you discuss important matters.")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide4 .slideText tspan').length + $('#slide4 .slideText').length-1))
        .text("From time to time, most people discuss important matters with other people. Looking back over the last six months, who are the people with whom you discussed matters important to you? Just tell me their first names or initials.")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide4 .slideText tspan').length + $('#slide4 .slideText').length-1))
        .text("You can name up to 10 people with whom you discuss important matters. CLICK ON THEIR NAMES TO TOGGLE GENDER. Pink is female and teal is male.")
        .call(wrap, textWidth);
      var textheight = $('#slide4 .slideText tspan').length + $('#slide4 .slideText').length;
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("What is the name or are the initials of the person you discuss important matters with?")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Is there another person with whom you discuss important matters? Please enter his or her name or initials.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Thank you for entering these names or initials. Please click \"Next\" to continue.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_4.style("display", "none");

      // Slide 4

      var slide_5 = d3.select("svg").append("g")
        .attr("id", "slide5");
      slide_5.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_5.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("We will now ask a number of questions about these people.")
        .call(wrap, textWidth);
      slide_5.style("display", "none");

      // Slide 5

      var slide_6 = d3.select("svg").append("g")
        .attr("id", "slide6");
      slide_6.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_6.append("text")
        .attr("class", "slideText numfri1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("How close is your relationship with each person?")
        .call(wrap, textWidth);
      slide_6.append("text")
        .attr("class", "slideText numfri2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide6 .slideText tspan').length + $('#slide6 .slideText').length-1))
        .text("Drag the circles with the names of each person into the box below that indicates how close your relationship is.")
        .call(wrap, textWidth);
      slide_6.style("display", "none");

      // Slide 6

      var slide_7 = d3.select("svg").append("g")
        .attr("id", "slide7");
      slide_7.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_7.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("Which of these people know each other?")
        .call(wrap, textWidth);
      slide_7.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide7 .slideText tspan').length + $('#slide7 .slideText').length-1))
        .text("To indicate that two persons know each other, click on the name of the first person and then on the name of the second person. This will create a line between the two.")
        .call(wrap, textWidth);
      slide_7.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide7 .slideText tspan').length + $('#slide7 .slideText').length-1))
        .text("Please create lines between all persons who know each other. Click \"Next\" when you are done.")
        .call(wrap, textWidth);
      slide_7.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide7 .slideText tspan').length + $('#slide7 .slideText').length-1))
        .text("If you created an incorrect line by accident, you can remove it with a right click of your mouse.")
        .call(wrap, textWidth);
      slide_7.style("display", "none");

      // Slide 7

      var slide_8 = d3.select("svg").append("g")
        .attr("id", "slide8")
        .style("display", "none")
      slide_8.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight)
      slide_8.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("We will now ask a some questions about yourself. Please click \"Next\" after answering each question to continue.")
        .call(wrap, textWidth)

        var slide_9 = d3.select("svg").append("g")
          .attr("id", "slide9");
        slide_9.append("rect")
          .style("fill", "white")
          .attr("class", "slide")
          .attr("x", 0)
          .attr("y", 0)
          .attr("width", bodyWidth)
          .attr("height", bodyHeight);
        slide_9.append("text")
          .attr("class", "slideText numfri")
          .attr("x", center - (textWidth / 2))
          .attr("y", text_offset_top)
          .text("PARTICIPANT DEBRIEF")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 3)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 4)
          .text("What was the purpose of the project?")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 8)
          .text("This project aimed to explain how loneliness and extroversion-introversion interact with social network size and emotional closeness to network members. Social networks play a huge role in all our lives and are incredibly important to our wellbeing (Berkman & Glass, 2000). So, understanding what variables are associated with emotionally closer and larger networks is integral to beginning to think about how we might aid those who struggle in fostering an appropriate social network. This research expects to discover extroversion to be related with larger network sizes, vice versa for introversion. And that loneliness would be related with smaller network sizes and low emotional closeness to network members.")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 3)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 16)
          .text("How will I find out about the results?")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 20)
          .text("After approximately 1-2 months, once the data analysis has been completed the researchers Connor Malcolm  (connor.malcolm@northumbria.ac.uk)) will email you a general summary of the results of this research, upon email request.")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 3)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 24)
          .text("If I change my mind and wish to withdraw the information I have provided, how do I do this?")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 28)
          .text("If you wish to withdraw your data then email the investigator (connor.malcolm@northumbria .ac.uk) or their supervisor (thomas.pollet@northumbria.ac.uk) within 1 week of taking part and give them the code word (that you input on the survey) After this time it might not be possible to withdraw your data as it could already have been processed and analysed.")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 34)
          .text("The data collected in this study may also be published in scientific journals or presented at conferences.  Information and data gathered during this research study will only be available to the research team identified in the information sheet. Should the research be presented or published in any form, all data will be anonymous (i.e. your personal information or data will not be identifiable). It will be hosted on the open science framework, should you")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 40)
          .text("All information and data gathered during this research will be stored in line with the Data Protection Act and GDPR. The anonymised data might be stored indefinitely in order to comply with good scientific practice. Should you not want this, then please contact the researchers and request removal of your data. Note however, that at no point will your personal information or data be revealed. ")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 48)
          .text("If you wish to receive feedback about the findings of this research study, then please contact the researcher at connor.malcolm@northumbria.ac.uk ")
          .call(wrap, textWidth);
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 52)
          .text("This study and its protocol have received full ethical approval from Faculty of Health and Life Sciences Research Ethics Committee. If you require confirmation of this, or if you have any concerns or worries concerning this research, or if you wish to register a complaint, please contact the Chair of this Committee, santosh.vijaykumar@northumbria.ac.uk stating the title of the research project and the name of the researcher")
          .call(wrap, textWidth);
        slide_9.style("display", "none");
        slide_9.append("text")
          .attr("class", "slideText")
          .attr("x", center - textWidth / 2)
          .attr("y", text_offset_top + title_offset_top + lineHeight * 56)
          .text("Thank you for participating in this survey. You may now close the browser")
          .call(wrap, textWidth);
        slide_9.style("display", "none");









      // Boxes indicating frequency into which nodes are dragged (4, 5 or 6 categories)

      var fourBar = d3.select("svg").append("g")
        .attr("id", "fourBar")
        .style("display", "none");

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "several")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "daily")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar4_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "multiple")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar4_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      fourBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "weekly")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar4_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar4_target_width)
        .attr("height", bar_target_height);

      var fiveBar = d3.select("svg").append("g")
        .attr("id", "fiveBar")
        .style("display", "none")

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "one")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "one_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width / 2) - 28)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "two")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "two_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin + (bar_target_width / 2) - 25)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "three")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "three_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2 + (bar_target_width / 2) - 23)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "four")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "four_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3 + (bar_target_width / 2) - 20)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      fiveBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "five")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y)
        .attr("width", bar_target_width)
        .attr("height", bar_target_height);

      fiveBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "five_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar_target_width)
        .attr("height", bar_label_height);

      fiveBar.append("text")
        .attr("class", "bar_text")
        .text("")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4 + (bar_target_width / 2) - 15)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      var sixBar = d3.select("svg").append("g")
        .attr("id", "sixBar")
        .style("display", "none")

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "one")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "one_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Family member")
        .attr("x", boxbar_offset_x + (bar6_target_width / 2) - 42)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "two")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "two_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Friend")
        .attr("x", boxbar_offset_x + bar6_target_width + boxbar_margin + (bar6_target_width / 2) - 20)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "three")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "three_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Co-worker")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 2 + (bar6_target_width / 2) - 33)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "four")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "four_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Neighbor")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 3 + (bar6_target_width / 2) - 25)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "five")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "five_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Member of the same social group/club")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 4 + (bar6_target_width / 2) - 118 )
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      sixBar.append("rect")
        .attr("class", "bar_target")
        .attr("id", "six")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y)
        .attr("width", bar6_target_width)
        .attr("height", bar_target_height);

      sixBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "six_lab")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar6_target_width)
        .attr("height", bar_label_height);

      sixBar.append("text")
        .attr("class", "bar_text")
        .text("Multiple/Other")
        .attr("x", boxbar_offset_x + (bar6_target_width + boxbar_margin) * 5 + (bar6_target_width / 2) - 35)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      // Boxes with labels

      var labelBar = d3.select("svg").append("g")
        .style("display", "none")
        .attr("id", "labelBar1");

      labelBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "extremely_close")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar5_target_width)
        .attr("height", bar_label_height);

      labelBar.append("text")
        .attr("class", "bar_text")
        .text("Extremely close")
        .attr("x", boxbar_offset_x + (bar_target_width / 2) - 48)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      labelBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "very_close")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + bar5_target_width + boxbar_margin)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar5_target_width)
        .attr("height", bar_label_height);

      labelBar.append("text")
        .attr("class", "bar_text")
        .text("Very close")
        .attr("x", boxbar_offset_x + bar_target_width + boxbar_margin + (bar_target_width / 2) - 30)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      labelBar.append("rect")
          .attr("class", "bar_label")
          .attr("id", "moderately_close")
          .attr("rx", 4)
          .attr("ry", 4)
          .attr("x", boxbar_offset_x + (bar5_target_width + boxbar_margin) * 2)
          .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
          .attr("width", bar5_target_width)
          .attr("height", bar_label_height);

      labelBar.append("text")
        .attr("class", "bar_text")
        .text("Moderately close")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 2 + (bar_target_width / 2) - 55)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      labelBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "a_little_close")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar5_target_width + boxbar_margin) * 3)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar5_target_width)
        .attr("height", bar_label_height);

      labelBar.append("text")
        .attr("class", "bar_text")
        .text("A little close")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 3 + (bar_target_width / 2) - 45)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);

      labelBar.append("rect")
        .attr("class", "bar_label")
        .attr("id", "not_at_all_close")
        .attr("rx", 4)
        .attr("ry", 4)
        .attr("x", boxbar_offset_x + (bar5_target_width + boxbar_margin) * 4)
        .attr("y", boxbar_offset_y - bar_label_height - boxbar_label_margin)
        .attr("width", bar5_target_width)
        .attr("height", bar_label_height);

      labelBar.append("text")
        .attr("class", "bar_text")
        .text("Not at all close")
        .attr("x", boxbar_offset_x + (bar_target_width + boxbar_margin) * 4 + (bar_target_width / 2) - 65)
        .attr("y", boxbar_offset_y - boxbar_label_margin - 6);


      //---------------------------------------------
      // Declaration of functions for nodes and links
      //---------------------------------------------

      // Graph iteration
      function tick() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });

        node.attr("cx", function(d) { return d.x; })
            .attr("cy", function(d) { return d.y; })
            .attr("name", function(d) { return d.name; })
            .attr("code", function(d) { return d.code;})
            .attr("age", function(d) {return d.age;})
            .attr("id", function(d) { return d.id; })
            .attr("race", function(d) { return d.race; })
            .attr("edu", function(d) { return d.edu; })
            .attr("freq", function(d) { return d.freq; })
            .attr("gender", function(d) { return d.gender; })
            .attr("transform", function(d){return "translate("+d.x+","+d.y+")"});
      }

      // Add node to graph
      function addFriend() {
        var friendName = document.getElementById("friendNameID");

        if (friendName.value.length > 20 || friendName.value.indexOf(' ') != -1) {
          promptOnlyOne();

        } else if (friendName.value.length > 0) {

          if (numFriends == 0) {

            document.getElementById("first_friend_text").style.display = "none";
            document.getElementById("second_friend_text").style.display = "block";
          }

          if (numFriends == 9) {
            document.getElementById("second_friend_text").style.display = "none";
            document.getElementById("final_friend_text").style.display = "block";
            document.getElementById("one_at_a_time").style.display = "none";

            document.getElementById("name_input").style.display = "none";
          }

          numFriends++;

          if (numFriends <= 10) {
            var node = {name: friendName.value,
                        id: numFriends,
                        gender:"",
                        code:"",
                        age:"",
                        race:"",
                        religion:"",
                        surveyTime:0,
                        sawStats:false,
                        edu:null,
                        freq:null,
                        friendsWith:",",
                        enjoy:"",
                        like:"",
                        interest:"",
                        motivation:""}
            n = nodes.push(node);

            links.push({source: node, target: 0});

            restart();
          }

          document.getElementById("friendNameID").value = '';
        }
      }

      // Whenever nodes or links are added or changes are made to their properties, the graph needs to be restarted
      function restart() {
        force.start();

        link = link.data(links);

        link.enter().insert("line", ".node")
            .attr("class", "link")
            .on("contextmenu", removeLink);

        link.exit().remove();

        node = node.data(nodes);

        var n = node.enter().append("svg:g")
          .attr("class", "node")
          .call(force.drag);

        n.append("svg:circle")
          .attr("class", "node")
          .attr("r", 25)
          .on("click", nodeSelect)
          .call(force.drag);

        n.append("svg:text")
          .attr("class", "node_text")
          .attr("text-anchor", "middle")
          .attr("dy", ".3em")
          .attr("pointer-events", "none")
          .text(function(d) { return d.name });

        n.attr("transform", function(d){return "translate("+d.x+","+d.y+")"});
      }

      // Remove link between two nodes
      function removeLink(l) {
        // Slide 9: draw links between friends that know each other
        if (currSlide == 9) {
          links.splice(links.indexOf(l), 1);
          restart();
        }
      }

      var selected = false;
      var targetId;
      var sourceId;

      // Handles node selections depending on the current slide
      function nodeSelect(d) {
	      // Slide 3: select female friends
	      if (currSlide == 6) {
	        if (d.name != "You") {
	          if (d.gender == "female") {
		          d3.select(this).style("fill", nodeColor)
      		    d.gender = "";
	          } else {
      		    d3.select(this).style("fill", femaleColor)
      		    d.gender = "female";
	          }
	        }
	      }

        // Slide 9: draw links between friends that know each other
        if (currSlide == 9) {
          var targetIndex;
          var sourceIndex;

          if (selected == false) {
            targetId = d.id;
            console.log("targetId: " + targetId);
            selected = true;
          } else {
            sourceId = d.id;
            console.log("sourceid: " + sourceId);
            if (targetId != sourceId) {
              nodes.forEach(function(n) {
                if (n.id == targetId) {
                  targetIndex = n.index;
                  console.log("target: " + targetIndex);
                } else if (n.id == sourceId) {
                  sourceIndex = n.index;
                  console.log("source: " + sourceIndex);
                }
              });

              nodes[sourceIndex].friendsWith += targetIndex.toString();
              nodes[targetIndex].friendsWith += sourceIndex.toString();


              links.push({source: sourceIndex, target: targetIndex});
            }
            selected = false;
          }
          restart();
        }
      }

      // Makes all nodes default color
      function clearColors() {
        d3.selectAll(".node").style("fill", nodeColor)
      }

      //-------------------------------------------------------------------------
      // Declaration of functions for manipulating text, boxes and other elements
      //-------------------------------------------------------------------------

      // Wraps text to fit in a span of width 'width'
      function wrap(text, width) {
        text.each(function() {
          var text = d3.select(this),
              words = text.text().split(/\s+/).reverse(),
              word,
              line = [],
              lineNumber = 0,
              lineHeight = 1.1, // ems
              y = text.attr("y"),
              x = text.attr("x")
              dy = parseFloat(text.attr("dy")),
              tspan = text.text(null).append("tspan").attr("x", x).attr("y", y);
          while (word = words.pop()) {
            line.push(word);
            tspan.text(line.join(" "));
            if (tspan.node().getComputedTextLength() > width) {
              line.pop();
              tspan.text(line.join(" "));
              line = [word];
              tspan = text.append("tspan").attr("x", x).attr("y", y).attr("dy", ++lineNumber * lineHeight + "em").text(word);
            }
          }
        });
      }

      function refreshRadio() {
        var text1 = "question" + askedAbout + "_text1";
        var text2 = "question" + askedAbout + "_text2";
        var text3 = "question" + askedAbout + "_text3";
        var win = "question" + askedAbout + "_window";

        document.getElementById(text1).style.display = "none";
        document.getElementById(text2).style.display = "none";
        document.getElementById(text3).style.display = "none";
        document.getElementById(win).style.display = "none";

        for(var i=0;i<have.length;i++) {
          have[i].checked = false;
        }
        for(var i=0;i<know.length;i++) {
          know[i].checked = false;
        }
        for(var i=0;i<see.length;i++) {
          see[i].checked = false;
        }
        for (var i = 0; i < friends.length; i++) {
          friends[i].checked = false;
        }
      }

      // If respondent has not filled in an answer, reminds them

      function promptNonresponse() {
        document.getElementById("nonresponse_box").style.display = "block";
        document.getElementById("popup").style.display = "block";
      }

      function promptOnlyOne() {
        document.getElementById("onlyone_box").style.display = "block";
        document.getElementById("onlyOnePopup").style.display = "block";
      }

      function friendPromptNonresponse() {
        document.getElementById("fewFriends_box").style.display = "block";
        document.getElementById("friendPopup").style.display = "block";
      }

      function dragPromptNonresponse() {
        document.getElementById("fewDragged_box").style.display = "block";
        document.getElementById("dragPopup").style.display = "block";
      }

      function closePopup() {
        document.getElementById("nonresponse_box").style.display = "none";
        document.getElementById("popup").style.display = "none";
      }

      function closeOnlyOnePopup() {
        document.getElementById("onlyone_box").style.display = "none";
        document.getElementById("popup").style.display = "none";
      }

      function closeFriendPopup() {
        document.getElementById("fewFriends_box").style.display = "none";
        document.getElementById("friendPopup").style.display = "none";
      }

      function closeDragPopup() {
        document.getElementById("fewDragged_box").style.display = "none";
        document.getElementById("dragPopup").style.display = "none";
      }

      // Questions about individuals in the network
      function drawBox(node) {
        var q_x = node.x - 142;
        var x = q_x.toString();

        var q_y = node.y - 175;
        var y = q_y.toString();

        haveFriends.style.left = x + "px";
        haveFriends.style.top = y + "px";
        haveFriends.style.display = "block";

        friendsFriends.style.top = y + "px";
        friendsFriends.style.left = x + "px";

        knowFriends.style.top = y + "px";
        knowFriends.style.left = x + "px"

        seeFriends.style.top = y + "px";
        seeFriends.style.left = x + "px"

        currSlide += .5;
      }


      // ---------------------------------------------------------------------------------------
      // showNext(): Prepares for next slide in survey. Hides previous slide and shows currSlide,
      // performing whatever operations needed for preparing slide.
      // A bit like the main() function
      // ---------------------------------------------------------------------------------------

      function showNext() {
        if (currSlide == 1) {
          var d = new Date();
          startTime = d.getTime();

          document.getElementById("Next").style.position="absolute";
          document.getElementById("slide0").style.display = "none";
           document.getElementById("slide1").style.display = "block";


          currSlide+= 0.5;



 } else if (currSlide == 1.5) {
          document.getElementById("slide1").style.display = "none";
          document.getElementById("slide2").style.display = "block"





          currSlide+= 0.1;

 } else if (currSlide == 1.6) {

          document.getElementById("slide2").style.display = "none"

          var ex = document.getElementById("code_input");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";

        currSlide+= 0.4;
   } else if (currSlide == 2) {


     if ($('input[name=code]').val().length == 0 && checked == false && !skipped) {
       promptNonresponse();
       checked = true;
     } else {
       // Collect data before going on
       if (!skipped) {
         nodes[0].code = $('input[name=code]').val();
       } else {
         nodes[0].code = "skipped";
       }}


          document.getElementById("code_input").style.display = "none";

          var ex = document.getElementById("age_input");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;


   } else if (currSlide == 3) {
     if ($('input[name=age]').val().length == 0 && checked == false && !skipped) {
       promptNonresponse();
       checked = true;
     } else {
       // Collect data before going on
       if (!skipped) {
         nodes[0].age = $('input[name=age]').val();
       } else {
         nodes[0].age = "skipped";
       }}

           document.getElementById("slide0").style.display = "none";
           document.getElementById("age_input").style.display = "none";


           var ex = document.getElementById("gender_input");
           ex.style.left = string_l + "px";
           ex.style.top = string_t;
           ex.style.display = "block";
           currSlide++;


   } else if (currSlide == 4) {
           document.getElementById("slide0").style.display = "none";
           if ($('input[name=gender]:checked').length == 0 && checked == false) {
             promptNonresponse();
             checked = true;
          } else {
            //Collect data before going on
            var gender = document.getElementById("genderuser")
            if (gender[0].checked) {
              nodes[0].q1 = "Male";
            } else if (gender[1].checked) {
               nodes[0].q1 = "Female";
            } else if (gender[2].checked) {
              nodes[0].q1 = "Other";
            } else if (gender[3].checked) {
              nodes[0].q1 = "Prefer_Not_To_Say";
            }
            checked = false;

               document.getElementById("gender_input").style.display = "none";
           numAsked= 1;
           lastAnswered = 0;

          currSlide++;
          showNext();
         }
       } else if (currSlide == 5) {
          d3.selectAll(".node").attr("display", "block");
          d3.selectAll(".node").on('mousedown.drag', function(d) {
            return d.index > 0 ? true : null;
          });

          // Q1: The following questions are about people with whom you discuss important matters

          document.getElementById("slide4").style.display = "block";
          document.getElementById("name_input").style.display = "block";
          document.getElementById("name_input").style.left = string_l + "px";

          currSlide++;
        } else if (currSlide == 6) {
          if (numFriends < 10 && checked == false) {
            checked = true;
            console.log("fewer than 10 friends")
            friendPromptNonresponse();
          } else {
            checked = false;
            document.getElementById("slide4").style.display = "none";
            document.getElementById("slide5").style.display = "block";
            var text = $("#slide5 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide5 .numfri").text(text);

            document.getElementById("name_input").style.display = "none";
            currSlide++;
          }
        } else if (currSlide == 7) {
          document.getElementById("slide5").style.display = "none";

          // Prepare nodes for dragging into boxes
          d3.selectAll(".node").style("display", "block");
          clearColors();
          node[0].y -= 100;
          restart();

          // Q2: How close is your relationship with each person?

          document.getElementById("slide6").style.display = "block";
          document.getElementById("fiveBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";

          var text = $("#slide6 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide6 .numfri1").text(text);

          var text = $("#slide6 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide6 .numfri2").text(text);

          d3.selectAll(".node").attr("display", "block");
          d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

          d3.selectAll(".node").classed("fixed", function(d) {
            if (d.index > 0 ) {
              d.fixed = false
            }
          });

          restart();

          d3.selectAll(".node").attr("opacity", function(d) { if (d.index == 0) { return 1}});

          setTimeout(function() {
            d3.selectAll(".node").classed("fixed", function(d) { d.fixed = true});
            d3.selectAll(".link").attr("display", "none");
            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });
          },1000);

          currSlide++;
        } else if (currSlide == 8) {
          var nodeAbove = false;
          var nodeBelow = false;

          // Make sure the nodes are correctly placed in one of the boxes
          nodes.forEach(function(n) {
            if (n.index > 0) {
              if (n.y < boxbar_offset_y) {
                nodeAbove = true;
                console.log("nodeAbove: " + nodeAbove);
              }
              else if (n.y > boxbar_offset_y + bar_target_height) {
                nodeBelow = true;
                console.log("nodeBelow: " + nodeBelow);
              }
            }
          });

          if ((nodeBelow || nodeAbove) && !checked) {
            dragPromptNonresponse();
            checked = true;
          } else {
            nodes.forEach(function(n) {
              if (n.index > 0) {
                if (n.x < boxbar_offset_x + bar5_target_width && n.y > boxbar_offset_y) {
                  n.q2 = "extremely_close";
                } else if (n.x < boxbar_offset_x + bar5_target_width * 2 + boxbar_margin && n.y > boxbar_offset_y) {
                  n.q2 = "very_close";
                } else if (n.x < boxbar_offset_x + (bar5_target_width + boxbar_margin) * 2 + bar5_target_width && n.y > boxbar_offset_y) {
                  n.q2 = "moderately_close";
                } else if (n.x < boxbar_offset_x + (bar5_target_width + boxbar_margin) * 3 + bar5_target_width && n.y > boxbar_offset_y) {
                  n.q2 = "a_little_close";
                } else if (n.y > boxbar_offset_y) {
                  n.q2 = "not_at_all_close";
                }
              }
            });

            checked = false;

            d3.selectAll(".node").classed("fixed", function(d) {
              if (d.index > 0 ) {
                d.fixed = false;
                setTimeout(function() {
                  d.fixed = true
                },2000);
              }
            });
            restart();

            document.getElementById("labelBar1").style.display = "none";
            document.getElementById("fiveBar").style.display = "none";
            document.getElementById("slide6").style.display = "none";

            // Q3: Which of these people know each other?
            document.getElementById("slide7").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;

            if (numFriends < 2) {
              showNext();
            }
          }

        } else if (currSlide == 9) {

          document.getElementById("slide7").style.display = "none";

          document.getElementById("slide8").style.display = "block";


          d3.selectAll(".node").classed("fixed", function(d) {d.fixed =true});
          d3.selectAll(".link").attr("display", "none");
          d3.selectAll(".link").style("display", "none");
          d3.selectAll(".node").style("display", "none");


          currSlide++;




        } else if (currSlide == 10) {

    document.getElementById("slide8").style.display = "none";

        //Q1: I feel reasonably satisfied with myself overall.

          var ex = document.getElementById("socialselfesteem1");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;

      } else if (currSlide == 11) {

          //If user has not selected an option, alert with popup
         if ($('input[name=ss1]:checked').length == 0 && checked == false) {
           promptNonresponse();
           checked = true;
        } else {
          //Collect data before going on
          var ss1 = document.getElementById("ss1user")
          if (ss1[0].checked) {
            nodes[0].q5 = "strongly_agree";
          } else if (ss1[1].checked) {
             nodes[0].q5 = "agree";
          } else if (ss1[2].checked) {
            nodes[0].q5 = "neither_agree_or_disagree";
          } else if (ss1[3].checked) {
            nodes[0].q5 = "disagree";
          } else if (ss1[4].checked) {
            nodes[0].q5 = "strongly_disagree";
          }
          checked = false;
          document.getElementById("socialselfesteem1").style.display = "none";

            // Q2: I think that mmost people like my personality.

            var ex = document.getElementById("socialselfesteem2");
            ex.style.left = string_l + "px";
            ex.style.top = string_t;
            ex.style.display = "block";
            currSlide++;
          }
        } else if (currSlide == 12) {
          // If user has not selected an option, alert with popup
          if ($('input[name=ss2]:checked').length == 0 && checked == false) {
            promptNonresponse();
            checked = true;
          } else {
            // Collect data before going on
            var ss2 = document.getElementById("ss2user")
            if (ss2[0].checked) {
              nodes[0].q6 = "strongly_agree";
            } else if (ss2[1].checked) {
               nodes[0].q6 = "agree";
            } else if (ss2[2].checked) {
              nodes[0].q6 = "neither_agree_or_disagree";
            } else if (ss2[3].checked) {
              nodes[0].q6 = "disagree";
            } else if (ss2[4].checked) {
              nodes[0].q6 = "strongly_disagree";
            }
            checked = false;

            document.getElementById("socialselfesteem2").style.display = "none";

            //Q3 I feel that I am an unpopular person.

            var ex = document.getElementById("socialselfesteem3");
            ex.style.left = string_l + "px";
            ex.style.top = string_t;
            ex.style.display = "block";
            currSlide++;
          }
        } else if (currSlide == 13) {
          // If user has not selected an option, alert with popup
          if ($('input[name=ss3]:checked').length == 0 && checked == false) {
            promptNonresponse();
            checked = true;
          } else {
            // Collect data before going on
            var ss3 = document.getElementById("ss3user")
            if (ss3[0].checked) {
              nodes[0].q7 = "strongly_agree";
            } else if (ss3[1].checked) {
               nodes[0].q7 = "agree";
            } else if (ss3[2].checked) {
              nodes[0].q7 = "neither_agree_or_disagree";
            } else if (ss3[3].checked) {
              nodes[0].q7 = "disagree";
            } else if (ss3[4].checked) {
              nodes[0].q7 = "strongly_disagree";
            }
            checked = false;

            document.getElementById("socialselfesteem3").style.display = "none";

            //Q4 I sometimes feel that I am a worthless person.

            var ex = document.getElementById("socialselfesteem4");
            ex.style.left = string_l + "px";
            ex.style.top = string_t;
            ex.style.display = "block";
            currSlide++;
           }
         } else if (currSlide == 14) {
                      // If user has not selected an option, alert with popup
            if ($('input[name=ss4]:checked').length == 0 && checked == false) {
            promptNonresponse();
            checked = true;
          } else {
          // Collect data before going on
            var ss4 = document.getElementById("ss4user")
            if (ss4[0].checked) {
            nodes[0].q8 = "strongly_agree";
          } else if (ss4[1].checked) {
            nodes[0].q8 = "agree";
          } else if (ss4[2].checked) {
            nodes[0].q8 = "neither_agree_or_disagree";
          } else if (ss4[3].checked) {
            nodes[0].q8 = "disagree";
          } else if (ss4[4].checked) {
            nodes[0].q8 = "strongly_disagree";
          }
           checked = false;

           document.getElementById("socialselfesteem4").style.display = "none";

           //Q5 I rarely express my opinions in group meetings.

           var ex = document.getElementById("socialboldness1");
           ex.style.left = string_l + "px";
           ex.style.top = string_t;
           ex.style.display = "block";
           currSlide++;
         }
       } else if (currSlide == 15) {
         // If user has not selected an option, alert with popup
         if ($('input[name=sb1]:checked').length == 0 && checked == false) {
           promptNonresponse();
           checked = true;
         } else {
           // Collect data before going on
           var sb1 = document.getElementById("sb1user")
           if (sb1[0].checked) {
             nodes[0].q9 = "strongly_agree";
           } else if (sb1[1].checked) {
              nodes[0].q9 = "agree";
           } else if (sb1[2].checked) {
             nodes[0].q9 = "neither_agree_or_disagree";
           } else if (sb1[3].checked) {
             nodes[0].q9 = "disagree";
           } else if (sb1[4].checked) {
             nodes[0].q9 = "strongly_disagree";
           }
           checked = false;

           document.getElementById("socialboldness1").style.display = "none";

           //Q6 In socical situations, I'm usually the one who makes the first move.

           var ex = document.getElementById("socialboldness2");
           ex.style.left = string_l + "px";
           ex.style.top = string_t;
           ex.style.display = "block";
           currSlide++;
          }
        } else if (currSlide == 16) {
                     // If user has not selected an option, alert with popup
           if ($('input[name=sb2]:checked').length == 0 && checked == false) {
           promptNonresponse();
           checked = true;
         } else {
         // Collect data before going on
           var sb2 = document.getElementById("sb2user")
           if (sb2[0].checked) {
           nodes[0].q10 = "strongly_agree";
         } else if (sb2[1].checked) {
           nodes[0].q10 = "agree";
         } else if (sb2[2].checked) {
           nodes[0].q10 = "neither_agree_or_disagree";
         } else if (sb2[3].checked) {
           nodes[0].q10 = "disagree";
         } else if (sb2[4].checked) {
           nodes[0].q10 = "strongly_disagree";
         }
          checked = false;

          document.getElementById("socialboldness2").style.display = "none";

//Q7 In group situations, I'm often the one who speaks on behalf of the group.

           var ex = document.getElementById("socialboldness3");
           ex.style.left = string_l + "px";
           ex.style.top = string_t;
           ex.style.display = "block";
           currSlide++;
          }
      } else if (currSlide == 17) {
// If user has not selected an option, alert with popup
        if ($('input[name=sb3]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
        } else {
// Collect data before going on
          var sb3 = document.getElementById("sb3user")
          if (sb3[0].checked) {
            nodes[0].q11 = "strongly_agree";
          } else if (sb3[1].checked) {
            nodes[0].q11 = "agree";
          } else if (sb3[2].checked) {
            nodes[0].q11 = "neither_agree_or_disagree";
          } else if (sb3[3].checked) {
            nodes[0].q11 = "disagree";
          } else if (sb3[4].checked) {
            nodes[0].q11 = "strongly_disagree";
          }
          checked = false;

          document.getElementById("socialboldness3").style.display = "none";

          //Q8 I tend to feel quite self concious when speaking infront of other people.

          var ex = document.getElementById("socialboldness4");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
         }
       } else if (currSlide == 18) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=sb4]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
        } else {
        // Collect data before going on
          var sb4 = document.getElementById("sb4user")
          if (sb4[0].checked) {
          nodes[0].q12 = "strongly_agree";
        } else if (sb4[1].checked) {
          nodes[0].q12 = "agree";
        } else if (sb4[2].checked) {
          nodes[0].q12 = "neither_agree_or_disagree";
        } else if (sb4[3].checked) {
          nodes[0].q12 = "disagree";
        } else if (sb4[4].checked) {
          nodes[0].q12 = "strongly_disagree";
        }
         checked = false;

         document.getElementById("socialboldness4").style.display = "none";

         //Q9 I avoid making small talk with people.

         var ex = document.getElementById("sociability1");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 19) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=so1]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var so1 = document.getElementById("so1user")
         if (so1[0].checked) {
         nodes[0].q13 = "strongly_agree";
       } else if (so1[1].checked) {
         nodes[0].q13 = "agree";
       } else if (so1[2].checked) {
         nodes[0].q13 = "neither_agree_or_disagree";
       } else if (so1[3].checked) {
         nodes[0].q13 = "disagree";
       } else if (so1[4].checked) {
         nodes[0].q13 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("sociability1").style.display = "none";

         //Q10 I enjoy having lots of people around to talk with.

         var ex = document.getElementById("sociability2");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 20) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=so2]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var so2 = document.getElementById("so2user")
         if (so2[0].checked) {
         nodes[0].q14 = "strongly_agree";
       } else if (so2[1].checked) {
         nodes[0].q14 = "agree";
       } else if (so2[2].checked) {
         nodes[0].q14 = "neither_agree_or_disagree";
       } else if (so2[3].checked) {
         nodes[0].q14 = "disagree";
       } else if (so2[4].checked) {
         nodes[0].q14 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("sociability2").style.display = "none";

         //Q11 I prefer jobs that involve active social interction to those that involve working alone.

         var ex = document.getElementById("sociability3");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 21) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=so3]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var so3 = document.getElementById("so3user")
         if (so3[0].checked) {
         nodes[0].q15 = "strongly_agree";
       } else if (so3[1].checked) {
         nodes[0].q15 = "agree";
       } else if (so3[2].checked) {
         nodes[0].q15 = "neither_agree_or_disagree";
       } else if (so3[3].checked) {
         nodes[0].q15 = "disagree";
       } else if (so3[4].checked) {
         nodes[0].q15 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("sociability3").style.display = "none";

         //Q12 The first thing that I always do in new places is make friends.

         var ex = document.getElementById("sociability4");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 22) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=so4]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var so4 = document.getElementById("so4user")
         if (so4[0].checked) {
         nodes[0].q16 = "strongly_agree";
       } else if (so4[1].checked) {
         nodes[0].q16 = "agree";
       } else if (so4[2].checked) {
         nodes[0].q16 = "neither_agree_or_disagree";
       } else if (so4[3].checked) {
         nodes[0].q16 = "disagree";
       } else if (so4[4].checked) {
         nodes[0].q16 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("sociability4").style.display = "none";

         //Q13 I am energetic nearly all the time.

         var ex = document.getElementById("liveliness1");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 23) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=li1]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var li1 = document.getElementById("li1user")
         if (li1[0].checked) {
         nodes[0].q17 = "strongly_agree";
       } else if (li1[1].checked) {
         nodes[0].q17 = "agree";
       } else if (li1[2].checked) {
         nodes[0].q17 = "neither_agree_or_disagree";
       } else if (li1[3].checked) {
         nodes[0].q17 = "disagree";
       } else if (li1[4].checked) {
         nodes[0].q17 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("liveliness1").style.display = "none";

         //Q14 On most days, I feel cheerful and optomistic

         var ex = document.getElementById("liveliness2");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 24) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=li2]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var li2 = document.getElementById("li2user")
         if (li2[0].checked) {
         nodes[0].q18 = "strongly_agree";
       } else if (li2[1].checked) {
         nodes[0].q18 = "agree";
       } else if (li2[2].checked) {
         nodes[0].q18 = "neither_agree_or_disagree";
       } else if (li2[3].checked) {
         nodes[0].q18 = "disagree";
       } else if (li2[4].checked) {
         nodes[0].q18 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("liveliness2").style.display = "none";

        //Q15 People often tell me that I should try to cheer up.

         var ex = document.getElementById("liveliness3");
         ex.style.left = string_l + "px";
         ex.style.top = string_t;
         ex.style.display = "block";
         currSlide++;
         }
       } else if (currSlide == 25) {
                   // If user has not selected an option, alert with popup
         if ($('input[name=li3]:checked').length == 0 && checked == false) {
         promptNonresponse();
         checked = true;
         } else {
         // Collect data before going on
         var li3 = document.getElementById("li3user")
         if (li3[0].checked) {
         nodes[0].q19 = "strongly_agree";
       } else if (li3[1].checked) {
         nodes[0].q19 = "agree";
       } else if (li3[2].checked) {
         nodes[0].q19 = "neither_agree_or_disagree";
       } else if (li3[3].checked) {
         nodes[0].q19 = "disagree";
       } else if (li3[4].checked) {
         nodes[0].q19 = "strongly_disagree";
         }
         checked = false;

         document.getElementById("liveliness3").style.display = "none";


         //Q16 Most people are more upbeat and dynamic than I generally am.

          var ex = document.getElementById("liveliness4");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 26) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=li4]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var li4 = document.getElementById("li4user")
          if (li4[0].checked) {
          nodes[0].q20 = "strongly_agree";
        } else if (li4[1].checked) {
          nodes[0].q20 = "agree";
        } else if (li4[2].checked) {
          nodes[0].q20 = "neither_agree_or_disagree";
        } else if (li4[3].checked) {
          nodes[0].q20 = "disagree";
        } else if (li4[4].checked) {
          nodes[0].q20 = "strongly_disagree";
          }
          checked = false;

          document.getElementById("liveliness4").style.display = "none";

          //Q1: How often do you feel that you are "in tune" with the people around you?

          var ex = document.getElementById("loneliness1");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
      } else if (currSlide == 27) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo1]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo1 = document.getElementById("lo1user")
          if (lo1[0].checked) {
          nodes[0].q21 = "Always";
        } else if (lo1[1].checked) {
          nodes[0].q21 = "Sometimes";
        } else if (lo1[2].checked) {
          nodes[0].q21 = "Rarely";
        } else if (lo1[3].checked) {
          nodes[0].q21 = "Never";
          }
          checked = false;

          document.getElementById("loneliness1").style.display = "none";

          //Q2: How often do you feel that you lack companionship?

          var ex = document.getElementById("loneliness2");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
      } else if (currSlide == 28) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo2]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo2 = document.getElementById("lo2user")
          if (lo2[0].checked) {
          nodes[0].q22 = "Always";
        } else if (lo2[1].checked) {
          nodes[0].q22 = "Sometimes";
        } else if (lo2[2].checked) {
          nodes[0].q22 = "Rarely";
        } else if (lo2[3].checked) {
          nodes[0].q22 = "Never";
          }
          checked = false;

          document.getElementById("loneliness2").style.display = "none";

          //Q3: How often do you feel that there is no one you can turn to?

          var ex = document.getElementById("loneliness3");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 29) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo3]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo3 = document.getElementById("lo3user")
          if (lo3[0].checked) {
          nodes[0].q23 = "Always";
        } else if (lo3[1].checked) {
          nodes[0].q23 = "Sometimes";
        } else if (lo3[2].checked) {
          nodes[0].q23 = "Rarely";
        } else if (lo3[3].checked) {
          nodes[0].q23 = "Never";
          }
          checked = false;

          document.getElementById("loneliness3").style.display = "none";

          //Q4: How often do you feel alone?

          var ex = document.getElementById("loneliness4");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 30) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo4]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo4 = document.getElementById("lo4user")
          if (lo4[0].checked) {
          nodes[0].q24 = "Always";
        } else if (lo4[1].checked) {
          nodes[0].q24 = "Sometimes";
        } else if (lo4[2].checked) {
          nodes[0].q24 = "Rarely";
        } else if (lo4[3].checked) {
          nodes[0].q24 = "Never";
          }
          checked = false;

          document.getElementById("loneliness4").style.display = "none";

          //Q5: How often do you feel part of a group of friends?

          var ex = document.getElementById("loneliness5");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 31) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo5]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo5 = document.getElementById("lo5user")
          if (lo5[0].checked) {
          nodes[0].q25 = "Always";
        } else if (lo5[1].checked) {
          nodes[0].q25 = "Sometimes";
        } else if (lo5[2].checked) {
          nodes[0].q25 = "Rarely";
        } else if (lo5[3].checked) {
          nodes[0].q25 = "Never";
          }
          checked = false;

          document.getElementById("loneliness5").style.display = "none";

          //Q6: How often do you feel that you have a lot in common with the people around you?

          var ex = document.getElementById("loneliness6");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 32) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo6]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo6 = document.getElementById("lo6user")
          if (lo6[0].checked) {
          nodes[0].q26 = "Always";
        } else if (lo6[1].checked) {
          nodes[0].q26 = "Sometimes";
        } else if (lo6[2].checked) {
          nodes[0].q26 = "Rarely";
        } else if (lo6[3].checked) {
          nodes[0].q26 = "Never";
          }
          checked = false;

          document.getElementById("loneliness6").style.display = "none";

          //Q7: How often do you feel that you are no longer close to anyone?

          var ex = document.getElementById("loneliness7");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 33) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo7]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo7 = document.getElementById("lo7user")
          if (lo7[0].checked) {
          nodes[0].q27 = "Always";
        } else if (lo7[1].checked) {
          nodes[0].q27 = "Sometimes";
        } else if (lo7[2].checked) {
          nodes[0].q27 = "Rarely";
        } else if (lo7[3].checked) {
          nodes[0].q27 = "Never";
          }
          checked = false;

          document.getElementById("loneliness7").style.display = "none";

          //Q8: How often do you feel that yourinterests and ideas are not shared by those around you?

          var ex = document.getElementById("loneliness8");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 34) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo8]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo8 = document.getElementById("lo8user")
          if (lo8[0].checked) {
          nodes[0].q28 = "Always";
        } else if (lo8[1].checked) {
          nodes[0].q28 = "Sometimes";
        } else if (lo8[2].checked) {
          nodes[0].q28 = "Rarely";
        } else if (lo8[3].checked) {
          nodes[0].q28 = "Never";
          }
          checked = false;

          document.getElementById("loneliness8").style.display = "none";

          //Q9: How often do you feel outgoing and freindly?

          var ex = document.getElementById("loneliness9");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 35) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo9]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo9 = document.getElementById("lo9user")
          if (lo9[0].checked) {
          nodes[0].q29 = "Always";
        } else if (lo9[1].checked) {
          nodes[0].q29 = "Sometimes";
        } else if (lo9[2].checked) {
          nodes[0].q29 = "Rarely";
        } else if (lo9[3].checked) {
          nodes[0].q29 = "Never";
          }
          checked = false;

          document.getElementById("loneliness9").style.display = "none";

          //Q10:How often do you feel close to people?

          var ex = document.getElementById("loneliness10");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 36) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo10]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo10 = document.getElementById("lo10user")
          if (lo10[0].checked) {
          nodes[0].q30 = "Always";
        } else if (lo10[1].checked) {
          nodes[0].q30 = "Sometimes";
        } else if (lo10[2].checked) {
          nodes[0].q30 = "Rarely";
        } else if (lo10[3].checked) {
          nodes[0].q30 = "Never";
          }
          checked = false;

          document.getElementById("loneliness10").style.display = "none";

          //Q11:How often do you feel left out?

          var ex = document.getElementById("loneliness11");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 37) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo11]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo11 = document.getElementById("lo11user")
          if (lo11[0].checked) {
          nodes[0].q31 = "Always";
        } else if (lo11[1].checked) {
          nodes[0].q31 = "Sometimes";
        } else if (lo11[2].checked) {
          nodes[0].q31 = "Rarely";
        } else if (lo11[3].checked) {
          nodes[0].q31 = "Never";
          }
          checked = false;

          document.getElementById("loneliness11").style.display = "none";

          //Q12:How often do you feel that your relationhips with others are not meaningful?

          var ex = document.getElementById("loneliness12");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 38) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo12]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo12 = document.getElementById("lo12user")
          if (lo12[0].checked) {
          nodes[0].q32 = "Always";
        } else if (lo12[1].checked) {
          nodes[0].q32 = "Sometimes";
        } else if (lo12[2].checked) {
          nodes[0].q32 = "Rarely";
        } else if (lo12[3].checked) {
          nodes[0].q32 = "Never";
          }
          checked = false;

          document.getElementById("loneliness12").style.display = "none";

          //Q13:How often do you feel that no one really knows you well?

          var ex = document.getElementById("loneliness13");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 39) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo13]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo13 = document.getElementById("lo13user")
          if (lo13[0].checked) {
          nodes[0].q33 = "Always";
        } else if (lo13[1].checked) {
          nodes[0].q33 = "Sometimes";
        } else if (lo13[2].checked) {
          nodes[0].q33 = "Rarely";
        } else if (lo13[3].checked) {
          nodes[0].q33 = "Never";
          }
          checked = false;

          document.getElementById("loneliness13").style.display = "none";

          //Q14:How often do you feel isolated from others?

          var ex = document.getElementById("loneliness14");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 40) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo14]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo14 = document.getElementById("lo14user")
          if (lo14[0].checked) {
          nodes[0].q34 = "Always";
        } else if (lo14[1].checked) {
          nodes[0].q34 = "Sometimes";
        } else if (lo14[2].checked) {
          nodes[0].q34 = "Rarely";
        } else if (lo14[3].checked) {
          nodes[0].q34 = "Never";
          }
          checked = false;

          document.getElementById("loneliness14").style.display = "none";

          //Q15:How often do you feel that you can find companionship when you want it?

          var ex = document.getElementById("loneliness15");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 41) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo15]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo15 = document.getElementById("lo15user")
          if (lo15[0].checked) {
          nodes[0].q35 = "Always";
        } else if (lo15[1].checked) {
          nodes[0].q35 = "Sometimes";
        } else if (lo15[2].checked) {
          nodes[0].q35 = "Rarely";
        } else if (lo15[3].checked) {
          nodes[0].q35 = "Never";
          }
          checked = false;

          document.getElementById("loneliness15").style.display = "none";

          //Q16:How often do you feel that there are people who really understand you?

          var ex = document.getElementById("loneliness16");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 42) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo16]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo16 = document.getElementById("lo16user")
          if (lo16[0].checked) {
          nodes[0].q36 = "Always";
        } else if (lo16[1].checked) {
          nodes[0].q36 = "Sometimes";
        } else if (lo16[2].checked) {
          nodes[0].q36 = "Rarely";
        } else if (lo16[3].checked) {
          nodes[0].q36 = "Never";
          }
          checked = false;

          document.getElementById("loneliness16").style.display = "none";

          //Q17:How often do you feel shy?

          var ex = document.getElementById("loneliness17");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 43) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo17]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo17 = document.getElementById("lo17user")
          if (lo17[0].checked) {
          nodes[0].q37 = "Always";
        } else if (lo17[1].checked) {
          nodes[0].q37 = "Sometimes";
        } else if (lo17[2].checked) {
          nodes[0].q37 = "Rarely";
        } else if (lo17[3].checked) {
          nodes[0].q37 = "Never";
          }
          checked = false;

          document.getElementById("loneliness17").style.display = "none";

          //Q18:How often do you feel that people are around you but not with you?

          var ex = document.getElementById("loneliness18");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 44) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo18]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo18 = document.getElementById("lo18user")
          if (lo18[0].checked) {
          nodes[0].q38 = "Always";
        } else if (lo18[1].checked) {
          nodes[0].q38 = "Sometimes";
        } else if (lo18[2].checked) {
          nodes[0].q38 = "Rarely";
        } else if (lo18[3].checked) {
          nodes[0].q38 = "Never";
          }
          checked = false;

          document.getElementById("loneliness18").style.display = "none";

          //Q19:How often do you feel that there are people you can talk to?

          var ex = document.getElementById("loneliness19");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 45) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo19]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo19 = document.getElementById("lo19user")
          if (lo19[0].checked) {
          nodes[0].q39 = "Always";
        } else if (lo19[1].checked) {
          nodes[0].q39 = "Sometimes";
        } else if (lo19[2].checked) {
          nodes[0].q39 = "Rarely";
        } else if (lo19[3].checked) {
          nodes[0].q39 = "Never";
          }
          checked = false;

          document.getElementById("loneliness19").style.display = "none";

          //Q20:How often do you feel that there are people you can turn to?

          var ex = document.getElementById("loneliness20");
          ex.style.left = string_l + "px";
          ex.style.top = string_t;
          ex.style.display = "block";
          currSlide++;
          }
        } else if (currSlide == 46) {
                    // If user has not selected an option, alert with popup
          if ($('input[name=lo20]:checked').length == 0 && checked == false) {
          promptNonresponse();
          checked = true;
          } else {
          // Collect data before going on
          var lo20 = document.getElementById("lo20user")
          if (lo20[0].checked) {
          nodes[0].q40 = "Always";
        } else if (lo20[1].checked) {
          nodes[0].q40 = "Sometimes";
        } else if (lo20[2].checked) {
          nodes[0].q40 = "Rarely";
        } else if (lo20[3].checked) {
          nodes[0].q40 = "Never";
          }
          checked = false;

            document.getElementById("loneliness20").style.display = "none";


          currSlide++;
          showNext();
        }
        } else if (currSlide == 47) {


          document.getElementById("submitForm").style.display = "block";
          var sb = document.getElementById("submitButton")
          document.getElementById("NextDiv").style.display = "none";



          checked = true;


            // Single array containing all answers
            var answer = [document.getElementById("nomem").value,nodes[0].code,nodes[0].age,nodes[0].q1,(nodes.length > 1) ? nodes[1].name : "", (nodes.length > 1) ? nodes[1].q2 : "",(nodes.length > 1) ? nodes[1].friendsWith : "",(nodes.length > 2) ? nodes[2].name : "",(nodes.length > 2) ? nodes[2].q2 : "",(nodes.length > 2) ? nodes[2].friendsWith : "",(nodes.length > 3) ? nodes[3].name : "",(nodes.length > 3) ? nodes[3].q2 : "",(nodes.length > 3) ? nodes[3].friendsWith : "",(nodes.length > 4) ? nodes[4].name : "",(nodes.length > 4) ? nodes[4].q2 : "",(nodes.length > 4) ? nodes[4].friendsWith : "",(nodes.length > 5) ? nodes[5].name : "",(nodes.length > 5) ? nodes[5].q2 : "",(nodes.length > 5) ? nodes[5].friendsWith : "",(nodes.length > 6) ? nodes[6].name : "",(nodes.length > 6) ? nodes[6].q2 : "",(nodes.length > 6) ? nodes[6].friendsWith : "",(nodes.length > 7) ? nodes[7].name : "",(nodes.length > 7) ? nodes[7].q2 : "",(nodes.length > 7) ? nodes[7].friendsWith : "",(nodes.length > 8) ? nodes[8].name : "",(nodes.length > 8) ? nodes[8].q2 : "",(nodes.length > 8) ? nodes[8].friendsWith : "",(nodes.length > 9) ? nodes[9].name : "",(nodes.length > 9) ? nodes[9].q2 : "",(nodes.length > 9) ? nodes[9].friendsWith : "",(nodes.length > 10) ? nodes[10].name : "",(nodes.length > 10) ? nodes[10].q2 : "",(nodes.length > 10) ? nodes[10].friendsWith : "",nodes[0].q5,nodes[0].q6,nodes[0].q7,nodes[0].q8,nodes[0].q9,nodes[0].q10,nodes[0].q11,nodes[0].q12,nodes[0].q13,nodes[0].q14,nodes[0].q15,nodes[0].q16,nodes[0].q17,nodes[0].q18,nodes[0].q19,nodes[0].q20,nodes[0].q21,nodes[0].q22,nodes[0].q23,nodes[0].q24,nodes[0].q25,nodes[0].q26,nodes[0].q27,nodes[0].q28,nodes[0].q29,nodes[0].q30,nodes[0].q31,nodes[0].q32,nodes[0].q33,nodes[0].q34,nodes[0].q35,nodes[0].q36,nodes[0].q37,nodes[0].q38,nodes[0].q39,nodes[0].q40];

  console.log(answer)

            window.addEventListener("load", e => {
            document.getElementById("qu1_id").value = answer.join(",");
          })


            //Post collected data to handler for recording
            $.post( "save_results.php", {
            nomem: document.getElementById("nomem").value,
            code: nodes[0].code,
            age: nodes[0].age,
            q1: nodes[0].q1,
            q2_1: (nodes.length > 1) ? nodes[1].name : "",
            q3_1: (nodes.length > 1) ? nodes[1].q2 : "",
            q4_1: (nodes.length > 1) ? nodes[1].friendsWith : "",
            q2_2: (nodes.length > 2) ? nodes[2].name : "",
            q3_2: (nodes.length > 2) ? nodes[2].q2 : "",
            q4_2: (nodes.length > 2) ? nodes[2].friendsWith : "",
            q2_3: (nodes.length > 3) ? nodes[3].name : "",
            q3_3: (nodes.length > 3) ? nodes[3].q2 : "",
            q4_3: (nodes.length > 3) ? nodes[3].friendsWith : "",
            q2_4: (nodes.length > 4) ? nodes[4].name : "",
            q3_4: (nodes.length > 4) ? nodes[4].q2 : "",
            q4_4: (nodes.length > 4) ? nodes[4].friendsWith : "",
            q2_5: (nodes.length > 5) ? nodes[5].name : "",
            q3_5: (nodes.length > 5) ? nodes[5].q2 : "",
            q4_5: (nodes.length > 5) ? nodes[5].friendsWith : "",
            q2_6: (nodes.length > 6) ? nodes[6].name : "",
            q3_6: (nodes.length > 6) ? nodes[6].q2 : "",
            q4_6: (nodes.length > 6) ? nodes[6].friendsWith : "",
            q2_7: (nodes.length > 7) ? nodes[7].name : "",
            q3_7: (nodes.length > 7) ? nodes[7].q2 : "",
            q4_7: (nodes.length > 7) ? nodes[7].friendsWith : "",
            q2_8: (nodes.length > 8) ? nodes[8].name : "",
            q3_8: (nodes.length > 8) ? nodes[8].q2 : "",
            q4_8: (nodes.length > 8) ? nodes[8].friendsWith : "",
            q2_9: (nodes.length > 9) ? nodes[9].name : "",
            q3_9: (nodes.length > 9) ? nodes[9].q2 : "",
            q4_9: (nodes.length > 9) ? nodes[9].friendsWith : "",
            q2_10: (nodes.length > 10) ? nodes[10].name : "",
            q3_10: (nodes.length > 10) ? nodes[10].q2 : "",
            q4_10: (nodes.length > 10) ? nodes[10].friendsWith : "",
            q5: nodes[0].q5,
            q6: nodes[0].q6,
            q7: nodes[0].q7,
            q8: nodes[0].q8,
            q9: nodes[0].q9,
            q10: nodes[0].q10,
            q11: nodes[0].q11,
            q12: nodes[0].q12,
            q13: nodes[0].q13,
            q14: nodes[0].q14,
            q15: nodes[0].q15,
            q16: nodes[0].q16,
            q17: nodes[0].q17,
            q18: nodes[0].q18,
            q19: nodes[0].q19,
            q20: nodes[0].q20,
            q21: nodes[0].q21,
            q22: nodes[0].q22,
            q23: nodes[0].q23,
            q24: nodes[0].q24,
            q25: nodes[0].q25,
            q26: nodes[0].q26,
            q27: nodes[0].q27,
            q28: nodes[0].q28,
            q29: nodes[0].q29,
            q30: nodes[0].q30,
            q31: nodes[0].q31,
            q32: nodes[0].q32,
            q33: nodes[0].q33,
            q34: nodes[0].q34,
            q35: nodes[0].q35,
            q36: nodes[0].q36,
            q37: nodes[0].q37,
            q38: nodes[0].q38,
            q39: nodes[0].q39,
            q40: nodes[0].q40
            });

            checked = false


            currSlide++;
            showNext()

        } else if (currSlide == 48) {
              document.getElementById("submitButton").style.display = "none";
              document.getElementById("submitForm").style.display = "none";
              document.getElementById("slide9").style.display = "block";
              document.getElementById('Next').style.display = "none";








// Release window close-prevention

              unhook();
        }

        $('#Next').blur();

      }


      // Detect Internet Explorer
      var ie = (function(){
        var undef,
            v = 3,
            div = document.createElement('div'),
            all = div.getElementsByTagName('i');
        while (
            div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
            all[0]
        );
        return v > 4 ? v : undef;
      }());

      function isIE () {
        return (ie < 10);
      }

    </script>

    <div class="input-group" id="code_input" method="get" display="none" onsubmit ="return false;">
      <form id="CodeID">
      <span class="slideText">Please enter a word that you will remember in case you wish to withdraw your data from this study.</span>
        <input type="text" name="code" class="form-control" placeholder="..." size="10"><br><br>
      </form>
      </div>


    <div class="input-group" id="age_input" method="get" display="none" onsubmit = "return false;">
      <form id="AgeID">
      <span class="slideText">Please state your age</span>
        <input type="text" name="age" class="form-control" placeholder="Age" size="10"><br><br>
        </form>
      </div>

    </div>
    <div class="input-group" display="none" id="gender_input" method="get">
      <form id="genderuser" display="none">
        <span class="slideText">Please select your gender.</span><br><br>
        <input type="radio" name="gender" value="Male"><span class="questionText"> Male</span><br>
        <input type="radio" name="gender" value="Female"><span class="questionText"> Female </span><br>
        <input type="radio" name="gender" value="Other"><span class="questionText"> Other</span><br>
        <input type="radio" name="gender" value="Prefer_Not_To_Say"><span class="questionText"> Prefer Not To Say</span><br>

      </form>
    </div>

  <div class="input-group" display="none" id="name_input" method="get" onsubmit="addFriend()">
      <input type="text" id="friendNameID" name="friendName" class="form-control" placeholder="Name" size="10">
      <button type="submit" class="btn btn-default" position="inline" value="Enter" onclick="addFriend()">Add
    </div>

  </div>
<div class="popop_box" id="nonresponse_box">
  <div class="popup_box" id="popup">
        <p class="popup_text">We noticed that you didn’t answer this question. It would very helpful for our research if you did. Please feel free to either give an answer or to go to the next question by clicking ‘Next’ again.</p>
        <button class="btn btn-default" onclick="closePopup()">Close</button>
  </div>
</div>

    <div class="popop_box" id="onlyone_box">
      <div class="popup_box" id="onlyOnePopup">
            <p class="popup_text">Enter only one name at once.</p>
            <button class="btn btn-default" onclick="closeOnlyOnePopup()">Close</button>
      </div>
    </div>

    <div class="popop_box" id="fewFriends_box">
      <div class="popup_box" id="friendPopup">
            <p class="popup_text">You have not entered 15 people. Are you sure that there is no one else with whom you discuss important matters? If so, please click ‘Next’ to continue. If there is someone else, please enter the name and click ‘add person’.</p>
            <button class="btn btn-default" onclick="closeFriendPopup()">Close</button>
      </div>
    </div>

    <div class="popop_box" id="fewDragged_box">
      <div class="popup_box" id="dragPopup">
            <p class="popup_text">You have not answered this question for every person in your network. It would very helpful for our research if you did. Please feel free to either give an answer or to go to the next question by clicking ‘Next’ again.”.</p>
            <button class="btn btn-default" onclick="closeDragPopup()">Close</button>
      </div>
    </div>

    <div id="NextDiv">
      <input type="button"
        class="btn btn-default"
        value="Next"
        id="Next"
        onclick="showNext()" />

    </div>
    <div class="input-group" display="none" id="socialselfesteem1" method="get">
      <form id="ss1user" display="none">
        <span class="slideText">I feel reasonably satisfied with myself overall.</span><br><br>
        <input type="radio" name="ss1" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="ss1" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="ss1" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="ss1" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="ss1" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialselfesteem2" method="get">
      <form id="ss2user" display="none">
        <span class="slideText">I think that most people like some aspects of my personality.</span><br><br>
        <input type="radio" name="ss2" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="ss2" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="ss2" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="ss2" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="ss2" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialselfesteem3" method="get">
      <form id="ss3user" display="none">
        <span class="slideText">I feel that I am an unpopular person.</span><br><br>
        <input type="radio" name="ss3" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="ss3" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="ss3" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="ss3" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="ss3" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialselfesteem4" method="get">
      <form id="ss4user" display="none">
        <span class="slideText">I sometimes feel that I am a worthless person.</span><br><br>
        <input type="radio" name="ss4" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="ss4" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="ss4" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="ss4" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="ss4" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialboldness1" method="get">
      <form id="sb1user" display="none">
        <span class="slideText">I rarely express my opinions in group meetings.</span><br><br>
        <input type="radio" name="sb1" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="sb1" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="sb1" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="sb1" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="sb1" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialboldness2" method="get">
      <form id="sb2user" display="none">
        <span class="slideText">In social situations, I'm usually the one who makes the first move.</span><br><br>
        <input type="radio" name="sb2" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="sb2" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="sb2" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="sb2" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="sb2" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialboldness3" method="get">
      <form id="sb3user" display="none">
        <span class="slideText">When I'm in a group of people, I'm often the one who speaks out on behalf of the group.</span><br><br>
        <input type="radio" name="sb3" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="sb3" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="sb3" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="sb3" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="sb3" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="socialboldness4" method="get">
      <form id="sb4user" display="none">
        <span class="slideText">I tend to feel quite self concious when speaking infront of other people.</span><br><br>
        <input type="radio" name="sb4" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="sb4" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="sb4" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="sb4" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="sb4" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="sociability1" method="get">
      <form id="so1user" display="none">
        <span class="slideText">I avoid making small talk with people.</span><br><br>
        <input type="radio" name="so1" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="so1" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="so1" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="so1" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="so1" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="sociability2" method="get">
      <form id="so2user" display="none">
        <span class="slideText">I enjoy having lots of people around to talk with.</span><br><br>
        <input type="radio" name="so2" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="so2" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="so2" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="so2" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="so2" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="sociability3" method="get">
      <form id="so3user" display="none">
        <span class="slideText">I prefer jobs that involve active social interction to those that involve working alone.</span><br><br>
        <input type="radio" name="so3" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="so3" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="so3" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="so3" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="so3" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="sociability4" method="get">
      <form id="so4user" display="none">
        <span class="slideText">The first thing that I always do in new places is make friends.</span><br><br>
        <input type="radio" name="so4" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="so4" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="so4" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="so4" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="so4" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="liveliness1" method="get">
      <form id="li1user" display="none">
        <span class="slideText">I am energetic nearly all the time.</span><br><br>
        <input type="radio" name="li1" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="li1" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="li1" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="li1" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="li1" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="liveliness2" method="get">
      <form id="li2user" display="none">
        <span class="slideText">On most days, I feel cheerful and optomistic.</span><br><br>
        <input type="radio" name="li2" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="li2" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="li2" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="li2" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="li2" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="liveliness3" method="get">
      <form id="li3user" display="none">
        <span class="slideText">People often tell me that I should try to cheer up.</span><br><br>
        <input type="radio" name="li3" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="li3" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="li3" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="li3" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="li3" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="liveliness4" method="get">
      <form id="li4user" display="none">
        <span class="slideText">Most people are more upbeat and dynamic than I generally am.</span><br><br>
        <input type="radio" name="li4" value="strongly_agree"><span class="questionText">  Strongly Agree</span><br>
        <input type="radio" name="li4" value="agree"><span class="questionText">  Agree </span><br>
        <input type="radio" name="li4" value="neither_agree_or_disagree"><span class="questionText">  Neither Agree or Disagree</span><br>
        <input type="radio" name="li4" value="disagree"><span class="questionText">  Disagree</span><br>
        <input type="radio" name="li4" value="strongly_disagree"><span class="questionText">  Strongly Disagree </span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness1" method="get">
      <form id="lo1user" display="none">
        <span class="slideText">How often do you feel that you are "in tune" with the people around you?</span><br><br>
        <input type="radio" name="lo1" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo1" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo1" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo1" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness2" method="get">
      <form id="lo2user" display="none">
        <span class="slideText">How often do you feel that you lack companionship?</span><br><br>
        <input type="radio" name="lo2" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo2" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo2" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo2" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness3" method="get">
      <form id="lo3user" display="none">
        <span class="slideText">How often do you feel that there is no one you can turn to?</span><br><br>
        <input type="radio" name="lo3" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo3" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo3" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo3" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness4" method="get">
      <form id="lo4user" display="none">
        <span class="slideText">How often do you feel alone?</span><br><br>
        <input type="radio" name="lo4" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo4" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo4" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo4" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness5" method="get">
      <form id="lo5user" display="none">
        <span class="slideText">How often do you feel part of a group of friends?</span><br><br>
        <input type="radio" name="lo5" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo5" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo5" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo5" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness6" method="get">
      <form id="lo6user" display="none">
        <span class="slideText">How often do you feel that you have a lot in common with people around you?</span><br><br>
        <input type="radio" name="lo6" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo6" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo6" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo6" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness7" method="get">
      <form id="lo7user" display="none">
        <span class="slideText">How often do you feel that you are no longer closer to anyone?</span><br><br>
        <input type="radio" name="lo7" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo7" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo7" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo7" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness8" method="get">
      <form id="lo8user" display="none">
        <span class="slideText">How often do you feel that your interests and ideas are not shared by those around you?</span><br><br>
        <input type="radio" name="lo8" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo8" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo8" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo8" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness9" method="get">
      <form id="lo9user" display="none">
        <span class="slideText">How often do you feel outgoing and friendly?</span><br><br>
        <input type="radio" name="lo9" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo9" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo9" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo9" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness10" method="get">
      <form id="lo10user" display="none">
        <span class="slideText">How often do you feel close to people?</span><br><br>
        <input type="radio" name="lo10" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo10" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo10" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo10" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness11" method="get">
      <form id="lo11user" display="none">
        <span class="slideText">How often do you feel left out?</span><br><br>
        <input type="radio" name="lo11" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo11" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo11" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo11" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness12" method="get">
      <form id="lo12user" display="none">
        <span class="slideText">How often do you feel that your relationships with others are not meaningful?</span><br><br>
        <input type="radio" name="lo12" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo12" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo12" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo12" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness13" method="get">
      <form id="lo13user" display="none">
        <span class="slideText">How often do you feel that no one really knows you well?</span><br><br>
        <input type="radio" name="lo13" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo13" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo13" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo13" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness14" method="get">
      <form id="lo14user" display="none">
        <span class="slideText">How often do you feel isolated from others?</span><br><br>
        <input type="radio" name="lo14" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo14" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo14" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo14" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness15" method="get">
      <form id="lo15user" display="none">
        <span class="slideText">How often do you feel that you can find companionship when you want it?</span><br><br>
        <input type="radio" name="lo15" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo15" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo15" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo15" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness16" method="get">
      <form id="lo16user" display="none">
        <span class="slideText">How often do you feel that there are people who really understand you?</span><br><br>
        <input type="radio" name="lo16" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo16" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo16" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo16" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness17" method="get">
      <form id="lo17user" display="none">
        <span class="slideText">How often do you feel shy?</span><br><br>
        <input type="radio" name="lo17" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo17" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo17" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo17" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness18" method="get">
      <form id="lo18user" display="none">
        <span class="slideText">How often do you feel that people are around you but not with you?</span><br><br>
        <input type="radio" name="lo18" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo18" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo18" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo18" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness19" method="get">
      <form id="lo19user" display="none">
        <span class="slideText">How often do you feel that there are people you can talk to?</span><br><br>
        <input type="radio" name="lo19" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo19" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo19" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo19" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div class="input-group" display="none" id="loneliness20" method="get">
      <form id="lo20user" display="none">
        <span class="slideText">How often do you feel that people are around you but not with you?</span><br><br>
        <input type="radio" name="lo20" value="Always"><span class="questionText">  Always</span><br>
        <input type="radio" name="lo20" value="Sometimes"><span class="questionText">  Sometimes </span><br>
        <input type="radio" name="lo20" value="Rarely"><span class="questionText">  Rarely</span><br>
        <input type="radio" name="lo20" value="Never"><span class="questionText">  Never</span><br>
      </form>
    </div>

    <div id="submitForm">
      <form id="customapplication" action="<?php echo $_POST  ['returnpage']; ?>" method="post">
        <input type="hidden" name="KeyValue" value="<?php echo $_POST['KeyValue']; ?>"/>
        <input type="hidden" name="InterviewID" value="<?php echo $_POST['InterviewId']; ?>"/>
        <input type="hidden" name="Lmr" value="<?php echo $_POST['Lmr']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['statusvarname1']; ?>" value="<?php echo $_POST['statusvarvalue1']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['varname1']; ?>" id="qu1_id" value=""/>
        <input type="hidden" id="nomem" name="nomem" value="<?php echo $_POST['nomem']; ?>"/>
        <input name="<?php echo $_POST['nextvarname']; ?>" id="submitButton" class="btn btn-default" type="Next" value="Sumbit"/>
      </form>
    </div>

    <script type="text/javascript">
        $("#Next").css("left",window.innerWidth * .8);
        $("#submitButton").css("left",window.innerWidth * .8);
    </script>
  </body>
</html>
