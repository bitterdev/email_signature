<?php

/**
 * Project: Email Signature
 *
 * @copyright 2019 Fabian Bitter
 * @author Fabian Bitter (fabian@bitter.de)
 * @version X.X.X
 */

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Core\Editor\EditorInterface;
use Concrete\Core\Form\Service\Form;
use Concrete\Core\Support\Facade\Application;
use Concrete\Core\Validation\CSRF\Token;
use Concrete\Core\View\View;

/** @var $locales array */
/** @var $locale string */
/** @var $signatures array */

$app = Application::getFacadeApplication();
/** @var $editor EditorInterface */
$editor = $app->make("editor");
/** @var $token Token */
$token = $app->make(Token::class);
/** @var $form Form */
$form = $app->make(Form::class);

?>

<form action="#" method="post">
    <?php echo $token->output('save_signature'); ?>

    <?php echo $form->hidden("signatures"); ?>

    <div class="form-group">
        <?php echo $form->label("locale", t("Locale"), ["class" => "control-label"]); ?>
        <?php echo $form->select("locale", $locales, $locale ?? null, ["class" => "form-control"]); ?>
    </div>

    <div class="form-group">
        <?php echo $form->label("signature", t("Signature"), ["class" => "control-label"]); ?>
        <?php echo $editor->outputBlockEditModeEditor("signature", null); ?>
    </div>

    <div class="ccm-dashboard-form-actions-wrapper">
        <div class="ccm-dashboard-form-actions">
            <div class="float-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> <?php echo t("Save"); ?>
                </button>
            </div>
        </div>
    </div>
</form>

<!--suppress JSUnusedAssignment -->
<script>
    (function ($) {
        $(function () {
            let signatures = <?php /** @noinspection PhpComposerExtensionStubsInspection */echo json_encode($signatures); ?>;
            let editorId = $("textarea[name='signature']").attr("id");
            let lockEditorUpdate = false;

            if (typeof signatures !== "object") {
                signatures = {};
            } else if (signatures.constructor === Array) {
                signatures = signatures.reduce(function(result, item, index) {
                    result[index] = item;
                    return result;
                }, {})
            }

            $("#signatures").val(JSON.stringify(signatures));

            if (typeof CKEDITOR === "object") {
                let editor = CKEDITOR.instances[editorId];

                if (typeof editor === "object") {
                    editor.on("change", function () {
                        if (lockEditorUpdate === false) {
                            let activeLocale = $("#locale").val();

                            // Update signature for active locale
                            signatures[activeLocale] = editor.getData();

                            // Update input field
                            $("#signatures").val(JSON.stringify(signatures));
                        }
                    });

                    $("#locale").bind("change", function () {
                        let activeLocale = $(this).val();
                        let signature = '';

                        if (typeof signatures !== "undefined" &&
                            typeof signatures[activeLocale] !== "undefined") {

                            // Get signature for active locale
                            signature = signatures[activeLocale];
                        }

                        lockEditorUpdate = true;

                        editor.setData(signature);

                        lockEditorUpdate = false;

                    }).trigger("change");
                }
            }
        });
    })(jQuery);
</script>
