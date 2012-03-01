<?php
/*
Plugin Name: Jobs Finder
Plugin URI: http://www.onlinerel.com/wordpress-plugins/  
Description: Plugin "Jobs Finder" gives visitors the opportunity to more than one million offer of employment.  Jobs search for U.S., Canada, UK, Australia
Version: 2.1
Author: A.Kilius
Author URI: http://www.onlinerel.com/wordpress-plugins/
*/

define(jobs_finder_URL_RSS_DEFAULT, 'http://www.superjobbank.com/category/jobs/feed/');
define(jobs_finder_TITLE, 'Jobs Finder');
define(jobs_finder_MAX_SHOWN_ITEMS, 4);

add_action('admin_menu', 'jobs_finder_menu');                    
function jobs_finder_menu() {
	 add_menu_page('Jobs Finder', 'Jobs Finder', 8, __FILE__, 'jobs_finder_options');
}

function jobs_finder_widget_ShowRss($args)
{
        $options = get_option('jobs_finder_widget');
                   
        if( $options == false ) {
                $options[ 'jobs_finder_widget_url_title' ] = jobs_finder_TITLE;
                $options[ 'jobs_finder_widget_RSS_count_items' ] = jobs_finder_MAX_SHOWN_ITEMS;
        }
   
$feed = jobs_finder_URL_RSS_DEFAULT;
$title = $options[ 'jobs_finder_widget_url_title' ];
$output .= '<!-- Jobs Finder:  http://www.onlinerel.com/wordpress-plugins/ -->';
$output .= '<center><form name="form1" method="get" action="http://www.howfindajob.com/" target="_blank">
 <input type="text" id="s"  name="s"  value="" /><input type="submit" id="go" value="Find Jobs"/>
</form>  </center>';
// end search form
$rss = fetch_feed( $feed );
                if ( !is_wp_error( $rss ) ) :
                        $maxitems = $rss->get_item_quantity($options['jobs_finder_widget_RSS_count_items'] );
                        $items = $rss->get_items( 0, $maxitems );
                                endif;
 $output .= '<b>Latest job offers:</b>';       
         $output .= '<ul>';     
        if($items) {
                        foreach ( $items as $item ) :
                                // Create post object
  $titlee = trim($item->get_title());
 $output .= '<li> <a href="';
 $output .=  $item->get_permalink();
  $output .= '"  title="'.$titlee.'" target="_blank">';
$output .= $titlee.'</a></span>';               
$output .= '</li>';
                        endforeach;             
        }
 $output .= '</ul><center>
 <form name="form2" method="post" action="http://www.superjobbank.com/post-your-free-ad/" target="_blank" >
 <input type="submit" name="submit" class="submit" value="Post a Job" /></form> 
</center> ';	 			
extract($args);	
  echo $before_widget;  
  echo $before_title . $title . $after_title;  
 echo $output;  
 echo $after_widget;  
}

function jobs_finder_widget_Admin()
{
        $options = $newoptions = get_option('jobs_finder_widget');     
        //default settings
        if( $options == false ) {
                $newoptions[ 'jobs_finder_widget_url_title' ] = jobs_finder_TITLE;
                $newoptions['jobs_finder_widget_RSS_count_items'] = jobs_finder_MAX_SHOWN_ITEMS;               
        }
        if ( $_POST["jobs_finder_widget_RSS_count_items"] ) {
                $newoptions['jobs_finder_widget_url_title'] = strip_tags(stripslashes($_POST["jobs_finder_widget_url_title"]));
                $newoptions['jobs_finder_widget_RSS_count_items'] = strip_tags(stripslashes($_POST["jobs_finder_widget_RSS_count_items"]));
        }       
                                                                                                         
        if ( $options != $newoptions ) {
                $options = $newoptions;
                update_option('jobs_finder_widget', $options);         
        }
        $jobs_finder_widget_url_title = wp_specialchars($options['jobs_finder_widget_url_title']);
        $jobs_finder_widget_RSS_count_items = $options['jobs_finder_widget_RSS_count_items'];   
        ?>
        <p><label for="jobs_finder_widget_url_title"><?php _e('Title:'); ?> <input style="width: 350px;" id="jobs_finder_widget_url_title" name="jobs_finder_widget_url_title" type="text" value="<?php echo $jobs_finder_widget_url_title; ?>" /></label></p>
        <p><label for="jobs_finder_widget_RSS_count_items"><?php _e('Count Items To Show:'); ?> <input  id="jobs_finder_widget_RSS_count_items" name="jobs_finder_widget_RSS_count_items" size="2" maxlength="2" type="text" value="<?php echo $jobs_finder_widget_RSS_count_items?>" /></label></p>   
        <br clear='all'></p>
 
        <?php
}

                                            
add_filter("plugin_action_links", 'jobs_finder_ActionLink', 10, 2);
                                                       
function jobs_finder_ActionLink( $links, $file ) {
	    static $this_plugin;		
		if ( ! $this_plugin ) $this_plugin = plugin_basename(__FILE__); 
        if ( $file == $this_plugin ) {
			$settings_link = "<a href='".admin_url( "options-general.php?page=".$this_plugin )."'>". __('Settings') ."</a>";
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

function jobs_finder_options() {	
	?>
	<div class="wrap">
		<h2>Jobs Finder</h2>     
<p><b>Plugin "Jobs Finder" gives visitors the opportunity to more than 1 million offer of employment.
Jobs search for U.S., Canada, UK, Australia</b> </p>
<p> <h3>Add the widget "Jobs Finder"  to your sidebar from  <a href="<? echo "./widgets.php";?>"> Appearance->Widgets</a>  and configure the widget options.</h3>
<h3>More <a href="http://www.onlinerel.com/wordpress-plugins/" target="_blank"> WordPress Plugins</a></h3></p>
                           
  	</div>
	<?php  }
function jobs_finder_widget_Init()
{
  register_sidebar_widget(__('Jobs Finder'), 'jobs_finder_widget_ShowRss');
  register_widget_control(__('Jobs Finder'), 'jobs_finder_widget_Admin', 500, 250);
}                                                                    
add_action("plugins_loaded", "jobs_finder_widget_Init");
?>