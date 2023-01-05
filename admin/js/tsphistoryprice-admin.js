(function ($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(window).load(function () {

        let $verInputs = $('form.tsp-update-form input#g_f_per, form.tsp-update-form input#f_f_per, form.tsp-update-form input#c_f_per, form.tsp-update-form input#s_f_per, form.tsp-update-form input#i_f_per');
        let $submitBtn = $('form.tsp-update-form input[type=submit]');
        let updChecks = {sum: false, date: true};

        function checkSumm() {
            let sum = 0;
            $verInputs.each(function () {
                sum += +$(this).val();
            });

            if (sum == 100) {
                updChecks.sum = true;
                $('form.tsp-update-form .notice-error.tsp-sum-err').remove();
            } else {
                updChecks.sum = false;
                if ($('.notice-error.tsp-sum-err').length == 0) {
                    $submitBtn.parent().before('<div class="notice notice-error tsp-sum-err"><p>The sum of all funds must equal 100%. Please check.</p></div>')
                }
            }
            updUnlock(updChecks);

            console.log('Fund Sum = ' + sum);
        }

        function ajaxDateCheck(inp) {
            jQuery.ajax({
                type: "POST",
                url: "/wp-admin/admin-ajax.php",
                data: {
                    action: 'tsphp_trade_date_check',
                    dateValue: inp,
                    // periodVal: $(e.target).val(),
                },
                success: function (output) {
                    let resp = JSON.parse(output);

                    console.log(JSON.parse(output));

                    // $submitBtn.parent().before(`<div class="notice notice-error tsp-date-err"><p>${resp.msg}</p></div>`)

                    if (resp.exist) {
                         // updChecks.date = true;
                        $('form.tsp-update-form .notice-warning.tsp-date-err').remove();

                    } else {

                         // updChecks.date = true;

                        if ($('.notice-warning.tsp-date-err').length == 0) {
                            $submitBtn.parent().before(`<div class="notice notice-warning tsp-date-err"><p>${resp.msg}</p></div>`)
                        }
                    }
                     updUnlock(updChecks);


                    // window.location.reload();

                }
            });
        }

        function updUnlock(obj) {
            $submitBtn.attr('disabled', '');
            if (obj.sum && obj.date) {
                $submitBtn.removeAttr('disabled');
            }
            console.log(obj);
        }

        if ($('form.tsp-update-form').length > 0) {
            ajaxDateCheck($('form.tsp-update-form #trade_date').val());
            $('form.tsp-update-form input[type=date]').on('change mouseup', (e) => {
                // console.log($(e.target).val())

                // updChecks.date = false;
                updUnlock(updChecks);
                console.log($(e.target))

                if ($(e.target).attr('id') == 'alert_date') {
                    $('#trade_date').attr('min', `${$('#alert_date').val()}`);
                    $('#trade_date').val(`${$('#alert_date').val()}`);
                }

                // $submitBtn.attr('disabled', '');

                if ($(e.target).val()) {
                    ajaxDateCheck($(e.target).val());
                }

            });


//save values from table
//             $('.tsphs_table input[data-input-id]').change((e) => {
//                 console.log($(e.target));
//                 console.log($(e.target).prop('value'));
//                 console.log($(e.target).attr('data-col-slug'));
//                 console.log($(e.target).attr('data-input-id'));
//
//                 jQuery.ajax({
//                     type: "POST",
//                     url: "/wp-admin/admin-ajax.php",
//                     data: {
//                         action: 'tsphp_update_period_data',
//                         inputID: $(e.target).attr('data-input-id'),
//                         inputVal: $(e.target).prop('value'),
//                         inputSlug: $(e.target).attr('data-col-slug'),
//                     },
//                     success: function (output) {
//                         console.log(output);
//                         // window.location.reload();
//
//                     }
//                 });
//
//             });


            //max100% check
            if ($verInputs.length > 0) checkSumm();
            $verInputs.on('change mouseup', (e) => {
                checkSumm();
            });
            //max100% check end


        }

//new page
//         if ($('#add-new-alert-wrap, #add-new-alert-wrap-switch').length > 1) {
//             $(` #add-new-alert-wrap-switch`).click(() => {
//                 $(this).toggleClass('active');
//                 $(`#add-new-alert-wrap`).toggleClass('active');
//             });
//         }


//fill check
//         $('form.tsp-add-alert-form.form input[name="alert_date"]').on('change mouseup', (e) => {
//             // console.log($(e.target).closest('.tsp-update-form').find('textarea, input:not(input[type=submit]):not(input[type=date])'));
//             let $editable = $('#alert_date').val().length > 1;
//             $(e.target).closest('.tsp-add-alert-form').find('textarea, input:not(#alert_date) ').each(function () {
//                 console.log($editable)
//
//                 if ($(this).attr('name') !== 'alert_date' && !$editable) {
//                     $(this).attr('disabled', '');
//
//                 } else {
//                     $(this).removeAttr('disabled');
//                     if ($(this).attr('name') == 'trade_date') {
//                         $(this).attr('min', `${$('#alert_date').val()}`);
//                         $(this).val(`${$('#alert_date').val()}`);
//                     }
//
//                 }
//
//             })
//         });


        //update page
        // $('form.tsp-update-form.form input[type="date"]').on('change mouseup', (e) => {
        //     // console.log($(e.target).closest('.tsp-update-form').find('textarea, input:not(input[type=submit]):not(input[type=date])'));
        //     let $editable = $('#alert_date').val().length > 1;
        //     $(e.target).closest('.tsp-update-form').find('textarea, input ').each(function () {
        //         console.log($editable)
        //
        //         if ($(this).attr('type') !== 'date' && !$editable) {
        //             $(this).attr('disabled', '');
        //
        //         } else {
        //             $(this).removeAttr('disabled');
        //         }
        //
        //     })
        // });

        //calc table green-yellow row
        $('.tsphs_table_content .tsphs_table .trade_date input[data-next-alert]').each(function () {
            if ($(this).attr('data-next-alert').includes('T00:00')) {
                $(this).closest('.next-alert').removeClass('active');
                $(this).val($(this).attr('data-next-alert').replace('T00:00', ''))
            }
        })

    });

})(jQuery);
