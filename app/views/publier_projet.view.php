<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Publier un projet']) ?>

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

.page-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
    color: #2b2b2b;
}

.page-subtitle {
    color: #6c757d;
    font-size: 15px;
    margin-bottom: 25px;
}

/* CARD */
.card {
    border-radius: 15px;
}

/* SELECTION CATEGORIE */
/* wrapper */

.select-wrapper {
    position: relative;
}

/* select */

.custom-select {

    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;

    width: 100%;
    padding: 10px 40px 10px 12px;

    border-radius: 10px;
    border: 1px solid #dcdcdc;

    background-color: #fff;

    font-size: 14px;

    cursor: pointer;

    transition: all .25s ease;

}

/* icone fleche */

.select-wrapper::after {

    content: "▾";

    position: absolute;

    right: 14px;
    top: 50%;

    transform: translateY(-50%);

    font-size: 14px;

    color: #4a6cf7;

    pointer-events: none;

}

/* hover */

.custom-select:hover {

    border-color: #4a6cf7;
    background: #fafbff;

}

/* focus */

.custom-select:focus {

    outline: none;

    border-color: #4a6cf7;

    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.15);

}

/* option */

.custom-select option {

    padding: 10px;

}


/* Technologie */
.tech-container {

    display: flex;
    flex-wrap: wrap;

    gap: 6px;

    border: 1px solid #dcdcdc;

    padding: 8px;

    border-radius: 10px;

    min-height: 42px;

    cursor: text;

    transition: 0.2s;

}

.tech-container:focus-within {

    border-color: #4a6cf7;

    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.15);

}

.tech-container input {

    border: none;

    outline: none;

    flex: 1;

    min-width: 120px;

    font-size: 14px;

}

/* TAG */

.tech-tag {

    background: #eef2ff;

    color: #4a6cf7;

    padding: 4px 10px;

    border-radius: 20px;

    font-size: 13px;

    display: flex;

    align-items: center;

    gap: 6px;

}

/* bouton supprimer */

.tech-remove {

    cursor: pointer;

    font-weight: bold;

}

/* DROP ZONE */

.upload-box {
    border: 2px dashed #cfd3d7;
    border-radius: 12px;
    padding: 35px;
    text-align: center;
    cursor: pointer;
    transition: 0.3s;
    background: #fafafa;
}

.upload-box:hover {
    border-color: #4a6cf7;
    background: #f5f7ff;
}

/* PREVIEW */

.preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.preview-item {
    position: relative;
}

