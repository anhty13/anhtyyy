<?php
$recipients = get_option('wt_test_mail_recipients');
$recipients_mail = isset($recipients) && !empty($recipients) ? $recipients : '';
if (!is_rtl()) {
    ?>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .topnav {
            overflow: hidden;
            background-color: white;
            position:fixed; 
            top:0px; 
            left:0px;
            margin-left: 20.8%;
            z-index:550000;
            width: 80%;
            height: 75px;
            display: none;
            border-bottom: 1px solid #dcdcde;
        }

        .topnav a {
            float: left;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
        .wt_dc_tbl{
            width: 100%;
            /*margin-top: 16px;*/
            margin: 16px 35px;
        }
        .wt_dc_tbl label {
            font-size: 14px;
        }
        .wt_dc_tbl select {
            /*display: block;*/
            width: 220px;
            padding: 0.35rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: 36px;
        }

        .wt_send_mail_box input {
            /*display: block;*/
            width: 80%;
            padding: 0.35rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: 34px;
        }

        .btn_schedule{
            margin-right: 5px; background: #78C77C !important; color: #FFFFFF !important; width: 75px;height: 36px;outline: none !important; box-shadow: none !important;
        }
        @keyframes components-button__busy-animation {
            0% {
                background-position: 200px 0;
            }
        }
        .btn_busy{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(45deg, #ffffff 33%, #e0e0e0 33%, #e0e0e0 70%, #ffffff 70%) !important;
        }
        .btn_busy_save{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(-45deg,#007cba 33%,#005a87 0,#005a87 70%,#007cba 0) !important;
        }
        
          .btn_busy_schedule{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(-45deg,#76e7e2 33%,#15cdcd 0,#15cdcd 70%,#76e7e2 0) !important;
        }
        .btn_send_mail{
            margin-right: 5px; border-color: #329D63 !important; color: #329D63 !important;width: 100px;height: 36px;outline: none !important; box-shadow: none !important; padding: 0px 3px 0px 0px !important; 
        }
        .btn_send_mail_down{
            margin-right: 5px !important; border-color: #329D63 !important; background: #FFFFFF !important; color: #329D63 !important;width: 36px;height: 36px; margin-left: -7px !important;border-top-left-radius: 0px !important;border-bottom-left-radius: 0px !important;outline: none !important; box-shadow: none !important;
        }
        .btn_publish_settings{
            margin-right: 5px !important;  border-left: 1px solid #FFFFFF !important;border-radius: 3px; background: #2271b1; color: #329D63 !important;width: 36px;height: 36px; margin-left: -7px !important;border-top-left-radius: 0px !important;border-bottom-left-radius: 0px !important;outline: none !important; box-shadow: none !important;
        }

        .wt_send_mail_box{    
            position: absolute;
            z-index: 1000000000000;
            width: 365px;
            right: 0;
            background-color: #FFFFFF;
            margin: 65px 192px 0;
            /*        padding: 15px 15px 10px;*/
            /*padding: 25px;*/
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }
        .wt_schedule_box{    
            position: absolute;
            z-index: 1000000000000;
            width: 270px;
            right: 0;
            background-color: #FFFFFF;
            margin: 65px 60px 0;
            padding: 15px 15px 10px;
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }

        .wt-mgdp-send-green-btn{
            background: #329D63;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            line-height: 0px;
            padding: 15px 20px;
            color: #FFFFFF;
            display: inline-block;
            text-decoration: none;
            transition: all .2s ease;
            border:none;
            float:right;
            margin-top: 12px;
            height: 34px;
        }
        .wt-feedback-terms-segment{
            margin-top: 15px;
            color: #A3A1A1;
        }

        ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
            color:    #A3A1A1;
        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color:    #A3A1A1;
            opacity:  1;
        }
        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color:    #A3A1A1;
            opacity:  1;
        }
        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color:    #A3A1A1;
        }
        ::-ms-input-placeholder { /* Microsoft Edge */
            color:    #A3A1A1;
        }

        ::placeholder { /* Most modern browsers support this now. */
            color:    #A3A1A1;
        }

        .wt_mail_popup_close {
            float: right;
            width: 40px;
            height: 18px;
            text-align: right;
            cursor: pointer;
        }


        select#_customize-input-rp_decorator_email_type , select#_customize-input-rp_decorator_preview_order_id{
            background-image:
                linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%),
                linear-gradient(to right, #ccc, #ccc);
            background-position:
               calc(100% - 16px) calc(1.15em),
                calc(100% - 11px) calc(1.15em),
                calc(100% - 2.5em) 0em;
            background-size:
                5px 5px,
                5px 5px,
                1px 2.5em;
            background-repeat: no-repeat;
             font-size: 14px;
            padding: 0 44px 0 10px;
            overflow:hidden !important; 
            white-space:nowrap; 
            text-overflow:ellipsis;
        }



        .button_actions{position: absolute;
                        z-index: 10000000000;
                        background: #fff;
                        /*    border: 1px solid #ccc;
                            border-radius: 4px;*/
                        padding: 10px;
                        display: none;
                        margin: 65px 85% 0;
                        width: 170px;
                        box-shadow: 0px 9px 23px rgb(0 0 0 / 8%);
                        border-radius: 7px;
        }
        .wt_dc_dropdown li {
            padding: 7px 14px;
            margin: 5px 3px;   
            cursor: pointer;
            text-align: center;
            background: #8DBB8F;
            width: 136px;
            border-radius: 3px;
        }
        .wt_scheduled_data{
            position: absolute;
            z-index: 1000000000000;
            width: 260px;
            right: 0;
            background-color: #FFFFFF;
            margin: 65px 60px 0;
            padding: 15px 15px 10px;
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }

        .wt_warn_box {
            background: #fff3cd;
            border: solid 1px #ffeeba;
            padding: 10px;
            width: 92%;
            margin: 24px 20px 12px 14px;
            box-sizing: border-box;
            color: #866506;
        }
        .wt-feedback-form{
            padding: 0px 25px 25px 20px;
        }
    </style>
<?php } else { ?>

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }

        .topnav {
            overflow: hidden;
            background-color: white;
            position:fixed; 
            top:0px; 
            left:-12px;
            z-index:550000;
            width: 80%;
            height: 75px;
            display: none;
            border-bottom: 1px solid #dcdcde;
            border-right: 1px solid #dcdcde;
        }

        .topnav a {
            float: left;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
        .wt_dc_tbl{
            width: 100%;
            /*margin-top: 16px;*/
            margin: 16px 35px;
        }
        .wt_dc_tbl label {
            font-size: 14px;
        }
        .wt_dc_tbl select {
            /*display: block;*/
            width: 220px;
            padding: 0.35rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: 36px;
        }

        .wt_send_mail_box input {
            /*display: block;*/
            width: 80%;
            padding: 0.35rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            height: 34px;
        }

        @keyframes components-button__busy-animation {
            0% {
                background-position: 200px 0;
            }
        }
        .btn_busy{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(45deg, #ffffff 33%, #e0e0e0 33%, #e0e0e0 70%, #ffffff 70%) !important;
        }
        .btn_busy_save{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(-45deg,#007cba 33%,#005a87 0,#005a87 70%,#007cba 0) !important;
        }
        
          .btn_busy_schedule{
            animation: components-button__busy-animation 2500ms infinite linear !important;
            opacity: 1 !important;
            background-size: 100px 100% !important;
            /* stylelint-disable */
            background-image: linear-gradient(-45deg,#76e7e2 33%,#15cdcd 0,#15cdcd 70%,#76e7e2 0) !important;
        }
        .btn_send_mail{
            margin-right: 5px; border-color: #329D63 !important; color: #329D63 !important;width: 100px;height: 36px;outline: none !important; box-shadow: none !important;
        }
        .btn_send_mail_down{
            margin-right: 5px !important; border-color: #329D63 !important; background: #FFFFFF !important; color: #329D63 !important;width: 36px;height: 36px; margin-right: -7px !important;border-radius: 0px !important;outline: none !important; box-shadow: none !important;
        }
        .btn_publish_settings{
            margin-right: 5px !important;border-radius: 3px;  border-right: 1px solid #FFFFFF !important; background: #2271b1; color: #329D63 !important;width: 36px;height: 36px; margin-right: -7px !important;border-top-left-radius: 0px !important;border-bottom-left-radius: 0px !important;outline: none !important; box-shadow: none !important;
        }

        .wt_send_mail_box{    
            position: absolute;
            z-index: 1000000000000;
            width: 365px;
            left: 0;
            background-color: #FFFFFF;
            margin: 65px 210px 0;
            /*        padding: 15px 15px 10px;*/
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }
        .wt_schedule_box{    
            position: absolute;
            z-index: 1000000000000;
            width: 270px;
            left: 0;
            background-color: #FFFFFF;
            margin: 65px 85px 0;
            padding: 15px 15px 10px;
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }
        .wt_scheduled_data{
            position: absolute;
            z-index: 1000000000000;
            width: 260px;
            left: 0;
            background-color: #FFFFFF;
            margin: 65px 85px 0;
            padding: 15px 15px 10px;
            background: #FFFFFF;
            box-shadow: 4px 4px 23px 6px rgba(0, 0, 0, 0.15);
            border-radius: 4px;
            display: none;
        }

        .wt-mgdp-send-green-btn{
            background: #329D63;
            border-radius: 4px;
            font-weight: 600;
            font-size: 16px;
            line-height: 0px;
            padding: 15px 20px;
            color: #FFFFFF;
            display: inline-block;
            text-decoration: none;
            transition: all .2s ease;
            border:none;
            float:right;
            margin-top: 12px;
            height: 34px;
        }
        .wt-feedback-terms-segment{
            margin-top: 15px;
            color: #A3A1A1;
        }

        ::-webkit-input-placeholder { /* WebKit, Blink, Edge */
            color:    #A3A1A1;
        }
        :-moz-placeholder { /* Mozilla Firefox 4 to 18 */
            color:    #A3A1A1;
            opacity:  1;
        }
        ::-moz-placeholder { /* Mozilla Firefox 19+ */
            color:    #A3A1A1;
            opacity:  1;
        }
        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color:    #A3A1A1;
        }
        ::-ms-input-placeholder { /* Microsoft Edge */
            color:    #A3A1A1;
        }

        ::placeholder { /* Most modern browsers support this now. */
            color:    #A3A1A1;
        }

        .wt_mail_popup_close {
            float: right;
            width: 40px;
            height: 18px;
            text-align: right;
            cursor: pointer;
        }


        select#_customize-input-rp_decorator_email_type , select#_customize-input-rp_decorator_preview_order_id{
            background-image:
                linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%),
                linear-gradient(to right, #ccc, #ccc);
            background-position:
                calc(100% - 20px) calc(1em),
                calc(100% - 15px) calc(1em),
                calc(100% - 2.5em) 0em;
            background-size:
                5px 5px,
                5px 5px,
                1px 2.1em;
            background-repeat: no-repeat;

            padding: 0 44px 0 10px;
            overflow:hidden !important; 
            white-space:nowrap; 
            text-overflow:ellipsis;
        }

        .button_actions{
            position: absolute;
            z-index: 10000000000;
            background: #fff;
            /*    border: 1px solid #ccc;
                border-radius: 4px;*/
            padding: 10px;
            display: none;
            margin: 65px 84% 0;
            width: 170px;
            box-shadow: 0px 9px 23px rgb(0 0 0 / 8%);
            border-radius: 7px;
        }
        .wt_dc_dropdown li {
            padding: 7px 14px;
            margin: 5px 3px;   
            cursor: pointer;
            text-align: center;
            background: #8DBB8F;
            border-radius: 3px;
            width: 136px;
        }

        .wt_warn_box {
            background: #fff3cd;
            border: solid 1px #ffeeba;
            padding: 10px;
            width: 92%;
            margin: 24px 20px 12px 14px;
            box-sizing: border-box;
            color: #866506;
        }
        .wt-feedback-form{
            padding: 0px 25px 25px 20px;
        }

    </style>

<?php } ?>

<div class="topnav" id="wt_decorator_topnav">
    <div class="" id="t1">
        <table class="wt_dc_tbl" id="t2">
            <tbody><tr>
                    <td style="width: 4%;padding-left: 15px;">
                        <div id="wt_order_type_div" >
                        </div>            </td> 
                    <td style="width: 5%;padding-left: 15px;">
                        <div id="wt_order_num_div">
                        </div>           
                    </td> 
                    <td style="width: .1%;display: none !important">
                        <div id="wt_div_publish">
                        </div>           
                    </td> 
                    <td style="width: 16%;padding-left: 15px;">
                        <div id="wt_header_right_div">

                            <button class="button btn_send_mail" id="wt_send_test_email_btn">
                                <span class="dashicons dashicons-email" style="margin-top: 3px;"></span> <?php echo 'Test email'; ?></button>

                            <button class="button btn_send_mail_down" id="wt_btn_send_mail">
                                <span class="dashicons dashicons-arrow-down" style="margin: 4px 2px 1px -3px;"></span></button>
                            <button class="btn_publish_settings" id="wt_btn_settings">
                                <span class="dashicons dashicons-arrow-down" style="margin: 0px 0px 0px 0px;color: #FFFFFF"></span></button>

                        </div>         
                    </td> 
                </tr>
            </tbody></table>
    </div>
</div>

<div class="wt_send_mail_box">
    <div class="wt_warn_box">
        <span><?php _e('Save the settings prior to sending the test email.', 'decorator-woocommerce-email-customizer'); ?></span></div>
    <div class="wt-feedback-form wt-feedback-form-shadow">
        <div style="width: 100%">            
            <label style="font-size: 17px">
                <?php _e('Enter recipient email ID:', 'decorator-woocommerce-email-customizer'); ?>
            </label>
            <input placeholder="<?php _e('samplemail@gmail.com', 'wp-migration-duplicator'); ?>" type="text" name="wt_recipient_email" id="wt_recipient_email" class="wt-feedback-email" value="<?php echo $recipients_mail; ?>" style="margin-top: 12px;" required/><span><img id="wt_mail_image" src="<?php echo RP_DECORATOR_PLUGIN_URL . '/assets/images/red-cross.svg' ?>" style="height:20px;width: 45px;margin-bottom: -4px;"> </span>
        </div>
        <div class="wt-feedback-terms-segment">
            <label >
                <?php _e('Enter valid email IDs separated by comma. Then, click on ‘Test email’ to get a preview of the customized email in your inbox ', 'decorator-woocommerce-email-customizer'); ?>
            </label>
        </div>

    </div>
</div>

<div class="button_actions" id="wt_button_actions">
    <ul class="wt_dc_dropdown" style="margin: 1px;color: #FFFFFF;padding: 3px 3px 0px 3px;" >
        <li id="wt_btn_publish"> <?php _e('Publish', 'decorator-woocommerce-email-customizer'); ?></li>
        <li id="wt_btn_draft"> <?php _e('Save draft', 'decorator-woocommerce-email-customizer'); ?></li>
        <li id="wt_btn_schedule" class="wt_btn_schedule"> <?php _e('Schedule', 'decorator-woocommerce-email-customizer'); ?></li>
        <li style="background: #FFF;color: black;padding: 0px" id="wt_btn_apply_to_all" class="wt_btn_apply_to_all"> <table><tr><td><input type="checkbox" name="wt_apply_to_all_template" id="wt_apply_to_all_template">&nbsp;&nbsp;</td><td style="text-align:left"><?php _e('Apply changes to all email type', 'decorator-woocommerce-email-customizer'); ?></td></tr></table></li>
    </ul>
</div>

<div class="wt_schedule_box" id="wt_schedule_box">
    <span><?php _e('Select a date to publish your customized email.', 'decorator-woocommerce-email-customizer'); ?></span>
    <div class="wt-feedback-form-shadow" id="wt_dc_dropdown_test">
    </div>
</div>

<div class="wt_scheduled_data" id="wt_scheduled_data">
    <p><?php _e('Scheduled to go live at ', 'decorator-woocommerce-email-customizer'); ?><span id="wt_schedule_date"></span></p>
    <button class="button btn_schedule" id="wt_schedule_ok_btn">
        Ok <span class="dashicons dashicons-yes" style="font-size: 25px;"></span> </button>
    <button class="button btn_schedule" id="wt_schedule_edit_btn">
        Edit <span class="dashicons dashicons-edit" ></span></button>

</div>
    
<div id="loader" class="wt_center_loader"></div>