<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-{{ cbLang('right') }} hidden-xs">
        {{ cbLang('powered_by') }} {{Session::get('appname')}}
    </div>
    <!-- Default to the left -->
    <strong>{{ cbLang('copyright') }} &copy; <?php echo date('Y') ?>. {{ cbLang('all_rights_reserved') }} .</strong>
    <script type="text/javascript">
      //Take the time
      if( '{{DB::table(config('crudbooster.USER_TABLE'))->where('id',CRUDBooster::myId())->first()->theme}}' == 'dark'){
        put_night_mode()
      }
      //Mode Light
      function put_light_mode() {
        $('body').removeClass('skin-black');
        $('body').addClass('skin-black-light');
        $('.content-wrapper').css( "background-color","#ecf0f5" );
        $('.content-wrapper').css( "color","#000000" );

        // change of tex color when switch
        $('.content-wrapper .breadcrumb *').css("color","black");
        $('.content-wrapper .form-dark label').css("color","black");
        $('.content-wrapper .table-bordered th').css("color","black");

        $('#icone-mode').removeClass('fa fa-sun-o');
        $('#icone-mode').addClass('fa fa-moon-o');
        $.ajax({
          method: "POST",
          url:"{{ $env.'/api/get-token' }}",
          data: { secret: "285eaae05e11847aec9f526c4c27e2c2"},
          success: function(response){
            $.ajax({
                method: "POST",
                headers: {"Authorization": response.data.access_token},
                url: "{{ $env.'/api/change_theme' }}",
                data: { id: "{{CRUDBooster::myId()}}", theme: "light" }
            })
          }
        })
      }
      //Mode Night
      function put_night_mode() {
        $('body').removeClass('skin-black-light');
        $('body').addClass('skin-black');

        $('.content-wrapper').css("background-color","#242d31");
        $('.content-wrapper').css("color","white");
        $('.content-wrapper .breadcrumb *').css("color","white");
        // change text color when background is still light (tables, forms)
        $('.content-wrapper .table').css("color","black");
        $('.content-wrapper .table-dark th').css("color","white");
        $('.content-wrapper label').css("color","black");
        $('.content-wrapper .form_all').css("color","black");
        $('.content-wrapper .form-report').css("color","black");
        $('.content-wrapper .form-dark label').css("color","white");
        $('.content-wrapper .modal').css("color","black");
        
        $('#icone-mode').removeClass('fa fa-moon-o');
        $('#icone-mode').addClass('fa fa-sun-o');
        $.ajax({
          method: "POST",
          url:"{{ $env.'/api/get-token' }}",
          data: { secret: "285eaae05e11847aec9f526c4c27e2c2"},
          success: function(response){
            $.ajax({
                method: "POST",
                headers: {"Authorization": response.data.access_token},
                url: "{{ $env.'/api/change_theme' }}",
                data: { id: "{{CRUDBooster::myId()}}", theme: "dark" }
            })
          }
        })
      }
      //Check the cookie and apply night mode if dark
        

      //Click on the button
      $('#mode').on('click', function(event){
        event.stopPropagation();

        if($('#icone-mode').hasClass('fa fa-sun-o')){
          put_light_mode();
        } else {
          put_night_mode();
        }
      });
    </script>
</footer>
