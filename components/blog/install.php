<?php
/* ****************************************************************************************** */
/* created by soft-solution.ru                                                                */
/* install.php of component blog for InstantCMS 1.9                                           */
/* ****************************************************************************************** */
function info_component_blog() {
    $_component['title']       = 'Блог';                                        //название
    $_component['description'] = 'Компонент Блог';                              //описание
    $_component['link']        = 'blog';                                        //ссылка (идентификатор)
    $_component['author']      = 'soft-solution.ru';                            //автор
    $_component['internal']    = '0';                                           //внутренний (только для админки)? 1-Да, 0-Нет
    $_component['version']     = '1.0';                                         //текущая версия

    //Настройки по-умолчанию
    $_component['config'] = array(
        'param1' => '1',
        'param2' => '2'
    );

    return $_component;
}

    function install_component_blog() {

        $inCore = cmsCore::getInstance();                                //подключаем ядро
        $inDB = cmsDatabase::getInstance();                              //подключаем базу данных
        $inConf = cmsConfig::getInstance();

        $sql = "INSERT INTO `cms_blogs` (`user_id`, `title`, `pubdate`, `allow_who`, `view_type`, `showcats`, `ownertype`, `premod`, `forall`, `owner`, `seolink`, `rating`, `comments_count`) VALUES
        (1, 'Блог', NOW(), 'all', 'list', 1, 'single', 0, 1, 'site', 'blog', 0, 0);";

        $inDB->query($sql);
        
        return true;
    }

    function upgrade_component_blog() {

        return true;

    }

    function remove_component_blog(){

        return true;
    }

?>