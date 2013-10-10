<?php
/**
 * Project:     SavantPaginate: Pagination for the Savant Template Engine
 * File:        SavantPaginate.php
 * Author:      Marko Martinović <marko at techytalk.info>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * For instructions for SavantPaginate go to http://www.techytalk.info/SavantPaginate
 * For instructions for Savant go to http://www.phpsavant.com/
 *
 * Based on work from SmartyPaginate project
 * http://www.phpinsider.com/php/code/SmartyPaginate/
 *
 * @link http://www.techytalk.info/savantpaginate
 * @author Marko Martinović <marko at techytalk.info>
 * @package SavantPaginate
 * @version 0.1
 */

class SavantPaginate {
    /**
     * Class Constructor
     */
    function __construct() { }

    /**
     * initialize the session data
     *
     * @param string $id the pagination id
     * @param string $formvar the variable containing submitted pagination information
     */
    public static function connect($id = 'default', $formvar = null){
        if(!isset($_SESSION['SavantPaginate'][$id])) {
            self::reset($id);
        }
        
        // use $_GET by default unless otherwise specified
        $_formvar = isset($formvar) ? $formvar : $_GET;
        
        // set the current page
        $_total = SavantPaginate::getTotal($id);
        if(isset($_formvar[self::getUrlVar($id)]) && $_formvar[self::getUrlVar($id)] > 0 && (!isset($_total) || $_formvar[self::getUrlVar($id)] <= $_total))
            $_SESSION['SavantPaginate'][$id]['current_item'] = $_formvar[$_SESSION['SavantPaginate'][$id]['urlvar']];


        // CurrentLocation equals $_SERVER['REQUEST_URI'] minus UrlVar
        $CurrentLocation = substr(preg_replace('/(.*)(\?|&)' . self::getUrlVar($id) . '=[^&]+?(&)(.*)/i', '$1$2$4', $_SERVER['REQUEST_URI'] . '&'), 0, -1);
        if($CurrentLocation != self::getURL()) {
            self::reset();
            self::setURL($CurrentLocation);
        }
    }

    /**
     * see if session has been initialized
     *
     * @param string $id the pagination id
     */
    static function isConnected($id = 'default') {
        return isset($_SESSION['SavantPaginate'][$id]);
    }    
        
    /**
     * reset/init the session data
     *
     * @param string $id the pagination id
     */
    static function reset($id = 'default') {
        $_SESSION['SavantPaginate'][$id] = array(
            'item_limit' => 10,
            'item_total' => null,
            'current_item' => 1,
            'urlvar' => 'next',
            'url' => $_SERVER['PHP_SELF'],
            'prev_text' => '&laquo;Previous',
            'next_text' => 'Next &raquo;',
            'first_text' => 'First',
            'last_text' => 'Last'
            );
    }
    
    /**
     * clear the SavantPaginate session data
     *
     * @param string $id the pagination id
     */
    static function disconnect($id = null) {
        if(isset($id))
            unset($_SESSION['SavantPaginate'][$id]);
        else
            unset($_SESSION['SavantPaginate']);
    }

    /**
     * set maximum number of items per page
     *
     * @param string $id the pagination id
     */
    static function setLimit($limit, $id = 'default') {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('SavantPaginate setLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('SavantPaginate setLimit: limit must be greater than zero.');
            return false;
        }
        $_SESSION['SavantPaginate'][$id]['item_limit'] = $limit;
    }    

    /**
     * get maximum number of items per page
     *
     * @param string $id the pagination id
     */
    static function getLimit($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['item_limit'];
    }    
            
    /**
     * set the total number of items
     *
     * @param int $total the total number of items
     * @param string $id the pagination id
     */
    static function setTotal($total, $id = 'default') {
        if(!preg_match('!^\d+$!', $total)) {
            trigger_error('SavantPaginate setTotal: total must be an integer.');
            return false;
        }
        settype($total, 'integer');
        if($total < 0) {
            trigger_error('SavantPaginate setTotal: total must be positive.');
            return false;
        }
        $_SESSION['SavantPaginate'][$id]['item_total'] = $total;
    }

    /**
     * get the total number of items
     *
     * @param string $id the pagination id
     */
    static function getTotal($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['item_total'];
    }    

    /**
     * set the url used in the links, default is $PHP_SELF
     *
     * @param string $url the pagination url
     * @param string $id the pagination id
     */
    static function setUrl($url, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['url'] = $url;
    }

    /**
     * get the url variable
     *
     * @param string $id the pagination id
     */
    static function getUrl($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['url'];
    }    
    
    /**
     * set the url variable ie. ?next=10
     *                           ^^^^
     * @param string $url url pagination varname
     * @param string $id the pagination id
     */
    static function setUrlVar($urlvar, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['urlvar'] = $urlvar;
    }

    /**
     * get the url variable
     *
     * @param string $id the pagination id
     */
    static function getUrlVar($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['urlvar'];
    }    
        
    /**
     * set the current item (usually done automatically by next/prev links)
     *
     * @param int $item index of the current item
     * @param string $id the pagination id
     */
    static function setCurrentItem($item, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['current_item'] = $item;
    }

    /**
     * get the current item
     *
     * @param string $id the pagination id
     */
    static function getCurrentItem($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['current_item'];
    }    

