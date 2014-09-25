<?php
/* for Services_JSON class */
define('SERVICES_JSON_SLICE',   1);
define('SERVICES_JSON_IN_STR',  2);
define('SERVICES_JSON_IN_ARR',  3);
define('SERVICES_JSON_IN_OBJ',  4);
define('SERVICES_JSON_IN_CMT',  5);
define('SERVICES_JSON_LOOSE_TYPE', 16);
define('SERVICES_JSON_SUPPRESS_ERRORS', 32);

// PARAMETRS FROM HTML CODE
if(!function_exists('json_decode'))
{
    function json_decode($data, $array = FALSE)
    {
        $json = new Services_JSON();
        if (FALSE === $array) {
            return($json->decode($data));
        } else {
            return(objectToArray($json->decode($data)));
        }
    }
}



function jumpout_run($content) {
    return $GLOBALS['JumpOutClass']->frontendRun($content);
}

class JumpOut
{
    private $settings, $settings_default;

    function JumpOut()
    {
        $this->loadSettings();
        $this->settings_default = array(
            'session_token' => NULL,
            'token_is_working' => FALSE,
            'list' => array(),
            'activated' => array(),
            'first_not_empty_import' => NULL, // чтобы дать инструкцию о том, как установить скрипт на сайт
        );
    }

    // Загружает настройки
    function loadSettings()
    {
        $settings = (array)get_option('jumpout_settings');

        // если был установлен старый плагин
        if (isset($settings['header_code']) || isset($settings['footer_code'])) {
            $settings = $this->renewSettings($settings);
        }

        // если настройки не заданы - заменяем их на дефолтные  
        if (0 == count($settings)) {
        	$settings = $this->settings_default;
        }

        $this->settings = $settings;

        return TRUE;
    }

    // Возвращает настройки
    function getSettings()
    {
        return $this->settings;
    }

    // Сохраняет настройки
    function saveSettings()
    {
	    update_option('jumpout_settings', $this->settings);
    }


    // Добавляет в админ меню наш плагин
    function addScripts() {

        //wp_register_script('jumpout-main', plugins_url('js/main.js', __FILE__ ), 'jquery');
        wp_enqueue_script('jumpout-main', (function_exists('plugins_url')) ? plugins_url('js/main.js', __FILE__) : '/wp-content/plugins/jumpout/js/main.js', 'jquery');

    }

    // Добавляет в админ меню наш плагин
    function createMenuItem()
    {
		if (function_exists('current_user_can')) {
			// In WordPress 2.x
			if (current_user_can('manage_options')) {
				$addfoot_is_admin = true;
			}
		} else {
			// In WordPress 1.x
			global $user_ID;
			if (user_can_edit_user($user_ID, 0)) {
				$addfoot_is_admin = true;
			}
		}

		if (function_exists('add_options_page') && $addfoot_is_admin) {

			add_options_page(__("JumpOut"), __("JumpOut"), 9, 'jumpout', array(&$this, 'pages'));        

			//add_options_page('Прятатель ссылок PRO: Настройки', 'Прятатель ссылок PRO', 10, 'wpHideLinksProOptions', array($this, 'OptionsPage'));
		}

    }


    private function syncScripts() {

        $version = 'XXX';
        $result = file_get_contents('http://jumpout.makedreamprofits.ru/api/get_popups/?v=' . $version . '&cms=wordpress&session_token=' . urlencode($this->settings['session_token']) . '&site=' . $_SERVER['HTTP_HOST']);

        $result = json_decode($result);

        if (defined('JSON_ERROR_NONE') || json_last_error() == JSON_ERROR_NONE) {

            if ('error' == $result->status) {

                return $result->message;

            } else {

                $this->settings['list'] = array();
                if (0 != count($result)) {
                    foreach ($result as $row) {
                        $this->settings['list'][] = (array)$row;
                    }

                    if (NULL === $this->settings['first_not_empty_import']) {
                        $this->settings['first_not_empty_import'] = TRUE;
                    }
                }

                $this->saveSettings();

                return TRUE;
            }
        } else {
            return FALSE;
        }
    }


