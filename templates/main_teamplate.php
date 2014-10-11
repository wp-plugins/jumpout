<div class="wrap">
	<div class="top_plugin_menu">
		<div style="float: right; margin-top: 15px;">
			<?php //=$this->settings['session_token']?>
			<a href="http://makedreamprofits.ru/support/" target="_blank" class="">Техподдержка</a>
		</div>

		<h2>
			<?php if ('list' != @$_GET['action']) :?><a href="?page=jumpout"><?php endif ?>JumpOut<?php if ('list' != @$_GET['action']) :?></a><?php endif ?>

			<?php echo ('' !== trim($caption)) ? ' — ' . $caption : ''; ?> 
			<a href="http://jumpout.makedreamprofits.ru/#add_new" target="_blank" class="add-new-h2">Новый попап</a>
			<a href="?page=jumpout&action=sync" class="sync add-new-h2">
				<!-- 3  -->
				  <svg style="margin-bottom: -4px" version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
				     width="20px" height="20px" viewBox="0 0 50 50" style="enable-background:new 0 0 50 50;" xml:space="preserve">
				  <path fill="#000" d="M43.935,25.145c0-10.318-8.364-18.683-18.683-18.683c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615c8.072,0,14.615,6.543,14.615,14.615H43.935z">
				    <animateTransform attributeType="xml"
				      attributeName="transform"
				      type="rotate"
				      from="0 25 25"
				      to="360 25 25"
				      dur="0.6s"
				      repeatCount="indefinite"/>
				    </path>
				  </svg>

				<span>Синхронизировать</span>
			</a>
			<a href="?page=jumpout&action=magic_begins" class="add-new-h2" style="margin-left: 10px">Скрипт MagicBegins</a>
		</h2>
	</div>

	<!--
	<div style="border: #ccc 1px solid; padding: 10px; margin-bottom: 10px;">
		<fieldset class="options">
			<div style="float: right; margin-top: 2px;">
			    <a href="http://makedreamprofits.ru/support/" target="_blank">Техподдержка</a>
			</div>
			
			<div>
			    <input class="button-primary" type="button" value="Добавить новый" style="margin-right: 10px; " onclick="location.href='?page=comebacker&action=add'" />
			    <input class="button-primary" type="button" value="Google Analytics" style="margin-right: 10px; " onclick="location.href='?page=comebacker&action=google_analytics'" />

			    Быстрые ссылки на сайт:
			    <a href="http://comebacker.makedreamprofits.ru/comebacker/?site_url=<?php echo str_replace('http://', '', get_bloginfo('url')); ?>" target="_blank">Список сохраненных скриптов</a> |
			    <a href="http://comebacker.makedreamprofits.ru/comebacker/add/?site_url=<?php echo str_replace('http://', '', get_bloginfo('url')); ?>" target="_blank">Добавить новый</a>
			</div>
		</fieldset>
	</div>-->


    <?php include $page_file ?>

</div>


<style>
.sync svg {
	display: none;
}
svg path,
svg rect{
  fill: #ffffff;
}
</style>