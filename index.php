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
                    <a href="#" class="navbar-brand d-flex align-items-center">
                        <strong>Tareas</strong>
                    </a>
                </div>
            </div>
        </header>

        <main role="main">
            <section class="jumbotron text-center">
                <div class="container">
                    <h1 class="jumbotron-heading">Usuarios</h1>
                    <p>
                        <a href="#" class="btn btn-secondary my-2" data-toggle="modal" data-target="#modalUser">Crear usuario</a>
                    </p>
                </div>
            </section>

            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <?php while ($row = mysqli_fetch_object($users)) { ?>
                            <div class="col-md-4">
                                <div class="card mb-4 shadow-sm">
                                    <div class="card-body">
                                        <p class="card-text"><?php echo $row->name; ?> <a href="index.php?action=delete_user&user=<?php echo $row->id; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <button onclick="window.location='tareas.php?user=<?php echo $row->id; ?>'" type="button" class="btn btn-sm btn-outline-secondary">Acceder a Tareas</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>

        <div class="modal fade" tabindex="-1" role="dialog" id="modalUser">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Nombre</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Default" aria-describedby="inputGroup-sizing-default" name="user" id="user">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="saveUser()">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

        <?php require('./footer.php'); ?>

        <script type="text/javascript">
            function saveUser() {
                window.location = 'index.php?action=insert_user&name=' + $('#user').val();
            };
        </script>
    </body>
</html>