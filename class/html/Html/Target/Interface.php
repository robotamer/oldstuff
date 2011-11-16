<?php
/**
 * $Id: Interface.php 1631 2007-05-12 22:40:28Z matthieu $
 */
if (!interface_exists('Html_Target_Interface')) {
    /**
     * target html interface
     * @author Matthieu MARY <matthieu@phplibrairies.com>
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     * @package html
     */
    interface Html_Target_Interface
    {
        const _TARG_SELF_ = '_self';
        const _TARG_BLANK_ = '_blank';
        const _TARG_PARENT_ = '_parent';
        /**
         * get the specified target
         * @access public
         * @return string
         */
        public function getTarget();
        /**
         * set the specified target
         * @param string $target : the target to set
         * @return void
         */
        public function setTarget($target);
    }
} 