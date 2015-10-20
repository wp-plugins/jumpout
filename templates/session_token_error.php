<p>
    <?php _e('К сожалению, судя по всему произошла ошибка получения секретного ключа от нашего сервиса.
    Если вы видите это сообщение в первый раз, попробуйте разрешить доступ еще раз:)', 'jumpout') ?>
</p>

<a class="button button-primary button-hero load-customize" href="<?php echo $api_url?>allow_access/?back_url=http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']?>">
	<?php _e('Попробовать еще раз!', 'jumpout') ?>
</a>


<p>
    <?php _e('Если же не в первый раз - обратитесь в техподдержку.', 'jumpout') ?>
</p>