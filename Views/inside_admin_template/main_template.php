<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <?php

    if (!isset($table_name)) $table_name = 'Inside Admin';

    if (isset($table_name)) $seo_title = $table_name;
    if (!isset($seo_title)) $seo_title = 'Inside';
    if (isset($table_config['table_title'])) $seo_title = $table_config['table_title'];

    ?>

    <!-- TO DO -->
    <title>Inside 4 : <?= @$seo_title?></title>

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Arsenal" rel="stylesheet">

    <!-- Font awesone -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- bootstrap css -->
    <link rel='stylesheet' href="/Public/InsideAdmin/inside_admin_template/js/bootstrap-3.3.7/css/bootstrap.css"/>

    <!-- bootstrap select -->
    <link rel='stylesheet' href="/Public/InsideAdmin/inside_admin_template/js/bootstrap-select-1.12.2/bootstrap-select.css"/>

    <!-- jquery ui css -->
    <link rel="stylesheet" href="/Public/InsideAdmin/inside_admin_template/js/jquery-ui-1.12.1/jquery-ui.css">

    <!-- Checkboxes -->
    <link rel='stylesheet' type='text/css' href="/Public/InsideAdmin/inside_admin_template/css/checkboxes.css"/>

    <!-- Theme css -->
    <link rel='stylesheet' type='text/css' href="/Public/InsideAdmin/inside_admin_template/css/style.css"/>

    <!-- Theme customization -->
    <link rel='stylesheet' type='text/css' href="/Public/InsideAdmin/inside_admin_template/css/ui.css"/>

    <link rel='stylesheet' type='text/css' href="/Public/InsideAdmin/inside_admin_template/vendor/bootstrap-datepicker-1.9.0-dist/css/bootstrap-datepicker.standalone.css"/>


    <!-- //i--- Include /views/outside/pages/" . $page_center."_head.php" in HEAD ; inside_template ; torrison ; 15.08.2018 ; 12 ---/ -->
    <?php
    if (@file_exists("Views/".$template_folder."/Pages/" . $page_center."_head.php"))
    {
        include "Views/".$template_folder."/Pages/" . $page_center."_head.php";

    }
    ?>

    <style>
        /* Need to Transfer to UI.css fixes !!! */
        .tooltip.right .tooltip-arrow {
            border-right: 5px solid #0b7d89;
        }

        .tooltip.top .tooltip-arrow {
            border-top: 5px solid #0b7d89;
        }

        .color-tooltip + .tooltip > .tooltip-inner {
            background-color: #0b7d89;
        }
        .mini_hr {
            padding: 0;
            margin: 0;
        }
        .back_button {
            display: block;
            float: left;
            border: 0;
            background-color: rgba(70, 200, 159, 1);
            border-radius: 2px;
            margin-right: 10px;
        }
        .form-control {
            font-size: 16px !important;
        }
        /*
        body {
            font-size: 16px;
        }
        */
    </style>

    <!-- For easy JS coding in HTML -->
    <script src="/Public/InsideAdmin/inside_admin_template/js/jquery-3.1.1/jquery-3.1.1.min.js"></script>

</head>
<body>
<div class="page-container sidebar-collapsed">
    <div class="content">
        <header class="top_header">

            <h3 class="page_name"><?= $seo_title ?></h3>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 left_side">
                        <?php if (isset($admin_interface_name)) echo "<h3>".$admin_interface_name."</h3>";?>
                        <!--
                        <div class="form-group top_search_holder">
                            <input type="text" class="form-control top_search" placeholder="Search...">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                        -->
                    </div>
                    <div class="col-md-6 right_side">
                        <ul class="top_nav">
                            <li>
                                <h1 class="top_logo"><?= $seo_title ?></h1>
                            </li>

                            <!--
                            <li><a class="settings_btn"
                                   onclick="$.get('/admin/ajax/user_data/',function(data){alert(data)})"><i
                                            class="fa fa-cogs" aria-hidden="true"></i></a></li>
                            <li><a><i class="fa fa-bell-o" aria-hidden="true"></i></a></li>
                            -->

                            <li><a href="/auth/page/profile"><i class="fa fa-user"
                                                           aria-hidden="true"></i>&nbsp;<?= $user['email'] ?></a><span
                                        class="nav_divider">|</span></li>
                            <li><a href="/auth/page/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="dropdown mob_dropdown_box">


                <button class="mob_dropdown_btn" id="mobDrop" type="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                </button>
                <ul onclick="event.stopPropagation();" class="dropdown-menu" aria-labelledby="mobDrop">
                    <?php if (!isset($top_dropdown_menu)) { ?>
                    <li class="mob_filrer_btn"><a>Фильтр</a></li>
                    <li><a class="add_btn">Добавить запись</a></li>
                    <li><a class="del_btn pdg_bdel">Удалить записи</a></li>
                    <?php } else echo $top_dropdown_menu;  ?>
                </ul>
            </div>


        </header>
        <div class="table_section">
            <div class="container">

                <!-- //i--- Content insert from: 'outside/pages/' . $page_center ; inside_core ; torrison ; 01.08.2018 ; 12 ---/ -->
                <?php include "Views/".$template_folder."/Pages/" . $page_center.".php" ?>



            </div>
        </div>
    </div>
    <!--/sidebar-menu-->
    <div class="sidebar-menu">
        <header class="logo1">
            <a class="mobile_logo" href="/inside/panel/admin"><?=$GLOBALS['config']['Admin']['admin_panel_name']?></a>
            <button type="button" class="sidebar-icon"><span class="fa fa-bars"></span></button>
        </header>
        <div class="menu">

            <ul id="menu">
                <li class="search_box_li">
                    <br>
                    <!--
                    <form>
                        <div class="search_box">
                            <input type="text" class="menu_search form-control" placeholder="Search...">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </div>
                    </form>
                    -->
                </li>
                <div class="inside_menu_search">
                </div>
                <div class="inside_menu">
                    <?php echo $top_menu; ?>
                </div>

            </ul>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<footer class="footer">

</footer>


<?php include "Views/".$template_folder."/main_template_footer.php"; ?>

<!-- //i--- Include /views/outside/pages/" . $page_center."_footer.php" before END /body ; inside_core ; torrison ; 01.08.2018 ; 20 ---/ -->
<?php

if (@file_exists("Views/".$template_folder."/Pages/" . $page_center."_footer.php"))
{
    include "Views/".$template_folder."/Pages/" . $page_center."_footer.php";

}
?>

</body>
</html>