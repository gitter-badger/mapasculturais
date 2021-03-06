MapasCulturais.Map = {};

MapasCulturais.Map.initialize = function(initializerOptions) {


    MapasCulturais.Map.iconOptions = {
        agent: {icon: L.icon({
                iconUrl: MapasCulturais.assetURL + '/img/pin-agente.png',
                shadowUrl: MapasCulturais.assetURL + '/img/pin-sombra.png',
                iconSize: [35, 43], // size of the icon
                shadowSize: [40, 16], // size of the shadow
                iconAnchor: [20, 30], // point of the icon which will correspond to marker's location
                shadowAnchor: [6, 3], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })},
        coletivo: {icon: L.icon({
                iconUrl: MapasCulturais.assetURL + '/img/pin-agente.png',
                shadowUrl: MapasCulturais.assetURL + '/img/pin-sombra.png',
                iconSize: [35, 43], // size of the icon
                shadowSize: [40, 16], // size of the shadow
                iconAnchor: [20, 30], // point of the icon which will correspond to marker's location
                shadowAnchor: [6, 3], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })},
        space: {icon: L.icon({
                iconUrl: MapasCulturais.assetURL + '/img/pin-espaco.png',
                shadowUrl: MapasCulturais.assetURL + '/img/pin-sombra.png',
                iconSize: [35, 43], // size of the icon
                shadowSize: [40, 16], // size of the shadow
                iconAnchor: [20, 30], // point of the icon which will correspond to marker's location
                shadowAnchor: [6, 3], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })},
        event: {icon: L.icon({
                iconUrl: MapasCulturais.assetURL + '/img/pin-evento.png',
                shadowUrl: MapasCulturais.assetURL + '/img/pin-sombra.png',
                iconSize: [35, 43], // size of the icon
                shadowSize: [40, 16], // size of the shadow
                iconAnchor: [20, 30], // point of the icon which will correspond to marker's location
                shadowAnchor: [6, 3], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })},
        location: {icon: L.icon({
                iconUrl: MapasCulturais.assetURL + '/img/marker-icon.png',
                shadowUrl: MapasCulturais.assetURL + '/img/pin-sombra.png',
                iconSize: [35, 43], // size of the icon
                shadowSize: [40, 16], // size of the shadow
                iconAnchor: [20, 30], // point of the icon which will correspond to marker's location
                shadowAnchor: [6, 3], // the same for the shadow
                popupAnchor: [-3, -76] // point from which the popup should open relative to the iconAnchor
        })},
    };

    if(initializerOptions.exportToGlobalScope){
        window.leaflet = {};
        window.leaflet.iconOptions = MapasCulturais.Map.iconOptions;
    }

    //jQuery(document).ready(function() {

        var mapSelector = initializerOptions.mapSelector;

        var changePrecision = function(value, isPrecise, map, mapMarkerLayer, circle, $dataTarget) {
            var mapId = map._container.id;
            if (value) {
                $('#' + mapId).parent().show();
            } else {
                $('#' + mapId).parent().hide();
                $dataTarget.editable('setValue', [0, 0]);
            }
            if (!isPrecise) {
                mapMarkerLayer.setIcon(new L.divIcon({className: 'marker-circle-icon', iconSize: new L.Point(circle._radius * 2, circle._radius * 2)}));
                map.addLayer(circle);
                setTimeout(function() {
                    map.setZoom(14);
                });
            } else {
                mapMarkerLayer.setIcon(MapasCulturais.Map.iconOptions[MapasCulturais.request.controller].icon);
                map.removeLayer(circle);
                setTimeout(function() {
                    map.setZoom(16);
                }, 200);
            }
        };


        $(mapSelector).each(function() {


            // });



            var id = $(this).attr('id');
            var isEditable = initializerOptions.isMapEditable===false ? false : MapasCulturais.isEditable;
            if (!isEditable)
                $('#' + id + ':active').css({'cursor': 'default'});
            var $dataTarget = $('#' + id + '-target');
            var isPositionDefined = $(this).data('lat') ? true : false;
            var defaultZoom = isPositionDefined ? 16 : 10;
            var defaultLocateMaxZoom = 16;
            var defaultAproximatePrecisionZoom = 14;
            var defaultMaxCircleRadius = 1000;
            var $dataPrecisionOption = $('#' + id + '-precisionOption');
            //var dataPrecisionOptionFieldName = $dataPrecisionOption.data('edit'); //precisao
            //var $dataPrecision = $('[data-edit="'+dataPrecisionOptionField+'"]').html();
            var dataPrecisionValue = $dataPrecisionOption.html();
            var dataPrecisionTrueValue = $dataPrecisionOption.data('truevalue');
            //$('#mapa-precisionOption').editable('getValue')[$('#mapa-precisionOption').data('edit')];
            var isPrecise = (dataPrecisionValue == dataPrecisionTrueValue);
            var defaultCircleStrokeWeight = 2;
            var saoPaulo = new L.LatLng(-23.54894, -46.63882);
            var mapCenter = isPositionDefined ? new L.LatLng($(this).data('lat'), $(this).data('lng')) : saoPaulo;
            var options = $(this).data('options') ? $(this).data('options') : {dragging: true, zoomControl: true, doubleClickZoom: true, scrollWheelZoom: true};

            var locateMeControl = initializerOptions.locateMeControl ? true : false;

            if(initializerOptions.mapCenter){
                options.center = new L.LatLng( initializerOptions.mapCenter.lat, initializerOptions.mapCenter.lng);
            }else{
                options.center = mapCenter;
            }

            options.zoom = defaultZoom;
            options.zoomControl = false;
            options.minZoom = 3;
            var openStreetMap = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: 'Dados e Imagens &copy; <a href="http://www.openstreetmap.org/copyright">Contrib. OpenStreetMap</a>, ',
                maxZoom: 18
            });
            var map = new L.Map(id, options).addLayer(openStreetMap);
            $(this).data('leaflet-map', map);
            var timeout;
            $(window).scroll(function() {
                map.scrollWheelZoom.disable();
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    if(!MapasCulturais.reenableScrollWheelZoom)
                        map.scrollWheelZoom.enable();
                }, 400);
            });

            var marker = new L.marker(map.getCenter(), {draggable: isEditable && MapasCulturais.request.controller !== 'event' });
            var markerIcon = {};
            if (MapasCulturais.request.controller == 'agent' || MapasCulturais.request.controller == 'space')
                markerIcon = MapasCulturais.Map.iconOptions[MapasCulturais.request.controller].icon;
            else if(MapasCulturais.request.controller == 'event')
                markerIcon = MapasCulturais.Map.iconOptions['space'].icon;
            if(Object.keys(markerIcon).length)
                marker.setIcon(markerIcon);

            map.addLayer(marker);


            var circle = new L.Circle(mapCenter, defaultMaxCircleRadius, {className : 'vetorial-padrao'});
            circle.addTo(map);

            var circleIcon = L.divIcon({
                className: 'marker-circle-icon',
                iconSize: new L.Point(circle._radius * 2, circle._radius * 2)
            });

            marker.on('move', function(e) {
                //var position = e.latlng;
                circle.setLatLng(e.latlng);
                //se for só visualização, não tem editable, não seta valor
                if (isEditable)
                    $dataTarget.editable('setValue', [e.latlng.lng, e.latlng.lat]);

            });


            if (!isPrecise) {
                marker.setIcon(new L.divIcon({className: 'marker-circle-icon', iconSize: new L.Point(circle._radius * 2, circle._radius * 2)}));
                setTimeout(function() {
                    map.setZoom(defaultAproximatePrecisionZoom);
                });
            } else {
                map.removeLayer(circle);
                //map.setZoom(defaultLocateMaxZoom);
            }

            map.on('zoomend', function() {
                if (!isPrecise)
                    marker.setIcon(new L.divIcon({className: 'marker-circle-icon', iconSize: new L.Point(circle._radius * 2, circle._radius * 2)}));
            });

            if (isPositionDefined) {
                marker.setLatLng(mapCenter).addTo(map);
            } else {
                // Find the user location
                //map.locate({setView : true, maxZoom:defaultLocateMaxZoom});

                //Só esconde o mapa caso exista a opção de alterar precisão. Caso contrário, sempre mostra
                if ($dataPrecisionOption.length)
                    $(this).parent().hide();
            }



            /* Events */
            map.on('locationfound', function(e) {
                var radius = e.accuracy / 2;
                if (true || radius > defaultMaxCircleRadius)
                    radius = defaultMaxCircleRadius;

                marker.setLatLng(e.latlng);
                //circle = new L.Circle(mapCenter, defaultMaxCircleRadius, {draggable: true, weight:defaultCircleStrokeWeight});
                if (!isPrecise)
                    marker.setIcon(circleIcon);
                else
                    map.removeLayer(circle);
            });

            map.on('locationerror', function(e) {
                /** @TODO feedback pro usuario **/
                // console.log(e.message);
            });


            marker.on('drag', function(e) {
                circle.setLatLng(e.target.getLatLng());
            });

            map.on('click', function(e) {

                //se for só visualização, não edição
                if (isEditable && MapasCulturais.request.controller !== 'event')
                    marker.setLatLng(e.latlng);
            });

            var $dataPrecisionRadios = $('input[name="' + id + '-precisionOption"]');
            $dataPrecisionRadios.each(function() {
                $(this).on('change', function() {

                    var editable = $('#' + id + '-precisionOption').data('editable');
                    editable.setValue(this.value);
                    isPrecise = (this.value == dataPrecisionTrueValue);
                    changePrecision(this.value, isPrecise, map, marker, circle, $dataTarget);
                });
            });

            $dataPrecisionOption.on("shown", function(e) {
                var editable = $(this).data('editable');
                if (!editable.input.$input)
                    return;

                editable.input.$input.on('change', function(ev) {
                    editable.setValue(this.value);
                    editable.hide('save');
                    editable.$element.triggerHandler('changePrecision');
                });

            });

            $dataPrecisionOption.on("changePrecision", function() {
                var editable = $(this).data('editable');
                var v = editable.input.$input.val();
                isPrecise = (v == dataPrecisionTrueValue);
                changePrecision(v, isPrecise, map, marker, circle, $dataTarget);
            });

            $('#buttonLocateMe').click(function() {
                map.locate({setView: true, maxZoom: defaultLocateMaxZoom});
            });

            L.Polygon.prototype.getCenter = function() {
                var pts = this._latlngs;
                var off = pts[0];
                var twicearea = 0;
                var x = 0;
                var y = 0;
                var nPts = pts.length;
                var p1, p2;
                var f;
                for (var i = 0, j = nPts - 1; i < nPts; j = i++) {
                    p1 = pts[i];
                    p2 = pts[j];
                    f = (p1.lat - off.lat) * (p2.lng - off.lng) - (p2.lat - off.lat) * (p1.lng - off.lng);
                    twicearea += f;
                    x += (p1.lat + p2.lat - 2 * off.lat) * f;
                    y += (p1.lng + p2.lng - 2 * off.lng) * f;
                }
                f = twicearea * 3;
                return new L.LatLng(
                    x / f + off.lat,
                    y / f + off.lng
                    );
            };


            var subprefs = new lvector.PRWSF({
                url: MapasCulturais.vectorLayersURL,
                geotable: '"sp_subprefeitura"',
                fields: "gid,nome",
                //where: 'true',
                geomFieldName: "ST_SimplifyPreserveTopology(the_geom,0.001)",
                uniqueField: "gid",
                srid: 4326,
                showAll: true,
                mouseoverEvent: function(feature, event) {
                    var labelText = feature.properties.nome ? feature.properties.nome : feature.properties.nome_distr;
                    feature.vector.bindLabel('<b style="text-transform: capitalize;">' + labelText.toLowerCase() + '</b>');
                    map.showLabel(feature.vector.label.setLatLng(feature.vector.getCenter()));
                },
                singlePopup: true,
                symbology: {
                    type: "single",
                    vectorOptions: {
                        // fillColor: "rgba(255,255,0,0.8)",
                        // fillOpacity: 0.2,
                        // weight: 1.0,
                        // color: "rgba(255,255,0,0.8)",
                        // opacity: 1
                        //     //clickable: true
                        className : 'vetorial-sp'
                    }
                }
            });



            $('#buttonSubprefs').click(function() {
                subprefs.setMap(map);
            });
            $('#buttonSubprefs_off').click(function() {
                subprefs.setMap(null);
            });




            /*Esconde os controles antigos por enquanto*/
            $('.btn-group[data-toggle="buttons-radio"],#buttonLocateMe').hide();
            $('#buttonSubprefs, #buttonSubprefs_off').hide();



            // activate google service

            var geocoder = null;
            if(typeof google !== 'undefined')
                geocoder =  new google.maps.Geocoder();

            // callback to handle google geolocation result
            function geocode_callback(results, status) {
                if(typeof google === 'undefined'){
                    return false;
                }
                if (status == google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    var foundLocation = new L.latLng(location.lat(), location.lng());
                    map.setView(foundLocation, 15);
                    marker.setLatLng(foundLocation);
                }
            }

            $('.js-editable').on('save', function(e, params) {
                if ($(this).data('edit') == 'endereco') {
                    $(this).trigger('changeAddress', params.newValue);
                }
            });

            $('.js-editable[data-edit="endereco"]').on('changeAddress', function(event, strAddress){
                geocoder.geocode({'address': strAddress + ', Brasil'}, geocode_callback);
            });

            //Mais controles
            if (isEditable) {
                var locateMeControl = L.Control.extend({
                    options: {
                        position: 'topright'
                    },
                    onAdd: function(map) {
                        var controlDiv = L.DomUtil.create('div', 'leaflet-control-command');
                        L.DomEvent
                            .addListener(controlDiv, 'click', L.DomEvent.stopPropagation)
                            .addListener(controlDiv, 'click', L.DomEvent.preventDefault)
                            .addListener(controlDiv, 'click', function() {
                                map.locate({setView: true, maxZoom: defaultLocateMaxZoom});
                            });

                        var controlUI = L.DomUtil.create('div', 'leaflet-control-command-interior', controlDiv);
                        controlUI.title = 'Localizar sua posição através do navegador';
                        controlUI.innerHTML = '<span class="icone icon_pin"></span> Localize-me';
                        return controlDiv;
                    }
                });

                if (initializerOptions.locateMeControl)
                    map.addControl(new locateMeControl({}));
            }


            var camadasBase = {};
            camadasBase['OpenStreetMap'] = openStreetMap;

            if(typeof google !== 'undefined') {
                var googleSatelite = new L.Google();

                var googleMapa = new L.Google();
                googleMapa._type = 'ROADMAP';
                googleMapa.options.maxZoom = 23;

                var googleHibrido = new L.Google();
                googleHibrido._type = 'HYBRID';

                var googleRelevo = new L.Google();
                googleRelevo._type = 'TERRAIN';
                googleRelevo.options.maxZoom = 15;


                /*Criação do Mapa*/
                var camadasGoogle = {
                    "Google Satélite": googleHibrido,
                    "Google Mapa": googleMapa,
                    "Google Satélite Puro": googleSatelite,
                    "Google Relevo": googleRelevo
                };

                for ( var key in camadasGoogle) {
                    camadasBase[key] = camadasGoogle[key];
                };
            }

            var regioes = {onAdd:function(map){return;}, onRemove:function(map){return;}};
            var subprefeituras = {onAdd:function(map){return;}, onRemove:function(map){return;}};
            var distritos = {onAdd:function(map){return;}, onRemove:function(map){return;}};

            /*Controles*/
            (new L.Control.FullScreen({position: 'bottomright', title: 'Tela Cheia'})).addTo(map);
            (new L.Control.Zoom({position: 'bottomright'})).addTo(map);
            var layersControl = new L.Control.Layers(
                camadasBase,
                {
                    '<span class="js-sp-geo" data-geot="regiao" data-fds="gid,nome">Regiões</span>': regioes,
                    '<span class="js-sp-geo" data-geot="subprefeitura" data-fds="gid,nome">Subprefeituras</span>': subprefeituras,
                    '<span class="js-sp-geo" data-geot="distrito" data-fds="gid,nome_distr">Distritos</span>': distritos
                }
            );
            layersControl.addTo(map);


            $('.js-sp-geo').each(function(){
                var $checkbox = $(this).parents('label').find('input:checkbox');
                var geotable = $(this).data('geot');
                var fields = $(this).data('fds');

                $checkbox.on('click', function(event){
                    subprefs.setMap(null);

                    if ($(this).prop('checked') === true) {
                        $('.js-sp-geo').not('[data-geot="'+geotable+'"]').each(function(){
                            $(this).parents('label').find('input:checkbox').prop('checked', false);
                        });

                        subprefs.options.geotable = '"sp_'+geotable+'"';
                        subprefs.options.fields = fields;
                        subprefs.setMap(map);
                    } else {
                        subprefs.setMap(null);
                    }
                });
            });

            subprefs._makeJsonpRequest = function(url){
                $('#resultados span[ng-if="!spinnerCount"]').hide();
                $('#resultados span[ng-show="spinnerCount > 0"]').removeClass('ng-hide');
                $.ajax({
                    url: url,
                    dataType: 'jsonp',
                    //jsonpCallback: myCallback,
                    cache: true,
                    success: function(data) {
                        subprefs._processFeatures(data);
                        $('#resultados span[ng-if="!spinnerCount"]').show();
                        $('#resultados span[ng-show="spinnerCount > 0"]').addClass('ng-hide');

                        $('.js-sp-geo').each(function(){
                            var $checkbox = $(this).parents('label').find('input:checkbox');
                            var geotable = $(this).data('geot');
                            if(subprefs.options.geotable !== '"sp_'+$(this).data('geot')+'"'){
                                $checkbox.prop('checked', false);
                            }else{
                                $checkbox.prop('checked', true);
                            }
                        });

                    }
                });

            };


            if (initializerOptions.exportToGlobalScope) {
                window.leaflet.map = map;
                window.leaflet.circle = circle;
                window.leaflet.marker = marker;
            }

        });


        $('.js-leaflet-control').each(function(){
            var $control = $(this);
            $control.addClass('leaflet-control');
            $('.leaflet-control-container').each(function(){
                $(this).find($control.data('leaflet-target')).append($control);
            });
        });

        $('.js-leaflet-control').on('click dblclick mousedown startdrag', function(e){
            e.stopPropagation();
        });

};

// Fix Leaflet FUllScreen control that not allows keyboard inputs
(function(){
    window.fullScreenApi.requestFullScreen = function(el) {

        //Change the element to use <html> tag
        el = document.querySelector('html');

        //Add permission to allow keyboard input
        return (this.prefix === '') ?
            el.requestFullscreen(Element.ALLOW_KEYBOARD_INPUT)
        :
            el[this.prefix + 'RequestFullScreen'](Element.ALLOW_KEYBOARD_INPUT);

        //Scroll the window to the bottom
        //didn't work window.scrollTo(0,document.body.scrollHeight);
    };
})();

