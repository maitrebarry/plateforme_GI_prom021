<?php $this->view( 'Partials/head', [ 'pageTitle' => $project->title ] ) ?>
<style>
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #4a6cf7;
    background: #eef2ff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-back .arrow {
    font-size: 16px;
    transition: transform 0.25s ease;
}

.btn-back:hover {
    background: #4a6cf7;
    color: white;
}

.btn-back:hover .arrow {
    transform: translateX(-4px);
}

.project-info-bar {

    display: flex;

    flex-wrap: wrap;

    align-items: center;

    gap: 12px;

    margin-top: 10px;

    margin-bottom: 20px;

}

.info-item {

    display: flex;

    align-items: center;

    gap: 6px;

    font-size: 13px;

    background: #f5f7ff;

    padding: 6px 12px;

    border-radius: 20px;

    color: #555;

    font-weight: 500;

}

/* badges technologies */

.tech-badge {

    background: #eef2ff;

    color: #4a6cf7;

    padding: 4px 10px;

    border-radius: 15px;

    font-size: 12px;

    margin-left: 4px;

}

.project-title {

    font-size: 30px;
    font-weight: 700;

    margin-bottom: 10px;

}

.project-status {

    padding: 4px 10px;

    border-radius: 6px;

    font-size: 13px;

}

.status-done {

    background: #e8f8f0;
    color: #1bbf73;

}

.status-progress {

    background: #fff4e5;
    color: #f59e0b;

}

.project-gallery {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 10px;

    margin-top: 20px;

}

.gallery-img {

    width: 100%;

    height: 160px;

    object-fit: cover;

    border-radius: 8px;

}

.project-description {

    margin-top: 25px;

    line-height: 1.7;

}

.tech-badge {

    background: #eef2ff;

    color: #4a6cf7;

    padding: 4px 10px;

    border-radius: 20px;

    margin-right: 5px;

    font-size: 12px;

}

.project-sidebar {

    background: #fff;

    padding: 20px;

    border-radius: 10px;

    border: 1px solid #eee;

}

.project-files li {

    margin-bottom: 6px;

}

/* SECTION VIDEO */

.project-video-section {

    margin-top: 30px;

    padding: 20px;

    background: #ffffff;

    border-radius: 12px;

    border: 1px solid #f0f0f0;

    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);

    transition: all .3s ease;

}

/* titre */

.video-title {

    font-size: 18px;

    font-weight: 600;

    margin-bottom: 15px;

    color: #2b2b2b;

    display: flex;

    align-items: center;

    gap: 6px;

}

/* conteneur iframe responsive */

.video-wrapper {

    position: relative;

    padding-bottom: 56.25%;

    height: 0;

    overflow: hidden;

    border-radius: 10px;

    background: #000;

    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);

}

/* iframe */

.video-wrapper iframe {

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    border: none;

}

/* effet hover */

.project-video-section:hover {

    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);

    transform: translateY(-2px);

}
</style>

<body>

    <?php $this->view( 'Partials/header' ) ?>

    <section class='project-detail py-5'>

        <div class='container'>

            <div class='row'>

                <div class='col-lg-8'>
                    <div class=' mb-2'>
                        <button type='button' class='btn-back' onclick='history.back()'>
                            <span class='arrow'>←</span> Retour
                        </button>
                    </div>

                    <!-- TITRE -->

                    <h2 class='project-title'>

                        <?php echo htmlspecialchars( $project->title ) ?>

                    </h2>

                    <!-- STATUT -->

                    <span
                        class="project-status <?= $project->status == strtolower("Valider") ? 'status-done' : 'status-progress' ?>">

                        <?php echo $project->status ?>

                    </span>

                    <!-- GALERIE IMAGES -->

                    <?php if ( !empty( $images ) ): ?>

                    <div class='project-gallery'>

                        <?php foreach ( $images as $img ): ?>

                        <img src="<?php echo ROOT_IMG ?>/uploads/projects/images/<?= $img->image ?>"
                            class='gallery-img'>

                        <?php endforeach ?>

                    </div>

                    <?php endif ?>

                    <!-- DESCRIPTION -->

                    <div class='project-description'>

                        <?php echo $project->description ?>

                    </div>

                    <!-- TECHNOLOGIES -->

                    <?php if ( !empty( $project->technologies ) ): ?>

                    <div class='project-tech'>

                        <h5>Technologies</h5>

                        <?php $techs = explode( ',', $project->technologies )?>
                        <?php foreach ( $techs as $tech ): ?>

                        <span class='tech-badge'>

                            <?php echo htmlspecialchars( $tech ) ?>

                        </span>

                        <?php endforeach ?>

                    </div>

                    <?php endif ?>

                    <!-- VIDEO -->
                    <div class='project-video-section mt-4'>

                        <h5 class='video-title'>

                            🎬 Vidéo de démonstration

                        </h5>

                        <?php $video_url = $this->youtube_embed( $project->video ) ?>

                        <?php if ( !empty( $video_url ) ): ?>

                        <div class='video-wrapper'>

                            <iframe src="<?= $video_url ?>" title='Video du projet' frameborder='0'
                                allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture'
                                allowfullscreen>

                            </iframe>

                        </div>

                        <?php endif ?>

                    </div>

                </div>

                <!-- SIDEBAR -->

                <div class='col-lg-4'>

                    <div class='project-sidebar'>

                        <h5>Informations</h5>

                        <p>

                            <strong>Catégorie :</strong>

                            <?php echo htmlspecialchars( $project->categorie )?>

                        </p>

                        <div class='project-info-bar'>

                            <span class='info-item time'>

                                🕒 <?php echo $this->temps_relatif ( $project->created_at ) ?>

                            </span>

                            <span class='info-item date'>

                                📅 <?php echo strftime( '%d %B %Y', strtotime( $project->created_at ) ) ?>

                            </span>

                            <span class='info-item tech'>

                                💻

                                <?php $techs = explode( ',', $project->technologies )?>
                                <?php foreach ( $techs as $tech ):?>

                                <span class='tech-badge'>

                                    <?php echo htmlspecialchars( trim( $tech ) ) ?>

                                </span>

                                <?php endforeach ?>

                            </span>

                        </div>

                        <!-- FICHIERS -->

                        <?php if ( !empty( $files ) ): ?>

                        <h6 class='mt-4'>Fichiers du projet</h6>

                        <ul class='project-files'>

                            <?php foreach ( $files as $file ): ?>

                            <li>

                                <a href="<?php echo ROOT_IMG ?>/uploads/projects/files/<?= $file->fichier ?>"
                                    target='_blank'>

                                    📄 <?php echo $file->fichier ?>

                                </a>

                            </li>

                            <?php endforeach ?>

                        </ul>

                        <?php endif ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <?php $this->view( 'Partials/footer' ) ?>

</body>