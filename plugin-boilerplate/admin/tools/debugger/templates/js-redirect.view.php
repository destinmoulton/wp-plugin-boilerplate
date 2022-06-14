<?php
/**
 * This file redirects after loading.
 *
 * This is important because it will then
 * clear or set cookies on the next load.
 */
?>
<script>
    (function($){
       $(function(){
           window.location="<?=$redirection_url?>";
       })
    })(jQuery);
</script>
