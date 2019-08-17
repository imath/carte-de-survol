<?php
/**
 * Template of the hovercard.
 *
 * @package   carte-de-survol
 * @subpackage \templates\buddypress\assets\hovercard\index
 *
 * @since 1.0.0
 */
?>
<script type="text/html" id="tmpl-hovercard">
	<div class="cds-container">

		<# if ( data.loader ) { #>
			<img src="{{data.loader}}" class="cds-loader">

		<# } else if ( data.message ) { #>
			<p class="error">{{{data.message}}}</p>

		<# } else { #>

			<# if ( data.avatar_urls && data.avatar_urls.thumb ) { #>
				<div class="cds-avatar">
					<img src="{{{data.avatar_urls.thumb}}}" class="avatar">
				</div>
			<# } #>

			<div class="cds-description" open="true">
				<div class="cds-title">{{{data.name}}}</div>
				<div class="cds-subtitle">@{{{data.mention_name}}}</div>

				<# if ( data.xprofile && data.xprofile.groups[1] ) { #>
					<dl class="cds-profile-fields">
						<# _.each( data.xprofile.groups[1].fields, function( field ) { #>
							<dt>{{field.name}}</dt>
							<dl>{{{field.value.rendered}}}</dl>
						<# } ) #>
					</dl>
				<# } #>
			</div>

		<# } #>
	</div>
</script>
