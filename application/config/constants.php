<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('_TITLE')           OR define('_TITLE', 'ฟุตบอลโลก 2022 มหกรรมกีฬาที่ทุกคนรอคอย');
defined('_KEYWORD')         OR define('_KEYWORD', 'ฟุตบอลโลก 2022, ฟุตบอลโลก, ข่าวฟุตบอลโลก, worldcup 2022, ผลบอล, ผลบอลโลก, โปรแกรมบอลโลก, ตารางคะแนน, ตารางถ่ายทอดสดบอลโลก, โปรแกรมถ่ายทอดสดบอลโลก, วิเคราะห์บอลโลก, แกลเลอรี่บอลโลก, ภาพบอลโลก, คลิปบอลโลก, ทีมอังกฤษ, ทีมเยอรมัน, ทีมฮอลแลนด์, ทีมอิตาลี, ทีมฝรั่งเศส, ทีมสเปน, ทีมโปรตุเกส');
defined('_DESCRIPTION')     OR define('_DESCRIPTION', 'ฟุตบอลโลก 2022 มหกรรมกีฬาที่ทุกคนรอคอย ติดตามข่าว โปรแกรม ผลการแข่งขัน ผลบอล ตารางคะแนน โปรแกรมถ่ายทอดสด วิเคราะห์บอลยูโร แกลเลอรี่ ภาพ คลิปวิดีโอ ข้อมูลทีม คอลัมน์ และร่วมสนุกเล่นเกม รวมถึงข้อมูลทีมต่างๆใน worldcup 2022');
defined('_COVER_WC2022')    OR define('_COVER_WC2022', 'https://worldcup2022.ballnaja.com/assets/images/ballnaja-banner-new.webp');


defined('_FB')           OR define('_FB', 'https://www.facebook.com/profile.php?id=100086290803929');
defined('_TW')           OR define('_TW', 'https://twitter.com/dooballnaja');
defined('_PT')           OR define('_PT', 'https://www.pinterest.com/dooballnaja/');
defined('_YT')           OR define('_YT', 'https://www.youtube.com/channel/UC70aoVc0_i4bJvX3S3Xm4WQ');
defined('_RD')           OR define('_RD', 'https://www.reddit.com/user/Ballnaja/');
defined('_BD')           OR define('_BD', 'https://www.blockdit.com/pages/631720a917b495a7d3ac05af');

defined('_NO_PLAYER')           OR define('_NO_PLAYER', 'assets/images/no_img.png');
defined('_NO_IMAGES')           OR define('_NO_IMAGES', 'assets/images/No_image_available.svg');

defined('_HOST_BACKLINK')       OR define('_HOST_BACKLINK', '');
defined('_BANNER_BILLBOARD')       OR define('_BANNER_BILLBOARD', '2024-970x250_64283598.png');

//****** Event *********/

defined('_GOAL')        OR define('_GOAL', 'assets/images/event/icon-goal.webp');
defined('_GOAL_PEN')    OR define('_GOAL_PEN', 'assets/images/event/icon-goal-green-p.webp');
defined('_GOAL_OG')     OR define('_GOAL_OG', 'assets/images/event/icon-goal-red-og.webp');
defined('_GOAL_FREEKICK')        OR define('_GOAL_FREEKICK', 'assets/images/event/icon-freekick.webp');
defined('_ASSITS')        OR define('_ASSITS', 'assets/images/event/icon-shoe.webp');

defined('_MISS_PEN')    OR define('_MISS_PEN', 'assets/images/event/icon-goal-red-x.webp');
defined('_YELLOWRED')   OR define('_YELLOWRED', 'assets/images/event/icon-card-yellow-red.webp');
defined('_YELLOW')      OR define('_YELLOW', 'assets/images/event/icon-card-yellow.webp');
defined('_RED')         OR define('_RED', 'assets/images/event/icon-card-red.webp');

defined('_PEN_OK')      OR define('_PEN_OK', 'assets/images/event/icon-pen-right.webp');
defined('_PEN_NO')      OR define('_PEN_NO', 'assets/images/event/icon-pen-wrong.webp');
defined('_CHG_OUT')     OR define('_CHG_OUT', 'assets/images/event/icon-change-out.webp');
defined('_CHG_IN')      OR define('_CHG_IN', 'assets/images/event/icon-change-in.webp');

defined('_LEAGUEID')           OR define('_LEAGUEID', 672);

