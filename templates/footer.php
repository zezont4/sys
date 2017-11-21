<!--<div id="footer">للملاحظات والمشاكل التقنية  ( 0559955114 - قسم الحاسب والتقنية ). وللاستفسارات العامة ( 064222837 ) تحويلة ( 107 أو 138 - قسم الإرتقاء ).
</div>--><!--footer-->
</div><!--container-->
</div><!--page-->
<?php //include_once("analyticstracking.php") ?>
<!--<script src="/sys/_js/jquery-ui-1.9.2.custom.min.js"></script>-->

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