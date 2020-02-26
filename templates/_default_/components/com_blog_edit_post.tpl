<h1 class="con_heading">{$pagetitle}</h1>

<form style="margin-top:15px" action="" method="post" name="msgform" enctype="multipart/form-data">
    <table class="addpost" width="100%" border="0" cellpadding="6" cellspacing="0" style="">
        <tr>
            <td width="160"><strong>{$LANG.TITLE_POST}: </strong></td>
            <td><input name="title" class="form-control" type="text" id="title" style="width:410px" value="{$mod.title|escape:'html'}"/></td>
        </tr>
        {if $blog.showcats && $cat_list}
            <tr>
                <td><strong>{$LANG.BLOG_CAT}:</strong></td>
                <td>
                    <select name="cat_id" id="cat_id" style="width:410px" class="form-control">
                        <option value="0" {if !isset($mod.cat_id) || $mod.cat_id==0}  selected {/if}>{$LANG.WITHOUT_CAT}</option>
                        {$cat_list}
                    </select>
                </td>
            </tr>
        {/if}
        {*if $myblog || $is_admin}
            <tr>
                <td><strong>{$LANG.SHOW_POST}:</strong></td>
                <td>
                    <select name="allow_who" id="allow_who" style="width:407px" class="form-control">
                        <option value="all" {if !isset($mod.allow_who) || $mod.allow_who=='all'} selected {/if}>{$LANG.TO_ALL}</option>
                        <option value="friends" {if $mod.allow_who=='friends'} selected {/if}>{$LANG.TO_MY_FRIENDS}</option>
                        <option value="nobody" {if $mod.allow_who=='nobody'} selected {/if}>{$LANG.TO_ONLY_ME}</option>
                    </select>
                </td>
            </tr>
        {else*}
            <input type="hidden" name="allow_who" value="{$blog.allow_who}" />
        {*/if*}
		
		{*<tr>
			<td><strong>{$LANG.YOUR_MOOD}:</strong></td>
			<td><input name="feel" class="text-input" type="text" id="feel" style="width:400px" value="{$mod.feel|escape:'html'}"/></td>
		</tr>
		<tr>
			<td><strong>{$LANG.PLAY_MUSIC}:</strong></td>
			<td><input name="music" class="text-input" type="text" id="music" style="width:400px" value="{$mod.music|escape:'html'}"/></td>
		</tr>*}
        {if $is_admin || $user_can_iscomments}
            <tr>
                <td valign="top">
                    <strong>{$LANG.COMMENTS}:</strong>
                </td>
                <td>
                    <select name="comments" id="comments" style="width:410px" class="form-control">
                        <option value="0" {if !$mod.comments}selected="selected"{/if}>{$LANG.NO}</option>
                        <option value="1" {if $mod.comments}selected="selected"{/if} >{$LANG.YES}</option>
                    </select>
                    <span class="hinttext" style="font-size:11px">{$LANG.IS_COMMENTS}</span>
                </td>
            </tr>
        {/if}
        <tr>
            <td valign="top">
                <strong>{$LANG.TAGS}:</strong>
            </td>
            <td>
                <input name="tags" class="form-control" type="text" id="tags" style="width:410px" value="{$mod.tags|escape:'html'}"/>
                <span class="hinttext" style="font-size:11px">{$LANG.KEYWORDS}</span>
                <script type="text/javascript">
                    {$autocomplete_js}
                </script>
            </td>
        </tr>
		<tr>
			<td colspan="2">
				<div class="usr_msg_bbcodebox">{$bb_toolbar}</div>
				{$smilies}
				{$autogrow}
				<div class="cm_editor"><textarea class="ajax_autogrowarea" name="content" id="message">{$mod.content|escape:'html'}</textarea></div>
                <div style="margin-top:12px;margin-bottom:15px;" class="hinttext">
                    <strong>{$LANG.IMPORTANT}:</strong> {$LANG.CUT_TEXT},<br/>
                    <a href="javascript:addTagCut('message');" class="ajaxlink">{$LANG.ADD_CUT_TAG}</a> {$LANG.BETWEEN}.
                </div>
			</td>
		</tr>
	</table>
	<p>
		<input name="goadd" type="submit"  class="btn btn-normal" id="goadd" value="{$LANG.SAVE_POST}" />
		<a href="javascript:void()" class="btn btn-default" onclick="window.history.go(-1)">{$LANG.CANCEL}</a>
	</p>
</form>
{literal}
<script type="text/javascript">
    $(document).ready(function(){
        $('#title').focus();
    });
</script>
{/literal}