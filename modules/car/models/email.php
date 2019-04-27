<?php
/**
 * @filesource modules/car/models/email.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Email;

use Kotchasan\Language;

/**
 * ส่งอีเมลไปยังผู้ที่เกี่ยวข้อง.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * ส่งอีเมลแจ้งการจอง.
     *
     * @param string $mailto
     * @param string $detail
     * @param int    $status
     */
    public static function send($mailto, $detail, $status)
    {
        if (self::$cfg->noreply_email != '') {
            // อีเมลของมาชิกที่สามารถอนุมัติได้ทั้งหมด
            $query = \Kotchasan\Model::createQuery()
                ->select('username')
                ->from('user')
                ->where(array('social', 0))
                ->andWhere(array(
                    array('status', 1),
                    array('permission', 'LIKE', '%,can_approve_car,%'),
                ), 'OR')
                ->cacheOn();
            $emails = array($mailto => $mailto);
            foreach ($query->execute() as $item) {
                $emails[$item->username] = $item->username;
            }
            // ส่งอีเมล
            $subject = '['.self::$cfg->web_title.'] '.Language::get('Book a vehicle');
            $msg = Language::get('Book a vehicle').' '.$detail;
            $msg .= "<br>\n".Language::get('Status').' : '.Language::find('CAR_BOOKING_STATUS', null, $status);
            $msg .= "<br>\n".WEB_URL;
            $err = \Kotchasan\Email::send(implode(',', $emails), self::$cfg->noreply_email, $subject, $msg);
            if ($err->error()) {
                // คืนค่า error
                return strip_tags($err->getErrorMessage());
            } else {
                // คืนค่า
                return Language::get('Your message was sent successfully');
            }
        } else {
            // ไม่สามารถส่งอีเมล์ได้
            return Language::get('Saved successfully');
        }
    }
}
