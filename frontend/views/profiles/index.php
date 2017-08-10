<?php

//use yii\helpers\Html;
use kartik\helpers\Html;
use yii\helpers\Url;
use common\models\Profiles;
use common\models\Actions;
use common\models\User;
use common\models\City;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use common\models\Translations;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MoviesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $profile Profiles */
/* @var $actions Actions */
/* @var $pages string */
/* @var $cities City */

$this->title = $profile->firstname . ' ' . $profile->lastname;
//$this->params['breadcrumbs'][] = $this->title;
$currentUser = User::findOne(Yii::$app->user->getId());
?>

<div class="col-xs-12 no-padding profile-heading">
    <?php
    if (!empty($profile->cover)) {
        $action = Actions::findOne(['imagePath' => $profile->cover]);
        echo $this->render('/actions/modal', ['action' => $action]);
    }
    ?>
    <img src="<?= Url::base() . '/' . $profile->cover; ?>" class="profile-cover" alt=""
        <?php if ($profile->cover) { ?>
            onclick="$('#action-modal-<?= $action->id ?>').modal()"
        <?php } ?>
    >

    <?php
    if ($profile->avatar) {
        $action = Actions::findOne(['imagePath' => $profile->avatar]);
        echo $this->render('/actions/modal', ['action' => $action]);
        ?>
        <img src="<?= Url::base() . '/' . $profile->avatar; ?>" class="profile-avatar" alt=""
            <?php if ($profile->cover) { ?>
                onclick="$('#action-modal-<?= $action->id ?>').modal()"
            <?php } ?>
        >
    <?php } ?>

    <div class="profile-name">
        <?php
        echo $profile->firstname . ' ' . $profile->lastname;
        if ($profile->nickname) {
            echo ' (' . $profile->nickname . ')';
        }
        ?>
    </div>

    <?php
    if (!Yii::$app->user->isGuest) {
        $url = explode('/', Url::current());
        $shortUrl = explode('?', $url[count($url) - 1])[0];
        $currentProfile = Profiles::findOne(Yii::$app->user->identity->getId());
        if ($shortUrl == $currentProfile->shortUrl OR $shortUrl == $currentUser->username OR $shortUrl == 'profile') {
            echo $this->render('/profiles/modal');
            ?>
            <?= Html::button('<i class="fa fa-pencil-square-o" style="font-size: 20px;"></i> ' . Translations::translate('app', 'Edit profile'),
                [
                    'class' => 'btn col-xs-12 profile-button',
                    'onclick' => "$('#profile-modal-" . $profile->user_id . "').modal()"
                ]) ?>
            <?php
        }
    }
    ?>
</div>

<div class="clearfix"></div>

