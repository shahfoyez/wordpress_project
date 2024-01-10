
jQuery(document).ready(function($){


    $('#gift_course_button').on('click',function(){
      // Toggle gift form on click of gift course button
      if($(this).hasClass('active')){
        $(this).removeClass('active');
        $('#gift_course_form').remove();
        return;
      }

      // Get Gift form data
      $('#gift_course_button').addClass('active');
      var from_email_label = $(this).attr('data-from');
      var gift_email_label = $(this).attr('data-to');
      var gift_message_label = $(this).attr('data-message');
      var free_gift = $(this).attr('data-free');
      var gift_variation_label = $(this).attr('data-variation');
      var gift_button_label = $(this).attr('data-button');

      var cart_url = $(this).parent().find('#gift_course_cart_url').val();
      var from_mail = '';

      /* Create gift course form */

      if(wplms_gift_course_js.hasOwnProperty("user_email")){
        from_mail = wplms_gift_course_js.user_email;
      }
      // Free gift form
      if(free_gift){
        $(this).after('<form id="gift_course_form" method="get"><input type="email" class="gift_from" name="gift_from" placeholder="'+from_email_label+'" value="'+from_mail+'" required><input type="email" class="gift_email" name="gift_email" placeholder="'+gift_email_label+'" required><textarea name="gift_message" class="gift_message" placeholder="'+gift_message_label+'"></textarea><a id="send_free_gift_form" class="button disabled">'+gift_button_label+'</a></form>');
        $('body').trigger('free_gift_dom_active');
      }
      // Normal product gift course Form
      else if(typeof gift_variation_label == 'undefined'){

        $(this).after('<form id="gift_course_form" method="get" action="'+cart_url+'">'+$('#move_field_c').html()+'<input type="email" class="gift_from form_field" name="gift_from" placeholder="'+from_email_label+'" value="'+from_mail+'" required><input type="email" name="gift_email" class="form_field" placeholder="'+gift_email_label+'" required><textarea name="gift_message" class="form_field" placeholder="'+gift_message_label+'"></textarea><input type="submit" id="send_gift_form" class="button full" value="'+gift_button_label+'"/></form>');
      }
      // Variable product gift course form
      else{
        var dom = '<form id="gift_course_form" method="get" action="'+cart_url+'">'+
        $('#move_field_c').html()
        +'<input type="email" class="gift_from form_field" name="gift_from" value="'+from_mail+'" placeholder="'+from_email_label+'" required>'
        +'<input type="email" name="gift_email" class="gift_email form_field" placeholder="'+gift_email_label+'" required>'
        +'<textarea name="gift_message" class="gift_message form_field" placeholder="'+gift_message_label+'"></textarea>'
        +'<a class="button disabled full" id="gift_variation_selection">'+gift_variation_label+'</a>'
        +'</form>';
        $(this).after(dom);
        //Trigger custom event
        $('body').trigger('gift_dom_active');
      }

    });

     $('body').on('gift_dom_active',function(){

        jQuery('body').delegate("#gift_variation_selection","click",function(){
          
          var $this = $('#gift_variation_selection');
          // Check for valid email address entered
          var gift_from = jQuery(".gift_from").val();
          var gift_email = jQuery(".gift_email").val();
          var regex = /^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/i;

          if(!gift_from.match(regex)){
            jQuery("#gift_variation_selection").addClass("disabled");
          }else{
            jQuery("#gift_variation_selection").removeClass("disabled");
          }
          if(!gift_email.match(regex)){
            jQuery("#gift_variation_selection").addClass("disabled");
          }else{
            jQuery("#gift_variation_selection").removeClass("disabled");
          }

          // return if disabled class 
          if( $this.hasClass('disabled')){
            var deftxt = $this.text();
            $this.text(wplms_gift_course_js.email_missing);
            setTimeout(function(){ $this.text(deftxt);},2000);
            return;
          }

          var gift_message = jQuery(".gift_message").val();
          var button_label;
          var href;
          var hrefs = [];
          var i = 0;

          $('#variations_popup .button').each(function(){
            //change gift button text
            button_label = $(this).text();
            $(this).text(wplms_gift_course_js.gift_button_label);
            href = $(this).attr('href');
            hrefs.push(href);
            //change gift url
            $(this).attr('href',href+'&gift_from='+gift_from+'&gift_email='+gift_email+'&git_message='+gift_message);
          });
          
          //change variable button url in popup if popup is closed
          jQuery(".course_button").trigger("click");
          $('.course_button').on('mfpClose', function(e) {
            $('#variations_popup .button').each(function(){
              $(this).text(button_label);
              $(this).attr('href',hrefs[i]);
              i++;
            });
          });

        });
        
    });

    $('body').on('free_gift_dom_active',function(){

      jQuery('#send_free_gift_form').on('click',function(){
        // Check for valid email address entered
        var gift_from = jQuery(".gift_from").val();
        var gift_email = jQuery(".gift_email").val();
        var regex = /^([a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}$)/i;

        if(!gift_from.match(regex)){
          jQuery(this).addClass("disabled");
        }else{
          jQuery(this).removeClass("disabled");
        }
        if(!gift_email.match(regex)){
          jQuery(this).addClass("disabled");
        }else{
          jQuery(this).removeClass("disabled");
        }

        // return if disabled class
        $this = jQuery(this);
        if( $this.hasClass('disabled')){
          var deftxt = $this.text();
          $this.text(wplms_gift_course_js.email_missing);
          setTimeout(function(){ $this.text(deftxt);},2000);
          return;
        }

        var gift_message = jQuery(".gift_message").val();
        var course_id = jQuery('body').find('#gift_course_id').val();
        // Ajax call for sending email
          jQuery.ajax({
            type: "POST",
            url: ajaxurl,
            dataType:'json',
            data: { action: 'send_free_gift_email',
                    course: course_id,
                    gift_from: gift_from,
                    gift_email: gift_email,
                    gift_message: gift_message,
                  },
            cache: false,
            success: function (json) {
              $this.after('<li class="success_message" style="color:#79c989;font-size:14px;">'+json.success_message+'</li>');
            }
          });

      });

    });

});
