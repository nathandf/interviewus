{if isset( $application_errors )}
	<div class="application-errors --c-hide">
		<div class="application-error-message-container mat-hov cursor-pt --c-hide">
			<div style="display: table; margin: 0 auto;">
				<svg height="100" width="100">
					<polygon points="50,25 17,80 82,80" stroke-linejoin="round" style="fill:none;stroke:#CA2A2A;stroke-width:8" />
					<text x="42" y="74" fill="#CA2A2A" font-family="sans-serif" font-weight="900" font-size="42px">!</text>
				</svg>
			</div>
			{foreach from=$application_errors item=message}
			<div class="application-error-message">
				<p class="user-message-body">{$message}</p>
			</div>
			{/foreach}
		</div>
	</div>
{/if}
