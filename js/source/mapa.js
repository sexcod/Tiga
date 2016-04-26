var map;
var directionsDisplay;
var directionsService = new google.maps.DirectionsService();


$(document).ready(function (){ 

    
//initialize();

    $("#btnEnviar").on('click', function(){carregar()});
    $("#destino").on('change', function(){if($("#origem").val() !== '') carregar();});

    //_('btnEnviar').onclick = function(){ carregar(); }    
    //_('destino').onchange = function(){ if(_('origem').value !== '') carregar();} 

});



function initialize() { 
    directionsDisplay = new google.maps.DirectionsRenderer();
    var latlng = new google.maps.LatLng(-22.904459823131024, -43.190747841369614);
    
    var options = {
        zoom: 9,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("mapa"), options);
    directionsDisplay.setMap(map);
    //directionsDisplay.setPanel(document.getElementById("trajeto-texto"));
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {

            pontoPadrao = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            map.setCenter(pontoPadrao);
            
            var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode({
                "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
            },
            function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    $("#origem").val(results[0].formatted_address);
                }
            });
        });
    }
}

// onkeyup="FormataValor(this,13,event)"


function carregar(){ 

    var enderecoPartida = $("#origem").val();
    var enderecoChegada = $("#destino").val();

    var request = {
        origin: enderecoPartida,
        destination: enderecoChegada,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            var route = result.routes[0];

            var kmp =0;
            for (var i = 0; i < route.legs.length; i++) {
                $("#km").val(route.legs[i].distance.text);

                kmp = route.legs[i].distance.text;
            }

/*            $.ajax({
                    type: "POST",
                    url: URL+"pedido/getvalorPedido",
                    data: "km="+kmp+"&id_empresa="+$('select[name=id_empresa]').val(),
                    dataType: "html",
                    success: function(msg){  console.log(msg)
                        $("#valor").val(msg);                        
                    }
                });*/

        }
    });
};


/*
var map,
    directionsDisplay,
    directionsService;

$(window).load(function(){  
    
    directionsService = new google.maps.DirectionsService();  

    initialize();

    _('btnEnviar').onclick = function(){ carregar(); }    
    _('destino').onchange = function(){ if(_('origem').value !== '') carregar();}    
});



function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);

    var options = {
        zoom: 5,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP 
    };

    map = new google.maps.Map(document.getElementById("mapa"), options);
    directionsDisplay.setMap(map);
    directionsDisplay.setPanel(document.getElementById("trajeto-texto"));

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            pontoPadrao = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            map.setCenter(pontoPadrao);

            var geocoder = new google.maps.Geocoder();

            geocoder.geocode(
                {"location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)},
                function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        $("#origem").val(results[0].formatted_address);
                    }
                }
            );
        });
    }       
}


function carregar(){ 

    var enderecoPartida = $("#origem").val();
    var enderecoChegada = $("#destino").val();

    var request = {
        origin: enderecoPartida,
        destination: enderecoChegada,
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);

            var route = result.routes[0];
            //start_address -- Endereço de origem
            //start_location.lat() -- Latidude de origem
            //start_location.lng() -- Longitude de origem

            //end_address   -- Endereço de destino
            //end_location.lat() -- Latidude de destino
            //end_location.lng()  -- Longitude de destino

            //distance  -- Distancial em KM
            //duration  -- Duração

            var kmp =0;

            for (var i = 0; i < route.legs.length; i++) {
                $("#km").val(route.legs[i].distance.text);

                kmp = route.legs[i].distance.text;
            }

            $.ajax({
                    type: "POST",
                    url: URL+"pedido/getvalorPedido",
                    data: "km="+kmp+"&id_empresa="+$('select[name=id_empresa]').val(),
                    dataType: "html",
                    success: function(msg){  console.log(msg)
                        $("#valor").val(msg);                        
                    }
                });

        }
    });
};

*/