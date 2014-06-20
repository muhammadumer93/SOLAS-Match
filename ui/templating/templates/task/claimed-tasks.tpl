{include file='header.tpl'}

<div class="page-header">
    <h1>
        {if isset($thisUser)}
            {if $thisUser->getDisplayName() != ''}
                {sprintf(Localisation::getTranslation('claimed_tasks_claimed_tasks_2'), {$thisUser->getDisplayName()})}
            {else}
                {Localisation::getTranslation('claimed_tasks_claimed_tasks')}
            {/if}
        {else}
            {Localisation::getTranslation('claimed_tasks_claimed_tasks')}
        {/if}
        <small>{Localisation::getTranslation('claimed_tasks_0')}</small>
    </h1>
</div>

<div class="pull-left" style="max-width: 70%; overflow-wrap: break-word; word-break:break-all;">
<claimed-tasks-stream userid="{$thisUser->getId()}" tasksperpage="10"></claimed-tasks-stream>
</div>
<br/>
{include file='footer.tpl'}
