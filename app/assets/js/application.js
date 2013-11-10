$(document).ready(function() {
  $('*[data-toggle="tooltip"]').tooltip()
  data_confirm();
  toogle_contact_message();
});

/*** Confirm dialog **/
var data_confirm = function () {
   $('a[data-confirm], button[data-confirm]').click( function () {
      var msg = $(this).data('confirm');
      return confirm(msg);
   });
};

/*
 * Show and hide contact message **/
var toogle_contact_message = function () {
   $('#contacts table tbody a').on('click', function (event) {
      event.preventDefault();
      var id = $(this).attr('href');
      var msg = $(this).closest('tbody').find(id);
      msg.toggleClass('hidden');
   });
};

jQuery(function() {
  new datepickr('checkin');
});
jQuery(function() {
  new datepickr('checkout');
});
