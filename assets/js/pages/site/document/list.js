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
import 'jquery-ui/ui/widgets/slider';

$(function() {

    $('[data-mr-number-filter]').val('');

    const conventionalDateLabelYearsElement = $('#conventional-date-label-years');
    const conventionalDateInitialYearElement = $('#conventionalDateInitialYear');
    const conventionalDateFinalYearElement = $('#conventionalDateFinalYear');

    const conventionalDateSliderElement = $('#conventional-date-range-slider');

    const step = 10;

    let minimalDate = conventionalDateSliderElement.attr('data-minimal-date');
    const maximalDate = conventionalDateSliderElement.attr('data-maximal-date');

    minimalDate -= minimalDate % step;

    conventionalDateSliderElement.slider({
        range: true,
        step: step,
        min: minimalDate,
        max: maximalDate,
        values: [conventionalDateInitialYearElement.val() || minimalDate, conventionalDateFinalYearElement.val() || maximalDate],
        slide: function(event, ui) {
            manageSliderValues(ui.values);
        }
    });

    manageSliderValues(conventionalDateSliderElement.slider('values'));

    function manageSliderValues(values) {
        conventionalDateLabelYearsElement.text(values[0] + ' — ' + values[1]);
        conventionalDateInitialYearElement.val(values[0]);
        conventionalDateFinalYearElement.val(values[1]);
    }
});