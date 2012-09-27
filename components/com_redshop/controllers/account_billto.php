<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';
require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'order.php');

/**
 * account_billtoController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class account_billtoController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
        $this->registerTask('', 'edit');
    }

    /**
     * Method to edit billing Address
     *
     */
    public function edit()
    {
        $user                        = JFactory::getUser();
        $order_functions             = new order_functions();
        $billingaddresses            = $order_functions->getBillingAddress($user->id);
        $GLOBALS['billingaddresses'] = $billingaddresses;

        $task = $this->input->get('submit', 'post');
        if ($task == 'Cancel')
        {
            $this->registerTask('save', 'cancel');
        }
        parent::display();
    }

    /**
     * Method to save Billing Address
     *
     */
    public function save()
    {
        $user    = JFactory::getUser();
        $post    = $this->input->getArray($_POST);
        $return  = $this->input->get('return');
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');
        //$cid = $this->input->post->getArray('cid', array(0));
        $cid = $this->input->post->get('cid', array(), 'array');

        $post['users_info_id'] = $cid[0];
        $post['id']            = $post['user_id'];
        $post['address_type']  = "BT";
        $post['email']         = $post['email1'];
        //$post['password']      = JRequest::getVar('password1', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['password'] = $this->input->post->getString('password1', '');
        //$post['password2']     = JRequest::getVar('password2', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['password2'] = $this->input->post->getString('password2', '');

        if (isset($user->username))
        {
            $post['username'] = $user->username;
        }

        $model = $this->getModel('account_billto');
        if ($reduser = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_SAVE');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_BILLING_INFORMATION');
        }

        $setexit = $this->input->getInt('setexit', 1);
        $link    = '';
        if ($return != "")
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&Itemid=' . $item_id, false);
            if (!isset($setexit) || $setexit != 0)
            {
                ?>
            <script language="javascript">
                window.parent.location.href = "<?php echo $link ?>";
            </script>
            <?php
                exit;
            }
        }
        else
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=account&Itemid=' . $item_id, false);
        }
        $this->setRedirect($link, $msg);
    }

    /**
     * Method called when user pressed cancel button
     *
     */
    public function cancel()
    {
        $option  = $this->input->get('option');
        $item_id = $this->input->get('Itemid');

        $msg = JText::_('COM_REDSHOP_BILLING_INFORMATION_EDITING_CANCELLED');

        $return  = $this->input->get('return');
        $setexit = $this->input->getInt('setexit', 1);

        $link = '';
        if ($return != "")
        {
            $link = JRoute::_('index.php?option=' . $option . '&view=' . $return . '&Itemid=' . $item_id, false);
            if (!isset($setexit) || $setexit != 0)
            {
                ?>
            <script language="javascript">
                window.parent.location.href = "<?php echo $link ?>";
            </script>
            <?php
                exit;
            }
        }
        else
        {
            $link = 'index.php?option=' . $option . '&view=account&Itemid=' . $item_id;
        }
        $this->setRedirect($link, $msg);
    }
}

