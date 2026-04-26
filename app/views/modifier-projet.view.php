<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Modifier le projet']); ?>

<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>
            
            <div class="dashboard-body__content p-4">
                <style>
                :root {
                    --primary-color: #6366f1;
                    --primary-hover: #4f46e5;
                    --bg-light: #f1f5f9;
                    --text-main: #0f172a;
                    --text-muted: #64748b;
                    --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                }

                .dashboard-body__content {
                    animation: fadeIn 0.5s ease-out;
                    background-color: var(--bg-light);
                }

                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                .student-hero {
                    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
                    border-radius: 20px;
                    padding: 2.5rem;
                    color: white;
                    margin-bottom: 2.5rem;
                    position: relative;
                    overflow: hidden;
                    box-shadow: var(--card-shadow);
                }

                .student-hero h1 { font-weight: 800; font-size: 2.4rem; margin-bottom: 0.75rem; }
                .student-hero p { font-size: 1.1rem; opacity: 0.9; max-width: 800px; margin-bottom: 0; }

                .common-card {
                    border: none;
                    border-radius: 20px;
                    box-shadow: var(--card-shadow);
                    background: #ffffff;
                    padding: 2.5rem;
                    margin-bottom: 3rem;
                }

                .form-label {
                    font-weight: 700;
                    color: var(--text-main);
                    margin-bottom: 0.6rem;
                    font-size: 0.95rem;
                }

                .form-control, .form-select {
                    border-radius: 12px;
                    border: 1.5px solid #e2e8f0;
                    padding: 0.8rem 1.1rem;
                    font-size: 0.95rem;
                    transition: all 0.3s;
                }

                .form-control:focus, .form-select:focus {
                    border-color: var(--primary-color);
                    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                    outline: none;
                }

                /* Editor Styling */
                .ql-container.ql-snow {
                    border-bottom-left-radius: 12px;
                    border-bottom-right-radius: 12px;
                    border: 1.5px solid #e2e8f0;
                    font-size: 0.95rem;
                    min-height: 250px;
                }
                .ql-toolbar.ql-snow {
                    border-top-left-radius: 12px;
                    border-top-right-radius: 12px;
                    border: 1.5px solid #e2e8f0;
                    border-bottom: none;
                    background: #f8fafc;
                }

                .tech-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    border: 1.5px solid #e2e8f0;
                    padding: 0.6rem;
                    border-radius: 12px;
                    background: #f8fafc;
                    min-height: 52px;
                    transition: all 0.3s;
                }

                .tech-container:focus-within {
                    border-color: var(--primary-color);
                    background: #ffffff;
                    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                }

                .tech-tag {
                    background: rgba(99, 102, 241, 0.1);
                    color: var(--primary-color);
                    padding: 4px 10px;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 700;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }

                .upload-box {
                    border: 2px dashed #cbd5e1;
                    border-radius: 16px;
                    padding: 2.5rem 1.5rem;
                    text-align: center;
                    cursor: pointer;
                    transition: all 0.3s;
                    background: #f8fafc;
                    color: var(--text-muted);
                }

                .upload-box:hover {
                    border-color: var(--primary-color);
                    background: rgba(99, 102, 241, 0.03);
                    color: var(--primary-color);
                }

                .preview-container { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 1rem; }
                .preview-item {
                    position: relative;
                    width: 90px;
                    height: 90px;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
                }
                .preview-item img { width: 100%; height: 100%; object-fit: cover; }
                
                .remove-btn-abs {
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    background: #ef4444;
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 20px;
                    height: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 11px;
                    cursor: pointer;
                    text-decoration: none;
                }

                .file-item {
                    background: #f8fafc;
                    border: 1px solid #e2e8f0;
                    padding: 0.75rem 1rem;
                    border-radius: 10px;
                    margin-bottom: 0.5rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .btn-update {
                    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                    color: white !important;
                    padding: 1rem 2.5rem;
                    border-radius: 14px;
                    font-weight: 800;
                    border: none;
                    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 10px;
                    text-decoration: none;
                }

                .btn-update:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
                }

                [data-reveal] {
                    opacity: 0;
                    transform: translateY(20px);
                    transition: all 0.6s cubic-bezier(0.22, 1, 0.36, 1);
                }
                [data-reveal].is-visible {
                    opacity: 1;
                    transform: translateY(0);
                }
                </style>

                <div class="edit-project-container">
                    <div class="student-hero" data-reveal>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <a href="<?= ROOT ?>/Projets/mes_projets" class="btn btn-sm" style="background: rgba(255,255,255,0.2); border: none; color: white;">
                                <i class='bx bx-arrow-back'></i> Annuler
                            </a>
                            <span class="badge bg-warning bg-opacity-25 text-white">Édition</span>
                        </div>
                        <h1 class="text-white">Modifier "<?= htmlspecialchars($project->title) ?>"</h1>
                        <p>Mettez à jour les informations, ajoutez de nouvelles images ou changez les fichiers de votre réalisation.</p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <div class="mb-4">
                                <?php $this->view('set_flash'); ?>
                            </div>

                            <form method="POST" action="<?= ROOT ?>/Projets/update/<?= $project->id ?>" enctype="multipart/form-data" id="projectForm" class="common-card" data-reveal>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label">Titre du projet <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($project->title) ?>" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <?php foreach($categories as $category): ?>
                                                <option value="<?= $category->id ?>" <?= $project->category_id == $category->id ? "selected" : "" ?>>
                                                    <?= $category->nom ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Technologies utilisées</label>
                                        <div class="tech-container" id="techContainer">
                                            <input type="text" id="techInput" placeholder="Tapez pour ajouter...">
                                            <input type="hidden" name="technologies" id="techHiddenInput" value="<?= htmlspecialchars($project->technologies) ?>">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description détaillée <span class="text-danger">*</span></label>
                                        <div id="editor"></div>
                                        <input type="hidden" name="description" id="description">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Lien vidéo (Optionnel)</label>
                                        <input type="url" name="video" class="form-control" value="<?= htmlspecialchars($project->video) ?>" placeholder="https://youtube.com/...">
                                    </div>

                                    <div class="col-12 border-top pt-4">
                                        <h5 class="fw-bold mb-4">Gestion des médias</h5>
                                        
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="form-label d-block">Images actuelles</label>
                                                <div class="preview-container mb-3">
                                                    <?php foreach($images as $img): ?>
                                                        <div class="preview-item">
                                                            <img src="<?= ROOT_IMG ?>/uploads/projects/images/<?= $img->image ?>">
                                                            <a href="<?= ROOT ?>/Projets/delete_image/<?= $img->id ?>/<?=$project->id ?>" class="remove-btn-abs" title="Supprimer">×</a>
                                                        </div>
                                                    <?php endforeach ?>
                                                </div>
                                                <div class="upload-box" id="imageDrop">
                                                    <i class='bx bx-plus-circle h3 d-block'></i>
                                                    <span>Ajouter des images</span>
                                                    <input type="file" id="images" name="images[]" multiple hidden>
                                                </div>
                                                <div class="preview-container" id="imagePreview"></div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label d-block">Fichiers actuels</label>
                                                <div class="file-list mb-3">
                                                    <?php if(!empty($files)): ?>
                                                        <?php foreach($files as $file): ?>
                                                            <div class="file-item">
                                                                <span class="small text-truncate"><i class='bx bx-file me-2 text-primary'></i><?= $file->fichier ?></span>
                                                                <a href="<?= ROOT ?>/Projets/delete_file/<?= $file->id ?>/<?=$project->id ?>" class="text-danger" title="Supprimer">
                                                                    <i class='bx bx-trash'></i>
                                                                </a>
                                                            </div>
                                                        <?php endforeach ?>
                                                    <?php endif ?>
                                                </div>
                                                <div class="upload-box" id="fileDrop">
                                                    <i class='bx bx-cloud-upload h3 d-block'></i>
                                                    <span>Ajouter des fichiers</span>
                                                    <input type="file" id="files" name="files[]" multiple hidden>
                                                </div>
                                                <div class="file-list mt-3" id="newFileList"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-center mt-5">
                                        <button type="submit" class="btn-update">
                                            <i class='bx bx-check-double'></i> Enregistrer les modifications
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->view('Partials/dashboard-footer'); ?>
        </div>
    </div>
