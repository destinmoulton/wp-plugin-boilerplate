<?php
/**
 * This file redirects after loading.
 *
 * This is important because it will then
 * clear or set cookies on the next load.
 */

/** @var $tool_url string */
?>
<script>
    (function ($) {
        $(function () {
            window.location = "<?= $tool_url ?>";
        })
    })(jQuery);
</script>
