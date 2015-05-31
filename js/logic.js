var problems;
var coordinates;
var currentProblem;
var processed;

//gets the likes/dislikes and updates the view


function getLikes() {
    $.get("/api/total_likes.php?problem=" + currentProblem.id,function(data) {

        processed = JSON.parse(data);
            var likes = processed.filter(function(x){return x=='1'}).length || 0;
            var dislikes =  processed.filter(function(x){return x=='0'}).length || 0;
            $(".confirmed-box").text(likes);
            $(".unconfirmed-box").text(dislikes);



    })
}
//event handlers
$(function() {
    $(".show-booklet").click(function() {

        $(".the-booklet").load('/includes/booklet.html',function(data) {
            $(".the-booklet").fadeIn("slow");

        });


            $("body").click(function() {
                var bookletHeight = $(".the-booklet").height();

                $(".the-booklet").fadeOut('slow').children().remove();

                $("body").css({"max-height" : $("body").height() + 200 - bookletHeight ,'overflow': 'hidden'})
            })
        return false;




    })



    $(".close-book").on("click",function() {
        $(".the-booklet").fadeOut("slow");
    })
    $(".confirm-now").click(function() {

        $.get("/api/confirmation.php?problem=" + currentProblem.id + "&confirm=yes",function(data) {
            console.log(data);
            getLikes();

        })

    })

    $(".unconfirm-now").click(function() {

        $.get("/api/confirmation.php?problem=" + currentProblem.id + "&unconfirm=yes",function(data) {
            getLikes();

        })
    })
//adds the map
    function initialize() {
        var mapOptions = {
            center: {lat: 43.849578600000000000, lng: 25.955229199999962000},
            zoom: 13
        };
        var map = new google.maps.Map(document.getElementById('all-map'),
            mapOptions);
        $.get("api/problems.php", function(data) {
            problems = JSON.parse(data);

           problems.forEach(function(problem) {
               var latLng = new google.maps.LatLng(problem.latitude, problem.longitude);

               var marker = new google.maps.Marker({
                   position: latLng,
                   title: problem.description,
                   map: map,
                   draggable: false
               });


               google.maps.event.addListener(marker, 'click', function() {

                   loadProblem(marker.getPosition());
                   $("html,body").animate({scrollTop: $(".specific-problem").offset().top}, 1200)



               });
           })


        });

    }

    google.maps.event.addDomListener(window, 'load', initialize);
});

//display the specific problem when the user clicks on a marker
function loadProblem(coords) {
    coordinates = coords;
    for (var i = 0;i < problems.length;i++) {
        if (problems[i].latitude == coords["A"] && problems[i].longitude == coords["F"]) {
            $(".specific-problem").hide();
            var problem = problems[i];
            currentProblem = problem;
            $(".problem-date").text(problem.date);
            $(".problem-description").text(problem.description);
            $(".problem-address").text(problem.address);
            if (problem['imagelink']) {
                $(".problem-image").html("<img src='" + problem['imagelink'] + "' alt='" + problem.description + "'>");
            }
            else {
                $(".problem-image").text("Няма снимка.");
            }

            if (problem.author) {
                $(".author-name").text(problem.author);
                $(".author").slideDown('slow');
            }
            $(".problem-category").text(problem.name);
            getLikes();
            $(".specific-problem").fadeIn('slow');
        }

    }
}













