$(document).ready(function() {
    $('#progressForm').submit(function(event) {
      event.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        type: 'POST',
        url: 'save_progress.php',
        data: formData,
        success: function(response) {
          $('#progressForm')[0].reset();
          loadProgressData();
        }
      });
    });
    
    function loadProgressData() {
      $.ajax({
        type: 'GET',
        url: 'get_progress.php',
        success: function(response) {
          var data = JSON.parse(response);
          $('#progressTable').DataTable().clear().rows.add(data).draw();
        }
      });
    }
    
    loadProgressData();
  });