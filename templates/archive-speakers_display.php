<?php
/* Template name: Custom template */

get_header(); ?>

    <section id="primary" class="site-content">
        <div id="content" role="main">

                <header class="archive-header">
                    <h1 class="archive-title"><?php _e('Speakers'); ?></h1>
                </header><!-- .archive-header -->

            <div class="speakers-wrapper">
                <div class="speakers-filter">
                    <?php
                    $terms = get_terms( array(
                        'taxonomy' => 'speakers_position_country',
                        'hide_empty' => false,
                    ) );
                    ?>
                    <ul>
                        <?php foreach ( $terms as $term ) : ?>
                            <li>
                                <a href="#" class="speakers-filter-link" data-id="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="speakers-list-wrapper">
                    <div class="speakers-list">
                        <?php members_display()->members_display(); ?>
                    </div>
                </div>
            </div>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
            <script>
                    document.addEventListener('DOMContentLoaded', function() {


                        $('.speakers-filter-link').click(function () {
                            let link = $(this);
                            let term_id = link.attr('data-id');

                            let data = {
                                action: 'members_display',
                                term_id: term_id
                            }

                            let ajax_url = '<?php echo admin_url('admin-ajax.php') ?>';

                            $.post(ajax_url, data, function(respose) {
                                $('.speakers-list').html(respose);
                            });

                            return false;
                        });
                    });
                </script>
        </div><!-- #content -->
    </section><!-- #primary -->

<?php get_footer(); ?>