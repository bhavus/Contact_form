jQuery(document).ready(function($) {

  $('form').submit(function(event) {
      //clear any errors and the red inout boxes (bootstrap feature)
      $('.form-group').removeClass('has-error');
      $('.help-block').remove();
      // get the form data to be submitted
      var formData = {
          'name'              : $('input[name=name]').val(),
          'email'             : $('input[name=email]').val(),
          'subject'           : $('input[name=subject]').val(),
          'message'           : $('textarea#message').val(),
          'captcha'           :  $('#g-recaptcha-response').val(),
          'admin_email'       : $('input[name=admin_email]').val(),
      };
      //post using jquery ajax
      $.ajax({
          type        : 'POST',
          url         : $('input[name=url]').val(),
          data        : formData,
          dataType    : 'json',
          encode          : true
      })

      .done(function(response) {

          if ( ! response.success) {
              //append error messages from the PHP script and highting their respective input boxes (bootstrap feature)
              if (response.errors.name) {
                  $('#name-group').addClass('has-error');
                  $('#name-group').append('<div class="help-block">' + data.errors.name + '</div>');
                }
              if (response.errors.email) {
                  $('#email-group').addClass('has-error');
                  $('#email-group').append('<div class="help-block">' + data.errors.email + '</div>');
                }
              if (response.errors.subject) {
                  $('#subject-group').addClass('has-error');
                  $('#subject-group').append('<div class="help-block">' + data.errors.subject + '</div>');
                }
              if (response.errors.message) {
                  $('#message-group').addClass('has-error');
                  $('#message-group').append('<div class="help-block">' + data.errors.message + '</div>');
                }
              if (response.errors.captcha) {
                  $('#captcha-group').addClass('has-error');
                  $('#captcha-group').append('<div class="help-block">' + data.errors.captcha + '</div>');
              }


          }
          //if we are here, it means the message was posted successfully
          else {
            $('#contact_form').append('<p><div class="alert alert-success">' + data.message + '</div>');
          }
      });
      //prevent the form from posting using the usual method
      event.preventDefault();
  });

});
