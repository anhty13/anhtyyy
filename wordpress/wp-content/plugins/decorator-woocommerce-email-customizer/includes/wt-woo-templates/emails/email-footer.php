<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails 
 * @version 3.7.0
 */
//                                                                                                            <img src="https://woocommerce-319567-2037137.cloudwaysapps.com/wp-content/plugins/kadence-woocommerce-email-designer/assets/images/gray/facebook.png" style="border: none; font-weight: bold; height: auto; outline: none; text-decoration: none; text-transform: capitalize; font-size: 14px; line-height: 24px; width: 24px; max-width: 100%; display: inline-block; vertical-align: bottom;">

defined( 'ABSPATH' ) || exit;

  $social_icons =  array(
                 'fa-facebook'=> 'facebook.png',
                  'fa-twitter' =>'twitter.png',
                  'fa-instagram' => 'instagram.png',
                  'fa-youtube' => 'youtube.png',
                  'fa-linkedin' => 'linkedin.png',
                  'fa-vimeo' => 'vimeo.png'
                );
		$social_enable = //get_option( 'footer_social_enable' );
		$social_links  = get_option( 'footer_social_repeater' );
                $social_position  = get_option( 'social_links_enable' );
                $social_folder  = get_option( 'social_links_icon_color' );
		$social_links  = isset($social_links) && !empty($social_links) ? json_decode( $social_links ):'';
?>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="wt_template_footer">
					<td align="center" valign="top">
                                            <div id="wt_template_footers">
						<!-- Footer -->
                                                <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer" style="background-color: #efefef">
							<tr>
								<td valign="top">
									<table border="0" cellpadding="10" cellspacing="0" width="100%">
										<tr>
                                                                                    <td colspan="2" valign="middle" id="credits" style="padding: 24px 0px">
											 <?php 
                                                                                         if(!empty($social_links) && $social_position == 'above'){
                                                                                              $items = count($social_links);
                                                                                            ?>
                                                                                            <table style="margin-left: auto; margin-right: auto;" id="wt_social_footer"><tr> <?php
                                                                                            foreach ($social_links as $social_link) {
                                                                                                ?>
                                                                                               <td> <div style="display: inline-block;">
                                                                                                    <a href="<?php echo esc_url($social_link->link); ?>" class="wt-footer-social-links" style=" text-decoration: none;">
                                                                                                        <?php
                                                                                                            if (isset($social_link->icon_color) && !empty($social_link->icon_color)) {
                                                                                                                $color = $social_link->icon_color;
                                                                                                            } else {
                                                                                                                $color = 'default';
                                                                                                            }
                                                                                                               if(array_key_exists($social_link->choice, $social_icons)){
                                                                                                                   if(isset($social_folder) && !empty($social_folder)){
                                                                                                                       $base = RP_DECORATOR_PLUGIN_URL . '/assets/images/'.$social_folder.'/';
                                                                                                                   }else{
                                                                                                                       $base = RP_DECORATOR_PLUGIN_URL . '/assets/images/default/';
                                                                                                                   }
                                                                                                                   $img_link = $base . $social_icons[$social_link->choice];
                                                                                                                   echo '<span class="wt-social-link-icon" style="font-size: 20px;"><img src='.$img_link.' width="20" ></img></span>';
                                                                                                               }
                                                                                                            
                                                                                                        ?>
                                                                                                        <span class="wt-social-link-title" ><?php echo esc_html($social_link->title); ?></span>
                                                                                                    </a>
                                                                                                </div></td>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                          </tr>
           
									                    </table>
                                                                                                   <?php
                                                                                                   }?><div id="credit" style="padding:0px;"><?php
                                                                                            echo wp_kses_post( wpautop( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); 
                                                                                           ?></div> <?php 
                                                                                            if(!empty($social_links) && $social_position == 'bellow'){
                                                                                            $items = count($social_links);
                                                                                            ?>
                                                                                            <table style="margin-left: auto; margin-right: auto;" id="wt_social_footer"><tr> <?php
                                                                                            foreach ($social_links as $social_link) {
                                                                                                ?>
                                                                                               <td> <div style="display: inline-block;">
                                                                                                    <a href="<?php echo esc_url($social_link->link); ?>" class="wt-footer-social-links" style=" text-decoration: none;">
                                                                                                        <?php
                                                                                                            if (isset($social_link->icon_color) && !empty($social_link->icon_color)) {
                                                                                                                $color = $social_link->icon_color;
                                                                                                            } else {
                                                                                                                $color = 'black';
                                                                                                            }
                                                                                                            if(array_key_exists($social_link->choice, $social_icons)){
                                                                                                                   if(isset($social_folder) && !empty($social_folder)){
                                                                                                                       $base = RP_DECORATOR_PLUGIN_URL . '/assets/images/'.$social_folder.'/';
                                                                                                                   }else{
                                                                                                                       $base = RP_DECORATOR_PLUGIN_URL . '/assets/images/black/';
                                                                                                                   }
                                                                                                                   $img_link = $base . $social_icons[$social_link->choice];
                                                                                                                   echo '<span class="wt-social-link-icon" style="font-size: 20px;"><img src='.$img_link.' width="20" ></img></span>';
                                                                                                               }
                                                                                                        ?>
                                                                                                        <span class="wt-social-link-title" ><?php echo esc_html($social_link->title); ?></span>
                                                                                                    </a>
                                                                                                </div></td>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                          </tr>
           
									                    </table>
                                                                                                   <?php
                                                                                         }
                                                                                            ?>
								</td>
							</tr>
						</table>
						<!-- End Footer -->
					</td>
				</tr>
			 </table>
                        <div>
                      </table>                          
		</div>
	</body>
</html>