    function receiveSessionToken($access_token) {
        $session_token = file_get_contents('http://jumpout.makedreamprofits.ru/api/get_session_token/?access_token=' . (string)$access_token);

        return $session_token;
    }


    
    public function frontendHeader() {
        $settings = $this->getSettings();


        // обрезаем параметры
        if (strpos($_SERVER['REQUEST_URI'], '?')) {
            $REQUEST_URI = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
        } else {
            $REQUEST_URI = $_SERVER['REQUEST_URI'];
        }


        if (isset($settings['list']) && 0 != count($settings['list'])) {
            foreach ($settings['list'] as $key => $item) if (in_array($item['id'], $settings['activated'])) {
                
                if ('' == trim($comebacker['work_on_page'])) {

                    echo $this->generateCode($item['id'], $item['uid']);

                } elseif (0 === strpos($REQUEST_URI, $item['work_on_page'])) {

                    echo $this->generateCode($item['id'], $item['uid']);

                }

            }
        }
    }


    private function generateCode($id, $uid) {
        $code = '<!--Начало кода "JumpOut" (id:' . $id . ')--><script type="text/javascript">(function(d,w){n=d.getElementsByTagName("script")[0],s=d.createElement("script"),f=function(){n.parentNode.insertBefore(s,n);};s.type="text/javascript";s.async=true;qs=document.location.search.split("+").join(" ");re=/[?&]?([^=]+)=([^&]*)/g;while(tokens=re.exec(qs))
            if("email"===decodeURIComponent(tokens[1]))m=decodeURIComponent(tokens[2]);s.src="http://popupfiles.makedreamprofits.ru/' . $uid . '-user.js";if("[object Opera]"===w.opera)d.addEventListener("DomContentLoaded",f,false);else f();})(document,window);</script><!--Конец кода "JumpOut" (id:' . $id . ')-->';

        return $code;
    }


    /*
    function frontendFooter() {
        $settings = $this->getSettings();

        // обрезаем параметры
        if (strpos($_SERVER['REQUEST_URI'], '?')) {
            $REQUEST_URI = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
        } else {
            $REQUEST_URI = $_SERVER['REQUEST_URI'];
        }

        $launch_id = FALSE;

        if (isset($settings['list']) && 0 != count($settings['list'])) {
            foreach ($settings['list'] as $key => $comebacker) {
                
                if ('' == trim($comebacker['page_url']) && FALSE === $launch_id) {
                    echo '<!--cb pl launched-->';
                    $launch_id = $key;
                } elseif ($_SERVER['REQUEST_URI'] == trim($comebacker['page_url']) || $_SERVER['REQUEST_URI'] . '/' == trim($comebacker['page_url']) || $_SERVER['REQUEST_URI'] == trim($comebacker['page_url']) . '/') {
                    $launch_id = $key;
                }
            }
        }

        if (FALSE !== $launch_id) 
        {
            echo ($settings['list'][$launch_id]['code']); //stripslashes
        }
    }*/


    // Ридерект с одной страницы плагина на другую
    function redirect($action = 'list') {
    	header('Location: ?page=jumpout&action=' . $action);
    	exit();
    }


    // Рендерит страницу плагина
    function pages() {
    	if (!isset($_GET['action'])) $_GET['action'] = 'list';

    	$settings = $this->getSettings();

    	switch ($_GET['action']) {

            case 'get_session_key':
                if (isset($_GET['access_token'])) {

                    $session_token = $this->receiveSessionToken($_GET['access_token']);

                    if ('FALSE' !== $session_token && '' != trim($session_token)) {
                        $this->settings['session_token'] = $session_token;
                        $this->settings['token_is_working'] = TRUE;
                        $this->saveSettings();
                        $this->redirect('sync');
                    } else {
                        $this->redirect('session_token_error');
                    }

                    //$this->redirect('list');

                } else {
                    echo 'Не получен токен. Обратитесь в техподдержку.';
                }

                $data = array(
                    'action' => 'add',
                );
                $this->pageRender('Добавление', 'change', $data);
            break;




            case 'sync':
                // запрашиваем настройки скриптов
                $result = $this->syncScripts();

                if (isset($_GET['type']) && 'json' == (string)$_GET['type']) {
                    // если запрос через ajax

                    if (TRUE === $result) {
                        $data = array('status' => 'success');
                    } else {
                        $data = array('status' => 'error', 'message' => $result);
                    }

                    ob_end_clean();
                    //wp_send_json($data); // Since: 3.5.0

                    header('Content-Type: json/application');
                    echo json_encode($data);
                    exit();

                } else {
                    // если запрос через переход на страницу

                    if (TRUE === $result) {
                        $this->redirect('list');
                    } else {
                        if ('session token not found' == $result->message) {
                            $this->settings['token_is_working'] = FALSE;
                            $this->saveSettings();

                            $this->redirect('session_token_error');
                        } elseif ('not enough params' == $result->message) {
                            echo $result->message;
                        } else {
                            echo $result->message;
                        }
                        exit();
                    }
                    
                }

            break;


            case 'activate':
                if (isset($_GET['id'])) {
                    $this->settings['activated'][] = (int)$_GET['id'];
                    array_unique($this->settings['activated']);
                    $this->saveSettings();
                }
                $this->redirect('list');
            break;

            case 'deactivate':
                if (isset($_GET['id'])) {

                    $key = array_search($_GET['id'], $this->settings['activated']);
                    unset($this->settings['activated'][$key]);

                    $this->saveSettings();
                }
                $this->redirect('list');
            break;

            /*
    		case 'add':
                if (isset($_POST['data'])) {
                	$this->addItem($_POST['data']);

                	$this->redirect('list');
                }

                $data = array(
	                'action' => 'add',
	            );
    		    $this->pageRender('Добавление', 'change', $data);
    		break;

    		case 'edit':
                if (isset($_POST['data'])) {
                	$this->editItem($_POST['key'], $_POST['data']);

                	$this->redirect('list');
                }

                $data = $settings['list'][(int)$_GET['key']];
                $data['action'] = 'edit';
    		    $this->pageRender('Редактирование', 'change', $data);
    		break;

    		case 'delete':
    		    if (isset($_GET['key'])) {
    		    	$this->deleteItem($_GET['key']);
    		    }
    		    $this->redirect('list');
    		break;
            */

    		case 'list':

                // активные попапы отображаем выше всех
                if (0 != count($settings['activated'])) {
                    $list = array();
                    rsort($settings['activated']);

                    foreach ($settings['activated'] as $script_id) {
                        // ищем скрипт
                        $found = FALSE;
                        foreach ($settings['list'] as $key => $item) if ($script_id == $item['id']) {
                            $found = $key;
                        }

                        if (FALSE !== $found) {
                            $list[] = $settings['list'][$found];
                            unset($settings['list'][$found]);
                        }
                    }

                    $settings['list'] = array_merge($list, $settings['list']);
                }

    		    $this->pageRender('', 'list', $settings);

                // показываем инструкцию по установке скрипта только 1 раз
                if (TRUE === $this->settings['first_not_empty_import']) {
                    $this->settings['first_not_empty_import'] = FALSE;
                    $this->saveSettings();
                }
    		break;

            case 'session_token_error':
                $this->pageRender('', 'session_token_error', $settings);
            break;

            case 'google_analytics':
                if (isset($_POST['data'])) {
                    $this->editGoogleAnalyticsCode($_POST['data']);

                    $this->redirect('list');
                }

                $data = (isset($settings['google_analytics_code'])) ? $settings['google_analytics_code'] : '';
                $this->pageRender('Код Google Analytics', 'google_analytics', $data);
            break;

    	}
    }

    // Собирает конечную страницу
    function pageRender($caption, $file_name, $data = array()) {
    	//echo SOCIALLOCKER_TEMPLATE_PATH . $file_name . '.php'; exit();

        $page_file = JUMPOUT_TEMPLATE_PATH . $file_name . '.php';
    	include JUMPOUT_TEMPLATE_PATH . 'main_teamplate.php';
    }




    // делает из старой версии настроек новую и сохраняет в бд
    function renewSettings($settings_old) {

        if ('' != trim($settings_old['header_code'])) {
            $settings_new['google_analytics_code'] = $settings_old['header_code'];
        }

        if ('' != trim($settings_old['footer_code'])) {

            // пытаемся вытащить id из кода
            $start = mb_strpos($settings_old['footer_code'], '(id:', 0, 'utf-8');

            if (FALSE !== $start) {
                // вытаскиваем из кода ID
                $id = mb_substr($settings_old['footer_code'], $start + 4, mb_strpos($settings_old['footer_code'], ')', $start, 'utf-8') - $start - 4, 'utf-8');

            }

            $settings_new['list'] = array(
                'id' => (isset($id)) ? $id : '',
                'code' => stripslashes($settings_old['footer_code']),
            );
        } else {
            $settings_new['list'] = array();
        }

        $this->saveSettings($settings_new);

        return $settings_new;
    }


    /*
    function __strSeplaceOnce($str_pattern, $str_replacement, $string) { 
        
        //if (strpos($string, $str_pattern) !== false){ 
            $occurrence = strpos($string, $str_pattern); 
            return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern)); 
        //} 

        //return $string; 
    }*/

}








































































