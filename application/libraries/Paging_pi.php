<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * A simple paging class by Jonathon Hill
 *
 */

class Page {

    public static $current;        // current page number
    public static $offset;        // database record starting position
    public static $perpage;        // items to show per page
    public static $pages;        // number of pages
    public static $items;        // number of items
    public static $key;            // key of $_GET variable indicating the current page
    public static $query;        // page query data ($_GET)
    public static $script_url;    // url of this page (without the query str);
    
    private static $full_tag_open = '<span class="paging">&nbsp;';
    private static $full_tag_close = '&nbsp;</span>';
    
    private static $first_link = '&laquo; First';
    private static $first_tag_open = '';
    private static $first_tag_close = '';
    
    private static $last_link = 'Last &raquo;';
    private static $last_tag_open = '';
    private static $last_tag_close = '';
    
    private static $next_link = '>';
    private static $next_tag_open = '';
    private static $next_tag_close = '';
    
    private static $prev_link = '<';
    private static $prev_tag_open = '';
    private static $prev_tag_close = '';
    
    private static $cur_tag_open = '<b>';
    private static $cur_tag_close = '</b>';
    
    private static $num_tag_open = '';
    private static $num_tag_close = '';
    
    
    public static function init($items, $per_page = 25, $key = 'pg') {
        
        // load the class properties
        self::$key = $key;
        self::$perpage = $per_page;
        self::$items = $items;
        self::$query = $_GET;
        self::$script_url = self::current_url();
        
        // determine what page we are on
        foreach(explode('/', self::$script_url) as $segment) {
            list($pg, $num) = explode(':', $segment);
            if($pg == $key && (int) $num > 0) {
                self::$current = (int) $num;
                break;
            }
        }
        if(self::$current <= 0) self::$current = 1;
        
        // run the page calculations
        self::$pages = (self::$items > 0)? (int) ceil(self::$items / $per_page) : 1;
        self::$offset = (self::$current - 1) * $per_page;

        // redirect to the first page if the page no. is out of range
        if((self::$current > self::$pages) || (self::$current < 1)) {
            die(self::get_url(1));
            self::redirect(self::get_url(1));
        }
        
        // load the config file if present
        if(file_exists(APPPATH.'config/pagination'.EXT)) {
            include(APPPATH.'config/pagination'.EXT);
            
            # enclosing markup
            if(isset($config['full_tag_open']))  self::$full_tag_open = $config['full_tag_open']; 
            if(isset($config['full_tag_close'])) self::$full_tag_close = $config['full_tag_close']; 
            
            # first link
            if(isset($config['first_link']))      self::$first_link = $config['first_link']; 
            if(isset($config['first_tag_open']))  self::$first_tag_open = $config['first_tag_open']; 
            if(isset($config['first_tag_close'])) self::$first_tag_close = $config['first_tag_close'];

            # last link
            if(isset($config['last_link']))      self::$last_link = $config['last_link']; 
            if(isset($config['last_tag_open']))  self::$last_tag_open = $config['last_tag_open']; 
            if(isset($config['last_tag_close'])) self::$last_tag_close = $config['last_tag_close'];

            # next link
            if(isset($config['next_link']))      self::$next_link = $config['next_link']; 
            if(isset($config['next_tag_open']))  self::$next_tag_open = $config['next_tag_open']; 
            if(isset($config['next_tag_close'])) self::$next_tag_close = $config['next_tag_close'];

            # previous link
            if(isset($config['prev_link']))      self::$prev_link = $config['prev_link']; 
            if(isset($config['prev_tag_open']))  self::$prev_tag_open = $config['prev_tag_open']; 
            if(isset($config['prev_tag_close'])) self::$prev_tag_close = $config['prev_tag_close']; 
            
            # current page
            if(isset($config['cur_tag_open']))  self::$cur_tag_open = $config['cur_tag_open']; 
            if(isset($config['cur_tag_close'])) self::$cur_tag_close = $config['cur_tag_close'];
            
            # digit link
            if(isset($config['num_tag_open']))  self::$num_tag_open = $config['num_tag_open']; 
            if(isset($config['num_tag_close'])) self::$num_tag_close = $config['num_tag_close'];
        }
    }
    
    
    # return the url of the next page
    public static function next_url($inc = 1) {
        $pg = (self::$current < self::$pages)? self::$current + $inc : self::$current;
        return self::get_url($pg);
    }
    
    
    # return the url of the last page
    public static function prev_url($inc = 1) {
        $pg = (self::$current > 1)? self::$current - $inc : self::$current;
        return self::get_url($pg);
    }
    
    
    # return the url of a specific page
    public static function get_url($page) {
        $regex = '/'.self::$key.'\:[0-9]+/';
        return preg_match($regex, self::$script_url)? preg_replace($regex, self::$key.':'.$page, self::$script_url) : rtrim(self::$script_url, '/').'/pg:'.$page;
    }
    
    
    # return HTML page links
    public static function page_links($show = 5) {
        
        $first = (self::$current - $show > 1)? self::$current - $show : 1;
        $last  = (self::$current + $show < self::$pages)? self::$current + $show : self::$pages;
        
        $links = array();
        
        if($first > 1) {
            if(strlen(self::$first_link) > 0) {
                $links[] = sprintf('%s<a href="%s">%s</a>%s', self::$first_tag_open, self::get_url(1), self::$first_link, self::$first_tag_close);
            }
            if(strlen(self::$prev_link) > 0) {
                $links[] = sprintf('%s<a href="%s">%s</a>%s', self::$prev_tag_open, self::prev_url(1), self::$prev_link, self::$prev_tag_close);
            }
        }
        
        for($i = $first; $i <= $last; $i++) {
            if($i == self::$current) {
                $links[] = "<b>$i</b>";
            } else {
                $links[] = sprintf('<a href="%s">%s</a>', self::get_url($i), $i);
            }
        }
        
        if($last < self::$pages) {
            if(strlen(self::$next_link) > 0) {
                $links[] = sprintf('%s<a href="%s">%s</a>%s', self::$next_tag_open, self::next_url(1), self::$next_link, self::$next_tag_close);
            }
            if(strlen(self::$last_link) > 0) {
                $links[] = sprintf('%s<a href="%s">%s</a>%s', self::$last_tag_open, self::get_url(self::$pages), self::$last_link, self::$last_tag_close);
            }
        }
        
        return (count($links) > 1)? self::$full_tag_open. implode('&nbsp;', $links) .self::$full_tag_close : '';
    }
    

    # return text description of result range
    public static function range($fmt = 'Viewing <b>%s</b> to <b>%s</b> of <b>%s</b>') {
        return sprintf(
            $fmt, 
            number_format(self::$offset + 1, 0), 
            number_format(((self::$offset + self::$perpage > self::$items)? self::$items : self::$offset + self::$perpage), 0), 
            number_format(self::$items, 0)
        );
    }
    
    
    # return the MySQL limit clause for the current page
    public static function limit() {
        return sprintf("LIMIT %d, %d", self::$offset, self::$perpage);
    }


    # return the full url of the current page
    private static function current_url($unset = array()) {
        $get = array_diff_key($_GET, (array) $unset);
        $url =
            (($_SERVER['HTTPS'] == 'on')? 'https://' : 'http://').
            $_SERVER['SERVER_NAME'].
            $_SERVER['REQUEST_URI'].
            ((count($_GET) > 0)? '?'.http_build_query($get) : '');
        return $url;
    }


    # redirect to an url
    private static function redirect($uri, $http_response_code = 302) {
        header("Location: $uri", TRUE, $http_response_code);
        exit;
    }


}