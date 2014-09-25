<?php if (TRUE === $data['first_not_empty_import']): ?>
    <div class="updated">
        <p>
            Отлично! Теперь, чтобы установить попап на сайт, просто нажмите "Активировать" под его названием.
        </p>
        <p>
            Чтобы обновить список попапов, нажмите кнопку "Синхронизировать" сверху.
        </p>
    </div>
<? endif ?>

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
            Название
        </th>
        <th>
            Работать на страницах
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
                <strong><?php echo $item['name']; ?></strong>
                <div style="min-height: 22px;"><!-- class="row-actions" -->
                    <?php if (TRUE === in_array($item['id'], $data['activated'])):?>
                        <span class="deactivate"><a title="Убрать элемент с сайта" href="?page=jumpout&action=deactivate&id=<?php echo $item['id']; ?>">Деактивировать</a> |</span>
                    <?php else:?>
                        <span class="activate"><a title="Установить на сайт элемент" href="?page=jumpout&action=activate&id=<?php echo $item['id']; ?>">Активировать</a> |</span>
                    <?php endif?>

                    <span class="edit"><a href="http://jumpout.makedreamprofits.ru/edit/<?php echo $item['id']; ?>" title="Редактировать этот элемент" target="_blank">Изменить</a></span>
                    <!--<span class="trash"><a class="submitdelete" title="Удалить элемент" href="http://jumpout.makedreamprofits.ru/delete/<?php echo $item['id']; ?>" target="_blank">Удалить</a></span>-->

                </div>
            </td>
            <td>
                <?php echo ('' != trim($item['work_on_page'])) ? $item['work_on_page'] : 'всех'; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>


<?php elseif (NULL === $data['session_token'] || TRUE !== $data['token_is_working']):?>

    <div class="wrap about-wrap">

        <style>.top_plugin_menu { display: none; }</style>

        <h1>Добро пожаловать в плагин JumpOut'а!</h1>

        <div class="about-text">
            Первое, что нам нужно сделать - разрешить доступ плагину к вашему аккаунту с попапами.
            Чтобы это сделать, просто нажмите на кнопку ниже:
            <br /><br />

            <a class="button button-primary button-hero load-customize" href="http://jumpout.makedreamprofits.ru/api/allow_access/?back_url=http://<?=$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']?>">Начать использование!</a>
        </div>

    </div>

<?php else:?>

    <div class="wrap about-wrap">

        <div class="about-text">
            Судя по всему, вы еще не создавали попапов. Что ж, пришло время это сделать! 
            Нажмите на кнопку "Новый попап" сверху или под этими строками:
            <br /><br />

            <a class="button button-primary button-hero load-customize" href="http://jumpout.makedreamprofits.ru/#add_new" target="_blank">Создать первый попап!</a>

            <br /><br />
            После того, как добавите, нажмите на кнопку сверху "Синхронизировать".

        </div>

    </div>


<?php endif?>






<?php 
