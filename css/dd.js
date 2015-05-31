
    //<![CDATA[

    // Check to see if this browser can run the Google API
    if (GBrowserIsCompatible()) {

      // A function to create the marker and set up the event window
      // Dont try to unroll this function. It has to be here for the function closure
      // Each instance of the function preserves the contends of a different instance
      // of the "marker" and "html" variables which will be needed later when the event triggers.    
      function createMarker(point,html) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html);
        });
        return marker;
      }

      // Display the map, with some controls and set the initial location 
      var map = new GMap2(document.getElementById("map"));

      // ====== Restricting the range of Zoom Levels =====
      // Get the list of map types      
      var mt = map.getMapTypes();
      // Overwrite the getMinimumResolution() and getMaximumResolution() methods
      for (var i=0; i<mt.length; i++) {
        mt[i].getMinimumResolution = function() {return 7;}
        mt[i].getMaximumResolution = function() {return 11;}
      }


      map.addControl(new GLargeMapControl());
      map.addControl(new GMapTypeControl());
      map.setCenter(new GLatLng(53.3,-1.6), 7);
      
    
      // Set up markers with info windows 
    
      var point = new GLatLng(53.848964,-3.039463);
      var marker = createMarker(point,"<b>Blackpool Community Church<\/b><br>Bispham Community Centre<br>Bispham Road<br>Blackpool")
      map.addOverlay(marker);

      var point = new GLatLng(51.503894,-3.191303);
      var marker = createMarker(point,"<b>Cardiff All Nations Church<\/b><br>Sachville Avenue<br>Cardiff")
      map.addOverlay(marker);

      var point = new GLatLng(53.642126,-1.800942);
      var marker = createMarker(point,"<b>Huddersfield Community Church<\/b><br>New Life Church<br>Jubilee Centre<br>Market Street<br>Huddersfield")
      map.addOverlay(marker);

      var point = new GLatLng(53.955930,-1.089173);
      var marker = createMarker(point,"<b>York King's Church<\/b><br>The Priory Street Centre<br>15 Priory Street<br>York")
      map.addOverlay(marker);

      // Add a move listener to restrict the bounds range
      GEvent.addListener(map, "move", function() {
        checkBounds();
      });

      // The allowed region which the whole map must be within
      var allowedBounds = new GLatLngBounds(new GLatLng(49.5,-10), new GLatLng(59,2.6));
      
      // If the map position is out of range, move it back
      function checkBounds() {
        // Perform the check and return if OK
        if (allowedBounds.contains(map.getCenter())) {
          return;
        }
        // It`s not OK, so find the nearest allowed point and move there
        var C = map.getCenter();
        var X = C.lng();
        var Y = C.lat();

        var AmaxX = allowedBounds.getNorthEast().lng();
        var AmaxY = allowedBounds.getNorthEast().lat();
        var AminX = allowedBounds.getSouthWest().lng();
        var AminY = allowedBounds.getSouthWest().lat();

        if (X < AminX) {X = AminX;}
        if (X > AmaxX) {X = AmaxX;}
        if (Y < AminY) {Y = AminY;}
        if (Y > AmaxY) {Y = AmaxY;}
        //alert ("Restricting "+Y+" "+X);
        map.setCenter(new GLatLng(Y,X));
      }
    }
    
    // display a warning if the browser was not compatible
    else {
      alert("Sorry, the Google Maps API is not compatible with this browser");
    }

    // This Javascript is based on code provided by the
    // Community Church Javascript Team
    // http://www.bisphamchurch.org.uk/   
    // http://econym.org.uk/gmap/

    //]]>
    