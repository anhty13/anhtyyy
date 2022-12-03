<?php
if (!class_exists('RP_Decorator_Uninstall_Feedback')) :

    /**
     * Class for catch Feedback on uninstall
     */
    class RP_Decorator_Uninstall_Feedback {
        
        public function __construct() {
            
            add_action('admin_footer', array($this, 'deactivate_scripts'));
            add_action('wp_ajax_decorator_submit_uninstall_reason', array($this, "send_uninstall_reason"));
        }

        private function get_uninstall_reasons() {

            $reasons = array(
                  array(
                        'id' => 'used-it',
                        'text' => __('Used it successfully. Don\'t need anymore.', 'decorator-woocommerce-email-customizer'),
                        'type' => 'reviewhtml',
                        'placeholder' => __('Have used it successfully and aint in need of it anymore', 'decorator-woocommerce-email-customizer')
                    ),
                array(
                    'id' => 'could-not-understand',
                    'text' => __('I couldn\'t understand how to make it work', 'decorator-woocommerce-email-customizer'),
                    'type' => 'supportlink',
                    'placeholder' => __('Would you like us to assist you?', 'decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'found-better-plugin',
                    'text' => __('I found a better plugin', 'decorator-woocommerce-email-customizer'),
                    'type' => 'text',
                    'placeholder' => __('Which plugin?', 'decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'not-have-that-feature',
                    'text' => __('The plugin is great, but I need specific feature that you don\'t support', 'decorator-woocommerce-email-customizer'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us more about that feature?', 'decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'is-not-working',
                    'text' => __('The plugin is not working', 'decorator-woocommerce-email-customizer'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us a bit more whats not working?', 'decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'looking-for-other',
                    'text' => __('It\'s not what I was looking for', 'decorator-woocommerce-email-customizer'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us a bit more?','decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'did-not-work-as-expected',
                    'text' => __('The plugin didn\'t work as expected', 'decorator-woocommerce-email-customizer'),
                    'type' => 'textarea',
                    'placeholder' => __('What did you expect?', 'decorator-woocommerce-email-customizer')
                ),
                array(
                    'id' => 'other',
                    'text' => __('Other', 'decorator-woocommerce-email-customizer'),
                    'type' => 'textarea',
                    'placeholder' => __('Could you tell us a bit more?', 'decorator-woocommerce-email-customizer')
                ),
            );

            return $reasons;
        }

        public function deactivate_scripts() {
             
            global $pagenow;
            if ('plugins.php' != $pagenow) {
               
                return;
            }
            $reasons = $this->get_uninstall_reasons();
            ?>
            <div class="decorator-modal" id="decorator-decorator-modal">
                <div class="decorator-modal-wrap">
                    <div class="decorator-modal-header">
                        <h3><?php _e('If you have a moment, please let us know why you are deactivating:', 'decorator-woocommerce-email-customizer'); ?></h3>
                    </div>
                    <div class="decorator-modal-body">
                        <ul class="reasons">
                            <?php foreach ($reasons as $reason) { ?>
                                <li data-type="<?php echo esc_attr($reason['type']); ?>" data-placeholder="<?php echo esc_attr($reason['placeholder']); ?>">
                                    <label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="decorator-modal-footer">
                        <a href="#" class="dont-bother-me"><?php _e('I rather wouldn\'t say', 'decorator-woocommerce-email-customizer'); ?></a>
                        
                        
                        <a href="https://wordpress.org/support/plugin/decorator-woocommerce-email-customizer/#bbp_topic_title" target="_blank" class="button-primary decorator-model-submit"><?php _e('Contact Support', 'decorator-woocommerce-email-customizer'); ?></a>
                        <button class="button-primary decorator-model-submit"><?php _e('Submit & Deactivate', 'decorator-woocommerce-email-customizer'); ?></button>
                        <button class="button-secondary decorator-model-cancel"><?php _e('Cancel', 'decorator-woocommerce-email-customizer'); ?></button>
                    </div>
                </div>
            </div>

            <style type="text/css">
                .decorator-modal {
                    position: fixed;
                    z-index: 99999;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    background: rgba(0,0,0,0.5);
                    display: none;
                }
                .decorator-modal.modal-active {display: block;}
                .decorator-modal-wrap {
                    width: 50%;
                    position: relative;
                    margin: 10% auto;
                    background: #fff;
                }
                .decorator-modal-header {
                    border-bottom: 1px solid #eee;
                    padding: 8px 20px;
                }
                .decorator-modal-header h3 {
                    line-height: 150%;
                    margin: 0;
                }
                .decorator-modal-body {padding: 5px 20px 20px 20px;}
                .decorator-modal-body .input-text,.decorator-modal-body textarea {width:75%;}
                .decorator-modal-body .reason-input {
                    margin-top: 5px;
                    margin-left: 20px;
                }
                .decorator-modal-footer {
                    border-top: 1px solid #eee;
                    padding: 12px 20px;
                    text-align: right;
                }
                .reviewlink, .support_link{
                        padding:10px 0px 0px 35px !important;
                        font-size: 15px;
                    }
                .review-and-deactivate, .reach-via-support{
                        padding:5px;
                    }
            </style>
            <script type="text/javascript">
                (function ($) {
                    $(function () {
                        var modal = $('#decorator-decorator-modal');
                        var deactivateLink = '';
                        $('#the-list').on('click', 'a.decorator-deactivate-link', function (e) {
                            e.preventDefault();
                            modal.addClass('modal-active');
                            deactivateLink = $(this).attr('href');
                            modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
                        });
                        
                        $('#decorator-decorator-modal').on('click', 'a.review-and-deactivate', function (e) {
                                e.preventDefault();
                                window.open("https://wordpress.org/support/plugin/decorator-woocommerce-email-customizer/reviews/?filter=5#new-post");
                                window.location.href = deactivateLink;
                            });
                        
                        modal.on('click', 'button.decorator-model-cancel', function (e) {
                            e.preventDefault();
                            modal.removeClass('modal-active');
                        });
                        modal.on('click', 'input[type="radio"]', function () {
                            var parent = $(this).parents('li:first');
                            modal.find('.reason-block').remove();
                            var inputType = parent.data('type'),
                                    inputPlaceholder = parent.data('placeholder');
                                    
                                if ('reviewhtml' === inputType) {
                                    var reasonInputHtml = '<div class="reviewlink reason-block"><a href="#" target="_blank" class="review-and-deactivate"><?php _e('Deactivate and leave a review', 'decorator-woocommerce-email-customizer'); ?> <span class="wt-smartcoupon-rating-link"> &#9733;&#9733;&#9733;&#9733;&#9733; </span></a></div>';
                                } else if( 'supportlink' === inputType ) {
                                    var reasonInputHtml = '<div class="support_link reason-block"> <a href="https://wordpress.org/support/plugin/decorator-woocommerce-email-customizer/#bbp_topic_title" target="_blank" class="reach-via-support"><?php _e('Let our support team help you', 'decorator-woocommerce-email-customizer'); ?> </a>' + '</div>';
                                } else {
                                    var reasonInputHtml = '<div class="reason-input reason-block">' + (('text' === inputType) ? '<input type="text" class="input-text" size="40" />' : '<textarea rows="5" cols="45"></textarea>') + '</div>';
                                }
                            if (inputType !== '') {
                                parent.append($(reasonInputHtml));
                                parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
                            }
                        });

                        modal.on('click', 'button.decorator-model-submit', function (e) {
                            e.preventDefault();
                            var button = $(this);
                            if (button.hasClass('disabled')) {
                                return;
                            }
                            var $radio = $('input[type="radio"]:checked', modal);
                            var $selected_reason = $radio.parents('li:first'),
                                    $input = $selected_reason.find('textarea, input[type="text"]');
                                    $reason_info = (0 !== $input.length) ? $input.val().trim() : '';
                                    $reason_id = (0 === $radio.length) ? 'none' : $radio.val()
                            if( $reason_id == 'could-not-understand' ) {
                                $reason_info = 'Let our support team help you';
                            }

                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'decorator_submit_uninstall_reason',
                                    reason_id: $reason_id,
                                    reason_info: $reason_info
                                },
                                beforeSend: function () {
                                    button.addClass('disabled');
                                    button.text('Processing...');
                                },
                                complete: function () {
                                    window.location.href = deactivateLink;
                                }
                            });
                        });
                    });
                }(jQuery));
            </script>
            <?php
        }

        public function send_uninstall_reason() {

            global $wpdb;

            if (!isset($_POST['reason_id'])) {
                wp_send_json_error();
            }

            $data = array(
                'reason_id' => sanitize_text_field($_POST['reason_id']),
                'plugin' => "decorator",
                'auth' => 'decorator_uninstall_1234#',
                'date' => gmdate("M d, Y h:i:s A"),
                'url' => '',
                'user_email' => '',
                'reason_info' => isset($_REQUEST['reason_info']) ? trim(stripslashes($_REQUEST['reason_info'])) : '',
                'software' => $_SERVER['SERVER_SOFTWARE'],
                'php_version' => phpversion(),
                'mysql_version' => $wpdb->db_version(),
                'wp_version' => get_bloginfo('version'),
                'wc_version' => (!defined('WC_VERSION')) ? '' : WC_VERSION,
                'locale' => get_locale(),
                'multisite' => is_multisite() ? 'Yes' : 'No',
                'decorator_version' => RP_DECORATOR_VERSION
            );
            // Write an action/hook here in webtoffe to recieve the data
            $resp = wp_remote_post('https://feedback.webtoffee.com/wp-json/decorator/v1/uninstall', array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking' => false,
                'body' => $data,
                'cookies' => array()
                    )
            );

            wp_send_json_success();
        }

    }
    new RP_Decorator_Uninstall_Feedback();

endif;