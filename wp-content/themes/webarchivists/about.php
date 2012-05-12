<?php
/*
Template Name: About
*/

get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

    <article class="page" id="about-webarchivists">
                <?php the_post_thumbnail() ?>

                <section class="intro">
                    <h1><?php the_title() ?></h1>
                    <?php the_content() ?>
                </section>

                <section class="portraits">
                    <!--<?php
                    
                    $team = array( 'spone', 'denis', 'baptiste', 'chloe', 'camille' );
                    foreach ($team as $slug):
                        if(  $member = get_user_by( 'slug', $slug ) ) :
                    
                    ?>
                    --><div class="portrait">
                        <?php
                            the_author_image( $member->id );
                         ?>
                        <h2><?php printf( '%s %s', $member->first_name,  $member->last_name ) ?></h2>
                        <?php if ( $member->description ): ?>
                        <p><?php echo $member->description ?></p>
                        <?php endif ?>
                        <ul class="links">
                            <?php if ( $member->user_email ): ?>
                            <li><a href="mailto:<?php echo antispambot( $member->user_email, true ) ?>"><?php echo antispambot( $member->user_email ) ?></a></li>
                            <?php endif ?>
                            <?php if ( $member->user_url ): ?>
                            <li><a href="<?php echo $member->user_url ?>"><?php echo $member->user_url ?></a></li>
                            <?php endif ?>
                            <?php if ( $member->twitter ): ?>
                            <li><a href="https://twitter.com/<?php echo $member->twitter ?>" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @<?php echo $member->twitter ?></a></li>
                            <?php endif ?>
                        </ul>
                    </div><!--
                    <?php
                    
                        endif;
                    endforeach;
                    
                    ?>-->
             </section>

            <section class="webarchives">

                <video poster="<?php bloginfo('template_directory') ?>/video/webarchives.jpg" width="960" height="426" controls="controls" preload="none" style="width:100%;height:100%">
                    <source type="video/mp4" src="<?php bloginfo('template_directory') ?>/video/webarchives.mp4" />
                    <source type="video/webm" src="<?php bloginfo('template_directory') ?>/video/webarchives.webm" />
                    <source type="video/ogg" src="<?php bloginfo('template_directory') ?>/video/webarchives.ogv" />
                    <!--
                        Optional: Add subtitles for each language
                        <track kind="subtitles" src="<?php bloginfo('template_directory') ?>/subtitles.srt" srclang="en" />
                        Optional: Add chapters
                        <track kind="chapters" src="<?php bloginfo('template_directory') ?>/chapters.srt" srclang="en" /> 
                    -->
                    <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
                    <object type="application/x-shockwave-flash" data="<?php bloginfo('template_directory') ?>/js/mep_files/flashmediaelement.swf">
                        <param name="movie" value="<?php bloginfo('template_directory') ?>/js/mep_files/flashmediaelement.swf" />
                        <param name="flashvars" value="controls=true&file=<?php bloginfo('template_directory') ?>/video/webarchives.mp4" />
                        <!-- Image as a last resort -->
                        <img src="<?php bloginfo('template_directory') ?>/video/webarchives.jpg" title="No video playback capabilities" />
                    </object>
                </video>

            </section>

        </article>
	<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
