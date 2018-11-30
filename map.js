$(document).ready(function () {
  // Paramétrage des marqueurs
  var pinColor = "0000ff";// couleur des épingles google MAP
  var pinImageParking = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
    new google.maps.Size(21, 34),
    new google.maps.Point(0, 0),
    new google.maps.Point(10, 34));
  var pinColor = "ff0000";
  var pinImageVelib = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
    new google.maps.Size(21, 34),
    new google.maps.Point(0, 0),
    new google.maps.Point(10, 34));
  var pinColor = "ffff00";
  var pinImageEvenement = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
    new google.maps.Size(21, 34),
    new google.maps.Point(0, 0),
    new google.maps.Point(10, 34));
    var pinColor = "00ff00";
    var pinImageParkProche = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
      new google.maps.Size(21, 34),
      new google.maps.Point(0, 0),
      new google.maps.Point(10, 34));
      var pinColor = "00ffff";
      var pinImageVelibProche = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
        new google.maps.Size(21, 34),
        new google.maps.Point(0, 0),
        new google.maps.Point(10, 34));
  var pinShadow = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
    new google.maps.Size(40, 37),
    new google.maps.Point(0, 0),
    new google.maps.Point(12, 35));


  // Récupération de la latitude et longitude pour centrer notre carte
  var latlng = new google.maps.LatLng(48.68585656828219, 6.1735336912475356);

  //Objet contenant des les propriétés d'affichage de la carte Google MAP
  var options = {
    center: latlng,
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };


  var carte = new google.maps.Map(document.getElementById("carte"), options);
  //Constructeur de la carte

  function refreshMap() {
    carte = new google.maps.Map(document.getElementById("carte"), options);
  }

  function afficherParking() {

    // Récupération en AJAX des données des lieux à épingler sur la carte Google map
    $.ajax({
      url: 'Ajax/list-parking.php',
      error: function (request, error) { // Info Debuggage si erreur         
        alert("Erreur sous genre - responseText: " + request.responseText);
      },
      dataType: "json",
      success: function (data) {
        $("#carte").fadeIn('slow');
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        // Parcours des données reçus depuis le fichier
        $.each(data.items['parkings'], function (i, item) {
          if (item) {
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(item.x_lieu, item.y_lieu),
              map: carte,
              icon: pinImageParking,
              shadow: pinShadow,
              title: "Parking " + item.Titre_lieu
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
              return function () {
                // Affichage de la légende de chaque parking
                infowindow.setContent('<p> Nombre de places :' + item.Places_lieu + '<br/>Capacité :' + item.Capacite_lieu + ' </p> ');
                infowindow.open(carte, marker);
              }
            })(marker, i));
          }
        });
      }
    });
  }
  function afficherVelib() {

    // Récupération en AJAX des données des lieux à épingler sur la carte Google map
    $.ajax({
      url: 'Ajax/list-velib.php',
      error: function (request, error) { // Info Debuggage si erreur         
        alert("Erreur sous genre - responseText: " + request.responseText);
      },
      dataType: "json",
      success: function (data) {
        $("#carte").fadeIn('slow');
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        // Parcours des données reçus depuis le fichier
        $.each(data.items['velibs'], function (i, item) {
          if (item) {
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(item.x_lieu, item.y_lieu),
              map: carte,
              icon: pinImageVelib,
              shadow: pinShadow,
              title: "VELIB " + item.Titre_lieu
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
              return function () {
                // Affichage de la légende de chaque Velib
                infowindow.setContent('<p> Nombre de vélos dispo :' + item.VeloDispo_lieu + '<br/>Emplacement dispo :' + item.EmplacementDispo_lieu + ' </p> ');
                infowindow.open(carte, marker);
              }
            })(marker, i));
          }
        });
      }
    });
  }

  function afficherChemin() {
    refreshMap()
    // Récupération en AJAX des données des lieux à épingler sur la carte Google map
    $.ajax({
      url: 'Ajax/chemin-autreCanal.php',
      error: function (request, error) { // Info Debuggage si erreur         
        alert("Erreur sous genre - responseText: " + request.responseText);
      },
      dataType: "json",
      success: function (data) {
        $("#carte").fadeIn('slow');
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        // Parcours des données reçus depuis le fichier
        $.each(data.items, function (i, item) {
          if (item) {
            marker = new google.maps.Marker({
              position: new google.maps.LatLng(item.x_lieu, item.y_lieu),
              map: carte,
              icon: pinImageEvenement,
              shadow: pinShadow,
              title: item.Titre_lieu
            });
            google.maps.event.addListener(marker, 'click', (function (marker, i) {
              return function () {
                // Affichage de la légende de l'autre canal
                infowindow.setContent('<p>' + item.Titre_lieu + '</p> ');
                infowindow.open(carte, marker);
              }
              })(marker, i));
                $.each(item.parkingProche_lieu, function (i, item) {
                  if (item) {
                    marker = new google.maps.Marker({
                      position: new google.maps.LatLng(item.x_lieu, item.y_lieu),
                      map: carte,
                      icon: pinImageParkProche,
                      shadow: pinShadow,
                      title: "Parking " + item.Titre_lieu
                    });
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                      return function () {
                        // Affichage de la légende de chaque parking alentour
                        infowindow.setContent('<p> Nombre de places :' + item.Places_lieu + '<br/>Capacité :' + item.Capacite_lieu + ' </p> ');
                        infowindow.open(carte, marker);
                      }
                      })(marker, i));
                  }
                });  
                $.each(item.velibProche_lieu, function (i, item) {
                  if (item) {
                    marker = new google.maps.Marker({
                      position: new google.maps.LatLng(item.x_lieu, item.y_lieu),
                      map: carte,
                      icon: pinImageVelibProche,
                      shadow: pinShadow,
                      title: "VELIB " + item.Titre_lieu
                    });
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                      return function () {
                        // Affichage de la légende de chaque velib alentour
                        infowindow.setContent('<p> Nombre de vélos dispo :' + item.VeloDispo_lieu + '<br/>Emplacement dispo :' + item.EmplacementDispo_lieu + ' </p> ');
                        infowindow.open(carte, marker);
                      }
                      })(marker, i));
                  }
                });  
          }
        });
      }
    });
  }

  $("#butt1").click(function () {
    afficherParking();
  });
  $("#butt2").click(function () {
    afficherVelib();
  });
  $("#butt3").click(function () {
    refreshMap();
  });
  $("#butt4").click(function () {
    afficherChemin();
  });
})

