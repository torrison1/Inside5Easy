
    <style>
        .erp_blocks a:hover {
            text-decoration: none;
        }
    </style>
    <div class="erp_blocks wblock1 container">
        <div class="blocks-list row">
            <?php if ($inside4_auth->is_logged_in()) { ?>
                <div class="col-md-2">
                    <div class="card mb-4 box-shadow text-center">
                        <a href="<?=$lang_link_prefix?>/auth/page/profile" class="fas fa-user" style="padding: 20px; font-size: 52px;"></a>
                        <div class="top-shadow">&nbsp;</div>
                        <div class="card-body">
                            <a href="<?=$lang_link_prefix?>/auth/page/profile"><?=$t->get('my_profile');?></a>
                        </div>
                    </div>
                </div>
				<div class="col-md-2">
					<div class="card mb-4 box-shadow text-center">
						<a href="/inside/panel/menu_tree" class="fa fa-briefcase" style="padding: 20px; font-size: 52px;"></a>
						<div class="top-shadow">&nbsp;</div>
						<div class="card-body">
							<a href="/inside/panel/menu_tree">Admin Panel</a>
						</div>
					</div>
				</div>
            <?php } else { ?>
                <div class="col-md-2">
                    <div class="card mb-4 box-shadow text-center">
                        <a href="<?=$lang_link_prefix?>/auth/page/login" class="fas fa-user" style="padding: 20px; font-size: 52px;"></a>
                        <div class="top-shadow">&nbsp;</div>
                        <div class="card-body">
                            <a href="<?=$lang_link_prefix?>/auth/page/login"><?=$t->get('login');?></a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
