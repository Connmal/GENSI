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

      var bodyWidth = $(document).width();
      var bodyHeight = $(document).height() - 20;
      if (bodyWidth < 800) bodyWidth = 800;
      if (bodyHeight < 750) bodyHeight = 750;
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

      var svg = d3.select("body").append("svg")
        .attr("width", bodyWidth)
        .attr("height", bodyHeight)
        .on("contextmenu", function() {d3.event.preventDefault()});

      var force = d3.layout.force()
        .size([bodyWidth, bodyHeight])
        .nodes([{x:bodyWidth / 2,
                  y:bodyHeight / 2.2,
                  fixed: true,
                  name:"You",
                  id:0,
                  gender:"",
                  age:0,
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
          .text("Please us a different browser for this survey.")
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
          .text("Completing this survey takes about five minutes. Please make sure to not leave the page before all questions have been answered.")
          .call(wrap, textWidth);
      }

      // Slide 1

      var slide_1 = d3.select("svg").append("g")
        .attr("id", "slide1");
      slide_1.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("The following questions are about people with whom you discuss important matters.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide1 .slideText tspan').length + $('#slide1 .slideText').length-1))
        .text("From time to time, most people discuss important matters with other people. Looking back over the last six months, who are the people with whom you discussed matters important to you? Just tell me their first names or initials.")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("id", "one_at_a_time")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide1 .slideText tspan').length + $('#slide1 .slideText').length-1))
        .text("You can name up to 15 people with whom you discuss important matters. Click on their names to toggle gender.")
        .call(wrap, textWidth);
      var textheight = $('#slide1 .slideText tspan').length + $('#slide1 .slideText').length;
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("id", "first_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .text("What is the name or are the initials of the person you discuss important matters with?")
        .call(wrap, textWidth);
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("id", "second_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Is there another person with whom you discuss important matters? Please enter his or her name or initials.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_1.append("text")
        .attr("class", "slideText")
        .attr("id", "final_friend_text")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * textheight)
        .style("stroke", "none")
        .style("fill", "red")
        .text("Thank you for entering these names or initials. Please click \"Next\" to continue.")
        .call(wrap, textWidth)
        .attr("display", "none");
      slide_1.style("display", "none");

      // Slide 2

      var slide_2 = d3.select("svg").append("g")
        .attr("id", "slide2");
      slide_2.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_2.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("We will now ask a number of questions about these people.")
        .call(wrap, textWidth);
      slide_2.style("display", "none");

      // Slide 3

      var slide_3 = d3.select("svg").append("g")
        .attr("id", "slide3");
      slide_3.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight);
      slide_3.append("text")
        .attr("class", "slideText numfri1")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("How close is your relationship with each person?")
        .call(wrap, textWidth);
      slide_3.append("text")
        .attr("class", "slideText numfri2")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide3 .slideText tspan').length + $('#slide3 .slideText').length-1))
        .text("Drag the circles with the names of each person into the box below that indicates how close your relationship is.")
        .call(wrap, textWidth);
      slide_3.style("display", "none");

      // Slide 4

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
        .text("Which of these people know each other?")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide4 .slideText tspan').length + $('#slide4 .slideText').length-1))
        .text("To indicate that two persons know each other, click on the name of the first person and then on the name of the second person. This will create a line between the two.")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide4 .slideText tspan').length + $('#slide4 .slideText').length-1))
        .text("Please create lines between all persons who know each other. Click \"Next\" when you are done.")
        .call(wrap, textWidth);
      slide_4.append("text")
        .attr("class", "slideText")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top + lineHeight * ($('#slide4 .slideText tspan').length + $('#slide4 .slideText').length-1))
        .text("If you created an incorrect line by accident, you can remove it with a right click of your mouse.")
        .call(wrap, textWidth);
      slide_4.style("display", "none");

      // Slide 5

      var slide_5 = d3.select("svg").append("g")
        .attr("id", "slide5")
        .style("display", "none")
      slide_5.append("rect")
        .style("fill", "white")
        .attr("class", "slide")
        .attr("x", 0)
        .attr("y", 0)
        .attr("width", bodyWidth)
        .attr("height", bodyHeight)
      slide_5.append("text")
        .attr("class", "slideText numfri")
        .attr("x", center - (textWidth / 2))
        .attr("y", text_offset_top)
        .text("We will now ask a couple of questions about the friends of these people. Please click \"Next\" to continue.")
        .call(wrap, textWidth)

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

          if (numFriends == 14) {
            document.getElementById("second_friend_text").style.display = "none";
            document.getElementById("final_friend_text").style.display = "block";
            document.getElementById("one_at_a_time").style.display = "none";

            document.getElementById("name_input").style.display = "none";
          }

          numFriends++;

          if (numFriends <= 15) {
            var node = {name: friendName.value,
                        id: numFriends,
                        gender:"",
                        age:0,
                        race:"",
                        religion:"",
                        surveyTime:0,
                        sawStats:false,
                        edu:null,
                        freq:null,
                        friendsWith:"",
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
        // Slide 5: draw links between friends that know each other
        if (currSlide == 5) {
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
	      if (currSlide == 2) {
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

        // Slide 5: draw links between friends that know each other
        if (currSlide == 5) {
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
        if (currSlide == 0) {
          var d = new Date();
          startTime = d.getTime();

          document.getElementById("Next").style.position="absolute";
          document.getElementById("slide0").style.display = "none";



        } else if (currSlide == 1) {
          d3.selectAll(".node").attr("display", "block");
          d3.selectAll(".node").on('mousedown.drag', function(d) {
            return d.index > 0 ? true : null;
          });

          // Q1: The following questions are about people with whom you discuss important matters

          document.getElementById("slide1").style.display = "block";
          document.getElementById("name_input").style.display = "block";
          document.getElementById("name_input").style.left = string_l + "px";

          currSlide++;
        } else if (currSlide == 2) {
          if (numFriends < 15 && checked == false) {
            checked = true;
            console.log("fewer than 15 friends")
            friendPromptNonresponse();
          } else {
            checked = false;
            document.getElementById("slide1").style.display = "block";
            document.getElementById("slide2").style.display = "block";
            var text = $("#slide2 .numfri").text();
            text = text.replace('personen', 'persoon');
            if (numFriends < 2) $("#slide2 .numfri").text(text);

            document.getElementById("name_input").style.display = "none";
            currSlide++;
          }
        } else if (currSlide == 3) {
          document.getElementById("slide2").style.display = "block";

          // Prepare nodes for dragging into boxes
          d3.selectAll(".node").style("display", "block");
          clearColors();
          node[0].y -= 100;
          restart();

          // Q2: How close is your relationship with each person?

          document.getElementById("slide3").style.display = "block";
          document.getElementById("fiveBar").style.display = "block";
          document.getElementById("labelBar1").style.display = "block";

          var text = $("#slide3 .numfri1").text();
          text = text.replace('each person', 'each person');
          if (numFriends < 2) $("#slide3 .numfri1").text(text);

          var text = $("#slide3 .numfri2").text();
          text = text.replace('elke', 'de');
          if (numFriends < 2) $("#slide3 .numfri2").text(text);

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
        } else if (currSlide == 4) {
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
            document.getElementById("slide3").style.display = "none";

            // Q3: Which of these people know each other?

            document.getElementById("slide4").style.display = "block";

            d3.selectAll(".node").attr("opacity", function(d) { return d.index == 0 ? .4 : 1 });

            currSlide++;

            if (numFriends < 2) {
              showNext();
            }
          }
        } else if (currSlide == 5) {
          document.getElementById("slide0").style.display = "none";

          document.getElementById("slide5").style.display = "block";
          var text = $("#slide5 .numfri").text();
          text = text.replace('mensen', 'persoon');
          if (numFriends < 2) $("#slide5 .numfri").text(text);





} else {

            // Single array containing all answers
          var answer = [document.getElementById("nomem").value,(nodes.length > 1) ? nodes[1].name : "",(nodes.length > 1) ? nodes[1].q2 : "",(nodes.length > 1) ? nodes[1].friendsWith : "",(nodes.length > 2) ? nodes[2].name : "",(nodes.length > 2) ? nodes[2].q2 : "",(nodes.length > 2) ? nodes[2].friendsWith : "",(nodes.length > 3) ? nodes[3].name : "",(nodes.length > 3) ? nodes[3].q2 : "",(nodes.length > 3) ? nodes[3].friendsWith : "",(nodes.length > 4) ? nodes[4].name : "",(nodes.length > 4) ? nodes[4].q2 : "",(nodes.length > 4) ? nodes[4].friendsWith : "",(nodes.length > 5) ? nodes[5].name : "",(nodes.length > 5) ? nodes[5].q2 : "",(nodes.length > 5) ? nodes[5].friendsWith : "", (nodes.length > 6) ? nodes[6].name : "",(nodes.length > 6) ? nodes[6].q2 : "",(nodes.length > 6) ? nodes[6].friendsWith : "",(nodes.length > 7) ? nodes[7].name : "",(nodes.length > 7) ? nodes[7].q2 : "",(nodes.length > 7) ? nodes[7].friendsWith : "",(nodes.length > 8) ? nodes[8].name : "",(nodes.length > 8) ? nodes[8].q2 : "",(nodes.length > 8) ? nodes[8].friendsWith : "",(nodes.length > 9) ? nodes[9].name : "",(nodes.length > 9) ? nodes[9].q2 : "",(nodes.length > 9) ? nodes[9].friendsWith : "",(nodes.length > 10) ? nodes[10].name : "",(nodes.length > 10) ? nodes[10].q2 : "",(nodes.length > 10) ? nodes[10].friendsWith : "",(nodes.length > 11) ? nodes[11].name : "",(nodes.length > 11) ? nodes[11].q2 : "",(nodes.length > 11) ? nodes[11].friendsWith : "",(nodes.length > 12) ? nodes[12].name : "",(nodes.length > 12) ? nodes[12].q2 : "",(nodes.length > 12) ? nodes[12].friendsWith : "",(nodes.length > 13) ? nodes[13].name : "",(nodes.length > 13) ? nodes[13].q2 : "",(nodes.length > 13) ? nodes[13].friendsWith : "",(nodes.length > 14) ? nodes[14].name : "",(nodes.length > 14) ? nodes[14].q2 : "",(nodes.length > 14) ? nodes[14].friendsWith : "",(nodes.length > 15) ? nodes[15].name : "",(nodes.length > 15) ? nodes[15].q2 : "",(nodes.length > 15) ? nodes[15].friendsWith : "",nodes[0].q4];

            document.getElementById("qu1_id").value = answer.join(",");

            //Post collected data to handler for recording
            $.post( "save_results.php", {
            nomem: document.getElementById("nomem").value,
            q1_1: (nodes.length > 1) ? nodes[1].name : "",
            q2_1: (nodes.length > 1) ? nodes[1].q2 : "",
            q3_1: (nodes.length > 1) ? nodes[1].friendsWith : "",
            q1_2: (nodes.length > 2) ? nodes[2].name : "",
            q2_2: (nodes.length > 2) ? nodes[2].q2 : "",
            q3_2: (nodes.length > 2) ? nodes[2].friendsWith : "",
            q1_3: (nodes.length > 3) ? nodes[3].name : "",
            q2_3: (nodes.length > 3) ? nodes[3].q2 : "",
            q2_3: (nodes.length > 3) ? nodes[3].friendsWith : "",
            q1_4: (nodes.length > 4) ? nodes[4].name : "",
            q2_4: (nodes.length > 4) ? nodes[4].q2 : "",
            q3_4: (nodes.length > 4) ? nodes[4].friendsWith : "",
            q1_5: (nodes.length > 5) ? nodes[5].name : "",
            q2_5: (nodes.length > 5) ? nodes[5].q2 : "",
            q3_5: (nodes.length > 5) ? nodes[5].friendsWith : "",
            q1_6: (nodes.length > 6) ? nodes[6].name : "",
            q2_6: (nodes.length > 6) ? nodes[6].q2 : "",
            q3_6: (nodes.length > 6) ? nodes[6].friendsWith : "",
            q1_7: (nodes.length > 7) ? nodes[7].name : "",
            q2_7: (nodes.length > 7) ? nodes[7].q2 : "",
            q3_7: (nodes.length > 7) ? nodes[7].friendsWith : "",
            q1_8: (nodes.length > 8) ? nodes[8].name : "",
            q2_8: (nodes.length > 8) ? nodes[8].q2 : "",
            q3_8: (nodes.length > 8) ? nodes[8].friendsWith : "",
            q1_9: (nodes.length > 9) ? nodes[9].name : "",
            q2_9: (nodes.length > 9) ? nodes[9].q2 : "",
            q3_9: (nodes.length > 9) ? nodes[9].friendsWith : "",
            q1_10: (nodes.length > 10) ? nodes[10].name : "",
            q2_10: (nodes.length > 10) ? nodes[10].q2 : "",
            q3_10: (nodes.length > 10) ? nodes[10].friendsWith : "",
            q1_11: (nodes.length > 11) ? nodes[11].name : "",
            q2_11: (nodes.length > 11) ? nodes[11].q2 : "",
            q3_11: (nodes.length > 11) ? nodes[11].friendsWith : "",
            q1_12: (nodes.length > 12) ? nodes[12].name : "",
            q2_12: (nodes.length > 12) ? nodes[12].q2 : "",
            q3_12: (nodes.length > 12) ? nodes[12].friendsWith : "",
            q1_13: (nodes.length > 13) ? nodes[13].name : "",
            q2_13: (nodes.length > 13) ? nodes[13].q2 : "",
            q3_13: (nodes.length > 13) ? nodes[13].friendsWith : "",
            q1_14: (nodes.length > 14) ? nodes[14].name : "",
            q2_14: (nodes.length > 14) ? nodes[14].q2 : "",
            q3_14: (nodes.length > 14) ? nodes[14].friendsWith : "",
            q1_15: (nodes.length > 15) ? nodes[15].name : "",
            q2_15: (nodes.length > 15) ? nodes[15].q2 : "",
            q3_15: (nodes.length > 15) ? nodes[15].friendsWith : "",
            });

            checked = false;
            bw.style.display = "none";

            var sf = document.getElementById("submitForm");
            var sb = document.getElementById("submitButton");
            var nd = document.getElementById("NextDiv");
            sf.style.display = "block";
            nd.style.display = "none";
            var motivationText = d3.select("svg").append("text")
              .attr("class", "slideText")
              .attr("id", "motivationText")
              .attr("x", center - (textWidth / 2) + 50)
              .attr("y", text_offset_top + 40)
              .text("Thank you for participating in this study. Click \"Next\" to end the survey.")
              .call(wrap, textWidth);

            // Release window close-prevention
            unhook();

        }
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

    <div class="input-group" display="none" id="name_input" method="get" onsubmit="addFriend()">
      <input type="text" id="friendNameID" name="friendName" class="form-control" placeholder="Naam" size="10">
      <button type="submit" class="btn btn-default" position="inline" value="Enter" onclick="addFriend()">Add
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

    <div id="submitForm">
      <form id="customapplication" action="<?php echo $_POST  ['returnpage']; ?>" method="post">
        <input type="hidden" name="sh" value="<?php echo $_POST['sh']; ?>"/>
        <input type="hidden" name="lsi" value="<?php echo $_POST['lsi']; ?>"/>
        <input type="hidden" name="pli" value="<?php echo $_POST['pli']; ?>"/>
        <input type="hidden" name="spi" value="<?php echo $_POST['spi']; ?>"/>
        <input type="hidden" name="aqi" value="<?php echo $_POST['aqi']; ?>"/>
        <input type="hidden" name="cqi" value="<?php echo $_POST['cqi']; ?>"/>
        <input type="hidden" name="KeyValue" value="<?php echo $_POST['KeyValue']; ?>"/>
        <input type="hidden" name="InterviewID" value="<?php echo $_POST['InterviewId']; ?>"/>
        <input type="hidden" name="Lmr" value="<?php echo $_POST['Lmr']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['statusvarname1']; ?>" value="<?php echo $_POST['statusvarvalue1']; ?>"/>
        <input type="hidden" name="<?php echo $_POST['varname1']; ?>" id="qu1_id" value=""/>
        <input type="hidden" id="nomem" name="nomem" value="<?php echo $_POST['nomem']; ?>"/>
        <input name="<?php echo $_POST['nextvarname']; ?>" id="submitButton" class="btn btn-default" type="submit" value="Next"/>
      </form>
    </div>

    <script type="text/javascript">
        $("#Next").css("left",window.innerWidth * .8);
        $("#submitButton").css("left",window.innerWidth * .8);
    </script>
  </body>
</html>
