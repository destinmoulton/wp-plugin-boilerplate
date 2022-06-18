<?php
/**
 * This file redirects after loading.
 *
 * This is important because it will then
 * clear or set cookies on the next load.
 */

/** @var $redirect_url string */
?>
<h1>PLUGIN_NAME</h1>
<p>
    <strong>Saving changes.</strong>
    <br>This page should automatically reload.
    <br><a href="<?= $redirect_url ?>">Click here</a> if it doesn't automatically redirect.
</p>
<script>
    (function ($) {
        $(function () {
            window.location = "<?= $redirect_url ?>";
        })
    })(jQuery);
</script>
