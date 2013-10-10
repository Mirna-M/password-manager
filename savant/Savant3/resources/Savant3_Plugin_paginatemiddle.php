<?php
/**
 * Project:     SavantPaginate: Pagination for the Savant Template Engine
 * File:        Savant3_Plugin_paginatemiddle.php
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
class Savant3_Plugin_paginatemiddle extends Savant3_Plugin{

    public function paginatemiddle(array $params = array()){
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Missing SavantPaginate class file'));
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'SavantPaginate is not initialized, use connect() first'));
            return;
        }

        /* page_limit can be set in two ways: First way is using
         * setPluginConf('paginatemiddle',array('page_limit' => 5)); */
        $_page_limit = isset($this->page_limit) ? $this->page_limit : null;

        /* page_limit default is 'page' and it can be changed in two ways:
         * First way is using setPluginConf('paginatemiddle',array('format' =>
         * 'range')); */
        if(isset($this->format) && $this->format == 'range'){
            $_format = $this->format;
        } else{
            $_format = 'page';
        }

        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Unknown pagination id '.$_val));
                        return;
                    }
                    $_id = $_val;
                    break;
                case 'page_limit';
                    $_page_limit = $_val;
                    break;
                case 'format':
                    if($_val == 'range' || $_val == 'page'){
                        $_format = $_val;
                    }
                    break;
            }
        }

        if (!isset($_SESSION['SavantPaginate'][$_id]['item_total'])) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Total was not set'));
            return;
        }

        if(!isset($_page_limit) && isset($_SESSION['SavantPaginate'][$_id]['page_limit'])) {
            $_page_limit = $_SESSION['SavantPaginate'][$_id]['page_limit'];
        }

        $_url = $_SESSION['SavantPaginate'][$_id]['url'];

        $_total = SavantPaginate::getTotal($_id);
        $_curr_item = SavantPaginate::getCurrentItem($_id);
        $_limit = SavantPaginate::getLimit($_id);

        $_item = 1;
        $_page = 1;
        $_display_pages = 0;
        $_ret = '';


        if(isset($_page_limit)) {
            // find halfway point
            $_page_limit_half = floor($_page_limit / 2);
            // determine what item/page we start with
            $_item_start = $_curr_item - $_limit * $_page_limit_half;
            if( ($_view = ceil(($_total - $_item_start) / $_limit)) < $_page_limit) {
                $_item_start -= ($_limit * ( $_page_limit - $_view ));
            }
            $_item = ($_item_start >= 1) ? $_item_start : 1;
            $_page = ceil($_item / $_limit);
        }

        while($_item <= $_total) {
            if($_format == 'page') {
                $_text = $_page;
            } else {
                $_text = $_item . '-';
                $_text .= ($_item + $_limit - 1 <= $_total) ? $_item + $_limit - 1 : $_total;
            }
            if($_item != $_curr_item) {
                $_this_url = $_url;
                $_this_url .= (strpos($_url, '?') === false) ? '?' : '&';
                $_this_url .= SavantPaginate::getUrlVar($_id) . '=' . $_item;
                $_ret .= '<a href="' . str_replace('&', '&amp;', $_this_url) . '">' . $_text . '</a>';
            } else {
                $_ret .= '<span class="current">'.$_text.'</span>';
            }
            $_item += $_limit;
            $_page++;
            $_display_pages++;
            if(isset($_page_limit) && $_display_pages == $_page_limit)
                break;
        }

        return $_ret;
    }
}
?>
