<?php

use \Entities\User;

class Sch_View_Helper_Avatar extends Zend_View_Helper_HtmlElement
{

    public function avatar(User $user, $size = User::USERPIC_BIG, $attribs = array())
    {
        $image = '/img/userpic.jpg';

        if (array_key_exists($size, User::$userpicSettings)) {
            $userpicSettings = User::$userpicSettings[$size];
            if (array_key_exists('empty', $userpicSettings)) {
                $image = $userpicSettings['empty'];
            }
        }

        if ($user instanceof User) {
            $attachmentsHelper = new Sch_Controller_Action_Helper_AttachmentPath();
            $path = $attachmentsHelper->getRealPath($user, 'user');
            $userpic = $user->getUserpic();

            if ($userpic) {
                $filename = $userpic;
                if (isset($userpicSettings)) {
                    $userpicParts = pathinfo($userpic);
                    $filename = $userpicParts['filename'];
                    if (array_key_exists('prefix', $userpicSettings)) {
                        $filename = $userpicSettings['prefix'] . $filename;
                    }
                    if (array_key_exists('suffix', $userpicSettings)) {
                        $filename .= $userpicSettings['suffix'];
                    }
                    if (isset($userpicParts['extension']) && !empty($userpicParts['extension'])) {
                        $filename .= '.' . $userpicParts['extension'];
                    }
                }

                if ($userpicPath = realpath($path . DIRECTORY_SEPARATOR . $filename)) {
                    $www = $attachmentsHelper->getWebPath($user, 'user');
                    $image = $www . $filename;
                    list($attribs['width'], $attribs['height']) = getimagesize($userpicPath);
                }
            }

            if (!array_key_exists('title', $attribs)) {
                $attribs['title'] = sprintf('%s (%s)', $user->getLogin(), $user->getFullName());
            }
        }

        return $this->_renderImage($image, $attribs);
    }

    protected function _renderImage($image, $attribs = array())
    {
        if (array_key_exists('__clear_cache', $attribs) && $attribs['__clear_cache']) {
            $image .= '?' . uniqid();
            unset($attribs['__clear_cache']);
        }
        return '<img' .
            ' src="' . $image . '"' .
            $this->_htmlAttribs($attribs) . ' ' .
            $this->getClosingBracket();
    }

}
