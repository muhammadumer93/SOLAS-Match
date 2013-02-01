{include file="header.tpl"}

{assign var="org_id" value=$org->getId()}
<h1 class="page-header">{$org->getName()}<small> A list of membership requests</small></h1>
{if isset($user_list) && count($user_list) > 0}
    {foreach $user_list as $user}
        {assign var="user_id" value=$user->getUserId()}
        {assign var="org_id" value=$org->getId()}
        {if $user->getDisplayName() != ''}
            <h3>{$user->getDisplayName()}</h3>
        {else}
            <h3>User {$user->getUserId()}</h3>
        {/if}
        <p>{$user->getBiography()}</p>
        <p>View their <a href="{urlFor name="user-public-profile" options="user_id.$user_id"}">profile</a></p>
        <form method="post" action="{urlFor name="org-request-queue" options="org_id.$org_id"}">
            <input type="hidden" name="user_id" value="{$user->getUserId()}" />
            <input type="submit" name="accept" value="    Accept Request" class="btn btn-primary" />
            <input type="submit" name="refuse" value="    Refuse Request" class="btn btn-inverse" />
            <i class="icon-ok-circle icon-white" style="position:relative; right:260px; top:2px;"></i>
            <i class="icon-remove-circle icon-white" style="position:relative; right:145px; top:2px;"></i>
        </form>

    {/foreach}
{else}
    <div class="alert alert-info">There are no current membership requests for this organisation</div>
{/if}

<h3>Add a User as an Organisation Member</h3>
<p>Enter the User's email to add them as a member of this organisation</p>

{if isset($flash['error'])}
    <div class="alert alert-error">{$flash['error']}</div>
{/if}

{if isset($flash['success'])}
    <div class="alert alert-success">{$flash['success']}</div>
{/if}
    
<form class="well" method="post" action="{urlFor name="org-request-queue" options="org_id.$org_id"}">
    <label for="email"><b>User's email address:</b></label>
    <input type="text" name="email" />

    <p>
        <input type="submit" value="    Add User" class="btn btn-primary" />
        <i class="icon-plus-sign icon-white" style="position:relative; right:88px; top:2px;"></i>
    </p>
</form>

{include file="footer.tpl"}
