<?php

$this->title = 'Сгенерировать токен для яндекса';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<style>
    .gen_token{
        font-size: 16px;
    }
    .gen_token ol li{
        margin: 10px;
        height: 24px;
    }
    .gen_token ol li.text_li{
        margin-top: 20px ;
    }

</style>

<div class="container gen_token">

    <ol>
        <li>
            Авторизуйтесь на Яндексе с учетной записью пользователя,
            от имени которого будет работать приложение
        </li>
        <?php if ($clientID): ?>
        <li >
            <form id="formCreateToken" target="_blank" method="get" action="https://oauth.yandex.ru/authorize">
                <input name="response_type" type="hidden" value="token"/>

                <div class="input-group">
                        <input name="client_id" placeholder="Идентификатор приложения" type="hidden"  value="<?= $clientID ?>"
                               class="form-control client_input">
                        <span class="input-group-btn">
                              <input class="btn btn-primary token_btn" type="submit" value="Сгенерировать токен"/>
                            </span>
                </div>
            </form>
        </li>
        <?php else: ?>
        <li>
            <form id="formGetUserId" method="get"  action="generate-yandex-token">
                <div class="input-group">
                    <span class="input-group-btn">
                        <input class="btn btn-primary token_btn" name="add-user" type="submit" value="Получить user_id"/>
                    </span>
                </div>
            </form>
        </li>
        <li>Сгенерировать токен (кнопка будеть доступьно после сгенерирования user_id)</li>
        <?php endif; ?>
        <li class="text_li">Нажмите "Сгенерировать токен"</li>
        <li>Вас перенаправит на страницу указанную в Callback URI в настройках приложения если она указана.
        </li>
        <li>Скопируйте из адресной строки значение параметра access_token</li>
        <li>Поменять значение yandex_oauth_token в /<?=Yii::$app->params['currentSiteHost']?>/common/config/params.php </li>
    </ol>

</div>


