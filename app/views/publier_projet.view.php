<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Publier un projet']); ?>

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
                    --secondary-color: #94a3b8;
                    --success-color: #10b981;
                    --warning-color: #f59e0b;
                    --danger-color: #ef4444;
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

                /* Hero Section */
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

                .description-container {
                    border: 1.5px solid #e2e8f0;
                    border-radius: 12px;
                    padding: 1rem;
                    background: #f8fafc;
                    transition: all 0.3s;
                }

                .description-container:focus-within {
                    border-color: var(--primary-color);
                    background: #ffffff;
                    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
                }

                .description-textarea {
                    width: 100%;
                    border: none;
                    outline: none;
                    background: transparent;
                    resize: vertical;
                    min-height: 200px;
                    font-size: 0.95rem;
                    line-height: 1.6;
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
                    padding: 5px 12px;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 700;
                    display: flex;
                    align-items: center;
                    gap: 6px;
                }

                .tech-tag i { cursor: pointer; opacity: 0.7; transition: 0.2s; }
                .tech-tag i:hover { opacity: 1; transform: scale(1.1); }

                #techInput {
                    border: none;
                    outline: none;
                    background: transparent;
                    flex: 1;
                    min-width: 150px;
                }

                .upload-box {
                    border: 2px dashed #cbd5e1;
                    border-radius: 16px;
                    padding: 3rem 2rem;
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

                .upload-box i { font-size: 2.5rem; display: block; margin-bottom: 1rem; }

                .preview-container { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 1rem; }
                .preview-item {
                    position: relative;
                    width: 100px;
                    height: 100px;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
                }
                .preview-item img { width: 100%; height: 100%; object-fit: cover; }
                .preview-item .remove-btn {
                    position: absolute;
                    top: 5px;
                    right: 5px;
                    background: var(--danger-color);
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 22px;
                    height: 22px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    font-size: 12px;
                }

                .file-item {
                    background: white;
                    border: 1px solid #e2e8f0;
                    padding: 0.75rem 1rem;
                    border-radius: 10px;
                    margin-bottom: 0.5rem;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    font-size: 0.9rem;
                }

                .btn-publish {
                    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
                    color: white !important;
                    padding: 1rem 2.5rem;
                    border-radius: 14px;
                    font-weight: 800;
                    border: none;
                    box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
                    transition: all 0.3s;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    gap: 10px;
                }

                .btn-publish:hover {
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

                <div class="publish-project-container">
                    <div class="student-hero" data-reveal>
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <a href="<?= ROOT ?>/Projets/mes_projets" class="btn btn-sm btn-primary" style="background: rgba(255,255,255,0.2); border: none; color: white;">
                                <i class='bx bx-arrow-back'></i> Retour
                            </a>
                            <span class="badge bg-primary bg-opacity-25 text-white">Nouveau Projet</span>
                        </div>
                        <h1 class="text-white">Publier une nouvelle réalisation</h1>
                        <p>Présentez votre travail à la communauté. Partagez vos détails techniques, vos fichiers et vos captures d'écran pour inspirer les autres.</p>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <div class="mb-4">
                                <?php $this->view('set_flash'); ?>
                            </div>

                            <form method="POST" action="<?= ROOT ?>/Projets/store" enctype="multipart/form-data" id="projectForm" class="common-card" data-reveal>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label class="form-label">Titre du projet <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" placeholder="Donnez un nom percutant à votre projet" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                        <select name="category_id" class="form-select" required>
                                            <option value="">Choisir une catégorie...</option>
                                            <?php if(!empty($categories)): ?>
                                                <?php foreach($categories as $category): ?>
                                                    <option value="<?= $category->id ?>"><?= $category->nom ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Technologies utilisées</label>
                                        <div class="tech-container" id="techContainer">
                                            <input type="text" id="techInput" placeholder="Tapez une tech (ex: React) + Entrée">
                                            <input type="hidden" name="technologies" id="techHiddenInput">
                                        </div>
                                        <div class="form-text small text-muted">Exemple: PHP, Laravel, MySQL, React...</div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Description détaillée <span class="text-danger">*</span></label>
                                        <div class="description-container">
                                            <textarea name="description" id="projectDescription" class="description-textarea" placeholder="Expliquez le contexte, vos objectifs et vos résultats..."></textarea>
                                            <div class="text-end pt-2">
                                                <span class="char-count small text-muted">0 / 2000 caractères</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Lien vidéo de démonstration</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class='bx bxl-youtube text-danger'></i></span>
                                            <input type="text" name="video" class="form-control border-start-0" placeholder="https://youtube.com/watch?v=...">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Images & Captures d'écran</label>
                                        <div class="upload-box" id="imageDrop">
                                            <i class='bx bx-images'></i>
                                            <div class="fw-bold text-main">Cliquez ou glissez vos images</div>
                                            <div class="small opacity-75">PNG, JPG ou WEBP (max 5MB)</div>
                                            <input type="file" id="images" name="images[]" multiple accept="image/*" hidden>
                                        </div>
                                        <div class="preview-container" id="imagePreview"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Fichiers du projet (Source, Docs)</label>
                                        <div class="upload-box" id="fileDrop">
                                            <i class='bx bx-file'></i>
                                            <div class="fw-bold text-main">Cliquez ou glissez vos fichiers</div>
                                            <div class="small opacity-75">ZIP, PDF ou tout autre format (max 100MB)</div>
                                            <input type="file" id="files" name="files[]" multiple hidden>
                                        </div>
                                        <div class="file-list mt-3" id="fileList"></div>
                                    </div>

                                    <div class="col-12 text-center mt-5 border-top pt-4">
                                        <button type="submit" class="btn-publish">
                                            <i class='bx bx-rocket'></i> Publier maintenant
                                        </button>
                                        <p class="small text-muted mt-3">Votre projet sera soumis à validation avant d'être visualisé publiquement.</p>
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
    const items = document.querySelectorAll('[data-reveal]');
    const observerOptions = { threshold: 0.1 };
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    items.forEach((item, index) => {
        item.style.transitionDelay = (index * 100) + 'ms';
        observer.observe(item);
    });

    /* TECHNOLOGY TAGS SYSTEM */
    const techContainer = document.getElementById("techContainer");
    const techInput = document.getElementById("techInput");
    const techHiddenInput = document.getElementById("techHiddenInput");
    let tags = [];

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
        tag.querySelector('i').onclick = function() {
            tags = tags.filter(t => t !== text);
            tag.remove();
            updateHiddenInput();
        };
        techContainer.insertBefore(tag, techInput);
    }

    function updateHiddenInput() {
        techHiddenInput.value = tags.join(",");
    }

    /* IMAGES UPLOAD & PREVIEW */
    const imageInput = document.getElementById("images");
    const imagePreview = document.getElementById("imagePreview");
    let imageFilesList = new DataTransfer();

    document.getElementById("imageDrop").onclick = () => imageInput.click();
    imageInput.onchange = e => handleFiles(e.target.files);

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith("image/") && file.size <= 5*1024*1024) {
                const reader = new FileReader();
                reader.onload = ev => {
                    const div = document.createElement("div");
                    div.className = "preview-item";
                    div.innerHTML = `<img src="${ev.target.result}"><button type="button" class="remove-btn">×</button>`;
                    
                    const currentIndex = imageFilesList.items.length;
                    imageFilesList.items.add(file);
                    imageInput.files = imageFilesList.files;

                    div.querySelector('.remove-btn').onclick = () => {
                        const dt = new DataTransfer();
                        Array.from(imageFilesList.files).forEach((f, i) => {
                            if (f.name !== file.name || f.size !== file.size) dt.items.add(f);
                        });
                        imageFilesList = dt;
                        imageInput.files = imageFilesList.files;
                        div.remove();
                    };
                    imagePreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    /* FILE UPLOAD LIST */
    const fileInput = document.getElementById("files");
    const fileList = document.getElementById("fileList");
    let pFiles = new DataTransfer();

    document.getElementById("fileDrop").onclick = () => fileInput.click();
    fileInput.onchange = e => {
        Array.from(e.target.files).forEach(f => {
            if (f.size <= 100*1024*1024) {
                pFiles.items.add(f);
                const div = document.createElement("div");
                div.className = "file-item";
                div.innerHTML = `<span><i class='bx bx-file me-2'></i>${f.name}</span><button type="button" class="btn btn-sm text-danger">×</button>`;
                div.querySelector('button').onclick = () => {
                   const dt = new DataTransfer();
                   Array.from(pFiles.files).forEach(p => {
                       if (p.name !== f.name || p.size !== f.size) dt.items.add(p);
                   });
                   pFiles = dt;
                   fileInput.files = pFiles.files;
                   div.remove();
                };
                fileList.appendChild(div);
            }
        });
        fileInput.files = pFiles.files;
    };

    /* DRAG & DROP Styling */
    ['imageDrop', 'fileDrop'].forEach(boxId => {
        const box = document.getElementById(boxId);
        ['dragenter', 'dragover'].forEach(e => {
            box.addEventListener(e, ev => {
                ev.preventDefault();
                box.style.borderColor = "var(--primary-color)";
                box.style.background = "rgba(99, 102, 241, 0.05)";
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            box.addEventListener(e, ev => {
                ev.preventDefault();
                box.style.borderColor = "#cbd5e1";
                box.style.background = "#f8fafc";
            });
        });
    });

    /* CHAR COUNTER */
    const desc = document.getElementById("projectDescription");
    const counter = document.querySelector(".char-count");
    desc.oninput = function() {
        this.style.height = "auto";
        this.style.height = (this.scrollHeight) + "px";
        counter.textContent = `${this.value.length} / 2000 caractères`;
        counter.style.color = this.value.length > 2000 ? "var(--danger-color)" : "var(--text-muted)";
    };
})();
</script>
</body>
</html>