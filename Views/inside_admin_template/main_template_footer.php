
<!-- Scripts Libraries-->
<script src="/Public/InsideAdmin/inside_admin_template/js/jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="/Public/InsideAdmin/inside_admin_template/js/bootstrap-3.3.7/js/bootstrap.js"></script>
<script src="/Public/InsideAdmin/inside_admin_template/js/bootstrap-select-1.12.2/bootstrap-select.js"></script>

<script src="/Public/InsideAdmin/inside_admin_template/inside/js/bootstrap-typeahead.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.js"></script>

<script src="/Public/InsideAdmin/inside_admin_template/inside/js/jquery.form.js"></script>
<script src="/Public/InsideAdmin/inside_admin_template/inside/js/inside_framework.js"></script>

<script type="text/javascript" src="/Public/InsideAdmin/inside_admin_template/inside/js/notify.js"></script>

<!-- Custom Scripts files -->
<script src="/Public/InsideAdmin/inside_admin_template/js/scripts.js" type="text/javascript"></script>

<script src="/Public/InsideAdmin/inside_admin_template/vendor/bootstrap-datepicker-1.9.0-dist/js/bootstrap-datepicker.js"></script>
<script>

    $.ajaxSetup({
        async: false,
        headers: {
            'x-request-type': 'AJAX_API',
            'x-csrf-token': '<?=$inside4_security->make_csfr_token($user['id'])?>',
        }
    });

    $( document ).ready(function() {

        // Sidebar Menu
        var toggle = true;
        $(".sidebar-icon").on( "click", function() {
            $(".sidebar-icon .fa").toggleClass("fa fa-times fa fa-bars");
            if (toggle){
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                $("#menu span").css({"position":"relative"});
                $(".submenu_icon").removeClass("fa-angle-up").addClass("fa-angle-down");
            }else{
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $(".submenu").slideUp();
                $(".submenu_icon").removeClass("fa-angle-up").addClass("fa-angle-down");
                setTimeout(function() {
                    $("#menu span").css({"position":"absolute"});
                }, 400);
            }
            toggle = !toggle;
        });

        $(".submenu_toggle").on( "click", function() {
            $(this).parent().next().slideToggle();
            $(this).parent().find(".submenu_icon").toggleClass("fa-angle-up fa-angle-down");

        });

        // Advanced filters
        $(".filters_button, .filters_button_erp").on( "click", function() {
            $(".advanced_filters").slideToggle("fast").toggleClass("overflow_hide");
        });

        $(".advanced_filters .cloce_btn").on( "click", function() {
            $(".advanced_filters").slideUp("fast");
            $('body').removeClass('overflow_hide');
        });

        $(".mob_filrer_btn").on( "click", function() {
            $('.mob_dropdown_box').trigger('click');
            $(".advanced_filters").slideDown("fast");
            $('body').addClass('overflow_hide');
        });

        // Datepicker
        $(".has_datepicker").datepicker();

        $("#menu input.menu_search").on("keyup", function(e){

            if (e.keyCode == 13) {
                return false;
            }

            var that = $(this);
            var id = 'menu_search';
            var query = $(this).val();
            // Box Shadow
            var blue = 'inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6)';
            var red = 'inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(208,28,28,.6)';

            if (pdg_timer[id] !== undefined) clearTimeout(pdg_timer[id]);
            pdg_timer[id]=setTimeout(function(){

                if(query != '') {
                    $.get("/admin/ajax/menu_search/?query=" + encodeURI(query), function (data) {

                        var obj = $.parseJSON(data);

                        // alert(obj.html);
                        if (obj.html !== '') {
                            that.css('box-shadow', blue);
                            $('.inside_menu_search').html(obj.html);
                            $('.inside_menu').hide();
                        } else {

                            if (query != '') {
                                that.css('box-shadow', red);
                            } else {
                                that.css('box-shadow', blue);
                            }

                            $('.inside_menu_search').html('');
                            $('.inside_menu').show();
                        }
                    });
                } else {
                    $('.inside_menu_search').remove();
                    $('.inside_menu').show();
                    that.css('box-shadow', blue);
                }

            },700);
        });

        $('header input.top_search').typeahead({
            ajax: '/admin/ajax/menu_search_type/',
            displayField: 'name',
            valueField: 'id',
            onSelect: function(data){
                dump_alert(data);
                //location.href = data.value;

            }
        });

    });
</script>

<script>

    // Debug Function
    function dump_alert(obj) {
        var out = "";
        if(obj && typeof(obj) == "object"){
            for (var i in obj) {
                out += i + ": " + obj[i] + "\n";
            }
        } else {
            out = obj;
        }
        alert(out);
    };

</script>