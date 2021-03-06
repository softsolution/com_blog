<?php
if(!defined('VALID_CMS_ADMIN')) { die('ACCESS DENIED'); }

    function cpBlogOwner($blog_id){
        $inDB = cmsDatabase::getInstance();
        $blog = $inDB->get_fields('cms_blogs', "id={$blog_id}", 'owner, user_id');
        if($blog['owner']=='user'){
            $nickname = $inDB->get_field('cms_users', "id={$blog['user_id']}", 'nickname');
            $link = '<a href="?view=users&do=edit&id='.$blog['user_id'].'" class="user_link" target="_blank">
                     '.$nickname.'
                     </a>';
        } else {
            $title = $inDB->get_field('cms_clubs', "id={$blog['user_id']}", 'title');
            $link = '<a href="?view=components&do=config&link=clubs&opt=edit&item_id='.$blog['user_id'].'" class="club_link" target="_blank">
                     '.$title.'
                     </a>';
        }
        return $link;
    }

    cpAddPathway('Блоги', '?view=components&do=config&id='.(int)$_REQUEST['id']);

    echo '<h3>Блоги</h3>';

    $opt = $inCore->request('opt', 'str', 'config');

    $toolmenu = array();

    if ($opt=='config' || $opt=='saveconfig'){

        $toolmenu[0]['icon'] = 'save.gif';
        $toolmenu[0]['title'] = 'Сохранить';
        $toolmenu[0]['link'] = 'javascript:document.optform.submit();';

        $toolmenu[2]['icon'] = 'listblogs.gif';
        $toolmenu[2]['title'] = 'Список блогов';
        $toolmenu[2]['link'] = '?view=components&do=config&link=blogs&opt=list_blogs';
        cpToolMenu($toolmenu);

    }

    if ($opt=='list_blogs'){

        cpAddPathway('Список блогов', $_SERVER['REQUEST_URI']);

		$toolmenu[1]['icon'] = 'edit.gif';
		$toolmenu[1]['title'] = 'Редактировать выбранные';
		$toolmenu[1]['link'] = "javascript:checkSel('?view=components&do=config&link=blogs&opt=edit_blog&multiple=1');";

		$toolmenu[2]['icon'] = 'delete.gif';
		$toolmenu[2]['title'] = 'Удалить выбранные';
		$toolmenu[2]['link'] = "javascript:checkSel('?view=components&do=config&link=blogs&opt=delete_blog&multiple=1');";

        $toolmenu[3]['icon'] = 'config.gif';
        $toolmenu[3]['title'] = 'Настройки компонента';
        $toolmenu[3]['link'] = '?view=components&do=config&link=blogs&opt=config';
        cpToolMenu($toolmenu);

		//TABLE COLUMNS
		$fields = array();

		$fields[0]['title'] = 'id';			$fields[0]['field'] = 'id';				$fields[0]['width'] = '30';

		$fields[1]['title'] = 'Создан';		$fields[1]['field'] = 'pubdate';		$fields[1]['width'] = '80';		$fields[1]['filter'] = 15;
		$fields[1]['fdate'] = '%d/%m/%Y';

		$fields[2]['title']  = 'Название';	$fields[2]['field'] = 'title';			$fields[2]['width'] = '';		$fields[2]['link'] = '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%';
		$fields[2]['filter'] = 15;

		$fields[3]['title'] = 'Владелец';		$fields[3]['field'] = 'id';         $fields[3]['width'] = '300';
		$fields[3]['prc']   = 'cpBlogOwner';

		//ACTIONS
		$actions = array();
		$actions[1]['title'] = 'Переименовать';
		$actions[1]['icon']  = 'edit.gif';
		$actions[1]['link']  = '?view=components&do=config&link=blogs&opt=edit_blog&item_id=%id%';

		$actions[2]['title'] = 'Удалить';
		$actions[2]['icon']  = 'delete.gif';
		$actions[2]['confirm'] = 'Удалить блог?';
		$actions[2]['link']  = '?view=components&do=config&link=blogs&opt=delete_blog&item_id=%id%';

		//Print table
		cpListTable('cms_blogs', $fields, $actions, '', 'pubdate DESC');

    }

	//LOAD CURRENT CONFIG
	$cfg = $inCore->loadComponentConfig('blogs');

    cmsCore::loadModel('blogs');
    $model = new cms_model_blogs();

	cmsCore::loadClass('blog');
	$inBlog = cmsBlogs::getInstance();

	if($opt=='saveconfig'){

		if(!cmsCore::validateForm()) { cmsCore::error404(); }

		$cfg = array();
		$cfg['perpage']             = $inCore->request('perpage', 'int');
		$cfg['perpage_blog'] 		= $inCore->request('perpage_blog', 'int');
		$cfg['update_date']         = $inCore->request('update_date', 'int');
		$cfg['update_seo_link']     = $inCore->request('update_seo_link', 'int');

		$cfg['min_karma_private'] 	= $inCore->request('min_karma_private', 'int');
		$cfg['min_karma_public'] 	= $inCore->request('min_karma_public', 'int');
		$cfg['min_karma'] 			= $inCore->request('min_karma', 'int');
		$cfg['list_min_rating']    = $inCore->request('list_min_rating', 'int');

		$cfg['watermark'] 			= $inCore->request('watermark', 'int');
		$cfg['img_on'] 				= $inCore->request('img_on', 'int');

		$cfg['update_seo_link_blog'] = $inCore->request('update_seo_link_blog', 'int');

		$inCore->saveComponentConfig('blogs', $cfg);

        cmsUser::clearCsrfToken();

		cmsCore::addSessionMessageAdmin('Настройки успешно сохранены', 'success');
		cmsCore::redirectBack();

	}

	if ($opt == 'delete_blog'){
        $id = $inCore->request('item_id', 'int', 0);
		if (!isset($_REQUEST['item'])){
			if ($id >= 0){
				$inBlog->deleteBlog($id);
			}
		} else {
			$inBlog->deleteBlogs($_REQUEST['item']);
		}
		cmsCore::addSessionMessageAdmin('Блог(и) успешно удалены', 'success');
		cmsCore::redirectBack();
	}

	if ($opt == 'update_blog'){

		if(!cmsCore::validateForm()) { cmsCore::error404(); }

		if($inCore->request('item_id', 'int', 0)) {

			$id = $inCore->request('item_id', 'int', 0);
            $title = $inCore->request('title', 'str');

			$seolink_new = $inBlog->updateBlog($id, array('id'=>$id, 'title'=>$title), true);

			$blog = $inBlog->getBlog($id);

			$blog['seolink'] = $seolink_new ? $seolink_new : $blog['seolink'];

			if(stripslashes($title) != $blog['title']){
				cmsActions::updateLog('add_post', array('target' => $title, 'target_url' => $model->getBlogURL($blog['seolink'])), 0, $blog['id']);
				cmsActions::updateLog('add_blog', array('object' => $title, 'object_url' => $model->getBlogURL($blog['seolink'])), $blog['id']);
			}

			cmsCore::addSessionMessageAdmin('Название блога "'.$blog['title'].'" успешно сохранено. URL изменился согласно заголовку.', 'success');

            cmsUser::clearCsrfToken();

			if (!isset($_SESSION['editlist']) || @sizeof($_SESSION['editlist'])==0){
				cmsCore::redirect('?view=components&do=config&link=blogs&opt=list_blogs');
			} else {
				cmsCore::redirect('?view=components&do=config&link=blogs&opt=edit_blog');
			}

		}
	}

