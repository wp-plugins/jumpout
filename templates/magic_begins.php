<style type="text/css">
.sociallocker div {
    margin-bottom: 6px;
}
.sociallocker small {
    display: block;
    color: gray;
    margin-top: 0px;
    line-height: 7px;
}
</style>

<form action="?page=jumpout&action=magic_begins&noheader=true" method="POST" class="">
    <p>
        <?php _e('Описание этого скрипта, вы можете найти по
        <a href="http://jumpout.makedreamprofits.ru/bonuses/" target="_blank">этой ссылке</a>.
        После включения, плагин автоматически вставит его на все страницы вашего сайта.', 'jumpout') ?>
    </p>

    <p>
        <label>
            <input type="checkbox" name="data[enabled]" <?php echo (isset($data['magic_begins']) && isset($data['magic_begins']['enabled']) && TRUE === $data['magic_begins']['enabled']) ? 'checked="checked"' : ''?> /> 
            <?php _e('Включить скрипт', 'jumpout') ?>
        </label>
    </p>
    <span id="autofill" style="display: none;">
        <p>
            <label>
                <input type="checkbox" name="data[autofill]" <?php echo (isset($data['magic_begins']) && isset($data['magic_begins']['autofill']) && TRUE === $data['magic_begins']['autofill']) ? 'checked="checked"' : ''?> /> 
                <?php _e('Включить автозаполнение форм подписки, когда есть данные подписчика', 'jumpout') ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="data[async]" <?php echo (isset($data['magic_begins']) && isset($data['magic_begins']['autofill']) && TRUE === $data['magic_begins']['async']) ? 'checked="checked"' : ''?> /> 
                <?php _e('Асинхронная загрузка (не задерживает загрузку вашего сайта, рекомендуется)', 'jumpout') ?>
            </label>
        </p>
    </span>
    <!--
    Код Google Analytics:<br />
    <textarea name="data[magic_begins_code]" style="width: 500px; height: 300px;"><?php echo $data; ?></textarea>
    <small>будет вставлен между тегами &lt;head&gt; и &lt;/head&gt; на всех страницах</small>
-->

    <input class="button-primary" type="submit" name="item_add" value="<?php _e('Сохранить', 'jumpout') ?>" style="margin: 5px 0 5px 8px;" />



    <input type="hidden" name="action" value="magic_begins" />

</form>


<script>
jQuery(document).ready(function(){
    jQuery('input[name=data\\[enabled\\]]').change(function(){

        script_settings_visibility();

    });

    script_settings_visibility();
});

function script_settings_visibility() {

    if (jQuery('input[name=data\\[enabled\\]]').is(':checked')) {
        jQuery('#autofill').show();
    } else {
        jQuery('#autofill').hide();
    }
    

}
</script>



<?php 
