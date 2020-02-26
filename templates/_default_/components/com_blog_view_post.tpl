<div class="row">
    <div class="col-sm-8 blog">
        <h1>{$post.title}</h1>
        {*if $blog.showcats && $cat}
            <div class="blog_post_data"><strong>{$LANG.CAT}:</strong> <a href="/{component}/{$blog.seolink}/cat-{$cat.id}">{$cat.title}</a></div>
        {/if*}
        <ul class="blog-detail list-unstyled list-inline">
            <li><i class="fa fa-calendar"></i>{*$post.fpubdate*}{$post.pubdate}</li>
            <li><i class="fa fa-user"></i> {$post.author_nickname}</li>
            {if $myblog || $is_admin || ($is_writer && $is_author)}
                {if !$post.published && ($is_admin)}<li><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.publishPost({$post.id});return false;"><i class="fa fa-pencil"></i> {$LANG.PUBLISH}</a></li>{/if}
                <li><a href="/{component}/editpost{$post.id}.html"><i class="fa fa-pencil"></i>{$LANG.EDIT}</a></li>
                <li><a class="ajaxlink" href="javascript:void(0)" onclick="{component}.deletePost({$post.id}, '{csrf_token}');return false;"><i class="fa fa-trash-o"></i>{$LANG.DELETE}</a></li>
            {/if}
        </ul>
        <div class="blog_post_body">{$post.content_html}</div>
        {if $post.tags}<ul class="blog-tags list-unstyled list-inline">{$post.tags}</ul>{/if}
    </div>
    <div class="col-sm-4">
        {if $blogcats}
        <h3>Категории</h3>
        <div class="box categories">
            <ul class="list-unstyled">
                <li><i class="fa fa-coffee"></i><a href="/blog">{$LANG.ALL_CATS}</a> <span style="color:#666666">({$total})</span></li>
                {foreach key=tid item=cat from=$blogcats}
                    <li{if $post.cat_id==$cat.id} class="current_cat"{/if}><a href="{$blog.blog_link}/cat-{$cat.id}"><i class="fa fa-coffee"></i> {$cat.title}</a> <span style="color:#666666">({$cat.post_count})</span></li>
                {/foreach}
            </ul>
        </div>
        {/if}
        {if $blog_modules}{$blog_modules}{/if}
    </div>
</div>
    

{if $navigation && ($navigation.prev || $navigation.next)}
<div class="row">
    <div class="col-sm-12">
        <div class="blog_post_nav">
        {if $navigation.prev}<a href="{$navigation.prev.url}" class="prev"><i class="fa fa-arrow-left"></i> {$navigation.prev.title}</a>{/if}
        {if $navigation.next}<a href="{$navigation.next.url}" class="next">{$navigation.next.title} <i class="fa fa-arrow-right"></i></a>{/if}
        </div>
    </div>
</div>
{/if}
{*
<!-- COMMENTS -->
<section class="content-item" id="comments">
    <div class="container">     	
        <div class="row">
            <div class="col-sm-8">
                asdfas dasd fasdf 
            </div>
        </div>
    </div>
</section>
*}