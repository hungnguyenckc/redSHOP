<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license   GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *            Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class newsletter_detailModelnewsletter_detail extends JModel
{
    var $_id = null;
    var $_data = null;
    var $_table_prefix = null;

    function __construct ()
    {
        parent::__construct();
        $this->_table_prefix = '#__redshop_';
        $array               = JRequest::getVar('cid', 0, '', 'array');
        $this->setId((int)$array[0]);
    }

    function setId ($id)
    {
        $this->_id   = $id;
        $this->_data = null;
    }

    function &getData ()
    {
        if ($this->_loadData()) {
        } else  {
            $this->_initData();
        }
        return $this->_data;
    }

    function _loadData ()
    {
        if (empty($this->_data)) {
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'newsletter WHERE newsletter_id = ' . $this->_id;
            $this->_db->setQuery($query);
            $this->_data = $this->_db->loadObject();
            return (boolean)$this->_data;
        }
        return true;
    }

    function _initData ()
    {
        if (empty($this->_data)) {
            $detail                = new stdClass();
            $detail->newsletter_id = 0;
            $detail->name          = null;
            $detail->subject       = null;
            $detail->body          = null;
            $detail->template_id   = 0;
            $detail->published     = 1;
            $this->_data           = $detail;
            return (boolean)$this->_data;
        }
        return true;
    }

    function store ($data)
    {
        $row = $this->getTable();
        if (!$row->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return $row;
    }

    function delete ($cid = array())
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'DELETE FROM ' . $this->_table_prefix . 'newsletter WHERE newsletter_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publish ($cid = array(), $publish = 1)
    {
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'UPDATE ' . $this->_table_prefix . 'newsletter'
                . ' SET published = ' . intval($publish)
                . ' WHERE newsletter_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            if (!$this->_db->query()) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function copy ($cid = array())
    {
        $copydata = array();
        if (count($cid)) {
            $cids = implode(',', $cid);

            $query = 'SELECT * FROM ' . $this->_table_prefix . 'newsletter '
                . 'WHERE newsletter_id IN ( ' . $cids . ' )';
            $this->_db->setQuery($query);
            $copydata = $this->_db->loadObjectList();
        }

        for ($i = 0; $i < count($copydata); $i++) {
            $post['newsletter_id'] = 0;
            $post['name']          = 'Copy Of ' . $copydata[$i]->name;
            $post['subject']       = $copydata[$i]->subject;
            $post['body']          = $copydata[$i]->body;
            $post['template_id']   = $copydata[$i]->template_id;
            $post['published']     = $copydata[$i]->published;

            $row = newsletter_detailModelnewsletter_detail::store($post);

            // Copy subscriber of newsletters
            $query = 'SELECT * FROM ' . $this->_table_prefix . 'newsletter_subscription '
                . 'WHERE newsletter_id IN ( ' . $copydata[$i]->newsletter_id . ' )';
            $this->_db->setQuery($query);
            $subscriberdata = $this->_db->loadObjectList();

            for ($j = 0; $j < count($subscriberdata); $j++) {
                $rowsubscr                  = & $this->getTable('newslettersubscr_detail');
                $rowsubscr->subscription_id = 0;
                $rowsubscr->user_id         = $subscriberdata[$j]->user_id;
                $rowsubscr->date            = time();
                $rowsubscr->newsletter_id   = $row->newsletter_id;
                $rowsubscr->name            = $subscriberdata[$j]->name;
                $rowsubscr->email           = $subscriberdata[$j]->email;
                $rowsubscr->published       = $subscriberdata[$j]->published;
                $rowsubscr->checkout        = $subscriberdata[$j]->checkout;
                $rowsubscr->store();
            }
        }
        return true;
    }

    function gettemplates ()
    {
        $query = 'SELECT template_id AS value,template_name AS text FROM ' . $this->_table_prefix . 'template '
            . 'WHERE template_section="newsletter" '
            . 'AND published=1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    function getnewslettertexts ()
    {
        $query = 'SELECT text_name,text_desc FROM ' . $this->_table_prefix . 'textlibrary '
            . 'WHERE section="newsletter" '
            . 'AND published=1';
        $this->_db->setQuery($query);
        return $this->_db->loadObjectlist();
    }

    function getNewsletterList ($newsletter_id = 0)
    {
        $and = "";
        if ($newsletter_id != 0) {
            $and .= "AND n.newsletter_id='" . $newsletter_id . "' ";
        }
        $query = 'SELECT n.*,CONCAT(n.name," (",n.subject,")") AS text FROM ' . $this->_table_prefix . 'newsletter AS n '
            . 'WHERE 1=1 '
            . $and;
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectlist();
        return $list;
    }

    function getNewsletterTracker ($newsletter_id = 0)
    {
        $data = $this->getNewsletterList($newsletter_id);

        $return = array();
        $qs     = array();
        $j      = 0;
        for ($d = 0; $d < count($data); $d++) {
            $query = "SELECT COUNT(*) AS total FROM " . $this->_table_prefix . "newsletter_tracker "
                . "WHERE newsletter_id='" . $data[$d]->newsletter_id . "' ";
            $this->_db->setQuery($query);
            $totalresult = $this->_db->loadResult();
            if (!$totalresult) {
                $totalresult = 0;
            }
            if ($newsletter_id != 0) {
                $totalread    = $this->getReadNewsletter($data[$d]->newsletter_id);
                $qs[0]->xdata = JText::_('COM_REDSHOP_NO_OF_UNREAD_NEWSLETTER');
                $qs[0]->ydata = $totalresult - $totalread;
                $qs[1]->xdata = JText::_('COM_REDSHOP_NO_OF_READ_NEWSLETTER');
                $qs[1]->ydata = $totalread;
            } else {
                $qs[$d]->xdata = $data[$d]->name;
                $qs[$d]->ydata = $totalresult;
            }
        }
        if ($newsletter_id != 0) {
            $return = array($qs, $data[0]->name);
        } else {
            $return = array($qs, JText::_('COM_REDSHOP_NO_OF_SENT_NEWSLETTER'));
        }
        return $return;
    }

    function getReadNewsletter ($newsletter_id)
    {
        $query = "SELECT COUNT(*) AS total FROM " . $this->_table_prefix . "newsletter_tracker "
            . "WHERE `newsletter_id`='" . $newsletter_id . "' "
            . "AND `read`='1' ";
        $this->_db->setQuery($query);
        $result = $this->_db->loadObject();
        if (!$result) {
            $result->total = 0;
        }
        return $result->total;
    }
}

?>
