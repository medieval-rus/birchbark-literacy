/*
 * This file is part of «Birchbark Literacy from Medieval Rus» database.
 *
 * Copyright (c) Department of Linguistic and Literary Studies of the University of Padova
 *
 * «Birchbark Literacy from Medieval Rus» database is free software:
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation, version 3.
 *
 * «Birchbark Literacy from Medieval Rus» database is distributed
 * in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even
 * the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. If you have not received
 * a copy of the GNU General Public License along with
 * «Birchbark Literacy from Medieval Rus» database,
 * see <http://www.gnu.org/licenses/>.
 */

import $ from 'jquery';
import { Loader } from "@googlemaps/js-api-loader"

$(document).ready(() => {

    const apiKey = $('[data-google-maps-api-key]').attr('data-google-maps-api-key');

    const loader = new Loader({
        apiKey: apiKey,
        version: "weekly"
    });

    loader.load().then(() => {
        const mrStyle = [{"stylers":[{"color":"#666633"}]},{"elementType":"labels.text.stroke","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#f5edda"},{"visibility":"on"},{"weight":4}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#f5edda"}]},{"featureType":"poi","stylers":[{"visibility":"off"}]},{"featureType":"road","stylers":[{"visibility":"off"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#666633"}]}];

        const mrMapType = new google.maps.StyledMapType(mrStyle);

        const townsMapElement = $('[data-towns-map]');

        const towns = JSON.parse(townsMapElement.attr('data-towns-map'));

        const map = new google.maps.Map(townsMapElement[0], {
            zoom: 4,
            mapTypeControlOptions: {
                mapTypeIds: []
            }
        });

        map.mapTypes.set('mr_map', mrMapType);
        map.setMapTypeId('mr_map');

        const bounds = new google.maps.LatLngBounds();

        for (const town of towns) {

            const latLng = JSON.parse(town.latLng);

            new google.maps.InfoWindow({
                content: '<span class="town-name">' + town.name + '</span>' + ' (' + town.documentsCount + ')',
                position: latLng
            }).open(map);

            bounds.extend(latLng);

            map.fitBounds(bounds);
        }
    });
})