<?php if (TRUE === $data['first_not_empty_import']): ?>
    <div class="updated">
        <p>
            <?php _e('Отлично! Теперь, чтобы установить попап на сайт, просто нажмите "Активировать" под его названием.', 'jumpout') ?>
        </p>
        <p>
            <?php _e('Чтобы обновить список попапов, нажмите кнопку "Синхронизировать" сверху.', 'jumpout') ?>
        </p>
    </div>
<?php endif ?>

<?php if (0 != count($data['list'])):?>

    <table class="widefat fixed plugins">
    <thead>
    <tr>
        <!--<th width="220">
        </th>-->
        <!--<th width="10">
            №
        </th>
        <th width="42">
            ID
        </th>-->
        <th style="width: 1px;"></th>
        <th>
            <?php _e('Название', 'jumpout') ?>
        </th>
        <th>
            <?php _e('Работать на страницах', 'jumpout') ?>
        </th>
    </tr>
    </thead>
        <?php foreach ($data['list'] as $key => $item): ?>
        <tr class="<?php echo (TRUE === in_array($item['id'], $data['activated'])) ? 'active' : 'inactive'?>">
            <th class="check-column"></th>
            <!--
            <td>
                <input class="button-primary" type="button" value="Редактировать" onclick="location.href='http://jumpout.makedreamprofits.ru/edit/<?php echo $item['id']; ?>'">
                <input class="button-primary" type="button" value="Удалить" onclick="location.href='http://jumpout.makedreamprofits.ru/delete/<?php echo $item['id']; ?>'" >
            </td>-->
            <!--<td>
                <?php echo $key; ?>
            </td>
            <td>
                <?php echo $item['id']; ?>
            </td>-->
            <td class="post-title page-title column-title">
                <strong>
                    <?php echo $item['name']; ?>


                    <?php if (isset($item['popups'])): ?>
                        <span class="group-type">
                            (<?php echo ('split-test' == $item['type']) ? __('сплит-тест', 'jumpout') : __('серия попапов', 'jumpout') ?>)
                        </span>
                    <?php endif ?>
                </strong>

                <?php if (isset($item['popups'])): ?>
                    <div style="margin-bottom: 7px">
                        <?php _e('Попапы')?>:
                        <?php foreach ($item['popups'] as $key => $popup): ?>

                            <?php echo ((0 == $key) ? '' : ', ') . $popup['name']; ?>

                        <?php endforeach; ?>
                    </div>
                <?php endif ?>


                <div style="min-height: 22px;"><!-- class="row-actions" -->
                    <?php if (TRUE === in_array($item['id'], $data['activated'])):?>
                        <span class="deactivate"><a title="<?php _e('Убрать элемент с сайта', 'jumpout')?>" href="?page=jumpout&action=deactivate&id=<?php echo $item['id']; ?>">
                            <?php _e('Деактивировать', 'jumpout') ?>
                        </a> |</span>
                    <?php else:?>
                        <span class="activate"><a title="<?php _e('Установить на сайт элемент', 'jumpout')?>" href="?page=jumpout&action=activate&id=<?php echo $item['id']; ?>">
                            <?php _e('Активировать', 'jumpout') ?>
                        </a> |</span>
                    <?php endif?>

                    <span class="edit"><a href="http://jumpout.makedreamprofits.ru/edit/<?php echo $item['id']; ?>" title="<?php _e('Редактировать этот элемент', 'jumpout') ?>" target="_blank">
                        <?php _e('Изменить', 'jumpout') ?>
                    </a></span>
                    <!--<span class="trash"><a class="submitdelete" title="Удалить элемент" href="http://jumpout.makedreamprofits.ru/delete/<?php echo $item['id']; ?>" target="_blank">Удалить</a></span>-->

                </div>
            </td>
            <td>
                <?php 
                    if (is_array($item['work_on_page'])) {
                        foreach ($item['work_on_page'] as $key => $value)
                            if ('' == trim($value)) $item['work_on_page'][$key] = __('всех', 'jumpout');
                        rsort($item['work_on_page']);
                        echo implode(', ', $item['work_on_page']);
                    } else {
                        echo ('' != trim($item['work_on_page'])) ? $item['work_on_page'] : __('всех', 'jumpout');
                    }
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>


<?php elseif (NULL === $data['session_token'] || TRUE !== $data['token_is_working']):?>

    <div class="wrap about-wrap">

        <style>.top_plugin_menu { display: none; }</style>

        <h1><?php _e('Добро пожаловать в плагин JumpOut\'а!', 'jumpout') ?></h1>

        <div class="about-text">
            <?php _e('Первое, что нам нужно сделать - разрешить доступ плагину к вашему аккаунту с попапами.
            Чтобы это сделать, просто нажмите на кнопку ниже:', 'jumpout') ?>
            <br /><br />

            <a class="button button-primary button-hero load-customize" href="<?php echo $api_url?>allow_access/?back_url=http://<?=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']?>">
                <?php _e('Начать использование!', 'jumpout') ?>
            </a>
        </div>

    </div>

<?php else:?>

    <div class="wrap about-wrap">

        <div class="about-text">
            <?php _e('Судя по всему, вы еще не создавали попапов. Что ж, пришло время это сделать! 
            Нажмите на кнопку "Новый попап" сверху или под этими строками:', 'jumpout') ?>
            <br /><br />

            <a class="button button-primary button-hero load-customize" href="http://jumpout.makedreamprofits.ru/#add_new" target="_blank">
                <?php _e('Создать первый попап!', 'jumpout') ?>
            </a>

            <br /><br />
            <?php _e('После того, как добавите, нажмите на кнопку сверху "Синхронизировать"', 'jumpout') ?>.

        </div>

    </div>


<?php endif?>
