CRM.$(function ($) {
   // Position to revert buttons.
   changeLogTab = document.querySelector('[title="Change Log"]');
   tabID = changeLogTab.getAttribute('id');
   ariaLabel = document.querySelector(`div[aria-labelledby=${tabID}]`);
   changeLogDiv = ariaLabel.getAttribute('id');
   $(changeLogDiv).prepend($('#revertButtons'));
  // Get the entity to revert from the button clicked.
  $(document).on('click', '.revertOne', function () {
    entity = this.getAttribute('entity');
    url = document.URL;
    params = new URLSearchParams(url);
    contact_id = params.get('cid');
    if (this.innerHTML == 'Revert all three') {
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
