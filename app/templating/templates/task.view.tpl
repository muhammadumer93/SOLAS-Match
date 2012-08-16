{include file="header.tpl"}

<h1 class="page-header">
    {if $task->getTitle() != ''}
        {$task->getTitle()}
    {else}
        Task {$task->getTaskId()}
    {/if}
    <small>Task Details</small>
    {assign var="task_id" value=$task->getTaskId()}
    <a href="{urlFor name="task-alter" options="task_id.$task_id"}" class='pull-right btn btn-primary'>
        Edit Details
    </a>
</h1>

{if isset($org)}
    <h3>Organisation</h3>
    <p>
        {assign var="org_id" value=$org->getId()}
        <a href="{urlFor name="org-public-profile" options="org_id.$org_id"}">
            {if $org->getName() != ''}
                {$org->getName()}
            {else}
                Organisation Public Profile
            {/if}
        </a>
    </p>
{/if}

{if $task->getImpact() != ''}
    <h3>Impact</h3>
    <p>{$task->getImpact()}</p>
{/if}

{if $task->getReferencePage() != ''}
    <h3>Context Reference</h3>
    <p>
        <a href="{$task->getReferencePage()}">{$task->getReferencePage()}</a>
    </p>
{/if}

{assign var="task_tags" value=$task->getTags()}
{if isset($task_tags)}
    <h3>Task Tags</h3>
    <ul class="nav nav-list unstyled">
        {foreach $task_tags as $tag}
            <li>
                <div class="tag">
                    <a class="label" href="{urlFor name="tag-details" options="label.$tag"}">{$tag}</a>
                </div>
            </li>
        {/foreach}
    </ul>
{/if}

<h3>Source Language</h3>
{assign var="source_id" value=$task->getSourceId()}
<p>{Languages::languageNameFromId($source_id)}</p>

<h3>Target Language</h3>
{assign var="target_id" value=$task->getTargetId()}
<p>{Languages::languageNameFromId($target_id)}</p>

<h3>Word Count</h3>
<p>{$task->getWordCount()}</p>

{include file="footer.tpl"}
