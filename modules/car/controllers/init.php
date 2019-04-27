<?php
/**
 * @filesource modules/car/controllers/init.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Car\Init;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * Init Module.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นเริ่มต้นการทำงานของโมดูลที่ติดตั้ง
     * และจัดการเมนูของโมดูล.
     *
     * @param Request                $request
     * @param \Index\Menu\Controller $menu
     * @param array                  $login
     */
    public static function execute(Request $request, $menu, $login)
    {
        $menu->addTopLvlMenu('vehicles', '{LNG_List of} {LNG_Car}', 'index.php?module=car-vehicles', null, 'member');
        $menu->addTopLvlMenu('car', '{LNG_Car}', null, array(
            array(
                'text' => '{LNG_My Booking}',
                'url' => 'index.php?module=car',
            ),
            array(
                'text' => '{LNG_Book a vehicle}',
                'url' => 'index.php?module=car-booking',
            ),
        ), 'member');
        if (Login::checkPermission($login, 'can_manage_car')) {
            // เมนูตั้งค่า
            $submenus = array(
                array(
                    'text' => '{LNG_Settings}',
                    'url' => 'index.php?module=car-settings',
                ),
                array(
                    'text' => '{LNG_List of} {LNG_Car}',
                    'url' => 'index.php?module=car-setup',
                ),
                array(
                    'text' => '{LNG_Add New} {LNG_Car}',
                    'url' => 'index.php?module=car-write',
                ),
            );
            foreach (Language::get('CAR_OPTIONS') as $type => $text) {
                $submenus[] = array(
                    'text' => $text,
                    'url' => 'index.php?module=car-categories&amp;type='.$type,
                );
            }
            foreach (Language::get('CAR_SELECT') as $type => $text) {
                $submenus[] = array(
                    'text' => $text,
                    'url' => 'index.php?module=car-categories&amp;type='.$type,
                );
            }
            $menu->add('settings', '{LNG_Car}', null, $submenus);
        }
        if (Login::checkPermission($login, 'can_approve_car')) {
            $submenus = array();
            foreach (Language::get('CAR_BOOKING_STATUS') as $type => $text) {
                $submenus[] = array(
                    'text' => $text,
                    'url' => 'index.php?module=car-report&amp;status='.$type,
                );
            }
            $menu->addTopLvlMenu('report', '{LNG_Report}', null, $submenus, 'signout');
        }
    }

    /**
     * รายการ permission ของโมดูล.
     *
     * @param array $permissions
     *
     * @return array
     */
    public static function updatePermissions($permissions)
    {
        $permissions['can_manage_car'] = '{LNG_Can manage car}';
        $permissions['can_approve_car'] = '{LNG_Can be approve}';

        return $permissions;
    }
}
