$(document).ready(function(){
  // Function to auto load selection list
  $.get("/training/training/people", function(data, status){
    var obj = data;
    var count = 0;
    var i;
    for (i in obj) {
      if (obj.hasOwnProperty(i)){count++;}
    }
    for (i=1; i<=count; i++){
      $("#person_list").append("<option value=\"" + i +
                      "\">" + obj[i].first_name +
                      " " + obj[i].last_name + "</option");
    }
  });

  // Functions to add people to database
  $("#submit_person").click(function(){
    var fname = $("#add_prs_fname").val();
    var lname = $("#add_prs_lname").val();
    var food = $("#add_prs_food").val();
    if(!fname || fname == "" || $.isNumeric(parseInt(fname)) ||
       !lname || lname == "" || $.isNumeric(parseInt(lname))) {
      alert("Please enter a name and try again");
      $("#add_prs_fname").val("");
      $("#add_prs_lname").val("");
      $("#add_prs_food").val("");
      return;
    }
    if(!food || food == "" || $.isNumeric(parseInt(food))) {
      alert("Please enter a food type and try again");
      $("#add_prs_fname").val("");
      $("#add_prs_lname").val("");
      $("#add_prs_food").val("");
      return;
    }
      $.post("/training/training/store-people",
      {
        //'_token': $('meta[name=csrf-token]').attr('content'),
        first_name: fname,
        last_name: lname,
        favorite_food: food
      },
      function(data, status){
        var obj = data;
        if(!obj.id || !obj.first_name || !obj.last_name){
          alert("Something went wrong.\nId: "+obj.id+"\nFirst Name: "+obj.first_name+
                "\nLast Name: "+obj.last_name);
          die();
        }else {
        $("#person_list").append("<option value=\"" +
          obj.id + "\">" + obj.first_name + " " + obj.last_name + "</option>");
          $("#add_prs_fname").val("");
          $("#add_prs_lname").val("");
          $("#add_prs_food").val("");
        }
      });
  });

  // Function to show the modal and load the select
  // lists for adding visits to database
  $("#add_visit").click(function(){
    //Clear out the select lists for the next button click
    $("#add_vis_prs_list").empty();
    $("#add_vis_state_list").empty();
    $.get("/training/training/people", function(data, status){
      var obj = data;
      var count = 0;
      var i;
      for (i in obj) {
        if (obj.hasOwnProperty(i)){count++;}
      }
      for (i=1; i<=count; i++){
        $("#add_vis_prs_list").append("<option value=\"" + i +
                        "\">" + obj[i].first_name +
                        " " + obj[i].last_name + "</option");
      }
    });
    $.get("/training/training/states", function(data, status){
      var obj = data;
      var count = 0;
      var i;
      for (i in obj) {
        if (obj.hasOwnProperty(i)){count++;}
      }
      for (i=1; i<=count; i++){
        $("#add_vis_state_list").append("<option value=\"" + i +
                        "\">" + obj[i].state_name + "</option");
      }
    });
  });

  // Function to submit the post request for the visits table
  $("#submit_visit").click(function(){
    var prs_id = $("#add_vis_prs_list").val();
    var ste_id = $("#add_vis_state_list").val();
    $.post("/visit/" + prs_id,
    {
      '_token': $('meta[name=csrf-token]').attr('content'),
      prs_id: prs_id,
      ste_id: ste_id,
    },
    function(data, status){
      var obj = data;
      if(!obj.id || !obj.person_state.person_id || !obj.state_name){
        alert("Something went wrong.\nState ID: "+obj.id +
              "\"Person ID: "+obj.person_state.person_id + "\nState Name: " + obj.state_name);
      }
    });
  });

  // Function to retrieve personal data from the server
  // and display it on the main page
  $("#sub_prs_btn").click(function(){
    $("#person_name").text(" ");
    $("#person_food").text(" ");
    $("#person_states").empty();
    //Function call to get person's personal info
    $.ajax({
        url: "/training/training/single-person",
        type: "get",
        data: {
          "id": $("#person_list").val()
        },
        success: function(data, status)
        {
          var obj = data;
          var name = obj[0].first_name + " " + obj[0].last_name;
          var food = obj[0].favorite_food;
          $("#person_name").text(name);
          $("#person_food").text(food);

          //Call Nested function for states visited
          $.get("app/people/"+ $("#person_list").val() +"/states",
                                    function(data, status) {
            var obj = data;
            var name;
            for (i=0;i<obj.length;i++){
              name = obj[i].state_name;
              $("#person_states").append("<li><label class=\"label label-default\">"+name+"</label></li>");
            }
          });
        },
        error: function(xhr) {}
    });
  });
});
