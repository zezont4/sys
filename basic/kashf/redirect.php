<?php
if (isset($_POST['absent'])) { ?>
    <form action='absent.php' method='post' name='frm'>
<?php } elseif (isset($_POST['students_data'])) {?>
    <form action='students_data.php' method='post' name='frm'>
<?php }
        foreach ($_POST as $a => $b) {
            echo "<input type='hidden' name='" . htmlentities($a) . "' value='" . htmlentities($b) . "'>";
        }
        ?>
    </form>
    <script language="JavaScript">
        document.frm.submit();
    </script>
