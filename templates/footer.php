</div><!--container-->
</div><!--page-->

<script>
    $(document).ready(function () {
        $(document).tooltip({
            content: function () {
                return $(this).attr('title');
            }
        });
    });
</script>

<style>
    .mm-menu li.admin,
    .mm-menu li.edarh,
    .mm-menu li.ms,
    .mm-menu li.er,
    .mm-menu li.mktbr,
    .mm-menu li.t3lem {
        display: none
    }

    <?php
    $allowdgroupes='';
    if (isset($_SESSION['user_group'])){
        $allowdgroupes=$_SESSION['user_group'];
    }
    $arrAllowdGroups = Explode ( ",", $allowdgroupes );
    $ii=0;
    do {
        echo '.mm-menu li.'.$arrAllowdGroups[$ii].'{display:block}';
    $ii++;
    }  while ($ii<count($arrAllowdGroups));

    ?>
</style>
</body>
</html>