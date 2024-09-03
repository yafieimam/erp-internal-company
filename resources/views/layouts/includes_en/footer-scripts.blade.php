<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script type="text/javascript" src="{{asset('app-assets/asset/js/jquery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
   
@yield('footer-script')

<script src="{{asset('app-assets/asset/js/all.js')}}"></script>
<!-- <script src="/asset/js/jquery.min3.3.1.js"></script> -->

<!-- <script type="text/javascript" src="{{asset('app-assets/assets/ee2585be/jquery.js')}}"></script> -->
<!-- <script type="text/javascript" src="{{asset('app-assets/assets/ee2585be/jui/js/jquery-ui.min.js')}}"></script> -->
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script type="text/javascript">
  $(function(){

  var sn_width = $(window).width();
  if (sn_width > 1150) {

      $(window).scroll(function(){
        var sntop1 = $(window).scrollTop();

        if(sntop1 > 115){
          // console.log(sntop1);
          $('.header-affixs').removeClass('affix-top').addClass('affix');
          // $('.header-affixs').addClass('affix');
        }else{
          $('.header-affixs.affix').removeClass('affix').addClass('affix-top');
          // $('body').css('padding-top', '0px');
        }
      });

    }

  });
</script>

<script>
  	$(document).ready(function(){
    	$("#headerproduct").css("display","none");
  	});
</script>

<script type="text/javascript">
    $(document).ready(function(){
        
        // if ($(window).width() >= 768) {
        //     var $item = $('#myCarousel_home.carousel .carousel-item'); 
        //     var $wHeight = $(window).height();
        //     $item.eq(0).addClass('active');
        //     $item.height($wHeight); 
        //     $item.addClass('full-screen');

        //     $('#myCarousel_home.carousel img.d-none.d-sm-block').each(function() {
        //       var $src = $(this).attr('src');
        //       var $color = $(this).attr('data-color');
        //       $(this).parent().css({
        //         'background-image' : 'url(' + $src + ')',
        //         'background-color' : $color
        //       });
        //       $(this).remove();
        //     });

        //     $(window).on('resize', function (){
        //       $wHeight = $(window).height();
        //       $item.height($wHeight);
        //     });

        //     $('#myCarousel_home.carousel').carousel({
        //       interval: 4000,
        //       pause: "false"
        //     });
        // }

    });
</script>

<script type="text/javascript">
    $(document).ready(function (){
        $("#click").click(function (){
            $('html, body').animate({
                scrollTop: $("#div1").offset().top
            }, 2000);
        });
    });
</script>

    
<!-- <div id="back-top" class="t-backtop " style="display:none">
<div class="clear height-5"></div>
    <a href="#top"><i class="fa fa-chevron-up"></i></a>
</div> -->
<script type="text/javascript">
	$(window).load(function(){
		$('.t-backtop').hide();
	});
    $(function(){
        $('.t-backtop').click(function () {
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        var $win = $(window);
        $win.scroll(function () {
            if ($win.scrollTop() == 0)
                $('.t-backtop').hide();
            else if ($win.height() + $win.scrollTop() != $(document).height() || $win.height() + $win.scrollTop() > 500) {
                $('.t-backtop').show();
            }
        });

        // $('.toscroll').click(function() {
        //     var sn_id = $(this).attr('data-id');
        //     $('html, body').animate({
        //         scrollTop: $("#"+ sn_id).offset().top - 60
        //     }, 1000);
        // });
    });
</script>

<script type="text/javascript">
    // $(function(){
    //         // pindah tulisan hero image
    //         var fullw = $(window).width();
    //         if (fullw <= 767) {
    //             $('.tops-cont-insidepage .insd-container.content-up').addClass('hidden-xs');
    //             var copyText = $('.tops-cont-insidepage .insd-container.content-up').html();
    //             $( ".outer-inside-middle-content.back-white .tops-cont-insidepage" ).after( "<div class='pindahan_text-heroimage'></div>" );
    //             $('.pindahan_text-heroimage').append(copyText);
    //         };
    // });       
</script>
<script src="{{asset('app-assets/asset/js/wow.js')}}"></script>
<script type="text/javascript">
    new WOW().init();
</script>

@yield('script_login')