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
      die();
    }
    if(!food || food == "" || $.isNumeric(parseInt(food))) {
      alert("Please enter a food type and try again");
      $("#add_prs_fname").val("");
      $("#add_prs_lname").val("");
      $("#add_prs_food").val("");
      die();
    }
      $.ajax({
        url: "/training/training/store-people",
        type: "post",
        data: {
          "first_name": fname,
          "last_name": lname,
          "favorite_food": food
        },
        success: function(data, status)
        {
            $("#add_prs_fname").val("");
            $("#add_prs_lname").val("");
            $("#add_prs_food").val("");
            console.log("success with post\nId: "+data.id +
                  "\nName: " + data.first_name + " " + data.last_name +
                  "\nFood: " + data.favorite_food);
            if(!data.id || !data.first_name || !data.last_name)
            {
              console.log("Something went wrong with the Controller's return " +
                    "array of data:\nID: " + data.id + "\nName: " +
                    data.first_name + " " + data.last_name);
              die();
            } else {
              $("#person_list").append("<option value=\"" +
                    data.id + "\">" + data.first_name + " " + data.last_name +
                    "</option>");
            }
        },
        error: function(xhr)
        {
            $("#add_prs_fname").val("");
            $("#add_prs_lname").val("");
            $("#add_prs_food").val("");
            console.log("something went wrong with ajax.");
            die();
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
    $.ajax({
      url: "/training/training/store-visits",
      type: "post",
      data: {
        "prs_id": prs_id,
        "ste_id": ste_id
      },
      success: function(data, status)
      {
          if(!data.prs_id || !data.ste_id)
          {
            console.log("Something went wrong.");
          } else {
              console.log("Success with post.");
          }

      },
      error: function(xhr)
      {
          console.log("Something went wrong with ajax.");
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
          var name = obj.first_name + " " + obj.last_name;
          var food = obj.favorite_food;
          $("#person_name").text(name);
          $("#person_food").text(food);

          //Call Nested function for states visited
          $.ajax({
            url: "/training/training/show-person-states",
            type: "get",
            data: {
              "id": $("#person_list").val()
            },
            success: function(data, status)
            {
                var name;
                for (i=0; i<data.length; i++)
                {
                  name = data[i];
                  $("#person_states").append("<li><label class=\"label label-default\">" +
                    name + "</label></li>");
                }
            },
            error: function(xhr)
            {
                console.log("Somthing went wrong with the GET states by person request");
            }
          });
        },
        error: function(xhr) {}
    });
  });
});
