CRM.$(function ($) {
   // Position to revert buttons.
  $('#revertButtons').insertAfter('.crm-contact-tabs-list');
  // Get the entity to revert from the button clicked.
  $(document).on('click', '.revertOne', function () {
    entity = this.getAttribute('entity');
    url = document.URL;
    params = new URLSearchParams(url);
    contact_id = params.get('cid');
    if (this.innerHTML == 'Revert all') {
      entity = ['email', 'phone', 'address'];
    }
    executeRevert(entity, contact_id);
  });

});

function executeRevert(entity, contact_id) {
  CRM.api3('ContactInfo', 'revertdata', {
    "contact_id": contact_id,
    "entity": entity
  })
  .done(function (result) {
    if (result.is_error) {
      CRM.alert(result.error_message, "", "error");
    }
    else {
      CRM.alert(result.values, "", "success");
    }
  });
}
