<?php

require('./app_top.php');

?>
<html>
    <head>
        <?php require('./header.php'); ?>
    </head>

    <body>
        <header>
            <div class="navbar navbar-dark bg-dark shadow-sm">
                <div class="container d-flex justify-content-between">
                    <a href="index.php" class="navbar-brand d-flex align-items-center">
                        <strong>Tareas</strong>
                    </a>
                </div>
            </div>
        </header>

        <main role="main">
            <section class="jumbotron text-center">
                <div class="container">
                    <h1 class="jumbotron-heading">Tareas de <?php echo $userName; ?></h1>
                    <input type="hidden" id="year" value="<?php echo $year; ?>" />
                    <p>
                        <select class="form-control" id="yearSelector" onchange="selectYear(this.value)">
                            <?php for ($i = ($currentYear - 5); $i < ($currentYear + 5); $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($year == $i) { ?>selected="selected"<?php } ?>><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>
                        <select class="form-control" id="weekSelector" onchange="selectWeek(this.value)">
                            <?php for ($i = 1; $i < 54; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php if ($week == $i) { ?>selected="selected"<?php } ?>><?php echo getWeekValues($i, $year); ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>
                        <a href="javascript:createTarea();" class="btn btn-secondary my-2">Crear Tarea</a>
                    </p>
                </div>
            </section>

            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <div class="table-responsive-xl">
                            <table class="table" id="table_data">
                                <thead>
                                    <tr>
                                        <th scope="col">DIA</th>
                                        <th scope="col">(TAREA PADRE EN JIRA): GESTIC</th>
                                        <th scope="col">DESCRIPCION DEL GESTIC</th>
                                        <th scope="col">DESCRIPCION DE LA TAREA</th>
                                        <th scope="col">(TAREA/S HIJA): GESER/GAA/GAB/EFOR</th>
                                        <th scope="col">TIPO DE HORA</th>
                                        <th scope="col">HORAS IMPUTADAS (<?php echo $totalHours; ?>)</th>
                                        <th scope="col">ESTADO</th>
                                        <th scope="col">% DE EJECUCIÓN DE LA TAREA</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($arrayTareas as $row) { ?>
                                        <tr>
                                            <td><?php echo $row['day']; ?></td>
                                            <td><?php echo $row['gestic']; ?></td>
                                            <td><?php echo $row['description_gestic']; ?></td>
                                            <td><?php echo $row['description']; ?></td>
                                            <td><?php echo $row['tarea']; ?></td>
                                            <td><?php echo $row['hour_type']; ?></td>
                                            <td><?php echo $row['hours']; ?></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td><?php echo $row['percent']; ?></td>
                                            <td>
                                                <a href="javascript:confirmDelete(<?php echo $row['id']; ?>);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                <a href="javascript:editTarea(<?php echo $row['id']; ?>);"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalTarea">
            <input type="hidden" id="tarea_id" value="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_title">Crear tarea</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <select class="form-control" id="day">
                                <option value="LUNES">LUNES</option>
                                <option value="MARTES">MARTES</option>
                                <option value="MIERCOLES">MIERCOLES</option>
                                <option value="JUEVES">JUEVES</option>
                                <option value="VIERNES">VIERNES</option>
                                <option value="SABADO">SABADO</option>
                                <option value="DOMINGO">DOMINGO</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">GESTIC</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="gestic">
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">GESTIC Desc.</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="gestic_description">
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Desc.</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="description">
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">GESER/GAA/GAB/EFOR</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" id="tarea">
                        </div>

                        <div class="input-group mb-3">
                            <select class="form-control" id="hour_type">
                                <option value="HORAS NORMALES">HORAS NORMALES</option>
                                <option value="HORAS RECUPERABLES">HORAS RECUPERABLES</option>
                                <option value="HORAS BAJA LABORAL">HORAS BAJA LABORAL</option>
                                <option value="HORAS BOMBERO">HORAS BOMBERO</option>
                                <option value="VACACIONES">VACACIONES</option>
                                <option value="HORAS AUSENCIA">HORAS AUSENCIA</option>
                                <option value="HORAS AUSENCIA MEDICA">HORAS AUSENCIA MEDICA</option>
                                <option value="ASUNTOS PROPIOS">ASUNTOS PROPIOS</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <select class="form-control" id="hours">
                                <option value="0.5">0.5h</option>
                                <option value="1">1h</option>
                                <option value="1.5">1.5h</option>
                                <option value="2">2h</option>
                                <option value="2.5">2.5h</option>
                                <option value="3">3h</option>
                                <option value="3.5">3.5h</option>
                                <option value="4">4h</option>
                                <option value="4.5">4.5h</option>
                                <option value="5">5h</option>
                                <option value="5.5">5.5h</option>
                                <option value="6">6h</option>
                                <option value="6.5">6.5h</option>
                                <option value="7">7h</option>
                                <option value="7.5">7.5h</option>
                                <option value="8">8h</option>
                                <option value="8.5">8.5h</option>
                                <option value="9">9h</option>
                                <option value="9.5">9.5h</option>
                                <option value="10">10h</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <select class="form-control" id="status">
                                <option value="EN PROCESO">EN PROCESO</option>
                                <option value="EN ESPERA">EN ESPERA</option>
                                <option value="RESUELTO">RESUELTO</option>
                                <option value="EN DECISION">EN DECISION</option>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <select class="form-control" id="percent">
                                <option value="10">10 %</option>
                                <option value="20">20 %</option>
                                <option value="30">30 %</option>
                                <option value="40">40 %</option>
                                <option value="50">50 %</option>
                                <option value="60">60 %</option>
                                <option value="70">70 %</option>
                                <option value="80">80 %</option>
                                <option value="90">90 %</option>
                                <option value="100">100 %</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="save_button" class="btn btn-primary" onclick="saveTarea()">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require('./footer.php'); ?>

        <script type="text/javascript">
            function createTarea() {
                $('#modal_title').text('Crear Tarea');
                $("#save_button").attr("onclick","saveTarea()");
                $('#modalTarea').modal('show');
            }
            function saveTarea() {
                window.location = 'index.php?action=insert_tarea&day=' + $('#day').val() + '&gestic=' + $('#gestic').val() + '&gestic_description=' + $('#gestic_description').val() + '&description=' + $('#description').val() + '&tarea=' + $('#tarea').val() + '&hour_type=' + $('#hour_type').val() + '&hours=' + $('#hours').val() + '&status=' + $('#status').val() + '&percent=' + $('#percent').val() + '&id_user=<?php echo $userId; ?>' + '&week=' + $('#weekSelector').val() + '&year=' + $('#year').val();
            };
            function saveEditTarea() {
                window.location = 'index.php?action=edit_tarea&day=' + $('#day').val() + '&gestic=' + $('#gestic').val() + '&gestic_description=' + $('#gestic_description').val() + '&description=' + $('#description').val() + '&tarea=' + $('#tarea').val() + '&hour_type=' + $('#hour_type').val() + '&hours=' + $('#hours').val() + '&status=' + $('#status').val() + '&percent=' + $('#percent').val() + '&tarea_id=' + $('#tarea_id').val() + '&week=' + $('#weekSelector').val() + '&year=' + $('#year').val();
            }
            function selectYear(year) {
                window.location = 'tareas.php?user=<?php echo $userId; ?>&year=' + $('#yearSelector').val() + '&week=1';
            }
            function selectWeek(year) {
                window.location = 'tareas.php?user=<?php echo $userId; ?>&year=' + $('#yearSelector').val() + '&week=' + $('#weekSelector').val();
            }
            function confirmDelete(id) {
                if (confirm('Confirma el borrado.')) {
                    window.location = 'index.php?action=delete_tarea&tarea=' + id;
                }
            }
            function editTarea(id) {
                $.ajax({
                    method: 'POST',
                    url: 'index.php?action=get_tarea',
                    data: {
                        'id': id
                    },
                    success: function (data) {
                        var obj = JSON.parse(data);
                        $('#tarea_id').val(obj.id);
                        $('#day').val(obj.day);
                        $('#description').val(obj.description);
                        $('#gestic_description').val(obj.description_gestic);
                        $('#gestic').val(obj.gestic);
                        $('#hour_type').val(obj.hour_type);
                        $('#hours').val(obj.hours);
                        $('#percent').val(obj.percent);
                        $('#status').val(obj.status);
                        $('#tarea').val(obj.tarea);
                        $('#modal_title').text('Editar Tarea');
                        $("#save_button").attr("onclick","saveEditTarea()");
                        $('#modalTarea').modal('show');
                    }
                });
            }

            $(document).ready(function () {
                $('#table_data').dataTable(
                    {
                        "bSort": false,
                        "bInfo" : false,
                        "bFilter": false,
                        "paging": false,
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'csv', 'excel', 'pdf'
                        ]
                    }
                );
            });
        </script>
    </body>
</html>