    /**
     * get the current item index
     *
     * @param string $id the pagination id
     */
    static function getCurrentIndex($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['current_item'] - 1;
    }    
    
    /**
     * get the last displayed item
     *
     * @param string $id the pagination id
     */
    static function getLastItem($id = 'default') {
        $_total = SavantPaginate::getTotal($id);
        $_limit = SavantPaginate::getLimit($id);
        $_last = SavantPaginate::getCurrentItem($id) + $_limit - 1;
        return ($_last <= $_total) ? $_last : $_total; 
    }    
    
    /**
     * assign $paginate var values
     *
     * @param obj &$savant the savant object reference
     * @param string $var the name of the assigned var
     * @param string $id the pagination id
     */
    static function assign(&$savant, $var = 'paginate', $id = 'default') {
        if(is_object($savant) && (strtolower(get_class($savant)) == 'savant3' || is_subclass_of($savant, 'savant3'))) {
            $_paginate['total'] = SavantPaginate::getTotal($id);
            $_paginate['first'] = SavantPaginate::getCurrentItem($id);
            $_paginate['last'] = SavantPaginate::getLastItem($id);
            $_paginate['page_current'] = ceil(SavantPaginate::getLastItem($id) / SavantPaginate::getLimit($id));
            $_paginate['page_total'] = ceil(SavantPaginate::getTotal($id)/SavantPaginate::getLimit($id));
            $_paginate['size'] = $_paginate['last'] - $_paginate['first'];
            $_paginate['url'] = SavantPaginate::getUrl($id);
            $_paginate['urlvar'] = SavantPaginate::getUrlVar($id);
            $_paginate['current_item'] = SavantPaginate::getCurrentItem($id);
            $_paginate['prev_text'] = SavantPaginate::getPrevText($id);
            $_paginate['next_text'] = SavantPaginate::getNextText($id);
            $_paginate['limit'] = SavantPaginate::getLimit($id);
            
            $_item = 1;
            $_page = 1;
            while($_item <= $_paginate['total'])           {
                $_paginate['page'][$_page]['number'] = $_page;   
                $_paginate['page'][$_page]['item_start'] = $_item;
                $_paginate['page'][$_page]['item_end'] = ($_item + $_paginate['limit'] - 1 <= $_paginate['total']) ? $_item + $_paginate['limit'] - 1 : $_paginate['total'];
                $_paginate['page'][$_page]['is_current'] = ($_item == $_paginate['current_item']);
                $_item += $_paginate['limit'];
                $_page++;
            }
            $savant->$var = $_paginate;
        } else {
            trigger_error("SavantPaginate: [assign] I need a valid Savant object.");
            return false;            
        }        
    }    

    
    /**
     * set the default text for the "previous" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
    static function setPrevText($text, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['prev_text'] = $text;
    }

    /**
     * get the default text for the "previous" page link
     *
     * @param string $id the pagination id
     */
    static function getPrevText($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['prev_text'];
    }    
    
    /**
     * set the text for the "next" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
    static function setNextText($text, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['next_text'] = $text;
    }
    
    /**
     * get the default text for the "next" page link
     *
     * @param string $id the pagination id
     */
    static function getNextText($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['next_text'];
    }    

    /**
     * set the text for the "first" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
    static function setFirstText($text, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['first_text'] = $text;
    }
    
    /**
     * get the default text for the "first" page link
     *
     * @param string $id the pagination id
     */
    static function getFirstText($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['first_text'];
    }    
    
    /**
     * set the text for the "last" page link
     *
     * @param string $text index of the current item
     * @param string $id the pagination id
     */
    static function setLastText($text, $id = 'default') {
        $_SESSION['SavantPaginate'][$id]['last_text'] = $text;
    }
    
    /**
     * get the default text for the "last" page link
     *
     * @param string $id the pagination id
     */
    static function getLastText($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['last_text'];
    }    
    
    /**
     * set default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
    static function setPageLimit($limit, $id = 'default') {
        if(!preg_match('!^\d+$!', $limit)) {
            trigger_error('SavantPaginate setPageLimit: limit must be an integer.');
            return false;
        }
        settype($limit, 'integer');
        if($limit < 1) {
            trigger_error('SavantPaginate setPageLimit: limit must be greater than zero.');
            return false;
        }
        $_SESSION['SavantPaginate'][$id]['page_limit'] = $limit;
    }    

    /**
     * get default number of page groupings in {paginate_middle}
     *
     * @param string $id the pagination id
     */
    static function getPageLimit($id = 'default') {
        return $_SESSION['SavantPaginate'][$id]['page_limit'];
    }
            
    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
    static function _getPrevPageItem($id = 'default') {
        
        $_prev_item = $_SESSION['SavantPaginate'][$id]['current_item'] - $_SESSION['SavantPaginate'][$id]['item_limit'];
        
        return ($_prev_item > 0) ? $_prev_item : false; 
    }    

    /**
     * get the previous page of items
     *
     * @param string $id the pagination id
     */
    static function _getNextPageItem($id = 'default') {
                
        $_next_item = $_SESSION['SavantPaginate'][$id]['current_item'] + $_SESSION['SavantPaginate'][$id]['item_limit'];
        
        return ($_next_item <= $_SESSION['SavantPaginate'][$id]['item_total']) ? $_next_item : false;
    }            
}

?>
