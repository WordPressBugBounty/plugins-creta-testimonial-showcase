<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Cretats_Recommended_Templates' ) ) {
    class Cretats_Recommended_Templates {

        const TRANSIENT_KEY = 'cretats_recommended_templates_v1';

        public function __construct() {
            add_action( 'add_meta_boxes', [ $this, 'cretats_add_recommended_templates_box' ] );
        }

        public function cretats_add_recommended_templates_box() {
            add_meta_box(
                'cretats_recommended_templates_box',
                'Recommended Templates',
                [ $this, 'cretats_render_recommended_templates_box' ],
                [ 'cretats_testimonial', 'cretats_tms_sc' ],
                'side',
                'low'
            );
        }

        public function cretats_render_recommended_templates_box() {
            $templates = $this->cretats_get_recommended_templates();
            $showcase_url = admin_url( 'admin.php?page=cretats-theme-showcase' );
            ?>
            <div class="cretats-reco-templates">

                <?php if ( empty( $templates ) ) : ?>
                    <p class="cretats-reco-empty"><?php echo esc_html( 'Templates could not be loaded right now.' ); ?></p>
                <?php else : ?>
                    <div class="cretats-reco-list">
                        <?php foreach ( $templates as $template ) : ?>
                            <div class="cretats-reco-card">
                                <a href="<?php echo esc_url( $template['url'] ); ?>" target="_blank" rel="noopener noreferrer">
                                    <img src="<?php echo esc_url( $template['image'] ); ?>" alt="<?php echo esc_attr( $template['title'] ); ?>">
                                </a>
                                <div class="cretats-reco-body">
                                    <h4 class="cretats-reco-title"><?php echo esc_html( $template['title'] ); ?></h4>
                                    <?php if ( $template['price'] !== '' ) : ?>
                                        <span class="cretats-reco-price">$<?php echo esc_html( $template['price'] ); ?></span>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url( $template['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="button button-primary cretats-reco-btn"><?php echo esc_html( 'View Template' ); ?></a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <a href="<?php echo esc_url( $showcase_url ); ?>" class="cretats-reco-viewall"><?php echo esc_html( 'View All Templates →' ); ?></a>
            </div>
            <?php
        }

        private function cretats_get_recommended_templates() {
            $cached = get_transient( self::TRANSIENT_KEY );
            if ( is_array( $cached ) ) {
                return $cached;
            }

            $templates = $this->cretats_fetch_recommended_templates();

            // Cache successful results longer than empty ones so a temporary
            // API hiccup doesn't hide the widget for a full day.
            $ttl = ! empty( $templates ) ? 12 * HOUR_IN_SECONDS : 15 * MINUTE_IN_SECONDS;
            set_transient( self::TRANSIENT_KEY, $templates, $ttl );

            return $templates;
        }

        private function cretats_fetch_recommended_templates() {
            $response = wp_remote_post( CRETATS_ELEMENTO_API_BASE . '/getFilteredProducts', [
                'headers' => [ 'Content-Type' => 'application/json' ],
                'timeout' => 5,
                'body'    => wp_json_encode( [
                    'collectionHandle' => '',
                    'productHandle'    => '',
                    'paginationParams' => [
                        'first'        => 3,
                        'afterCursor'  => '',
                        'beforeCursor' => '',
                        'reverse'      => true,
                    ],
                ] ),
            ] );

            if ( is_wp_error( $response ) ) {
                return [];
            }

            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            $products = $data['data']['products'] ?? [];

            $templates = [];
            foreach ( $products as $product ) {
                $node = $product['node'] ?? [];

                if ( empty( $node ) || ( isset( $node['inCollection'] ) && $node['inCollection'] === false ) ) {
                    continue;
                }
                if ( ( $node['handle'] ?? '' ) === 'wp-theme-bundle-copy' ) {
                    continue;
                }

                $price = $node['variants']['edges'][0]['node']['price'] ?? '';
                if ( $price === '' || floatval( $price ) === 0.0 ) {
                    continue;
                }

                $image = $node['images']['edges'][0]['node']['src'] ?? '';
                $url   = $node['onlineStoreUrl'] ?? '';
                if ( ! $image || ! $url ) {
                    continue;
                }

                $templates[] = [
                    'title' => $node['title'] ?? '',
                    'image' => $image,
                    'price' => $price,
                    'url'   => add_query_arg( [
                        'utm_source'   => 'testimonial-plugin',
                        'utm_medium'   => 'admin-sidebar',
                        'utm_campaign' => 'recommended-templates',
                    ], $url ),
                ];

                if ( count( $templates ) >= 3 ) {
                    break;
                }
            }

            return $templates;
        }
    }

    new Cretats_Recommended_Templates();
}
