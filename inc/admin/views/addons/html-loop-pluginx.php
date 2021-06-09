<?php
/**
 * Admin View: Displaying loop single plugin.
 * This template is default for js render
 *
 * @author  ThimPress
 * @package LearnPress/Views
 * @version 4.0.8
 */

defined( 'ABSPATH' ) || exit();
?>
<ul class="wrapper-list-lp-addons addons-browse">
	<div class="struct-html-item-addons" style="display:none;">
		<li class="plugin-card" id="learn-press-plugin-[slug]">
			<div class="plugin-card-top">
				<span class="plugin-icon">
					<img src="[icon]">
				</span>

				<div class="name column-name">
					<h3 class="item-title">[name]</h3>
				</div>

				<div class="action-links">
					<ul class="plugin-action-buttons">
					[action_links]
					</ul>					
				</div>
				<div class="desc column-description" title="[short_description]">
					<p>[short_description]</p>
					<p>By <span class="authors">[author]</span></p>
				</div>
			</div>
		</li>
	</div>

</ul>

