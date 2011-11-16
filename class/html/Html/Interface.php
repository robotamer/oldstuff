<?php
/**
 * $Id: Interface.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!interface_exists('Html_Interface')) {
    /**
     * form interface Element
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    interface Html_Interface
    {
        /**
         * set the id
         * @param string $id
         * @access public
         * @return string
         */
        public function setId($id);
        /**
         * @access public
         * @return string
         * return the id
         */
        public function getId();
    }
}