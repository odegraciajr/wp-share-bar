(function($){
  $('a.social_btn.facebook').click(function(e){
    e.preventDefault();
    var url = $(this).data('permalink');
    window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(url),'facebook-share-dialog',"width=626,height=436");
  });

  $('a.social_btn.email').click(function(e){
    e.preventDefault();
    var title = $(this).attr('title');;
    var body = $(this).data('permalink');
    window.open('mailto:?subject='+encodeURIComponent(title)+'&body='+encodeURIComponent(body));
  });
  $('a.social_btn.print').click(function(e){
    e.preventDefault();
    window.print();
  });
  /*
  $fb = document.querySelector('a.social_btn.facebook');
  $fb.onclick = function(event){
    var url = $fb.getAttribute('data-permalink');
    window.open('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(url),'facebook-share-dialog',"width=626,height=436");
    event.preventDefault();
  }
  $email = document.querySelector('a.social_btn.email');
  $email.onclick = function(event){
    event.preventDefault();
    var title = $email.getAttribute('title');;
    var body = $email.getAttribute('data-permalink');
    window.open('mailto:?subject='+encodeURIComponent(title)+'&body='+encodeURIComponent(body));
  }

  $print = document.querySelector('a.social_btn.print');
  $print.onclick = function(event){
    event.preventDefault();
    window.print();
  }*/
  twttr.events.bind('tweet', function(){});
  var $fb_count_link = $('a.social_btn.facebook').data('permalink');
  $.getJSON( 'http://graph.facebook.com/?ids=' + $fb_count_link, function( data ) {
    if(typeof data[$fb_count_link]['shares'] !== 'undefined'){
      $('a.social_btn.facebook').text(data[$fb_count_link].shares);
    }
  });
})(jQuery)
