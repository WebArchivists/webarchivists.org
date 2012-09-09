<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the main section and all content after
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?>
<?php if (!is_page('about') ) : ?>
<footer id="about">
    <div class="organization">
        <p><?php
            $logo = '<img class="wa" src="'. get_bloginfo('template_directory') . '/img/webarchivists.png" alt="WebArchivists" />';
            printf(__('%s is a french non-profit organization founded in 2009.'), $logo) ?></p>
        <ul class="options">
            <li><a href="<?php echo get_permalink(286) ?>"><?php _e('Learn more', 'webarchivists') ?></a></li>
            <li><a href="<?php echo get_permalink(312) ?>"><?php _e('Meet the team', 'webarchivists') ?></a></li>
        </ul>
    </div>
</footer>
<?php endif ?>

</section>
<footer id="footer" role="contentinfo">
    <small class="credit">webarchivists.org<br />2009 - <?php echo date('Y') ?></small>
    <ul class="sections">
        <?php
            wp_nav_menu(
                array (
                    'menu'            => 'footer-menu',
                    'container'       => false,
                    'container_id'    => false,
                    'menu_class'      => false,
                    'menu_id'         => false,
                    'depth'           => 1,
                    'walker'          => new Webarchivists_Walker
                )
            );
        ?>
    </ul>
    <ul class="contact">
        <li><a href="http://twitter.com/webarchivists"><?php echo __('follow us on twitter') ?></a></li>
        <li><a href="http://www.facebook.com/webarchivists"><?php echo __('like us on facebook') ?></a></li>
        <li><a href="/contact"><?php echo __('contact us by mail') ?></a></li>
        <li><a href="<?php bloginfo('rss2_url'); ?>"><?php echo __('RSS feed') ?></a></li>
    </ul>
    <small class="commons">
        all content, unless specified, is under <a href="#">creative commons</a><br />
        <img src="<?php bloginfo('template_directory') ?>/img/cc.png" alt="Creative Commons NC-BY-SA" />
    </small>
    <a class="to-top" href="#">Back to top</a>
</footer>

<?php if (is_page('about') ) : ?>
<script src="<?php bloginfo('template_directory') ?>/js/libs/mep/mediaelement-and-player.min.js"></script>
<?php endif; ?>

<?php wp_footer(); ?>
<script src="<?php bloginfo('template_directory') ?>/js/libs/jquery-1.7.1.min.js"></script>
<script src="<?php bloginfo('template_directory') ?>/js/scripts.js"></script>
</body>
</html>