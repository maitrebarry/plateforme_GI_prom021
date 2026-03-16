<?php $this->view( 'Partials/head', [ 'pageTitle' => $pageTitle ?? 'Dashboard étudiant' ] )?>
<style>
.project-card {

    background: #fff;
    border-radius: 12px;
    overflow: hidden;

    border: 1px solid #eee;

    transition: 0.3s;

    height: 100%;

    display: flex;
    flex-direction: column;

}

.project-card:hover {

    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);

}

.project-header {

    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 16px 20px;

    border-bottom: 1px solid #f1f1f1;

}

/* titre */

.project-title {

    font-size: 18px;
    font-weight: 600;

    display: flex;
    align-items: center;
    gap: 8px;

    color: #2b2b2b;

}

/* bouton publier */

.btn-create-project {

    display: flex;
    align-items: center;
    gap: 6px;

    padding: 8px 14px;

    background: linear-gradient(135deg, #4a6cf7, #6a85ff);

    color: white;

    font-size: 14px;
    font-weight: 500;

    border-radius: 8px;

    text-decoration: none;

    transition: 0.25s;

}

/* hover */

.btn-create-project:hover {

    transform: translateY(-2px);

    box-shadow: 0 6px 14px rgba(74, 108, 247, 0.35);

    background: linear-gradient(135deg, #3c5be0, #5f7bff);

    color: white;

}

.project-image {

    height: 180px;
    overflow: hidden;

}

.project-image img {

    width: 100%;
    height: 100%;

    object-fit: cover;

}

.project-content {

    padding: 15px;

    flex: 1;

    display: flex;
    flex-direction: column;

}

.project-title {

    font-weight: 600;
    margin-bottom: 6px;

}

.project-desc {

    font-size: 13px;
    color: #6c757d;

    margin-top: 8px;

    flex: 1;

}

.project-tech {

    margin-top: 10px;

    display: flex;
    flex-wrap: wrap;
    gap: 6px;

}

.tech-badge {

    background: #eef2ff;

    color: #4a6cf7;

    padding: 3px 8px;

    font-size: 12px;

    border-radius: 6px;

}

.project-actions {

    display: flex;
    gap: 8px;

}

/* bouton général */

.btn-action {

    display: flex;
    align-items: center;
    justify-content: center;

    width: 34px;
    height: 34px;

    border-radius: 50%;

    font-size: 16px;

    text-decoration: none;

    transition: all .25s ease;

}

/* bouton details */

.view-btn {

    background: #eef2ff;
    color: #4a6cf7;

}

.view-btn:hover {

    background: #4a6cf7;
    color: white;

    transform: translateY(-2px);

    box-shadow: 0 4px 12px rgba(74, 108, 247, 0.25);

}

/* bouton modifier */

.edit-btn {

    background: #fff4e5;
    color: #f59e0b;

}

.edit-btn:hover {

    background: #f59e0b;
    color: white;

    transform: translateY(-2px);

    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);

}
</style>

<body>
    <?php $this->view( 'Partials/global-shell' )?>
    <?php $this->view( 'Partials/mobile-menu' )?>

    <section class='dashboard'>
        <div class='dashboard__inner d-flex'>
            <?php $this->view( 'Partials/dashboard-sidebar' )?>
            <div class='dashboard-body'>
                <?php $this->view( 'Partials/dashboard-nav' )?>

                <div class='dashboard-body__content p-2'>
                    <?php $this->view( 'set_flash' )?>
                    <div class='card common-card mt-0'>

                        <div class='r project-header'>

                            <h5 class='project-title'>
                                <i class='bx bx-folder'></i> Mes projets
                            </h5>

                            <a href='<?= ROOT ?>/Projets/publier_projet' class='btn-create-project'>

                                <i class='bx bx-plus'></i>
                                Publier

                            </a>

                        </div>

                        <div class='card-body'>

                            <?php if ( !empty( $projects ) ): ?>

                            <div class='row g-4'>

                                <?php foreach ( $projects as $project ): ?>

                                <div class='col-lg-4'>

                                    <div class='project-card'>

                                        <!-- IMAGE -->

                                        <div class='project-image'>

                                            <?php if ( !empty( $project->image ) ): ?>

                                            <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= $project->image ?>"
                                                alt=''>

                                            <?php else: ?>

                                            <img src='<?= ROOT ?>/assets/images/no-image.png' alt=''>

                                            <?php endif ?>

                                        </div>

                                        <!-- CONTENU -->

                                        <div class='project-content'>

                                            <h5 class='project-title'>
                                                <?php echo htmlspecialchars( $project->title ) ?>
                                            </h5>

                                            <span class='badge bg-primary'>
                                                <?php echo htmlspecialchars( $project->categorie ) ?>
                                            </span>

                                            <p class='project-desc'>

                                                <?php $cleanDesc = strip_tags( $project->description )?>
                                                <?php echo substr( $cleanDesc, 0, 120 ).' ...'?>

                                            </p>

                                            <div class='project-tech'>

                                                <?php $techs = explode( ',', $project->technologies )?>
                                                <?php foreach ( $techs as $tech ):?>

                                                <span class='tech-badge'>
                                                    <?php echo htmlspecialchars( $tech ) ?>
                                                </span>

                                                <?php endforeach ?>

                                            </div>

                                            <div class='project-actions my-3'>

                                                <a href="<?= ROOT ?>/Projets/detail/<?= $project->id ?>"
                                                    class='btn-action view-btn' title='Voir détails'>
                                                    👁
                                                </a>

                                                <a href="<?= ROOT ?>/Projets/modifier/<?= $project->id ?>"
                                                    class='btn-action edit-btn' title='Modifier'>
                                                    ✏
                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <?php endforeach ?>

                            </div>

                            <?php else: ?>

                            <div class='text-center p-5'>

                                <h6>Aucun projet publié</h6>

                                <p class='text-muted'>
                                    Commencez par publier votre premier projet.
                                </p>

                                <a href='<?= ROOT ?>/Projets/publier_projet' class='btn btn-primary'>

                                    Publier un projet

                                </a>

                            </div>

                            <?php endif ?>

                        </div>

                    </div>
                    <?php $this->view( 'Partials/dashboard-footer' )?>
                </div>
            </div>
    </section>

    <?php $this->view( 'Partials/scripts' )?>
</body>

</html>