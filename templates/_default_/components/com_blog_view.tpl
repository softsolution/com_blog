{if $myblog || $is_writer || $is_admin}
    <div class="blog_toolbar">
	{if $myblog || $is_admin}
            <table cellspacing="0" cellpadding="2">
                <tr>
                {if $on_moderate}
                    <td width="16"><img src="/templates/{template}/images/icons/folder_table.png" border="0"/></td>
                    <td width=""><a class="blog_moderate_link" href="{$blog.moderate_link}">{$LANG.MODERATING} ({$on_moderate})</a></td>
                {/if}
                    <td><a href="{$blog.add_post_link}" style="padding: 0 10px 0 0"><i class="fa fa-plus-circle"></i> {$LANG.NEW_POST}</a></td>
                    <td><a class="ajaxlink" href="javascript:void(0)" onclick="$('#opt_cat').toggle();" style="padding: 0 10px 0 0"><i class="fa fa-folder-open-o"></i> {$LANG.CATS}</a></td>
                {if $is_config}
                    <td><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.editBlog({$blog.id});return false;"><i class="fa fa-cog"></i> {$LANG.CONFIG}</a></td>
                {/if}
                </tr>
            </table>

            <table cellspacing="0" cellpadding="5" id="opt_cat" style="display:none;">
                <tr><td><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.addBlogCat({$blog.id});return false;" style="padding: 5px;display: inline-block;"><i class="fa fa-plus-square"></i> {$LANG.NEW_CAT}</a></td><tr>
            {if $cat_id>0}
                <tr><td><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.editBlogCat({$cat_id});return false;"><i class="fa fa-pencil"></i> {$LANG.RENAME_CAT}</a></td><tr>
                <tr><td><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.deleteCat({$cat_id}, '{csrf_token}');return false;"><i class="fa fa-trash-o"></i> {$LANG.DEL_CAT}</a></td><tr>
            {/if}
            </table>

	{elseif $is_writer}
            <table cellspacing="0" cellpadding="5">
                <tr>
                    <td width="16"><img src="/templates/{template}/images/icons/edit.png" border="0"/></td>
                    <td width=""><a href="{$blog.add_post_link}">{$LANG.NEW_POST}</a></td>
                </tr>
            </table>
	{/if}
    </div>
    <div class="clearfix"></div>
{/if}
<div class="row">
    <div class="col-sm-8">
        <h1>{if $cat_id && $cur_title}{$cur_title}{else}{$blog.title}{/if}</h1>
        {if $cur_desc && $blogcats}
            <div class="box">{$cur_desc|nl2br}</div>
        {/if}
        {if $posts}
        <div class="timeline">
            {foreach key=tid item=post from=$posts}
            <div class="blog-post">
                <div class="blog-info">
                    <img src="{$blog.author_avatar}" class="img-responsive" alt="">
                    <div class="date"><div class="number">{$post.number}</div><div>{$post.month_name}</div></div>
                </div>
                <h3><a href="{$post.url}">{$post.title}</a></h3>
                <div class="post_intro">{$post.content_html}</div>
                {if $post.tagline != false}
                    <ul class="blog-tags list-unstyled list-inline">{$post.tagline}</ul>
                {/if}
            </div>
            {/foreach}
        </div>
            {$pagination}
        {else}
            <p style="clear:both">{$LANG.NOT_POSTS}</p>
        {/if}
    </div>
    <div class="col-sm-4">
        {if $blogcats}
        <h3>Категории</h3>
        <div class="box categories">
            <ul class="list-unstyled">
                <li {if !$cat_id}class="current_cat"{/if}><i class="fa fa-coffee"></i><a href="{$blog.blog_link}">{$LANG.ALL_CATS}</a> <span style="color:#666666">({$total})</span></li>
                {foreach key=tid item=cat from=$blogcats}
                    <li{if $cat_id==$cat.id} class="current_cat"{/if}>
                        <a href="{$blog.blog_link}/cat-{$cat.id}"><i class="fa fa-coffee"></i> {$cat.title}</a> <span style="color:#666666">({$cat.post_count})</span>
                    </li>
                {/foreach}
            </ul>
        </div>
        {/if}
        {if $blog_modules}{$blog_modules}{/if}
    </div>
</div>