.preview-item img {
    width: 90px;
    height: 90px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.remove-btn {
    position: absolute;
    top: -6px;
    right: -6px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    cursor: pointer;
}

/* FILE LIST */

.file-list {
    margin-top: 10px;
}

.file-item {
    background: #f5f5f5;
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* BUTTON */

.btn-primary {
    border-radius: 8px;
    background: #4a6cf7;
    border: none;
}

.btn-primary:hover {
    background: #3d5bd6;
}

.btn-publish {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    font-size: 15px;
    font-weight: 600;
    color: white;
    background: linear-gradient(135deg, #4a6cf7, #6a85ff);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 4px 10px rgba(74, 108, 247, 0.25);
}

/* hover */

.btn-publish:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(74, 108, 247, 0.35);
    background: linear-gradient(135deg, #3c5be0, #5f7bff);
}

/* click */

.btn-publish:active {
    transform: scale(0.96);
    box-shadow: 0 3px 8px rgba(74, 108, 247, 0.2);
}

/* icone */

.btn-publish .icon {
    font-size: 16px;
}

/* CONTENEUR */

.description-container {

    border: 1px solid #e5e7eb;

    border-radius: 12px;

    padding: 12px;

    background: #fafafa;

    transition: all .25s ease;

}

/* effet focus */

.description-container:focus-within {

    border-color: #4a6cf7;

    background: #ffffff;

    box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.15);

}

/* textarea */

.description-textarea {

    width: 100%;

    min-height: 160px;

    border: none;

    resize: none;

    outline: none;

    font-size: 14px;

    line-height: 1.7;

    background: transparent;

    color: #333;

}

/* footer */

.description-footer {

    display: flex;

    justify-content: flex-end;

    margin-top: 6px;

}

/* compteur */

.char-count {

    font-size: 12px;

    color: #999;

}
</style>

<body>

    <?php $this->view('Partials/global-shell') ?>
    <?php $this->view('Partials/mobile-menu') ?>
    <?php $this->view('Partials/header') ?>

    <main class='change-gradient'>

        <section class='all-product'>
            <div class='container container-two'>

                <div class='row justify-content-center'>
                    <div class="col-12">
                        <?php $this->view('set_flash'); ?>
                    </div>
                    <div class='col-lg-8'>
                        <div>
                            <button type="button" class="btn-back" onclick="history.back()">
                                <span class="arrow">←</span> Retour
                            </button>
                        </div>
                        <div class='card shadow-sm border-0'>

                            <div class='card-body p-5'>

                                <h2 class="page-title">Publier un nouveau projet</h2>
                                <p class="page-subtitle">
                                    Présentez votre réalisation, partagez vos compétences et inspirez d'autres
                                    étudiants.
                                </p>

                                <form method='POST' action='<?= ROOT ?>/Projets/store' enctype='multipart/form-data'
                                    id="projectForm">

                                    <!-- TITRE -->

                                    <div class='mb-4'>
                                        <label class='form-label fw-bold'>Titre du projet</label>
                                        <input type='text' name='title' class='form-control' required>
                                    </div>

                                    <!-- CATEGORIE -->
                                    <div class='mb-4'>

                                        <label class='form-label fw-bold'>Catégorie du projet</label>

                                        <div class="select-wrapper">

                                            <select name='category_id' class='form-control custom-select' required>

                                                <option value=''>Choisir une catégorie</option>

                                                <?php if(!empty($categories)): ?>
                                                <?php foreach($categories as $category): ?>

                                                <option value="<?= $category->id ?>">
                                                    <?= $category->nom ?>
                                                </option>

                                                <?php endforeach ?>
                                                <?php endif ?>

                                            </select>

                                        </div>

                                        <small class="text-muted">
                                            Sélectionnez la catégorie qui correspond le mieux à votre projet.
                                        </small>

                                    </div>


                                    <!-- DESCRIPTION -->
                                    <div class="mb-4">

                                        <label class="form-label fw-bold">Description du projet</label>

                                        <div class="description-container">

                                            <textarea name="description" id="projectDescription" default=""
                                                class="description-textarea" placeholder="Décrivez votre projet...

                                                    Plan conseillé :

                                                    1. Contexte du projet
                                                    2. Objectifs
                                                    3. Fonctionnalités principales
                                                    4. Technologies utilisées
                                                    5. Résultats obtenus
                                                    6. Difficultés rencontrées"></textarea>

                                            <div class="description-footer">

                                                <span class="char-count">
                                                    0 / 2000 caractères
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- TECHNO -->

                                    <div class='mb-4'>

                                        <label class='form-label fw-bold'>Technologies utilisées</label>

                                        <div class="tech-container">

                                            <input type="text" id="techInput" name="technologies"
                                                placeholder="Tapez une technologie puis appuyez sur Entrée">

                                        </div>

                                        <small class="text-muted">
                                            Exemple : PHP, Laravel, MySQL, React, Python...
                                        </small>

                                    </div>

                                    <!-- VIDEO -->

                                    <div class='mb-4'>
                                        <label class='form-label fw-bold'>Lien vidéo</label>
                                        <input type='text' name='video' class='form-control'>
                                    </div>

                                    <!-- IMAGES -->

                                    <div class="mb-4">

                                        <label class='form-label fw-bold'>Images du projet</label>

                                        <div class="upload-box" id="imageDrop">

                                            📷 Glisser vos images ici ou cliquer pour sélectionner

                                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                                hidden>

                                        </div>

                                        <div class="preview-container" id="imagePreview"></div>

                                    </div>

                                    <!-- FILES -->

                                    <div class="mb-4">

                                        <label class='form-label fw-bold'>Fichiers du projet</label>

                                        <div class="upload-box" id="fileDrop">

                                            📁 Glisser vos fichiers ici ou cliquer pour sélectionner

                                            <input type="file" id="files" name="files[]" multiple hidden>

                                        </div>

                                        <div class="file-list" id="fileList"></div>

                                    </div>

                                    <div class='text-center mt-4'>
                                        <button type="submit" class="btn-publish">
                                            <span class="icon">🚀</span>
                                            Publier le projet
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>

        <?php $this->view('Partials/footer') ?>

    </main>

    <?php $this->view('Partials/scripts') ?>

    <script>
    /* ============================= */
    /*  IMAGES MULTIPLES + PREVIEW   */
    /* ============================= */

    const imageInput = document.getElementById("images");
    const imageDrop = document.getElementById("imageDrop");
    const preview = document.getElementById("imagePreview");

    let imageFiles = new DataTransfer();

    /* ouvrir le select */

    imageDrop.addEventListener("click", () => {
        imageInput.click();
    });

    /* sélection via input */

    imageInput.addEventListener("change", function() {

        Array.from(this.files).forEach(file => {

            if (!file.type.startsWith("image/")) {
                alert("Seulement les images sont autorisées");
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert("Image trop lourde (max 5MB)");
                return;
            }

            imageFiles.items.add(file);

        });

        imageInput.files = imageFiles.files;

        displayImages();

    });

    /* afficher preview */

    function displayImages() {

        preview.innerHTML = "";

        Array.from(imageFiles.files).forEach((file, index) => {

            const reader = new FileReader();

            reader.onload = function(e) {

                const div = document.createElement("div");
                div.className = "preview-item";

                const img = document.createElement("img");
                img.src = e.target.result;

                const btn = document.createElement("button");
                btn.className = "remove-btn";
                btn.innerText = "x";

                btn.onclick = function() {

                    const dt = new DataTransfer();

                    Array.from(imageFiles.files)
                        .filter((_, i) => i !== index)
                        .forEach(f => dt.items.add(f));

                    imageFiles = dt;
                    imageInput.files = dt.files;

                    displayImages();

                };

                div.appendChild(img);
                div.appendChild(btn);

                preview.appendChild(div);

            };

            reader.readAsDataURL(file);

        });

    }

    /* ============================= */
    /*        FICHIERS PROJET        */
    /* ============================= */

    const fileInput = document.getElementById("files");
    const fileDrop = document.getElementById("fileDrop");
    const fileList = document.getElementById("fileList");

    let projectFiles = new DataTransfer();

    /* clic */

    fileDrop.addEventListener("click", () => {
        fileInput.click();
    });

    /* sélection */

    fileInput.addEventListener("change", function() {

        Array.from(this.files).forEach(file => {

            if (file.size > 100 * 1024 * 1024) {
                alert("Fichier trop lourd (max 100MB)");
                return;
            }

            projectFiles.items.add(file);

        });

        fileInput.files = projectFiles.files;

        displayFiles();

    });

    /* afficher fichiers */

    function displayFiles() {

        fileList.innerHTML = "";

        Array.from(projectFiles.files).forEach((file, index) => {

            const div = document.createElement("div");
            div.className = "file-item";

            const span = document.createElement("span");
            span.innerText = file.name;

            const btn = document.createElement("button");
            btn.type = "button";
            btn.innerText = "x";

            btn.onclick = function() {

                const dt = new DataTransfer();

                Array.from(projectFiles.files)
                    .filter((_, i) => i !== index)
                    .forEach(f => dt.items.add(f));

                projectFiles = dt;
                fileInput.files = dt.files;

                displayFiles();

            };

            div.appendChild(span);
            div.appendChild(btn);

            fileList.appendChild(div);

        });

    }

    /* ============================= */
    /*         DRAG & DROP           */
    /* ============================= */

    ["dragenter", "dragover"].forEach(event => {
        imageDrop.addEventListener(event, e => {
            e.preventDefault();
            imageDrop.style.borderColor = "#4a6cf7";
        });
    });

    ["dragleave", "drop"].forEach(event => {
        imageDrop.addEventListener(event, e => {
            e.preventDefault();
            imageDrop.style.borderColor = "#cfd3d7";
        });
    });

    imageDrop.addEventListener("drop", function(e) {

        e.preventDefault();

        Array.from(e.dataTransfer.files).forEach(file => {
            imageFiles.items.add(file);
        });

        imageInput.files = imageFiles.files;

        displayImages();

    });


    ["dragenter", "dragover"].forEach(event => {
        fileDrop.addEventListener(event, e => {
            e.preventDefault();
            fileDrop.style.borderColor = "#4a6cf7";
        });
    });

    ["dragleave", "drop"].forEach(event => {
        fileDrop.addEventListener(event, e => {
            e.preventDefault();
            fileDrop.style.borderColor = "#cfd3d7";
        });
    });

    fileDrop.addEventListener("drop", function(e) {

        e.preventDefault();

        Array.from(e.dataTransfer.files).forEach(file => {
            projectFiles.items.add(file);
        });

        fileInput.files = projectFiles.files;

        displayFiles();

    });



    // LA DESCRIPTION
    const textarea = document.getElementById("projectDescription");

    const counter = document.querySelector(".char-count");

    const maxLength = 2000;

    /* auto resize */

    textarea.addEventListener("input", function() {

        this.style.height = "auto";

        this.style.height = this.scrollHeight + "px";

        /* compteur */

        let length = this.value.length;

        counter.textContent = length + " / " + maxLength + " caractères";

        /* limite */

        if (length > maxLength) {

            counter.style.color = "red";

        } else {

            counter.style.color = "#999";

        }

    });
    </script>

</body>

</html>