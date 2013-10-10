<?php
/**
 * Project:     SavantPaginate: Pagination for the Savant Template Engine
 * File:        Savant3_Plugin_paginatenext.php
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
class Savant3_Plugin_paginatenext extends Savant3_Plugin{
    
    public function paginatenext(array $params = array()){
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Missing SavantPaginate class file'));
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'SavantPaginate is not initialized, use connect() first'));
            return;
        }

        /* Text can be set in two ways: First way is using
         * setPluginConf('paginatenext',array('text' => 'Next &raquo;')); */
        $_text = isset($this->text) ? $this->text : SavantPaginate::getNextText($_id);

        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Unknown pagination id '.$_val));
                        return;
                    }
                    $_id = $_val;
                    break;
                    
                /* Text can be set in two ways: Second way (takes precedence) is
                 *  passing array argument inside template like this
                 *  paginatenext(array('text' => '&laquo;Next')) */
                case 'text':
                    $_text = $_val;
                    break;
            }
        }

        if (SavantPaginate::getTotal($_id) === false) {
            $this->Savant->error('ERR_PLUGIN', array('method' => __METHOD__, 'Message' => 'Total was not set'));
            return;
        }

        $_url = SavantPaginate::getURL($_id);
        
        if(($_item = SavantPaginate::_getNextPageItem($_id)) !== false) {
            $_link = true;
            $_url .= (strpos($_url, '?') === false) ? '?' : '&';
            $_url .= SavantPaginate::getUrlVar($_id) . '=' . $_item;
        } else {
            $_link = false;
        }

        if($_link === true){
            return '<a href="' . str_replace('&','&amp;', $_url) . '" class="next">' . $_text . '</a>';
        } else {
            return  '<span class="disabled">'.$_text.'</span>';
        }
    }
}
?>
