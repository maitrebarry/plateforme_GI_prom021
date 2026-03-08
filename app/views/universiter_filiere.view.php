<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Dashboard administrateur']); ?>


<body>
    <?php $this->view('Partials/global-shell'); ?>
    <?php $this->view('Partials/mobile-menu'); ?>

    <section class="dashboard">
        <div class="dashboard__inner d-flex">
            <?php $this->view('Partials/dashboard-sidebar'); ?>
            <div class="dashboard-body">
                <?php $this->view('Partials/dashboard-nav'); ?>
                <?php $this->view('Partials/alerts', ['flashMessages' => $flashMessages ?? [], 'notifications' => $notifications ?? []]); ?>

              <div class="dashboard-body__content p-4">
                 <?php $this->view("set_flash"); ?>

    <div class="row">

        <!-- Ajouter Université -->
        <div class="col-md-6">

            <div class="card">

                <div class="card-header">
                    <h5>Ajouter Université</h5>
                </div>

                <div class="card-body">

                    <form method="post">

                        <div class="mb-3">

                            <label>Nom université</label>

                            <input type="text" name="nom_universite" class="form-control" required>

                        </div>

                        <button type="submit" name="save_universite" class="btn btn-primary">

                            Ajouter Université

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Ajouter Filière -->
        <div class="col-md-6">

            <div class="card">

                <div class="card-header">
                    <h5>Ajouter Filière</h5>
                </div>

                <div class="card-body">

                    <form method="post">

                        <div class="mb-3">

                            <label>Université</label>

                            <select name="universite_id" class="form-control" required>

                                <option value="">Choisir université</option>

                                <?php foreach ($universites as $u): ?>

                                    <option value="<?= $u->id_universite ?>">

                                        <?= $u->nom_universite ?>

                                    </option>

                                <?php endforeach ?>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label>Nom filière</label>

                            <input type="text" name="nom_filiere" class="form-control" required>

                        </div>

                        <button type="submit" name="save_filiere" class="btn btn-success">

                            Ajouter Filière

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <!-- Liste des universités -->
    <div class="card mt-4">

        <div class="card-header">
            <h5>Liste des Universités</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Université</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($universites as $u): ?>

                            <tr>

                                <td><?= $u->id_universite ?></td>

                                <td><?= $u->nom_universite ?></td>

                            </tr>

                        <?php endforeach ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- Liste des filières -->
    <div class="card mt-4">

        <div class="card-header">
            <h5>Liste des Filières</h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Filière</th>
                            <th>Université</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($filieres as $f): ?>

                            <tr>

                                <td><?= $f->id_filiere ?></td>

                                <td><?= $f->nom_filiere ?></td>

                                <td>
                                    <?php
                                    foreach ($universites as $u) {
                                        if ($u->id_universite == $f->universite_id) {
                                            echo $u->nom_universite;
                                        }
                                    }
                                    ?>
                                </td>

                            </tr>

                        <?php endforeach ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
      <?php $this->view('Partials/scripts'); ?>

                    <?php $this->view('Partials/dashboard-footer'); ?>

</div>
            </div>
    </section>

</body>

</html>
