/*
 * Customizer Scripts
 */
jQuery(document).ready(function () {

    document.body.classList.add('wt-decorator-customizer');
    jQuery('.wp-picker-default').hide();
    jQuery('.wp-picker-open').css({'width': '50%'});
    jQuery("#customize-header-actions").removeClass("wp-full-overlay-header");
    jQuery("#customize-header-actions").addClass("wt-full-overlay-header");
    var status_arr;
    var i=0;
    var btn_id;
    jQuery("#publish-settings").appendTo("#wt_div_publish");
    var email_type = jQuery('#_customize-input-rp_decorator_email_type').val();
    let content_tab = document.getElementById("sub-accordion-section-rp_decorator_text_editor").querySelectorAll('li');

    content_tab.forEach((item, index) => {
        var content_li = item.id;
        if (content_li != 'customize-control-shortcodes') {
            if (email_type == 'wt_smart_coupon') {
                if (content_li.indexOf('wt_smart_coupon_settings') > -1 || content_li.indexOf('wt_smart_coupon_subtitle') > -1 || content_li.indexOf('wt_smart_coupon_body') > -1)
                {
                    jQuery('#' + content_li).show();
                } else {
                    jQuery('#' + content_li).hide();
                }

            } else {
                if (content_li.indexOf(email_type) > -1)
                {
                    jQuery('#' + content_li).show();
                } else {
                    jQuery('#' + content_li).hide();
                }
                
                if (content_li.indexOf('body_text_enable_switch') > -1)
                {
                    jQuery('#' + content_li).css({'height': '45px', });
                }
            }
        }
    });
    
    jQuery("button").click(function() {
        
       if (this.id.indexOf('_wt_reset') > -1)
        {
            var eid = this.id.replace("_wt_reset", "");
            var new_eid = eid.replace("rp_decorator_", "");
            var sid = 'rp_decorator['+new_eid+']';
            var data = {
                wp_customize: 'on',
                action: 'wt_send_reset_slider',
                selector: new_eid,
            };

            // Send request to server
            jQuery.post(rp_decorator.ajax_url, data, function (result) {  
                if(result.data){
                    jQuery( "[data-customize-setting-link='"+ sid +"']" ).val(result.data).trigger('change'); 
                    jQuery( "[data-customize-setting-link='"+ sid +"']" ).next('.range-slider__value').text(result.data+'px');
                }
            });
     
        }
    });

   let address_content_tab = document.getElementById("sub-accordion-section-rp_decorator_address_table").querySelectorAll('li');

    address_content_tab.forEach((item, index) => {
        var content_li = item.id;
            if (content_li.indexOf('address_subtitle') > -1){
                if (content_li.indexOf(email_type) > -1)
                {
                    jQuery('#' + content_li).show();
                } else {
                    jQuery('#' + content_li).hide();
                }
            }
    });

    let img_content_tab = document.getElementById("sub-accordion-section-rp_decorator_header_image").querySelectorAll('li');

    img_content_tab.forEach((item, index) => {
        var content_li = item.id;
        if (content_li.indexOf('image_link_btn_switch') > -1)
        {
            if (content_li.indexOf(email_type) > -1)
            {
                jQuery('#' + content_li).show();
            } else {
                jQuery('#' + content_li).hide();
            }
        }
    });

    jQuery('#_customize-input-social_links_enable').change(function () {
        let social_content_tab = document.getElementById("sub-accordion-section-rp_decorator_social_links").querySelectorAll('li');
        var social_links_enable = jQuery('#_customize-input-social_links_enable').val();
        social_content_tab.forEach((item, index) => {
            var contents_li = item.id;
            if (contents_li != 'customize-control-social_links_enable') {
                if (social_links_enable == 'normal') {
                    jQuery('#' + contents_li).hide();
                } else {
                    jQuery('#' + contents_li).show();
                }
            }
        });
    });

    let social_content_tab = document.getElementById("sub-accordion-section-rp_decorator_social_links").querySelectorAll('li');
    var social_links_enable = jQuery('#_customize-input-social_links_enable').val();
    social_content_tab.forEach((item, index) => {
        var contents_li = item.id;
        if (contents_li != 'customize-control-social_links_enable') {
            if (social_links_enable == 'normal') {
                jQuery('#' + contents_li).hide();
            } else {
                jQuery('#' + contents_li).show();
            }
        }
    });

    var data = {
        wp_customize: 'on',
        action: 'rp_decorator_button_text',
    };
    jQuery.ajax({
        url: rp_decorator.ajax_url,
        type: 'POST',
        data: data,
        success: function (response) {
            status_arr = response.data;
            jQuery('#save').prop('disabled', false);
            jQuery('#save').val();
            jQuery('#wt_btn_settings').bind('click', function () {
                if (jQuery('.wt_send_mail_box').is(':visible')) {
                    jQuery(".wt_send_mail_box").hide();
                }
                var iframe = jQuery('iframe').contents();
                iframe.find("body").click(function () {
                    jQuery("#wt_button_actions").hide();
                });

                if (jQuery('#wt_button_actions').is(':visible')) {
                    jQuery('#wt_button_actions').hide();
                } else if (!jQuery('#wt_schedule_box').is(':visible')) {
                    jQuery('#wt_button_actions').show();
                }
            });
            var val_bck = jQuery('#_customize-input-rp_decorator_heading_font_family').val();
            jQuery('#_customize-input-rp_decorator_heading_font_family option').each(function () {
                if (val_bck != jQuery(this).val())
                {
                    jQuery('#_customize-input-rp_decorator_heading_font_family').val(jQuery(this).attr('value')).trigger('change');
                    return false;
                }
            });
            jQuery('#_customize-input-rp_decorator_heading_font_family').val(val_bck).trigger('change');
            var cutom_id = jQuery("input[name^='customize-selected-changeset-status-control-input']").attr('id');
            var result = cutom_id.replace("customize-selected-changeset-status-control-input-", '');
            btn_id = result.split('-')[0];
            if (status_arr.drafted.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-draft').trigger('click');

            } else if (status_arr.scheduled.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-future').trigger('click');
            } else {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-publish').trigger('click');
            }

            var save_btn_text = wp.customize.state('selectedChangesetStatus').get();

            if (save_btn_text == 'draft') {
                jQuery('#wt_btn_draft').hide();
                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#298ADC'});
                jQuery('#wt_btn_settings').css({'background': '#298ADC'});
            } else if (save_btn_text == 'publish') {
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#78C77C', });
                jQuery('#wt_btn_publish').hide();
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#2271b1'});
                jQuery('#wt_btn_settings').css({'background': '#2271b1 !important'});

            } else if (save_btn_text == 'future') {

                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#55C6CE', });
                jQuery('#wt_btn_schedule').hide();
                jQuery('#save').css({'background': '#55C6CE'});
                jQuery('#wt_btn_settings').css({'background': '#55C6CE '});
            }

        }
    });

    jQuery(".wt-decorator-customizer input").change(function(){
        i = i+1;
    });
    
    jQuery(".wt-decorator-customizer button").click(function(){
      i = i+1;
    });
    
    jQuery(".wt-decorator-customizer select").click(function(){
      i = i+1;
    });

    jQuery('#wt_btn_draft').click(function () {
        jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-draft').trigger('click');
        jQuery('#wt_button_actions').hide();
        jQuery('#wt_btn_draft').hide();
        jQuery('#wt_btn_publish').show();
        jQuery('#wt_btn_publish').css({'background': '#78C77C', });
        jQuery('#wt_btn_schedule').show();
        jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
        jQuery('#save').css({'background': '#298ADC'});
        jQuery('#wt_btn_settings').css({'background': '#298ADC'});
        jQuery('#save').trigger('click');
    });
    jQuery('#wt_btn_publish').click(function () {
        jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-publish').trigger('click');
        jQuery('#wt_button_actions').hide();
        jQuery('#wt_btn_draft').show();
        jQuery('#wt_btn_draft').css({'background': '#78C77C', });
        jQuery('#wt_btn_publish').hide();
        jQuery('#wt_btn_schedule').show();
        jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
        jQuery('#save').css({'background': '#2271b1'});
        jQuery('#wt_btn_settings').css({'background': '#2271b1'});
        jQuery('#save').trigger('click');
    });
    jQuery('#wt_btn_schedule').click(function () {
        var save_btn_text = jQuery('#save').val();
        jQuery('#wt_schedule_box').show();
        jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-future').trigger('click');
        jQuery("div.customize-control-notifications-container").appendTo("#wt_dc_dropdown_test");
        jQuery("div.date-time-fields.includes-time").appendTo("#wt_dc_dropdown_test");
        jQuery('#wt_button_actions').hide();
        var iframe = jQuery('iframe').contents();
        iframe.find("body").click(function () {
            var cutom_id = jQuery("input[name^='customize-selected-changeset-status-control-input']").attr('id');
            var result = cutom_id.replace("customize-selected-changeset-status-control-input-", '');
            btn_id = result.split('-')[0];
            if (status_arr.drafted.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-draft').trigger('click');
                jQuery('#save').css({'background': '#2271b1 !important'});
                jQuery('#wt_btn_settings').css({'background': '#2271b1 !important'});
            } else if (status_arr.scheduled.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-future').trigger('click');
            } else {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-publish').trigger('click');
                jQuery('#save').css({'background': '#2271b1'});
                jQuery('#wt_btn_settings').css({'background': '#2271b1'});
            }
            jQuery(".wt_schedule_box").hide();
            if (save_btn_text == 'Save Draft') {
                jQuery('#wt_btn_draft').hide();
                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#298ADC'});
                jQuery('#wt_btn_settings').css({'background': '#298ADC'});
            } else if (save_btn_text == 'Publish') {
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#78C77C', });
                jQuery('#wt_btn_publish').hide();
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#2271b1'});
                jQuery('#wt_btn_settings').css({'background': '#2271b1 !important'});

            } else if (save_btn_text == 'Schedule') {

                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#55C6CE', });
                jQuery('#wt_btn_schedule').hide();
                jQuery('#save').css({'background': '#55C6CE'});
                jQuery('#wt_btn_settings').css({'background': '#55C6CE '});
            }
        });
        jQuery('#wt_btn_publish').show();
        jQuery('#wt_btn_publish').css({'background': '#78C77C', });
        jQuery('#wt_btn_draft').show();
        jQuery('#wt_btn_draft').css({'background': '#55C6CE', });
        jQuery('#wt_btn_schedule').hide();
        jQuery('#save').css({'background': '#55C6CE'});
        jQuery('#wt_btn_settings').css({'background': '#55C6CE '});
    });


    jQuery('#save').click(function () {
        document.getElementById("save").disabled = false;
        var email_type = jQuery('#_customize-input-rp_decorator_email_type').val();
        var save_btn_text =   wp.customize.state('selectedChangesetStatus').get();
        if(save_btn_text == 'future'){
            jQuery('#save').addClass('btn_busy_schedule');
        }else{
            jQuery('#save').addClass('btn_busy_save');
        }
        if (jQuery("#wt_apply_to_all_template").prop('checked') == true) {
            var template_default_value = 'on';
        } else {
            var template_default_value = 'off';
        }

        var data = {
            wp_customize: 'on',
            action: 'rp_decorator_set_as_default',
            email_type: email_type,
            template_default_value: template_default_value,
            save_btn_text: save_btn_text
        };
        jQuery.ajax({
            url: rp_decorator.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                status_arr = response.data;
                if (wp.customize.state('selectedChangesetStatus').get() == 'future' && status_arr.scheduled.includes(email_type) && jQuery('.wt_schedule_box').is(":hidden")) {
                    if (email_type in response.data.scheduled_data) {
                        var datas = response.data.scheduled_data;
                        document.getElementById("wt_schedule_date").innerHTML = datas[email_type];
                    }
                    jQuery('.wt_scheduled_data').show();
                }

            }
        });
        if (wp.customize.state('selectedChangesetStatus').get() == 'future' && jQuery("li.notice.notice-error").parent().parent().is(':visible')) {
            var count = jQuery(this).data("count") || 0;
            if (count == 0)
            {
                jQuery("li.notice.notice-info").parent().css("display", "none");
            }
            if (jQuery('#customize-control-trash_changeset').is(':visible')) {
                jQuery('#publish-settings').trigger('click');
                jQuery('#save').removeClass('btn_busy_schedule');
                jQuery(this).data("count", ++count);
            } else {
                setTimeout(function () {
                    jQuery('#save').removeClass('btn_busy_schedule');
                }, 3000);
                jQuery("li.notice.notice-error").parent().css("display", "none");
                jQuery(".wt_schedule_box").hide();
            }
        } else {
            setTimeout(function () {
                jQuery('#save').removeClass('btn_busy_save');
            }, 3000);
            setTimeout(function () {
                jQuery('#save').removeClass('btn_busy_schedule');
            }, 3000);
            jQuery(".wt_schedule_box").hide();
        }
//        var val_bck = jQuery('#_customize-input-rp_decorator_header_text_align').val();
//        jQuery('#_customize-input-rp_decorator_header_text_align option').each(function () {
//            if (val_bck != jQuery(this).val())
//            {
//                jQuery('#_customize-input-rp_decorator_header_text_align').val(jQuery(this).attr('value')).trigger('change');
//                return false;
//            }
//        });
//        jQuery('#_customize-input-rp_decorator_header_text_align').val(val_bck).trigger('change');
        if (wp.customize.state('selectedChangesetStatus').get() !== 'future') {
            setTimeout(function () {
                jQuery('#save').removeClass('btn_busy_save');
            }, 4000);
        }

    });
    var country = document.getElementById("_customize-input-rp_decorator_email_type");
    country.options[country.options.selectedIndex].selected = true;

    jQuery('#_customize-input-rp_decorator_email_type').change(function () {
        jQuery("body").css("overflow", "hidden");
        jQuery( window ).off( 'beforeunload' );
        if(i>2){
             var confirmation = confirm(rp_decorator.labels.wt_beforeunload);
            if (!confirmation) {
                return;
            }
        }
        jQuery('.wt_center_loader').show();        
        var current_email_type = jQuery('#_customize-input-rp_decorator_email_type').val();
        var data = {
            wp_customize: 'on',
            current_email_type: current_email_type,
            action: 'rp_decorator_delete_autosave_post',
        };
        jQuery.ajax({
            url: rp_decorator.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                jQuery('.wt_center_loader').hide();
                jQuery('body').css('overflow', 'auto');
                window.location.replace(rp_decorator.customizer_url);
                jQuery('#_customize-input-rp_decorator_preview_order_id').val(response.data);
            }
        });
        if (status_arr.length !== 0) {
            var email_type = jQuery('#_customize-input-rp_decorator_email_type').val();
            if (status_arr.drafted.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-draft').trigger('click');
            } else if (status_arr.scheduled.includes(email_type)) {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-future').trigger('click');
            } else {
                jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-publish').trigger('click');
            }
            var save_btn_text = wp.customize.state('selectedChangesetStatus').get();
            if (save_btn_text == 'draft') {
                jQuery('#wt_btn_draft').hide();
                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#298ADC'});
                jQuery('#wt_btn_settings').css({'background': '#298ADC'});
            } else if (save_btn_text == 'publish') {
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#78C77C', });
                jQuery('#wt_btn_publish').hide();
                jQuery('#wt_btn_schedule').show();
                jQuery('#wt_btn_schedule').css({'background': '#55C6CE', });
                jQuery('#save').css({'background': '#2271b1'});
                jQuery('#wt_btn_settings').css({'background': '#2271b1'});
            } else if (save_btn_text == 'future') {
                jQuery('#wt_btn_publish').show();
                jQuery('#wt_btn_publish').css({'background': '#78C77C', });
                jQuery('#wt_btn_draft').show();
                jQuery('#wt_btn_draft').css({'background': '#55C6CE', });
                jQuery('#wt_btn_schedule').hide();
                jQuery('#save').css({'background': '#55C6CE'});
                jQuery('#wt_btn_settings').css({'background': '#55C6CE '});
            }
        }

        let content_tab = document.getElementById("sub-accordion-section-rp_decorator_text_editor").querySelectorAll('li');

        content_tab.forEach((item, index) => {
            var content_li = item.id;
            if (content_li != 'customize-control-shortcodes') {
                if (email_type == 'wt_smart_coupon') {
                    if (content_li.indexOf('wt_smart_coupon_settings') > -1 || content_li.indexOf('wt_smart_coupon_subtitle') > -1 || content_li.indexOf('wt_smart_coupon_body') > -1)
                    {
                        jQuery('#' + content_li).show();
                    } else {
                        jQuery('#' + content_li).hide();
                    }

                } else {
                    if (content_li.indexOf(email_type) > -1)
                    {
                        jQuery('#' + content_li).show();
                    } else {
                        jQuery('#' + content_li).hide();
                    }
                }
            }

        });

        let img_content_tab = document.getElementById("sub-accordion-section-rp_decorator_header_image").querySelectorAll('li');

        img_content_tab.forEach((item, index) => {
            var content_li = item.id;
            if (content_li.indexOf('image_link_btn_switch') > -1)
            {
                if (content_li.indexOf(email_type) > -1)
                {
                    jQuery('#' + content_li).show();
                } else {
                    jQuery('#' + content_li).hide();
                }
            }
        });

    });


    /**
     * Change description
     */
    jQuery('#customize-info .customize-panel-description').html(rp_decorator.labels.description);

    // Add reset button
    jQuery('#wt_header_right_div button#wt_send_test_email_btn').before('<button name="rp_decorator_reset" id="rp_decorator_reset" class="button button-secondary" style="margin-right: 8px; margin-left: 9px;padding: 3px 15px 0px 17px;border-color: #FF6565 !important; background: #FFFFFF !important; color: #FF6565 !important;height: 36px; width:90px;"><span style="float:left;margin-top: 3px;margin-left: -3px;"><img src="' + rp_decorator.labels.wt_rest_btn_icon + '" style="height:14px"></span>' + rp_decorator.labels.reset + ' </button>');
    jQuery("#save").appendTo(jQuery("#wt_header_right_div"));
    jQuery("#save").after(jQuery("#wt_btn_settings"));
    jQuery("select#_customize-input-rp_decorator_email_type").appendTo(jQuery("#wt_order_type_div"));
    jQuery('#wt_order_type_div select#_customize-input-rp_decorator_email_type').before('<label for="dta_size">' + rp_decorator.labels.email_type_lbl + ' </label>');
    jQuery("select#_customize-input-rp_decorator_preview_order_id").appendTo(jQuery("#wt_order_num_div"));
    jQuery('#wt_order_num_div select#_customize-input-rp_decorator_preview_order_id').before('<label for="dta_size">' + rp_decorator.labels.choose_order_lbl + ' </label>');
    jQuery('#customize-save-button-wrapper').remove();
    jQuery('.spinner').remove();
    jQuery('#wt_decorator_topnav').show();


    var styleElem = document.head.appendChild(document.createElement("style"));
    styleElem.innerHTML = ".customize-controls-close:before {top: 15px}";
    jQuery('#customize-header-actions ').append('<div class="wt_band_section"><label>' + rp_decorator.labels.wt_plugin_name + '</label><br><img src=' + rp_decorator.labels.wt_logo + '></div>');

    // Handle reset button click
    jQuery('#wt_header_right_div #rp_decorator_reset').click(function (e) {

        // Prevent form submit
        e.preventDefault();

        // Display confirmation prompt
        var confirmation = confirm(rp_decorator.labels.reset_confirmation);
        var email_type = jQuery('#_customize-input-rp_decorator_email_type').val();

        // Check user input
        if (!confirmation) {
            return;
        }

        // Disable reset button
        jQuery(this).prop('disabled', true);

        // Populate request data object
        var data = {
            wp_customize: 'on',
            action: 'rp_decorator_reset',
            email_type: email_type
        };

        // Send request to server
        jQuery.post(rp_decorator.ajax_url, data, function () {
            wp.customize.state('saved').set(true);
            window.location.replace(rp_decorator.customizer_url);
        });
    });

    jQuery(document).on('click', "#wt_btn_send_mail", function (e) {
        if (jQuery('.wt_send_mail_box').is(':visible')) {
            jQuery(".wt_send_mail_box").hide();
        } else {
            if (jQuery('.button_actions').is(':visible')) {
                jQuery(".button_actions").hide();
            }
            jQuery('#wt_schedule_box').hide();
            jQuery('.wt_send_mail_box').show();
            var email = jQuery('#wt_recipient_email').val();
            var re = /\S+@\S+\.\S+/;
            if (re.test(email)) {
                jQuery("#wt_mail_image").attr("src", rp_decorator.labels.green_tic);
            } else {
                jQuery("#wt_mail_image").attr("src", rp_decorator.labels.red_cross);
            }
        }

        var iframe = jQuery('iframe').contents();
        iframe.find("body").click(function () {
            jQuery(".wt_send_mail_box").hide();
        });

    });

    jQuery(document).on('click', ".wt_mail_popup_close", function () {
        jQuery('.wt_send_mail_box').hide();

    });
    jQuery('#wt_recipient_email').keyup(function () {
        var email = jQuery('#wt_recipient_email').val();
        var re = /\S+@\S+\.\S+/;
        if (re.test(email)) {
            jQuery("#wt_mail_image").attr("src", rp_decorator.labels.green_tic);
        } else {
            jQuery("#wt_mail_image").attr("src", rp_decorator.labels.red_cross);
        }

    });
    jQuery(document).on('click', "#wt_schedule_edit_btn", function (e) {
        jQuery("#wt_scheduled_data").hide();
        jQuery(".wt_schedule_box").show();
        jQuery('#customize-selected-changeset-status-control-input-' + btn_id + '-future').trigger('click');
        jQuery("div.date-time-fields.includes-time").appendTo("#wt_dc_dropdown_test");
        jQuery('#wt_button_actions').hide();
        var iframe = jQuery('iframe').contents();
        iframe.find("body").click(function () {
            jQuery(".wt_schedule_box").hide();
        });
    });

    jQuery(document).on('click', "#wt_schedule_ok_btn", function (e) {
        jQuery("#wt_scheduled_data").hide();
    });

    jQuery(document).click(function (e) {
        if (jQuery(e.target).parents(".wt_send_mail_box").length === 0)
        {
            if (jQuery('.wt_send_mail_box').is(':visible') && (e.target.className !== "button btn_send_mail_down")) {
                if ((e.target.className !== "dashicons dashicons-arrow-down")) {
                    jQuery(".wt_send_mail_box").hide();
                }
            }
        }

        if (jQuery(e.target).parents(".wt_scheduled_data").length === 0)
        {
            if (jQuery('.wt_scheduled_data').is(':visible')) {
                jQuery(".wt_scheduled_data").hide();
            }
        }
//        if (jQuery(e.target).parents(".wt_schedule_box").length === 0)
//        {
//            if (jQuery('.wt_schedule_box').is(':visible') && (e.target.className !== "") && (e.target.className !== "wt_btn_schedule")) {
//                jQuery(".wt_schedule_box").hide();
//            }
//        }

        if (jQuery(e.target).parents(".button_actions").length === 0)
        {
            if (jQuery('.button_actions').is(':visible') && (e.target.className !== "") && (e.target.className !== "dashicons dashicons-arrow-down")) {
                if ((e.target.className !== "button btn_publish_settings")) {
                    jQuery(".button_actions").hide();
                    jQuery(".wt_schedule_box").hide();
                }
            }
        }
    });

    // Handle send email button click
    jQuery('#wt_send_test_email_btn').click(function (e) {

        // Prevent form submit
        e.preventDefault();

        // Get recipients
        var recipients = jQuery('#wt_recipient_email').val();
        if (recipients == '') {
            jQuery(".wt_send_mail_box").show();
            var iframe = jQuery('iframe').contents();
            iframe.find("body").click(function () {
                jQuery(".wt_send_mail_box").hide();
            });
            return false;
        }
        jQuery('#wt_send_test_email_btn').addClass('btn_busy');
        var email_type = jQuery('#_customize-input-rp_decorator_email_type').val();
        var preview_order_id = jQuery('#_customize-input-rp_decorator_preview_order_id').val();
        var data = {
            wp_customize: 'on',
            action: 'wt_send_test_email',
            recipients: recipients,
            email_type: email_type,
            preview_order_id: preview_order_id
        };

        // Send request to server
        jQuery.post(rp_decorator.ajax_url, data, function (result) {
            jQuery('.wt_send_mail_box').hide();
            jQuery('#wt_send_test_email_btn').removeClass('btn_busy');
            jQuery('#wt_send_test_email_btn').css({'background': '#ffffff', });
            if (result != 0) {
                alert(rp_decorator.labels.sent);
            } else {
                alert(rp_decorator.labels.failed);
            }
        });
    });
});


