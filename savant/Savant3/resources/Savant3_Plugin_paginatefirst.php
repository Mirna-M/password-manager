<?php
/**
 * Project:     SavantPaginate: Pagination for the Savant Template Engine
 * File:        Savant3_Plugin_paginatefirst.php
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
class Savant3_Plugin_paginatefirst extends Savant3_Plugin{
    
    public function paginatefirst(array $params = array()) {
        $_id = 'default';

        if (!class_exists('SavantPaginate')) {
            $Savant->trigger_error("paginate_first: missing SavantPaginate class");
            return;
        }
        if (!isset($_SESSION['SavantPaginate'])) {
            $Savant->trigger_error("paginate_first: SavantPaginate is not initialized, use connect() first");
            return;
        }
        
        /* Text can be set in two ways: First way is using
         * setPluginConf('paginatefirst',array('text' => 'First')); */
        $_text = isset($this->text) ? $this->text : SavantPaginate::getFirstText($_id);

        foreach($params as $_key => $_val) {
            switch($_key) {
                case 'id':
                    if (!SavantPaginate::isConnected($_val)) {
                        $Savant->trigger_error("paginate_first: unknown id '$_val'");
                        return;
                    }
                    $_id = $_val;
                    break;

                /* Text can be set in two ways: Second way (takes precedence) is
                 *  passing array argument inside template like this
                 *  paginatefirst(array('text' => 'First')) */
                case 'text':
                    $_text = $_val;
                    break;
            }
        }

        if (SavantPaginate::getTotal($_id) === false) {
            $Savant->trigger_error("paginate_first: total was not set");
            return;
        }
		
		$_url = SavantPaginate::getURL($_id);

        if(($_item = SavantPaginate::_getPrevPageItem($_id)) !== false) {
            $_link = true;
            $_url .= (strpos($_url, '?') === false) ? '?' : '&';
            $_url .= SavantPaginate::getUrlVar($_id) . '=1';
        } else {
            $_link = false;
        }

        if($_link === true){
            return '<a href="' . str_replace('&','&amp;', $_url) . '" class="first">' . $_text . '</a>';
        } else {
             return  '<span class="disabled">'.$_text.'</span>';
        }
    }
}
?>
