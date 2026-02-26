<?php
if (!defined( 'ABSPATH' )) {
    exit; 
}
$bg_color_lighter = $bg_color ? cretats_lighten_color($bg_color,70) : '#943C87';
$bg_color_darker = $bg_color ? cretats_darken_color($bg_color,30) : '#943C87';
if ( $query->have_posts() ) :
   
?>

<div class="container container-hide">
    <div class="row">
        <div class="col-md-12">
            <div id="testimonial-slider" class="testimonial-slider owl-carousel-old owl-theme smart-slider-layout-5">
                <?php
                while ( $query->have_posts() ) : $query->the_post();
                    $var = cretats_get_shortcode_customization_variables( get_the_ID(), $columns );
                    $designation   = $var['designation'];
                    $rating        = $var['rating'];
                    $info          = $var['info'];
                    $image_url     = $var['image_url'];
                    $columns       = $var['columns'];
                    $bootstrap_col = $var['bootstrap_col'];
                ?>

                <div class="testimonial-card" <?php echo  ( cretats_get_inline_style(['bg_color' => $bg_color] )); ?>>
                <div class="profile-img smart-slider-profile-img-layout-5" <?php echo  ( cretats_get_inline_style([ 'box_shadow' => '0 0 10px 0 ' . $bg_color_darker ])); ?>>
                       <?php
                        $image_to_use = $image_url ?: CRETATS_DUMMYIMG_URL;
                        ?>
                        <img src="<?php echo esc_url( $image_to_use ); ?>" alt="" loading="lazy">

                    </div>
                    <h5 <?php echo  ( cretats_get_inline_style( ['font_family' => $font, 'font_size' => $header_font_size, 'color' => $color ])); ?> class="smart-slider-title-layout-5">
                        <?php the_title(); ?>
                    </h5>
                    <div class="px-3" <?php echo  ( cretats_get_inline_style( ['font_family' => $font, 'font_size' => $body_font_size, 'color' => $color ])); ?>>
                    <?php echo wp_kses_post( strip_tags( get_the_content(), '<strong><em><a><span><div><br>' ) );
                        if ($info && $info != ''){?>
                        <span <?php echo  ( cretats_get_inline_style( ['font_family' => $font, 'font_size' => $body_font_size, 'color' => $color ])); ?>> <?php echo esc_html( $info ); ?></span> 
                        <?php } ?>
                        </div>
                    <div class="d-flex justify-content-center mt-3 smart-slider-stars-layout-5">
                        <?php if ( $rating >= 0 ) : 
                           cretats_get_star_avg_rating($rating);
                          ?>
                        <?php endif; ?>
                    </div>
                    <div class="feedback mb-1 smart-slider-feedback-layout-5">
                        <?php if ( $designation  ) : ?>
                                <small <?php echo  ( cretats_get_inline_style( ['font_family' => $font, 'font_size'=> $body_font_size,'color'=> $color] )); ?> class="smart-slider-location-layout-5">  
                                    <?php echo esc_html( $designation );  ?>
                                </small>
                            <?php endif; ?></div>
                    <div class="triangle-pointer" <?php echo  ( cretats_get_inline_style( ['bg_color' => $bg_color] )); ?>></div>
                </div>

                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php
endif;
wp_reset_postdata();
?>