{if !empty($flash_messages)}
	{foreach from=$flash_messages key=type item=messages}
		{foreach from=$messages item=message}
		<div class="con-message-{$type} mat-hov cursor-pt --c-hide">
			<p class="user-message-body">{$message}</p>
		</div>
		{/foreach}
	{/foreach}
{/if}