<div class="row">
    <div id="profile-sidebar">
        <div class="col-md-4 col-xs-12">
            <?php if (
                $profile->phoneNumber OR $profile->currentCity OR $profile->birthCity OR $profile->birthday OR
                $profile->sex OR $profile->interestedIn OR $profile->relationship OR $profile->work
            ) { ?>
                <div class="panel panel-default text-center">
                    <div class="panel-heading">
                        <i class="fa fa-2x">
                            <?= Translations::translate('app', 'About') ?>
                        </i>
                    </div>
                    <div class="panel-body text-center">
                        <?php if ($profile->work) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-suitcase"></i>
                                <?= $profile->work ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->knownLanguages) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-language"></i>
                                <?= $profile->knownLanguages ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->birthday) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-birthday-cake"></i>
                                <?= date('d-m-Y', strtotime($profile->birthday)) ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->phoneNumber) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-mobile"></i>
                                <?= $profile->phoneNumber ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->currentCity) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-globe"></i>
                                <?= Translations::translate('app', 'Living in') ?>:
                                <?= $cities[$profile->currentCity] ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->birthCity) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-globe"></i>
                                <?= Translations::translate('app', 'From') ?>:
                                <?= $cities[$profile->birthCity] ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->sex) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-transgender"></i>
                                <?= $profile->sexEnum[$profile->sex] ?>
                            </div>
                        <?php } ?>
                        <?php if ($profile->interestedIn) { ?>
                            <div class="col-xs-6">
                                <i class="fa fa-hand-o-right"></i>
                                <?= $profile->sexEnum[$profile->interestedIn] ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ($profile->relationship) { ?>
                        <div class="panel-footer">
                            <i class="fa fa-heartbeat fa-2x">
                                <?= Translations::translate('app', $profile->relationshipEnum[$profile->relationship]) ?>
                            </i>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="col-md-8" id="profile-activity">
        <?php $form = ActiveForm::begin(['action' => ['/add-post'], 'options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="panel panel-default action-new">
            <!--            <div class="panel-heading action-header"></div>-->
            <div class="panel-body ">
                <div class="col-xs-12">
                    <?php
                    $newAction = new Actions();
                    $newAction->privacy = 'Public';
                    ?>
                    <?= $form->field($newAction, 'description')->textarea(
                        [
                            'maxlength' => true,
                            'placeholder' => 'What are you thinking about?',
                            'style' => 'resize: vertical;min-height: 50px'
                        ])->label(false);
                    ?>
                </div>
                <div class="col-xs-12">
                    <?= $form->field($newAction, 'imagePath')->widget(FileInput::classname(), [
                        'options' => [
                            'accept' => 'image/*',
                            'multiple' => true
                        ],
                        'pluginOptions' => [
                            'showPreview' => true,
                            'showCaption' => true,
                            'showRemove' => true,
                            'showUpload' => false
                        ]
                    ])->label(false) ?>
                </div>
                <div class="col-xs-12 no-padding">
                    <div class="col-xs-6">
                        <?= $form->field($newAction, 'privacy')->widget(Select2::classname(),
                            [
                                'name' => 'Actions[type]',
                                'data' => $newAction->privacyEnum,
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'hideSearch' => true,
                                'options' => [
                                    'placeholder' => 'Privacy',
                                ],
                                'pluginOptions' => [
                                    'allowClear' => false
                                ],
                                'addon' => [
                                    'prepend' => [
                                        'content' => Html::icon('globe')
                                    ],
                                ]
                            ])->label(false) ?>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <?= Html::submitButton('<i class="fa fa-pencil-square-o"></i> Post',
                                [
                                    'class' => 'btn btn-orange col-xs-12',
                                    'name' => 'action-edit-button',
                                    'onclick' => "",
                                ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--            <div class="panel-footer"></div>-->
        </div>
        <?php ActiveForm::end(); ?>
        <div id="actions-group">
            <?php
            foreach ($actions as $action) {
                echo $this->render('/actions/panel', ['action' => $action]);
            } ?>
        </div>
        <p id="loading" class="text-center" data-offset="<?= count($actions) ?>" style="display: none;">
            <img src="/Icons/loading3.gif" alt="Loading…"/>
        </p>
        <!--        <div class="text-center">-->
        <!--            --><?php
        //            echo LinkPager::widget([
        //                'pagination' => $pages,
        //            ]);
        //            ?>
        <!--        </div>-->
    </div>
</div>

<script>
    $(document).ready(function () {
        var win = $(window);

        // Each time the user scrolls
        win.scroll(function () {
            // End of the document reached?
            var loading = $('#loading');
            if ($(document).height() - win.height() == win.scrollTop() && !loading.is(":visible")) {
                var offset = loading.data('offset');
                loading.show();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        offset: offset
                    },
                    success: function (response) {
                        result = JSON.parse(response);
                        if (result.html.length != 0) {
                            $("#actions-group").append(result.html).fadeIn(10000);
                            loading.data('offset', offset + result.actions);
                        }
                        loading.hide();
                    },
                    error: function (request, status, error) {
                        window.alert(error);
                    }
                });
            }
        });
    });

    var back = ["#22A7F0", "#8E44AD", "#AEA8D3", "#F62459", "#DB0A5B", "#D64541", "#D2527F", "#2C3E50", "#1E8BC3", "#87D37C", "#4ECDC4", "#3FC380", "#E87E04", "#F9690E", "#F9BF3B"];

    $('.profile-heading').each(function () {

        // First random color
        var rand1 = back[Math.floor(Math.random() * back.length)];
        // Second random color
        var rand2 = back[Math.floor(Math.random() * back.length)];

        var grad = $(this);

        // Convert Hex color to RGB
        function convertHex(hex, opacity) {
            hex = hex.replace('#', '');
            r = parseInt(hex.substring(0, 2), 16);
            g = parseInt(hex.substring(2, 4), 16);
            b = parseInt(hex.substring(4, 6), 16);

            // Add Opacity to RGB to obtain RGBA
            result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
            return result;
        }

        // Gradient rules
        grad.css('background-color', convertHex(rand1, 40));
        grad.css("background-image", "-webkit-gradient(linear, left top, left bottom, color-stop(0%," + convertHex(rand1, 40) + "), color-stop(100%," + convertHex(rand2, 40) + "))");
        grad.css("background-image", "-webkit-linear-gradient(top,  " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "-o-linear-gradient(top, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "-ms-linear-gradient(top, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("background-image", "linear-gradient(to bottom, " + convertHex(rand1, 40) + " 0%," + convertHex(rand2, 40) + " 100%)");
        grad.css("filter", "progid:DXImageTransform.Microsoft.gradient( startColorstr='" + convertHex(rand1, 40) + "', endColorstr='" + convertHex(rand2, 40) + "',GradientType=0 )");

    });

    var elementPosition = $('#profile-sidebar').offset();

    $(window).scroll(function () {
        if ($(window).scrollTop() > elementPosition.top - 70) {
            $('#profile-sidebar').css('position', 'fixed').css('top', '70px').addClass("container no-padding");
            $('#profile-activity').addClass('col-md-offset-4');
        } else {
            $('#profile-sidebar').css('position', 'static').removeClass("container no-padding");
            $('#profile-activity').removeClass('col-md-offset-4');
        }
    });
</script>