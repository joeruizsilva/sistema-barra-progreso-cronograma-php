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
              
              // Actualiza la tabla y las barras de progreso
              updateProgressTable(data);
          }
      });
  }

  function updateProgressTable(data) {
      var tableBody = $('#progressTable tbody');
      tableBody.empty();
      
      data.forEach(function(item) {
          var progressBar = `<div class="progress">
                                  <div class="progress-bar" role="progressbar" style="width: ${item.progress}%" aria-valuenow="${item.progress}" aria-valuemin="0" aria-valuemax="100">${item.progress}%</div>
                             </div>`;
          var row = `<tr>
                         <td>${item.id}</td>
                         <td>${item.task}</td>
                         <td>${progressBar}</td>
                     </tr>`;
          tableBody.append(row);
      });
  }

  loadProgressData();
});