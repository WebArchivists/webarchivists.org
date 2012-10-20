<?php
/*  Copyright 2011 Eric van der Vlist (vdv@dyomedea.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Plugin Name: owark
Plugin URI: http://owark.org
Description: Tired of broken links? Archive yours with owark, the Open Web Archive!
Version: 0.1
Author: Eric van der Vlist
Author URI: http://eric.van-der-vlist.com
License: GLP2
*/


if (!class_exists("Owark")) {
	class Owark {

        private $broken_links = array();
        private $post_id = -1;
        private $post_type = "";
        private $version = '0.2';
        private $notices = "";
        
        /**
         * Class constructor
         *
         * @package owark
         * @since 0.1
         *
         *
         */
		function Owark() {


            if (is_admin()) {
                add_action('admin_menu', array($this, 'owark_admin_menu'));
                add_action('plugins_loaded', array($this, 'sanity_checks'));
            }

            // See http://stackoverflow.com/questions/2210826/need-help-with-wp-rewrite-in-a-wordpress-plugin
            // Using a filter instead of an action to create the rewrite rules.
            // Write rules -> Add query vars -> Recalculate rewrite rules
            add_filter('rewrite_rules_array', array($this, 'create_rewrite_rules'));
            add_filter('query_vars',array($this, 'add_query_vars'));

            // Recalculates rewrite rules during admin init to save resources.
            // Could probably run it once as long as it isn't going to change or check the
            // $wp_rewrite rules to see if it's active.
            add_filter('admin_init', array($this, 'flush_rewrite_rules'));
            add_action( 'template_redirect', array($this, 'template_redirect_intercept') );

            add_filter ( 'the_content', array($this, 'content_filter'));
            add_filter ( 'comment_text', array($this, 'comment_filter'));
            add_filter ( 'get_comment_author_link', array($this, 'comment_filter'));

            add_action('owark_schedule_event', array(Owark, 'schedule'));
            if ( !wp_next_scheduled( 'owark_schedule_event', array('occurrences' => 30) ) ) {
		        wp_schedule_event(time(), 'hourly', 'owark_schedule_event', array('occurrences' => 30));
	        }


		}

        /**
         * Check we have everything we need...
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function sanity_checks(){

            // Install or upgrade tables if needed

            $installed_ver = get_option( "owark_db_version" );
            if ($installed_ver != $this->version) {
                global $wpdb;
                $table = $wpdb->prefix."owark";
                $sql = "CREATE TABLE $table (
                    id int(10) unsigned NOT NULL AUTO_INCREMENT,
                    url text NOT NULL,
                    status varchar(20) NOT NULL DEFAULT 'to-archive',
                    arc_date datetime,
                    arc_location text,
                    encoding varchar(10),
                    PRIMARY KEY(`id`),
                    KEY `url` (`url`(150)) )";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);

                update_option( "owark_db_version", $this->version );
                $this->notices = "<div class=\"updated fade\"><p><strong>The owark table has been installed or upgraded to version {$this->version}</strong></p></div>";
            }

            // Check that the broken link checker is installed
            if (!function_exists('get_plugins'))
				require_once (ABSPATH."wp-admin/includes/plugin.php");

            $blc = 'not-found';
            foreach(get_plugins() as $plugin_file => $plugin_data) {
                if ($plugin_data['Title'] == 'Broken Link Checker') {
                    if (is_plugin_active($plugin_file)) {
                        $blc = 'active';
                    } else {
                        $blc = 'inactive';
                    }
                }
	        }

            if ($blc == 'inactive') {
                 $this->notices = $this->notices . "<div class=\"updated fade\"><p><strong>Please activate the Broken Link Checker so that the Open Web Archive can be fully functional.</strong></p></div>";
            } else if ($blc == 'not-found') {
                 $this->notices = $this->notices . "<div class=\"error fade\"><p><strong>The Open Web Archive relies on the <a href=\"http://w-shadow.com/blog/2007/08/05/broken-link-checker-for-wordpress/\">Broken Link Checker</a>. Please install this plugin!</strong></p></div>";
            }

            // Check if we have an archive subdirectory

            if (!is_dir(dirname(__FILE__) . '/archives')) {
                @mkdir(dirname(__FILE__) . '/archives');
                if (!is_dir(dirname(__FILE__) . '/archives')) {
                    $this->notices = $this->notices . "<div class=\"error fade\"><p><strong>The Open Web Archive has not been able to create the folder /archives in its installation directory. Please create it by hand and make it writable for the web server.</strong></p></div>";                        
                }
            }

            // Check that we can execute commands

            if ( ini_get('disable_functions') ) {
                $not_allowed = ini_get('disable_functions');
                if ( stristr($not_allowed, 'exec') ) {
                    $this->notices = $this->notices . "<div class=\"error fade\"><p><strong>The Open Web Archives requires that exec() is allowed to run wget and retrieve the pages to archive.</strong></p></div>";
               }
            }

            // Check that wget is installed

            $output = array();
            exec('/usr/bin/wget -V', &$output);


            if ( empty($output) ) {
                $this->notices = $this->notices . "<div class=\"error fade\"><p><strong>The Open Web Archives is not able to run wget and retrieve the pages to archive. Please check that wget is installed and on the default path.</strong></p></div>";
            }

            // We need as least version 1.12 or higher
            $helper = preg_match('/GNU Wget ([0-9\.]+) /', $output[0], $wget_version);
            if ( $wget_version[1] < '1.12' ) {
                $this->notices = $this->notices . "<div class=\"error fade\"><p><strong>The Open Web Archives needs wget version 1.12 or higher.</strong><br />Version read: {$wget_version[0]}</p></div>";
            }

            if ($this->notices != '') {
                add_action('admin_notices', array($this, 'admin_notices'));
             }

        }

        /**
         * Show admin notices
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function admin_notices(){

            echo $this->notices;

        }

        /**
         * Admin menus
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function owark_admin_menu() {
            add_management_page(__('The Open Web Archive', 'owark'), __('Web Archive', 'owark'), 'edit_others_posts', 'owark', array($this, 'management_page'));
        }

        /**
         * URL of an archive page
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function get_archive_url($archive_id) {
            return home_url().'/owark/'.$archive_id;
        }

        /**
         * Display the admin/tools page.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function management_page() {
            //must check that the user has the required capability
            if (!current_user_can('edit_others_posts')) {
                wp_die( __('You do not have sufficient permissions to access this page.') );
            }

            global $wpdb;

            echo '<div class="wrap">';
            screen_icon();
            echo '<h2>Owark - The Open Web Archive</h2>';
            echo '<p><em>Tired of broken links? Archive yours with the Open Web Archive!</em></p>';
            echo "</div>";

            echo '<p>List of broken links with archived pages:</p>';

            $query = "SELECT owark.id, owark.url, owark.status, owark.arc_date, owark.arc_location, blc_links.status_text
                        FROM {$wpdb->prefix}owark AS owark, {$wpdb->prefix}blc_links as blc_links
                        WHERE owark.url = blc_links.final_url COLLATE latin1_swedish_ci and blc_links.broken = 1
                        ORDER BY owark.url";
            $results = $wpdb->get_results($query);

            echo '<table class="widefat">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>URL</th>';
            echo '<th>Archive</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($results as $link) {
                $archive_url = $this->get_archive_url($link->id);
                echo "<tr>
                        <td><a href=\"{$link->url}\" target='_blank'>{$link->url}</a></td>
                        <td><a href=\"{$archive_url}\" target='_blank'>{$link->arc_date}</a></td>
                    </tr>";
            }

            echo '</tbody>';
            echo '</table>';


        }

        /**
         * Add a rewrite rule to display archive pages
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function create_rewrite_rules($rules) {
            global $wp_rewrite;
            $newRule = array('owark/(.+)' => 'index.php?owark='.$wp_rewrite->preg_index(1));
            $newRules = $newRule + $rules;
            return $newRules;
        }

        /**
         * Add a query variable used to display archive pages
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function add_query_vars($qvars) {
            $qvars[] = 'owark';
            return $qvars;
        }

        /**
         * Title says it all ;) ...
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function flush_rewrite_rules() {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }

        /**
         * Intercepts archive pages.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function template_redirect_intercept() {
            global $wp_query;
            if ($wp_query->get('owark')) {
                $this->display_archive($wp_query->get('owark'));
                exit;
            }
        }

        /**
         * Filter to replace broken links in comments.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function content_filter($content) {
            global $post;
            return $this->link_filter($content, $post->ID, $post->post_type);
        }

        /**
         * Filter to replace broken links in comments.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function comment_filter($content) {
            return $this->link_filter($content, get_comment_ID(), 'comment');
        }

        /**
         * Generic filter to replace broken links in content.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function link_filter($content, $post_id, $post_type) {

            global $wpdb;

            // See if we haven't already loaded the broken links for this post...
            if ($this->post_id != $post_id || $this->post_type != $post_type) {

                $this->post_id =  $post_id;
                $this->post_type = $post_type;

                //Retrieve info about all occurrences of broken links in the current post
                //which happens for comments (they have links to check in 2 different filters)
                $q = "
                    SELECT instances.raw_url, owark.id
                    FROM {$wpdb->prefix}blc_instances AS instances,
                        {$wpdb->prefix}blc_links AS links,
                        {$wpdb->prefix}owark AS owark
                    WHERE
                        instances.link_id = links.link_id
                        AND owark.url = links.final_url COLLATE latin1_swedish_ci
                        AND instances.container_id = %s
                        AND instances.container_type = %s
                        AND links.broken = 1
                ";
                $q = $wpdb->prepare($q, $this->post_id, $this->post_type);
                $results = $wpdb->get_results($q);

                $this->broken_links = array();

                foreach ($results as $link) {
                    $this->broken_links[$link->raw_url] = $link->id;
                }

            }


            if (empty($this->broken_links)) {
                return $content;
            }

            // Regexp : see http://stackoverflow.com/questions/2609095/hooking-into-comment-text-to-add-surrounding-tag
            return preg_replace_callback('/(<a.*?href\s*=\s*["\'])([^"\'>]+)(["\'][^>]*>.*?<\/a>)/si', array( $this, 'replace_a_link'), $content);
        }

        /**
         * Replace a link.
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function replace_a_link($matches) {
            if (array_key_exists($matches[2], $this->broken_links)) {
                return $matches[1].$this->get_archive_url($this->broken_links[$matches[2]]).$matches[3];
            } else {
                return $matches[0];
            }
        }


        /**
         * Display an archive page
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        function display_archive($parameter) {

            global $wpdb;

            $id = intval($parameter);

            $query = "SELECT *
                        from {$wpdb->prefix}owark AS owark
                        where id = {$id}";
            $link = $wpdb->get_row($query);
            $wpdb->flush();

            // Find the file to read
            $blog_title = get_bloginfo('name');
            $home_url = home_url();

            $loc = "";
            if( ($pos = strpos($link->arc_location, '/archives')) !== FALSE )
                $loc = '/wp-content/plugins/owark' . substr($link->arc_location, $pos);
            $arc_loc = home_url() . $loc;

            $file_location = '.'. $loc .'/index.html';
            if (!file_exists($file_location)) {
                // If index.html doesn't exist, find another html file!
                $dir = opendir('.'.$loc);
                while (false !== ($file = readdir($dir))) {
                    if ('.html' === substr($file, strlen($file) - 5)) {
                        $file_location = '.'.$loc.'/' . $file;
                        break;
                    }
                }
                closedir($dir);
            }

            // Read the file

            $f = fopen($file_location, "r");
            $content = fread($f, filesize($file_location));
            fclose($f);

            // Which encoding?
            $encoding = $link->encoding;

            if ($encoding == NULL) {
                // We need to guess the encoding!

                $matches = NULL;
                // <meta http-equiv="Content-Type" content="text/xml; charset=iso-8859-1"/>
                if (preg_match('/<meta\s*http-equiv\s*=\s*["\']Content-Type["\']\s+content\s*=\s*["\'][^"\'>]*charset\s*=\s*([^"\'>]+)\s*["\']/si',
                        $content, &$matches) > 0) {
                    $encoding = $matches[1];
                } else {
                    $encoding = mb_detect_encoding($content);
                }

                if ($encoding) {
                    $wpdb->update(
                        "{$wpdb->prefix}owark",
                        array('encoding' => $encoding),
                        array('id' => $id));
                }
            }

            header("Content-Type: text/html; charset=$encoding");

            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset='.$encoding.'">';

            echo "<base href=\"{$arc_loc}/\">";
            echo '<div style="background:#fff;border:1px solid #999;margin:-1px -1px 0;padding:0;">';
            echo '<div style="background:#ddd;border:1px solid #999;color:#000;font:13px arial,sans-serif;font-weight:normal;margin:12px;padding:8px;text-align:left">';
            echo "This is an <a href='http://owark.org'>Open Web Archive</a> archive of <a href=\"{$link->url}\">{$link->url}</a>.";
            echo "<br />This snapshot has been taken on {$link->arc_date} for the website <a href=\"{$home_url}\">{$blog_title}</a> which contains a link to this page and has saved a copy to be displayed in the page ever disappears.";
            echo '</div></div><div style="position:relative">';


             $f = fopen($file_location, "r");
             echo $content;
             echo '</div>';

          }

        /**
         * Check if we've got something to archive
         *
         * @package owark
         * @since 0.1
         *
         *
         */
        public static function schedule($occurrences) {

            $archiving  = get_option( 'owark_archiving', false);
            if (! $archiving) {
                update_option('owark_archiving', true);
            } else {
                return;
            }
            global $wpdb;

            $query = "SELECT DISTINCT final_url from {$wpdb->prefix}blc_links
                        WHERE final_url NOT IN (SELECT url COLLATE latin1_swedish_ci FROM {$wpdb->prefix}owark)
                        AND broken=0
                        AND final_url!=''";
            $url = $wpdb->get_row($query);
            $wpdb->flush();

            if ($url != NULL) {
                $date = date('c');
                $relpath = '/archives/'. str_replace('%2F', '/', urlencode(preg_replace('/https?:\/\//', '', $url->final_url))) . '/' . $date;
                $path = dirname(__FILE__).$relpath;
                //mkdir($path, $recursive=true);                                           

                $output = array();
                $status = 0;
                exec("wget -t3 -E -H -k -K -p -nd -nv --adjust-extension --timeout=60 --user-agent=\"Mozilla/5.0 (compatible; owark/0.1; http://owark.org/)\" -P $path {$url->final_url}",
                    &$output, &$status);

                $q = $wpdb->insert("{$wpdb->prefix}owark", array(
                    'url' => $url->final_url,
                    'status' => $status,
                    'arc_date' => $date,
                    'arc_location' => $relpath));

                if ($occurrences > 0) {
                    wp_schedule_single_event(time() + 90, 'owark_schedule_event', array('occurrences' => $occurrences - 1));    
                }

            }
            delete_option('owark_archiving');
        }




	}


}


if (class_exists("Owark")) {
	$owark = new Owark();
}



?>
