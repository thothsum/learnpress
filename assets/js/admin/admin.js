(function () {
    var $ = jQuery;

    var updateItemPreview = function updateItemPreview() {
        $.ajax({
            url: '',
            data: {
                'lp-ajax': 'toggle_item_preview',
                item_id: this.value,
                previewable: this.checked ? 'yes' : 'no',
                nonce: $(this).attr('data-nonce')
            },
            dataType: 'text',
            success: function success(response) {
                response = LP.parseJSON(response);
            }
        });
    };
    /**
     * Callback event for button to creating pages inside error message.
     *
     * @param {Event} e
     */


    var createPages = function createPages(e) {
        var $button = $(this).addClass('disabled');
        e.preventDefault();
        $.post({
            url: $button.attr('href'),
            data: {
                'lp-ajax': 'create-pages'
            },
            dataType: 'text',
            success: function success(res) {
                var $message = $button.closest('.lp-notice').html('<p>' + res + '</p>');
                setTimeout(function () {
                    $message.fadeOut();
                }, 2000);
            }
        });
    };

    var hideUpgradeMessage = function hideUpgradeMessage(e) {
        e.preventDefault();
        var $btn = $(this);
        $btn.closest('.lp-upgrade-notice').fadeOut();
        $.post({
            url: '',
            data: {
                'lp-hide-upgrade-message': 'yes'
            },
            success: function success(res) {
            }
        });
    };

    var pluginActions = function pluginActions(e) {
        // Premium addon
        if ($(e.target).hasClass('buy-now')) {
            return;
        }

        e.preventDefault();
        var $plugin = $(this).closest('.plugin-card');

        if ($(this).hasClass('updating-message')) {
            return;
        }

        $(this).addClass('updating-message button-working disabled');
        $.ajax({
            url: $(this).attr('href'),
            data: {},
            success: function success(r) {
                $.ajax({
                    url: window.location.href,
                    success: function success(r) {
                        var $p = $(r).find('#' + $plugin.attr('id'));

                        if ($p.length) {
                            $plugin.replaceWith($p);
                        } else {
                            $plugin.find('.plugin-action-buttons a').removeClass('updating-message button-working').html(learn_press_admin_localize.plugin_installed);
                        }
                    }
                });
            }
        });
    };

    var preventDefault = function preventDefault(e) {
        e.preventDefault();
        return false;
    };

    var onReady = function onReady() {
        $('.learn-press-dropdown-pages').LP('DropdownPages');
        $('.learn-press-advertisement-slider').LP('Advertisement', 'a', 's').appendTo($('#wpbody-content'));
        $('.learn-press-toggle-item-preview').on('change', updateItemPreview);
        $('.learn-press-tip').LP('QuickTip'); //$('.learn-press-tabs').LP('AdminTab');

        $(document).on('click', '#learn-press-create-pages', createPages).on('click', '.lp-upgrade-notice .close-notice', hideUpgradeMessage).on('click', '.plugin-action-buttons a', pluginActions).on('click', '[data-remove-confirm]', preventDefault).on('mousedown', '.lp-sortable-handle', function (e) {
            $('html, body').addClass('lp-item-moving');
            $(e.target).closest('.lp-sortable-handle').css('cursor', 'inherit');
        }).on('mouseup', function (e) {
            $('html, body').removeClass('lp-item-moving');
            $('.lp-sortable-handle').css('cursor', '');
        });

        /**
         * Function Export invoice LP Order
         * @author hungkv
         * @since 3.2.7.
         */
        if ($('#order-export__section').length) {
            const tabs = document.querySelectorAll(".tabs");
            const tab = document.querySelectorAll(".tab");
            const panel = document.querySelectorAll(".panel");

            function onTabClick(event) {

                // deactivate existing active tabs and panel

                for (let i = 0; i < tab.length; i++) {
                    tab[i].classList.remove("active");
                }

                for (let i = 0; i < panel.length; i++) {
                    panel[i].classList.remove("active");
                }


                // activate new tabs and panel
                event.target.classList.add('active');
                let classString = event.target.getAttribute('data-target');
                document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active");
            }

            for (let i = 0; i < tab.length; i++) {
                tab[i].addEventListener('click', onTabClick, false);
            }

            // modal export order to pdf

            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the button that opens the modal
            var btn = document.getElementById("order-export__button");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on the button, open the modal
            btn.onclick = function () {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function () {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            if ($('#lp-invoice__content').length) {
                $('#lp-invoice__export').click(function () {
                    var doc = new jsPDF('p', 'pt', 'letter');

                    // We'll make our own renderer to skip this editor
                    var specialElementHandlers = {
                        '#bypassme': function (element, renderer) {
                            return true;
                        }
                    };
                    var margins = {
                        top: 80,
                        bottom: 60,
                        left: 40,
                        width: 522
                    };

                    doc.fromHTML(
                        $('#lp-invoice__content')[0],
                        margins.left, // x coord
                        margins.top, { // y coord
                            'width': margins.width, // max width of content on PDF
                            'elementHandlers': specialElementHandlers
                        },
                        function (dispose) {
                            // dispose: object with X, Y of the last line add to the PDF
                            //          this allow the insertion of new lines after html
                            var blob = doc.output("blob");
                            window.open(URL.createObjectURL(blob));
                        }, margins);
                });
            }

            // Script update option export to pdf
            $('#lp-invoice__update').click(function () {
                var order_id = $(this).data('id'),
                    site_title = $('input[name="site_title"]'),
                    order_date = $('input[name="order_date"]'),
                    invoice_no = $('input[name="invoice_no"]'),
                    order_customer = $('input[name="order_customer"]'),
                    order_email = $('input[name="order_email"]'),
                    order_payment = $('input[name="order_payment"]');
                if (site_title.is(':checked')) {
                    site_title = 'check';
                } else {
                    site_title = 'uncheck';
                }
                if (order_date.is(':checked')) {
                    order_date = 'check';
                } else {
                    order_date = 'uncheck';
                }
                if (invoice_no.is(':checked')) {
                    invoice_no = 'check';
                } else {
                    invoice_no = 'uncheck';
                }
                if (order_customer.is(':checked')) {
                    order_customer = 'check';
                } else {
                    order_customer = 'uncheck';
                }
                if (order_email.is(':checked')) {
                    order_email = 'check';
                } else {
                    order_email = 'uncheck';
                }
                if (order_payment.is(':checked')) {
                    order_payment = 'check';
                } else {
                    order_payment = 'uncheck';
                }

                $.ajax({
                    type: "post",
                    dataType: "html",
                    url: 'admin-ajax.php',
                    data: {
                        site_title: site_title,
                        order_date: order_date,
                        invoice_no: invoice_no,
                        order_customer: order_customer,
                        order_email: order_email,
                        order_id: order_id,
                        order_payment: order_payment,
                        action: "learnpress_update_order_exports",
                    },
                    beforeSend: function () {
                        $('.export-options__loading').addClass('active');
                    },
                    success: function (response) {
                        $("#lp-invoice__content").html("");
                        $('#lp-invoice__content').append(response);
                        $('.export-options__loading').removeClass('active');
                        $('.options-tab').removeClass('active');
                        $('.preview-tab').addClass('active');
                        $('#panels .export-options').removeClass('active');
                        $('#panels .pdf-preview').addClass('active');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('The following error occured: ' + textStatus, errorThrown);
                    }
                });
            });
        }

        //show hide user custom time
        $(document).on("change", ".directional__opt select", function () {
            var directional_opt_val = $('.directional__opt select'),
                directional__ctime = $('.directional__ctime');
            if (directional_opt_val.val() === 'custom-time') {
                directional__ctime.addClass('active');
            } else {
                directional__ctime.removeClass('active');
                $('.directional__ctime input[name="from"]').val("");
                $('.directional__ctime input[name="to"]').val("");
            }
        });

        function search_course_by_name(){
            // ajax search course by name order statistic export
            $('#course-data__name').select2({
                minimumInputLength: 1,
                placeholder: 'Search a course',
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search query
                            action: 'learnpress_search_course_by_name' // AJAX action for admin-ajax.php
                        };
                    },
                    processResults: function (data) {
                        if (data) {
                            return {
                                results: data
                            };

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('The following error occured: ' + textStatus, errorThrown);
                    },
                    cache: true
                }
            });
        }

        if ($('.statistic-exports__directional').length) {
            $(document).on("change", ".directional__saleby select", function () {
                if ($(this).val() === 'date') {
                    $('.directional__opt').removeClass('deactive');
                    $('.directional__course').removeClass('active');
                } else if ($(this).val() === 'course') {
                    $('.directional__opt').addClass('deactive');
                    $('.directional__opt select').val('all');
                    $('.directional__course').addClass('active');
                } else {
                    $('.directional__opt').addClass('deactive');
                }
            });
        }

        // Js export statistic

        //define
        var export_statistic = $('.learn-press-statistic-exports'),
            directional_opt = $('#statistic-exports__type'),
            directional_opt_val = $('.directional__opt select'),
            directional_select = $('#export-select'),
            directional__ctime = $('.directional__ctime');

        if (export_statistic.length) {

            // Ajax load result
            var click_result = $('.statistic-exports__showpreview button'),
                content_result = $('.statistic-exports__content'),
                loading_result = $('.statistic-exports__loading');
            if ($('.statistic-exports__showpreview').length) {
                click_result.click(function () {
                    var statistic_exports_select = $('#export-select').val(),
                        statistic_exports_type = $('#statistic-exports__type').val(),
                        statistic_exports_role = $('#statistic-exports__role').val(),
                        directional_ctime_from = $('input[name="from"]').val(),
                        directional_ctime_to = $('input[name="to"]').val(),
                        directional_saleby = $('.directional__saleby select').val(),
                        directional_by_course = $('#course-data__name').val(),
                        directional_by_cat = $('#course-cat__id').val();
                    console.log(directional_by_course);
                    $.ajax({
                        type: "post",
                        dataType: "html",
                        url: 'admin-ajax.php',
                        data: {
                            select: statistic_exports_select,
                            type: statistic_exports_type,
                            role: statistic_exports_role,
                            from: directional_ctime_from,
                            to: directional_ctime_to,
                            sale_by: directional_saleby,
                            course: directional_by_course,
                            cat: directional_by_cat,
                            action: "learnpress_update_result_statistic",
                        },
                        beforeSend: function () {
                            loading_result.addClass('active');
                        },
                        success: function (response) {
                            content_result.html("");
                            content_result.append(response);
                            loading_result.removeClass('active');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('The following error occured: ' + textStatus, errorThrown);
                        }
                    }); // end ajax click result

                }); // end click result
            }

            // Ajax update_statistic_exports_directional
            if (directional_select.length) {
                directional_select.change(function () {
                    var directional_result = $('.statistic-exports__directional ul'),
                        loading_directional = $('.statistic-directional__loading');
                    $.ajax({
                        type: "post",
                        dataType: "html",
                        url: 'admin-ajax.php',
                        data: {
                            select_value: directional_select.val(),
                            action: "learnpress_update_statistic_exports_directional",
                        },
                        beforeSend: function () {
                            loading_directional.addClass('active');
                        },
                        success: function (response) {
                            directional_result.html("");
                            directional_result.append(response);
                            search_course_by_name();
                            if (typeof $.fn.datepicker != 'undefined') {
                                $(".date-picker").datepicker({
                                    dateFormat: 'yy/mm/dd'
                                });
                            }
                            loading_directional.removeClass('active');
                            $('.statistic-exports__content').html('');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('The following error occured: ' + textStatus, errorThrown);
                        }
                    }); // end ajax click result
                });
            }
        } // end check export_statistic length


    };

    $(document).ready(onReady);
})();
(function ($) {
    'use strict';

    var Package = function Package(data) {
        this.data = data;
        var currentIndex = -1,
            currentVersion = null,
            currentPackage = null,
            versions = Object.keys(this.data);

        this.reset = function (current) {
            current = current === undefined || current > versions.length - 1 || current < 0 ? 0 : current;
            currentIndex = current;
            currentVersion = versions[current];
            currentPackage = this.data[currentVersion];
            return currentPackage;
        };

        this.next = function () {
            if (currentIndex >= versions.length - 1) {
                return false;
            }

            currentIndex++;
            this.reset(currentIndex);
            return currentPackage;
        };

        this.prev = function () {
            if (currentIndex <= 0) {
                return false;
            }

            currentIndex--;
            this.reset(currentIndex);
            return currentPackage;
        };

        this.currentVersion = function () {
            return currentVersion;
        };

        this.hasPackage = function () {
            return versions.length;
        };

        this.getPercentCompleted = function () {
            return currentIndex / versions.length;
        };

        this.getTotal = function () {
            return versions.length;
        };

        if (!this.data) {
            return;
        }
    };

    var UpdaterSettings = {
        el: '#learn-press-updater',
        data: {
            packages: null,
            status: '',
            force: false
        },
        watch: {
            packages: function packages(newPackages, oldPackages) {
                if (newPackages) {
                }
            }
        },
        mounted: function mounted() {
            $(this.$el).show();
        },
        methods: {
            getUpdatePackages: function getUpdatePackages(callback) {
                var that = this;
                $.ajax({
                    url: lpGlobalSettings.admin_url,
                    data: {
                        'lp-ajax': 'get-update-packages',
                        force: this.force,
                        _wpnonce: lpGlobalSettings._wpnonce
                    },
                    success: function success(res) {
                        var packages = LP.parseJSON(res);
                        that.packages = new Package(packages);
                        callback && callback.call(that);
                    }
                });
            },
            start: function start(e, force) {
                this.packages = null;
                this.force = force;
                this.getUpdatePackages(function () {
                    if (this.packages.hasPackage()) {
                        var p = this.packages.next();
                        this.status = 'updating';
                        this.doUpdate(p);
                    }
                });
            },
            getPackages: function getPackages() {
                return this.packages ? this.packages.data : {};
            },
            hasPackage: function hasPackage() {
                return !$.isEmptyObject(this.getPackages());
            },
            updateButtonClass: function updateButtonClass() {
                return {
                    'disabled': this.status === 'updating'
                };
            },
            doUpdate: function doUpdate(p, i) {
                var that = this;
                p = p ? p : this.packages.next();
                i = i ? i : 1;

                if (p) {
                    $.ajax({
                        url: lpGlobalSettings.admin_url,
                        data: {
                            'lp-ajax': 'do-update-package',
                            "package": p,
                            version: this.packages.currentVersion(),
                            _wpnonce: lpGlobalSettings._wpnonce,
                            force: this.force,
                            i: i
                        },
                        success: function success(res) {
                            var response = LP.parseJSON(res),
                                $status = $(that.$el).find('.updater-progress-status');

                            if (response.done === 'yes') {
                                that.update(that.packages.getPercentCompleted() * 100);
                                that.doUpdate();
                            } else {
                                var newWidth = that.packages.getPercentCompleted() * 100;

                                if (response.percent) {
                                    var stepWidth = 1 / that.packages.getTotal();
                                    newWidth += stepWidth * response.percent;
                                }

                                that.update(newWidth);
                                that.doUpdate(p, ++i);
                            }
                        },
                        error: function error() {
                            that.doUpdate(p, i);
                        }
                    });
                } else {
                    that.update(100).addClass('completed');
                    setTimeout(function (x) {
                        x.status = 'completed';
                    }, 2000, this);
                }
            },
            update: function update(value) {
                return $(this.$el).find('.updater-progress-status').css('width', value + '%').attr('data-value', parseInt(value));
            }
        }
    };

    function init() {
        window.lpGlobalSettings = window.lpGlobalSettings || {};

        if ($('#learn-press-updater').length) {
            var Updater = new Vue(UpdaterSettings);
        }
    }

    $(document).ready(init);
})(jQuery);