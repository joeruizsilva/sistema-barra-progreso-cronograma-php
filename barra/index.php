<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Progreso de Personas</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Formulario de Progreso</h1>
    <button id="addBtn">Añadir</button>
    <h2>Lista de Personas</h2>
    <table id="personasTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Progreso</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <!-- Modal para añadir -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Añadir Persona</h2>
            <form id="addForm">
                Nombre: <input type="text" id="nombre" name="nombre" required><br>
                Fecha Inicio: <input type="date" id="fecha_inicio" name="fecha_inicio" required><br>
                Fecha Fin: <input type="date" id="fecha_fin" name="fecha_fin" required><br>
                <button type="submit">Agregar</button>
            </form>
        </div>
    </div>

    <!-- Modal para editar -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Persona</h2>
            <form id="editForm">
                <input type="hidden" id="edit_id" name="id">
                Nombre: <input type="text" id="edit_nombre" name="nombre" required><br>
                Fecha Inicio: <input type="date" id="edit_fecha_inicio" name="fecha_inicio" required><br>
                Fecha Fin: <input type="date" id="edit_fecha_fin" name="fecha_fin" required><br>
                <button type="submit">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#personasTable').DataTable({
                ajax: {
                    url: 'fetch.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var startDate = new Date(row.fecha_inicio);
                            var endDate = new Date(row.fecha_fin);
                            var today = new Date();
                            var progress = Math.min(100, Math.max(0, ((today - startDate) / (endDate - startDate)) * 100));
                            var progressColor = progress >= 100 ? 'red' : 'green';

                            return `
                                <div class="progress-bar">
                                    <div class="progress-bar-fill" style="width: ${progress}%; background-color: ${progressColor};"></div>
                                </div>
                            `;
                        }
                    },
                    { data: 'fecha_inicio' },
                    { data: 'fecha_fin' },

                    

                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <button class="editBtn" data-id="${row.id}" data-nombre="${row.nombre}" data-fecha_inicio="${row.fecha_inicio}" data-fecha_fin="${row.fecha_fin}">Editar</button>
                                <button class="deleteBtn" data-id="${row.id}">Eliminar</button>
                            `;
                        }
                    }
                ]
            });

            $('#addForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'insert.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        table.ajax.reload();
                        $('#addModal').hide();
                        $('#addForm')[0].reset();
                    }
                });
            });

            $('#personasTable tbody').on('click', '.deleteBtn', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: 'delete.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        table.ajax.reload();
                    }
                });
            });

            // Edit functionality
            var editModal = document.getElementById("editModal");
            var editSpan = editModal.getElementsByClassName("close")[0];

            $('#personasTable tbody').on('click', '.editBtn', function() {
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var fecha_inicio = $(this).data('fecha_inicio');
                var fecha_fin = $(this).data('fecha_fin');

                $('#edit_id').val(id);
                $('#edit_nombre').val(nombre);
                $('#edit_fecha_inicio').val(fecha_inicio);
                $('#edit_fecha_fin').val(fecha_fin);

                editModal.style.display = "block";
            });

            editSpan.onclick = function() {
                editModal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == editModal) {
                    editModal.style.display = "none";
                }
            }

            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        editModal.style.display = "none";
                        table.ajax.reload();
                    }
                });
            });

            // Add functionality
            var addModal = document.getElementById("addModal");
            var addSpan = addModal.getElementsByClassName("close")[0];

            document.getElementById("addBtn").onclick = function() {
                addModal.style.display = "block";
            }

            addSpan.onclick = function() {
                addModal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == addModal) {
                    addModal.style.display = "none";
                }
            }
        });
    </script>
</body>
</html>