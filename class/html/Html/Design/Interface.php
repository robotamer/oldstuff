<?php
/**
 * $Id: Interface.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!interface_exists('Html_Design_Interface')) {
    /**
     * interfaces for html objects that can have design style
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    interface Html_Design_Interface
    {
        const __ALIGN_LEFT__ = 'left';
        const __ALIGN_RIGHT__ = 'right';
        const __ALIGN_CENTER__ = 'center';
        const __ALIGN_JUSTIFY__ = 'justify';
        /**
         * set the style
         * @access public
         * @param string $style : the style
         * @return void
         */
        public function setStyle($style = '');
        /**
         * add the style
         * @access public
         * @param string $style : the style
         * @return void
         */
        public function addStyle($style = '');
        /**
        * set the style
        * @access public
        * @param string $style : the style
        * @return void
        */
        public function getStyle();
        /**
         * get the class style
         * @access public
         * @return void
         */
        public function setStyleClass($class = '');
        /**
         * get the class style
         * @access public
         * @return void
         */
        public function getStyleClass();
    }
}