</section>

<?php $this->view('Partials/scripts'); ?>
<script>
(function() {
    /* Reveal Animation */
    const revealItems = document.querySelectorAll('[data-reveal]');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealItems.forEach(item => observer.observe(item));

    /* QUILL EDITOR */
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{'list': 'ordered'}, {'list': 'bullet'}],
                ['link', 'code-block'],
                ['clean']
            ]
        }
    });
    quill.root.innerHTML = <?= json_encode($project->description) ?>;

    /* TECH TAGS */
    const techContainer = document.getElementById("techContainer");
    const techInput = document.getElementById("techInput");
    const techHiddenInput = document.getElementById("techHiddenInput");
    let tags = [];

    if (techHiddenInput.value) {
        tags = techHiddenInput.value.split(',').filter(t => t.trim() !== "");
        tags.forEach(t => addTagUI(t));
    }

    techInput.addEventListener("keydown", function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            const val = this.value.trim();
            if (val && !tags.includes(val)) {
                tags.push(val);
                addTagUI(val);
                this.value = "";
                updateHiddenInput();
            }
        }
    });

    function addTagUI(text) {
        const tag = document.createElement("div");
        tag.className = "tech-tag";
        tag.innerHTML = `${text} <i class='bx bx-x' style='cursor:pointer'></i>`;
        tag.querySelector('i').onclick = () => {
            tags = tags.filter(t => t !== text);
            tag.remove();
            updateHiddenInput();
        };
        techContainer.insertBefore(tag, techInput);
    }

    function updateHiddenInput() {
        techHiddenInput.value = tags.join(",");
    }

    /* UPLOADS */
    const imgInput = document.getElementById("images");
    const docInput = document.getElementById("files");
    const imgPrev = document.getElementById("imagePreview");
    const docPrev = document.getElementById("newFileList");
    let imgDt = new DataTransfer();
    let docDt = new DataTransfer();

    document.getElementById("imageDrop").onclick = () => imgInput.click();
    imgInput.onchange = e => {
        Array.from(e.target.files).forEach(file => {
            if (file.type.startsWith("image/")) {
                imgDt.items.add(file);
                const reader = new FileReader();
                reader.onload = ev => {
                    const div = document.createElement("div");
                    div.className = "preview-item";
                    div.innerHTML = `<img src="${ev.target.result}"><button type="button" class="remove-btn-abs">×</button>`;
                    div.querySelector('button').onclick = () => {
                        const newDt = new DataTransfer();
                        Array.from(imgDt.files).forEach(f => { if(f.name !== file.name) newDt.items.add(f); });
                        imgDt = newDt; imgInput.files = imgDt.files; div.remove();
                    };
                    imgPrev.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
        imgInput.files = imgDt.files;
    };

    document.getElementById("fileDrop").onclick = () => docInput.click();
    docInput.onchange = e => {
        Array.from(e.target.files).forEach(file => {
            docDt.items.add(file);
            const div = document.createElement("div");
            div.className = "file-item";
            div.innerHTML = `<span><i class='bx bx-file me-2 text-primary'></i>${file.name}</span><button type="button" class="btn btn-sm text-danger">×</button>`;
            div.querySelector('button').onclick = () => {
                const newDt = new DataTransfer();
                Array.from(docDt.files).forEach(f => { if(f.name !== file.name) newDt.items.add(f); });
                docDt = newDt; docInput.files = docDt.files; div.remove();
            };
            docPrev.appendChild(div);
        });
        docInput.files = docDt.files;
    };

    /* FORM SUBMIT */
    document.getElementById("projectForm").onsubmit = function() {
        document.getElementById("description").value = quill.root.innerHTML;
    };
})();
</script>
</body>
</html>