?>

<?php
    if ($opt=='config'){
?>
<form action="index.php?view=components&do=config&id=<?php echo (int)$_REQUEST['id'];?>" method="post" name="optform" target="_self" id="form1">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <table width="609" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td colspan="2" valign="top" bgcolor="#EBEBEB"><h4>Просмотр блога </h4></td>
        </tr>
        <tr>
            <td valign="top"><strong>Постов на странице в блоге: </strong></td>
            <td width="100" valign="top">
                <input name="perpage" type="text" id="perpage" value="<?php echo @$cfg['perpage'];?>" size="5" /> шт.
            </td>
        </tr>
        <tr>
            <td valign="top"><strong>Количество блогов на странице в списке блогов: </strong></td>
            <td width="100" valign="top">
                <input name="perpage_blog" type="text" id="perpage_blog" value="<?php echo @$cfg['perpage_blog'];?>" size="5" /> шт.
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top" bgcolor="#EBEBEB"><h4>Опции фотографий</h4></td>
        </tr>
        <tr>
            <td valign="top"><strong>Разрешить загрузку фотографий к постам в блоге:</strong></td>
            <td width="100" valign="top">
                <input name="img_on" type="radio" value="1" <?php if (@$cfg['img_on']) { echo 'checked="checked"'; } ?> /> Да
                <input name="img_on" type="radio" value="0" <?php if (@!$cfg['img_on']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>
        <tr>
            <td valign="top"><strong>Наносить водяной знак:</strong>  <br />Если включено, то на все загружаемые
			      фотографии к постам будет наносится изображение
			      из файла "<a href="/images/watermark.png" target="_blank">/images/watermark.png</a>"</td>
            <td width="100" valign="top">
                <input name="watermark" type="radio" value="1" <?php if (@$cfg['watermark']) { echo 'checked="checked"'; } ?> /> Да
                <input name="watermark" type="radio" value="0" <?php if (@!$cfg['watermark']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>

        <tr>
            <td colspan="2" valign="top" bgcolor="#EBEBEB"><h4>Настройки редактирования</h4></td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Обновлять дату поста после редактирования:</strong><br />
                <span class="hinttext">
                    Если включено, после редактирования поста его дата будет устанавливаться в текущую.
                </span>
            </td>
            <td valign="top">
                <input name="update_date" type="radio" value="1" <?php if (@$cfg['update_date']) { echo 'checked="checked"'; } ?> /> Да
                <input name="update_date" type="radio" value="0" <?php if (@!$cfg['update_date']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Обновлять ссылку блога после редактирования при смене заголовка:</strong><br />
                <span class="hinttext">
                    Если включено, после редактирования блога его ссылка, а так же все ссылки постов в блоге, будут изменены согласно новому заголовку блога.
                </span>
            </td>
            <td valign="top">
                <input name="update_seo_link_blog" type="radio" value="1" <?php if (@$cfg['update_seo_link_blog']) { echo 'checked="checked"'; } ?> /> Да
                <input name="update_seo_link_blog" type="radio" value="0" <?php if (@!$cfg['update_seo_link_blog']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Обновлять ссылку поста после редактирования при смене заголовка:</strong><br />
                <span class="hinttext">
                    Если включено, после редактирования поста его ссылка будет изменена согласно новому заголовку.
                </span>
            </td>
            <td valign="top">
                <input name="update_seo_link" type="radio" value="1" <?php if (@$cfg['update_seo_link']) { echo 'checked="checked"'; } ?> /> Да
                <input name="update_seo_link" type="radio" value="0" <?php if (@!$cfg['update_seo_link']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top" bgcolor="#EBEBEB"><h4>Ограничения по карме</h4></td>
        </tr>

        <tr>
            <td valign="top">
                <strong>Использовать ограничения:</strong><br />
                <span class="hinttext">Если выключено, то любой пользователь сможет создать блог,<br />независимо от значения своей кармы</span>
            </td>
            <td valign="top">
                <input name="min_karma" type="radio" value="1" <?php if (@$cfg['min_karma']) { echo 'checked="checked"'; } ?> /> Да
                <input name="min_karma" type="radio" value="0" <?php if (@!$cfg['min_karma']) { echo 'checked="checked"'; } ?>/> Нет
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Создание личного блога:</strong><br />
                <span class="hinttext">Сколько очков кармы нужно для создания личного блога </span>
            </td>
            <td valign="top">
                <input name="min_karma_private" type="text" id="min_karma_private" value="<?php echo @$cfg['min_karma_private'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Создание коллективного блога:</strong><br />
                <span class="hinttext">Сколько очков кармы нужно для создания коллективного блога </span>
            </td>
            <td valign="top">
                <input name="min_karma_public" type="text" id="min_karma_public" value="<?php echo @$cfg['min_karma_public'];?>" size="5" />
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Посты, с рейтингом ниже заданного в ленте блогов не показываются:</strong><br />
                <span class="hinttext">Общий список постов - последние/популярные. 0 - без ограничений.</span>
            </td>
            <td valign="top">
                <input name="list_min_rating" type="text" value="<?php echo @$cfg['list_min_rating'];?>" size="5" />
            </td>
        </tr>
    </table>
    <p>
        <input name="opt" type="hidden" value="saveconfig" />
        <input name="save" type="submit" id="save" value="Сохранить" />
        <input name="back" type="button" id="back" value="Отмена" onclick="window.location.href='index.php?view=components';"/>
    </p>
</form>
<?php } ?>

<?php
    if ($opt=='edit_blog'){

        if(isset($_REQUEST['multiple'])){
				if (isset($_REQUEST['item'])){
					$_SESSION['editlist'] = $_REQUEST['item'];
				} else {
					echo '<p class="error">Нет выбранных объектов!</p>';
					return;
				}
			 }

			 $ostatok = '';

			 if (isset($_SESSION['editlist'])){
				$id = array_shift($_SESSION['editlist']);
            if (sizeof($_SESSION['editlist'])==0) {
                unset($_SESSION['editlist']);
            } else {
                $ostatok = '(На очереди: '.sizeof($_SESSION['editlist']).')';
            }
         } else {
            $id = (int)$_REQUEST['item_id'];
         }

			 $sql = "SELECT id, title
					 FROM cms_blogs
					 WHERE id = $id LIMIT 1";
			 $result = dbQuery($sql) ;
			 if (mysql_num_rows($result)){
				$mod = mysql_fetch_assoc($result);
			 }

			 echo '<h3>Редактировать блог '.$ostatok.'</h3>';
			 cpAddPathway($mod['title'], $_SERVER['REQUEST_URI']);

?>
<form action="index.php?view=components&do=config&link=blogs&opt=update_blog&item_id=<?php echo $mod['id']; ?>" method="post" name="optform" target="_self" id="form1">
<input type="hidden" name="csrf_token" value="<?php echo cmsUser::getCsrfToken(); ?>" />
    <table width="609" border="0" cellpadding="10" cellspacing="0" class="proptable">
        <tr>
            <td width="120"><strong>Название блога: </strong></td>
            <td>
                <input name="title" type="text" id="title" value="<?php echo htmlspecialchars($mod['title']);?>" style="width:99%" /><br />
                <span class="hinttext">Внимание! При изменении заголовка, изменится URL блога и постов</span>
            </td>
        </tr>
    </table>
    <p>
        <input name="opt" type="hidden" value="update_blog" />
        <input name="save" type="submit" id="save" value="Сохранить" />
        <input name="back" type="button" id="back" value="Отмена" onclick="window.location.href='index.php?view=components&do=config&link=blogs&opt=list_blogs';"/>
    </p>
</form>
<?php } ?>