<?php
/*
  Plugin Name: WP Bootstrap Menu
  Version: 1.1.1
  Description: Convert Wordpress nav menu to Twitter Bootstrap style.
  Plugin URI: http://wordpress.org/extend/plugins/wp-bootstrap-navmenu/
  Author: Sajjad Rad
  Author URI: http://sajjadrad.com/
 */

    $plugin_dir = plugin_basename(__FILE__);
    function getNavMenu($menuName,$dropDownOption = "click")
    {
        $menu = $menuName; //Nav menu name
        $items = wp_get_nav_menu_items($menu); // Get nav menu items list
        $level = 0;
        $last_title = "";
        $last_url = "";
        $objectID_stack = array();
        $objectIDStackTop = 0;
        $output = "";
		$active = False;
		global $post;
        foreach ($items as $list)
        {
            if($list->menu_item_parent == "0")
            {
                while(count($objectID_stack))
                {
                    array_pop($objectID_stack);
                }
                if($level == 1)
                {
					if($active)
						$class = " class=\"active\"";
					else
						$class = "";
					$output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li>";
                }
                if($level == 3)
                {
					if($active)
						$class = " class=\"active\"";
					else
						$class = "";
                    $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li></ul></li></ul></li>";
                }
                if($level == 2)
                {
					if($active)
						$class = " class=\"active\"";
					else
						$class = "";
                    $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li></ul></li>";
                }
                $level = 1 ;
                array_push($objectID_stack, $list->object_id);
                $last_title = $list->title;
                $last_url = $list->url;
				if($post->ID == $list->object_id)
					$active = True;
				else
					$active = False;
            }
            else
            {
                $stackTop = count($objectID_stack)-1;
                if($list->menu_item_parent == $objectID_stack[$stackTop])
                {
                    if($level == 1)
                    {
						if($active)
							$class = " active";
						else
							$class = "";
                        if($dropDownOption == "click")
                            $output = $output."<li class=\"dropdown".$class."\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">".$last_title."<b class=\"caret\"></b></a><ul class=\"dropdown-menu\">";
						else
                            $output = $output."<li class=\"dropdown".$class."\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"".$last_url."\">".$last_title."<b class=\"caret\"></b></a><ul class=\"dropdown-menu\">";
                        $last_title = $list->title;
                        $last_url = $list->url;
						if($post->ID == $list->object_id)
							$active = True;
						else
							$active = False;
                        $level = 2;
                        array_push($objectID_stack, $list->object_id);
                    }
                    else if ($level == 2)
                    {
						if($active)
							$class = " active";
						else
							$class = "";
                        if($dropDownOption == "click")
                            $output = $output."<li class=\"dropdown-submenu".$class."\"><a href=\"#\">".$last_title."</a><ul class=\"dropdown-menu sub-menu\">";
                        else
                            $output = $output."<li class=\"dropdown-submenu".$class."\"><a href=\"".$last_url."\">".$last_title."</a><ul class=\"dropdown-menu sub-menu\">";
                        $last_title = $list->title;
                        $last_url = $list->url;
						if($post->ID == $list->object_id)
							$active = True;
						else
							$active = False;
                        $level = 3;
                    }
                    else if($level == 3)
                    {
						if($active)
							$class = " class=\"active\"";
						else
							$class = "";
                        $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li>";
                        $last_title = $list->title;
                        $last_url = $list->url;
						if($post->ID == $list->object_id)
							$active = True;
						else
							$active = False;
                        $level = 3;
                    }       
                }
                else
                {
                    if($level == 2)
                    {
						if($active)
							$class = " class=\"active\"";
						else
							$class = "";
                        array_pop($objectID_stack);
                        $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li>";
                        $last_title = $list->title;
                        $last_url = $list->url;
						if($post->ID == $list->object_id)
							$active = True;
						else
							$active = False;
                        $level = 2;
                        array_push($objectID_stack, $list->object_id);
                    }
                    if($level == 3)
                    {
						if($active)
							$class = " class=\"active\"";
						else
							$class = "";
                        array_pop($objectID_stack);
                        $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li></ul></li>";
                        $last_title = $list->title;
                        $last_url = $list->url;
						if($post->ID == $list->object_id)
							$active = True;
						else
							$active = False;
                        $level = 2;
                        array_push($objectID_stack, $list->object_id);
                    }
                    
                }
            }
        }
		if($active)
			$class = " class=\"active\"";
		else
			$class = "";
        if($level == 1) //If is parent and not printed.
            $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li>";
        else if($level == 2) //If is sub and not printed.
            $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li></ul></li>";
        else if($level == 3) //If is sub of sub and not printed.
            $output = $output."<li".$class."><a href=\"".$last_url."\">".$last_title."</a></li></ul></li></ul></li>";
        return $output;
    }