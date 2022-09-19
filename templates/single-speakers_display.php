<?php
    get_header();
    $sessions_of_current_speakers = get_field( 'sessions_of_current_speakers' );
?>


    <div id="primary" class="site-content">
        <div id="content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>
            <div class="single-member-container">
                <div class="single-member-wrapper">

                    <h1 class="heading-speakers">
                        <?php the_title(); ?>
                    </h1>

                        <div class="content-wrapper">
                            <?php the_content(); ?>
                            <h1 class="sessions-header">Seasons</h1>
                            <?php if ( $sessions_of_current_speakers ): ?>
                                <ul class="list-group" style="list-style: none; padding: 0">
                                    <?php foreach ( $sessions_of_current_speakers as $sessions ): ?>
                                        <li class="list-group-item" style="margin-bottom: 20px;">
                                            <a href="<?php echo get_the_permalink( $sessions -> ID );?>"></a>
                                        </li>

                                        <?php echo $sessions -> post_title;?>

                                    <?php endforeach; ?>
                                </ul>
                            <?php endif ?>
                    </div>
                </div>

                <?php the_post_thumbnail();
            endwhile; // end of the loop. ?>
            </div>




        </div><!-- #content -->
    </div><!-- #primary -->



<?php get_footer(); ?>