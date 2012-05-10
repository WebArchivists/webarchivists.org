<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the main section and all content after
 *
 * @package WordPress
 * @subpackage Webarchivists
 */
?>

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
        <li><a href="#">follow us on twitter</a></li>
        <li><a href="#">like us on facebook</a></li>
        <li><a href="#">contact us</a></li>
        <li><a href="#">RSS feed</a></li>
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