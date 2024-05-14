define([
    'jquery',
    'selectize'
], function ($, selectize) {
    'use strict';
    return function (config) {
        $(config.selector).selectize({
            valueField: 'id',
            labelField: 'label',
            searchField: ['make', 'model', 'year'],
            options: config.options,
            create: false,
            render: {
                option: function (item, escape) {
                    return '<div>' +
                        '<span class="title">' +
                        '<span class="car-make">' + escape(item.make) + '</span>' +
                        '<span class="car-model">' + ' ' + escape(item.model) + '</span>' +
                        '<span class="car-year">' + ' ' + escape(item.year) +  '</span>' +
                        '</span>' +
                        '</div>';
                }
            },
            maxItems: 1,
            onChange: function (value) {
                $(config.hiddenInputSelector).val(value);
            }
        });

        $('#car_make').change(function () {
            var selectedMake = $(this).val();
            var selectize = $(config.selector)[0].selectize;
            var url = config.сarListApiUrl;
            $.ajax({
                url: url,
                data: { make: selectedMake },
                success: function(response) {
                    selectize.clearOptions();
                    selectize.addOption(response);
                    selectize.refreshOptions();
                },
                error: function() {
                    console.error('Failed to load cars for make: ' + selectedMake);
                }
            });
        });
    };
});