class Services_JSON
{
    function Services_JSON($use = 0)
    {
        $this->use = $use;
    }

    function utf162utf8($utf16)
    {
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }

        $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

        switch(true) {
            case ((0x7F & $bytes) == $bytes):
                return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
                return chr(0xC0 | (($bytes >> 6) & 0x1F)) . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
                return chr(0xE0 | (($bytes >> 12) & 0x0F)) . chr(0x80 | (($bytes >> 6) & 0x3F)) . chr(0x80 | ($bytes & 0x3F));
        }

        return '';
    }

    function utf82utf16($utf8)
    {
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
        }

        switch(strlen($utf8)) {
            case 1:
                return $utf8;

            case 2:
                return chr(0x07 & (ord($utf8{0}) >> 2)) . chr((0xC0 & (ord($utf8{0}) << 6)) | (0x3F & ord($utf8{1})));

            case 3:
                return chr((0xF0 & (ord($utf8{0}) << 4)) | (0x0F & (ord($utf8{1}) >> 2))) . chr((0xC0 & (ord($utf8{1}) << 6)) | (0x7F & ord($utf8{2})));
        }

        return '';
    }


    function name_value($name, $value)
    {
        $encoded_value = $this->encode($value);

        if(Services_JSON::isError($encoded_value)) {
            return $encoded_value;
        }

        return $this->encode(strval($name)) . ':' . $encoded_value;
    }


    function reduce_string($str)
    {
        $str = preg_replace(array(
                '#^\s*//(.+)$#m',
                '#^\s*/\*(.+)\*/#Us',
                '#/\*(.+)\*/\s*$#Us'
            ), '', $str);

        return trim($str);
    }

    function decode($str)
    {
        $str = $this->reduce_string($str);

        switch (strtolower($str)) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return null;

            default:
                $m = array();

                if (is_numeric($str)) {
                    return ((float)$str == (integer)$str)
                        ? (integer)$str
                        : (float)$str;

                } elseif (preg_match('/^("|\').*(\1)$/s', $str, $m) && $m[1] == $m[2]) {
                    $delim = substr($str, 0, 1);
                    $chrs = substr($str, 1, -1);
                    $utf8 = '';
                    $strlen_chrs = strlen($chrs);

                    for ($c = 0; $c < $strlen_chrs; ++$c) {

                        $substr_chrs_c_2 = substr($chrs, $c, 2);
                        $ord_chrs_c = ord($chrs{$c});

                        switch (true) {
                            case $substr_chrs_c_2 == '\b':
                                $utf8 .= chr(0x08);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\t':
                                $utf8 .= chr(0x09);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\n':
                                $utf8 .= chr(0x0A);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\f':
                                $utf8 .= chr(0x0C);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\r':
                                $utf8 .= chr(0x0D);
                                ++$c;
                                break;

                            case $substr_chrs_c_2 == '\\"':
                            case $substr_chrs_c_2 == '\\\'':
                            case $substr_chrs_c_2 == '\\\\':
                            case $substr_chrs_c_2 == '\\/':
                                if (($delim == '"' && $substr_chrs_c_2 != '\\\'') ||
                                   ($delim == "'" && $substr_chrs_c_2 != '\\"')) {
                                    $utf8 .= $chrs{++$c};
                                }
                                break;

                            case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $c, 6)):
                                // single, escaped unicode character
                                $utf16 = chr(hexdec(substr($chrs, ($c + 2), 2)))
                                       . chr(hexdec(substr($chrs, ($c + 4), 2)));
                                $utf8 .= $this->utf162utf8($utf16);
                                $c += 5;
                                break;

                            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                                $utf8 .= $chrs{$c};
                                break;

                            case ($ord_chrs_c & 0xE0) == 0xC0:
                                $utf8 .= substr($chrs, $c, 2);
                                ++$c;
                                break;

                            case ($ord_chrs_c & 0xF0) == 0xE0:
                                $utf8 .= substr($chrs, $c, 3);
                                $c += 2;
                                break;

                            case ($ord_chrs_c & 0xF8) == 0xF0:
                                $utf8 .= substr($chrs, $c, 4);
                                $c += 3;
                                break;

                            case ($ord_chrs_c & 0xFC) == 0xF8:
                                $utf8 .= substr($chrs, $c, 5);
                                $c += 4;
                                break;

                            case ($ord_chrs_c & 0xFE) == 0xFC:
                                $utf8 .= substr($chrs, $c, 6);
                                $c += 5;
                                break;

                        }

                    }

                    return $utf8;

                } elseif (preg_match('/^\[.*\]$/s', $str) || preg_match('/^\{.*\}$/s', $str)) {


                    if ($str{0} == '[') {
                        $stk = array(SERVICES_JSON_IN_ARR);
                        $arr = array();
                    } else {
                        if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = array();
                        } else {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = new stdClass();
                        }
                    }

                    array_push($stk, array('what'  => SERVICES_JSON_SLICE,
                                           'where' => 0,
                                           'delim' => false));

                    $chrs = substr($str, 1, -1);
                    $chrs = $this->reduce_string($chrs);

                    if ($chrs == '') {
                        if (reset($stk) == SERVICES_JSON_IN_ARR) {
                            return $arr;

                        } else {
                            return $obj;

                        }
                    }


                    $strlen_chrs = strlen($chrs);

                    for ($c = 0; $c <= $strlen_chrs; ++$c) {

                        $top = end($stk);
                        $substr_chrs_c_2 = substr($chrs, $c, 2);

                        if (($c == $strlen_chrs) || (($chrs{$c} == ',') && ($top['what'] == SERVICES_JSON_SLICE))) {
                            $slice = substr($chrs, $top['where'], ($c - $top['where']));
                            array_push($stk, array('what' => SERVICES_JSON_SLICE, 'where' => ($c + 1), 'delim' => false));

                            if (reset($stk) == SERVICES_JSON_IN_ARR) {
                                array_push($arr, $this->decode($slice));

                            } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                                $parts = array();

                                if (preg_match('/^\s*(["\'].*[^\\\]["\'])\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {

                                    $key = $this->decode($parts[1]);
                                    $val = $this->decode($parts[2]);

                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                } elseif (preg_match('/^\s*(\w+)\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {

                                    $key = $parts[1];
                                    $val = $this->decode($parts[2]);

                                    if ($this->use & SERVICES_JSON_LOOSE_TYPE) {
                                        $obj[$key] = $val;
                                    } else {
                                        $obj->$key = $val;
                                    }
                                }

                            }

                        } elseif ((($chrs{$c} == '"') || ($chrs{$c} == "'")) && ($top['what'] != SERVICES_JSON_IN_STR)) {
                            array_push($stk, array('what' => SERVICES_JSON_IN_STR, 'where' => $c, 'delim' => $chrs{$c}));

                        } elseif (($chrs{$c} == $top['delim']) && ($top['what'] == SERVICES_JSON_IN_STR) && ((strlen(substr($chrs, 0, $c)) - strlen(rtrim(substr($chrs, 0, $c), '\\'))) % 2 != 1)) {

                            array_pop($stk);

                        } elseif (($chrs{$c} == '[') && in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {

                            array_push($stk, array('what' => SERVICES_JSON_IN_ARR, 'where' => $c, 'delim' => false));

                        } elseif (($chrs{$c} == ']') && ($top['what'] == SERVICES_JSON_IN_ARR)) {

                            array_pop($stk);

                        } elseif (($chrs{$c} == '{') && in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {

                            array_push($stk, array('what' => SERVICES_JSON_IN_OBJ, 'where' => $c, 'delim' => false));

                        } elseif (($chrs{$c} == '}') && ($top['what'] == SERVICES_JSON_IN_OBJ)) {
                            array_pop($stk);
                        } elseif (($substr_chrs_c_2 == '/*') && in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {

                            array_push($stk, array('what' => SERVICES_JSON_IN_CMT, 'where' => $c, 'delim' => false));
                            $c++;


                        } elseif (($substr_chrs_c_2 == '*/') && ($top['what'] == SERVICES_JSON_IN_CMT)) {

                            array_pop($stk);
                            $c++;

                            for ($i = $top['where']; $i <= $c; ++$i)
                                $chrs = substr_replace($chrs, ' ', $i, 1);



                        }

                    }

                    if (reset($stk) == SERVICES_JSON_IN_ARR) {
                        return $arr;

                    } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                        return $obj;

                    }

                }
        }
    }


    function isError($data, $code = null)
    {
        if (class_exists('pear')) {
            return PEAR::isError($data, $code);
        } elseif (is_object($data) && (get_class($data) == 'services_json_error' || is_subclass_of($data, 'services_json_error'))) {
            return true;
        }

        return false;
    }
}