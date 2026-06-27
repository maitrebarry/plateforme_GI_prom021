<?php $this->view('Partials/head', ['pageTitle' => $pageTitle ?? 'Publier un projet']); ?>
<body>
<?php $this->view('Partials/global-shell'); ?>
<?php $this->view('Partials/mobile-menu'); ?>
<?php $categories = $categories ?? []; ?>

<section class="dashboard">
    <div class="dashboard__inner d-flex">
        <?php $this->view('Partials/dashboard-sidebar'); ?>
        <div class="dashboard-body">
            <?php $this->view('Partials/dashboard-nav'); ?>

            <div class="dashboard-body__content p-3 p-lg-4">
                <style>
                    .pp-wrap { max-width: 920px; margin: 0 auto; }
                    .pp-hero { position: relative; overflow: hidden; background: linear-gradient(135deg, var(--ds-brand-700), var(--ds-brand-800)); border-radius: var(--ds-radius-xl); padding: 26px; color: #fff; margin-bottom: 22px; }
                    .pp-hero::before { content: ''; position: absolute; top: -70px; right: -50px; width: 260px; height: 260px; border-radius: 50%; background: radial-gradient(circle, rgba(224,168,46,.22), transparent 70%); }
                    .pp-hero__top { position: relative; z-index: 1; display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
                    .pp-back { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); color: #fff; font-weight: 600; font-size: .82rem; padding: 7px 13px; border-radius: var(--ds-radius-pill); text-decoration: none; }
                    .pp-back:hover { background: rgba(255,255,255,.24); color: #fff; }
                    .pp-tag { display: inline-flex; align-items: center; gap: 5px; background: rgba(224,168,46,.2); border: 1px solid rgba(224,168,46,.35); color: var(--ds-accent); font-weight: 700; font-size: .74rem; padding: 5px 12px; border-radius: var(--ds-radius-pill); }
                    .pp-hero h1 { position: relative; z-index: 1; font-family: var(--ds-font-heading); font-weight: 800; font-size: 1.6rem; color: #fff; margin: 0 0 8px; }
                    .pp-hero p { position: relative; z-index: 1; color: rgba(231,240,235,.82); font-size: .95rem; line-height: 1.55; margin: 0; max-width: 640px; }

                    #projectForm { background: var(--ds-surface); border: 1px solid var(--ds-border); border-radius: var(--ds-radius-lg); box-shadow: var(--ds-shadow-sm); padding: 22px; }
                    #projectForm .form-label { font-weight: 700; color: var(--ds-ink); margin-bottom: 7px; font-size: .9rem; }
                    #projectForm .form-control, #projectForm .form-select { border-radius: var(--ds-radius); border: 1px solid var(--ds-border-strong); padding: 11px 14px; font-size: .94rem; color: var(--ds-ink); background: var(--ds-surface); }
                    #projectForm .form-control:focus, #projectForm .form-select:focus { border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); outline: none; }
                    #projectForm .input-group-text { background: var(--ds-surface-2); border: 1px solid var(--ds-border-strong); border-right: 0; border-radius: var(--ds-radius) 0 0 var(--ds-radius); color: var(--ds-muted); }

                    .description-container { border: 1px solid var(--ds-border-strong); border-radius: var(--ds-radius); padding: 12px 14px; background: var(--ds-surface); transition: all var(--ds-transition); }
                    .description-container:focus-within { border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .description-textarea { width: 100%; border: none; outline: none; background: transparent; resize: vertical; min-height: 170px; font-size: .94rem; line-height: 1.6; color: var(--ds-ink); font-family: var(--ds-font-sans); }
                    .char-count { color: var(--ds-muted); }

                    .tech-container { display: flex; flex-wrap: wrap; gap: 8px; border: 1px solid var(--ds-border-strong); padding: 8px; border-radius: var(--ds-radius); background: var(--ds-surface); min-height: 50px; transition: all var(--ds-transition); }
                    .tech-container:focus-within { border-color: var(--ds-brand-400); box-shadow: var(--ds-ring); }
                    .tech-tag { background: var(--ds-brand-50); color: var(--ds-brand-700); padding: 5px 11px; border-radius: var(--ds-radius-pill); font-size: .82rem; font-weight: 700; display: flex; align-items: center; gap: 6px; }
                    .tech-tag i { cursor: pointer; opacity: .7; transition: .2s; }
                    .tech-tag i:hover { opacity: 1; transform: scale(1.1); }
                    #techInput { border: none; outline: none; background: transparent; flex: 1; min-width: 140px; color: var(--ds-ink); font-family: var(--ds-font-sans); }

                    .upload-box { border: 2px dashed var(--ds-border-strong); border-radius: var(--ds-radius-lg); padding: 28px 18px; text-align: center; cursor: pointer; transition: all var(--ds-transition); background: var(--ds-surface-2); color: var(--ds-muted); }
                    .upload-box:hover { border-color: var(--ds-brand-400); background: var(--ds-brand-50); color: var(--ds-brand-700); }
                    .upload-box i { font-size: 2.2rem; display: block; margin-bottom: 8px; color: var(--ds-brand-500); }
                    .upload-box .fw-bold { color: var(--ds-ink); }

                    .preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 12px; }
                    .preview-item { position: relative; width: 86px; height: 86px; border-radius: 12px; overflow: hidden; box-shadow: var(--ds-shadow-sm); }
                    .preview-item img { width: 100%; height: 100%; object-fit: cover; }
                    .preview-item .remove-btn { position: absolute; top: 4px; right: 4px; background: var(--ds-danger); color: #fff; border: none; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 13px; line-height: 1; }
                    .file-item { background: var(--ds-surface); border: 1px solid var(--ds-border); padding: 10px 13px; border-radius: var(--ds-radius); margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; font-size: .88rem; color: var(--ds-ink); }
                    .file-item button { background: none; border: 0; color: var(--ds-danger); font-size: 1.1rem; cursor: pointer; }

                    .btn-publish { background: var(--ds-brand-600); color: #fff !important; padding: 13px 30px; border-radius: var(--ds-radius-pill); font-weight: 800; border: none; box-shadow: var(--ds-shadow); transition: all var(--ds-transition); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 9px; cursor: pointer; }
                    .btn-publish:hover { background: var(--ds-brand-700); transform: translateY(-2px); box-shadow: var(--ds-shadow-md); }

                    [data-reveal] { opacity: 0; transform: translateY(18px); transition: all .6s cubic-bezier(.22,1,.36,1); }
                    [data-reveal].is-visible { opacity: 1; transform: none; }

                    @media (min-width: 768px) { .pp-hero { padding: 30px; } .pp-hero h1 { font-size: 1.9rem; } #projectForm { padding: 30px; } }
                </style>

                <div class="pp-wrap">
                    <div class="pp-hero" data-reveal>
                        <div class="pp-hero__top">
                            <a href="<?= ROOT ?>/Projets/mes_projets" class="pp-back"><i class='bx bx-arrow-back'></i> Retour</a>
                            <span class="pp-tag"><i class='bx bx-plus-circle'></i> Nouveau projet</span>
                        </div>
                        <h1>Publier une nouvelle réalisation</h1>
                        <p>Présentez votre travail à la communauté : détails techniques, fichiers et captures d'écran pour inspirer et être contacté.</p>
                    </div>

                    <div class="mb-3"><?php $this->view('set_flash'); ?></div>

                    <form method="POST" action="<?= ROOT ?>/Projets/store" enctype="multipart/form-data" id="projectForm" data-reveal>
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((string) ($_SESSION['csrf_token'] ?? '')) ?>">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label">Titre du projet <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="Donnez un nom percutant à votre projet" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Choisir une catégorie…</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= (int) ($category->id ?? 0) ?>"><?= htmlspecialchars((string) ($category->nom ?? '')) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Technologies utilisées</label>
                                <div class="tech-container" id="techContainer">
                                    <input type="text" id="techInput" placeholder="Tapez une techno (ex : React) puis Entrée">
                                    <input type="hidden" name="technologies" id="techHiddenInput">
                                </div>
                                <div class="form-text small text-muted">Exemple : PHP, Laravel, MySQL, React…</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description détaillée <span class="text-danger">*</span></label>
                                <div class="description-container">
                                    <textarea name="description" id="projectDescription" class="description-textarea" placeholder="Expliquez le contexte, vos objectifs et vos résultats…"></textarea>
                                    <div class="text-end pt-2"><span class="char-count small">0 / 2000 caractères</span></div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Lien vidéo de démonstration</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class='bx bxl-youtube text-danger'></i></span>
                                    <input type="text" name="video" class="form-control border-start-0" placeholder="https://youtube.com/watch?v=…">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Images &amp; captures d'écran</label>
                                <div class="upload-box" id="imageDrop">
                                    <i class='bx bx-images'></i>
                                    <div class="fw-bold">Cliquez ou glissez vos images</div>
                                    <div class="small opacity-75">PNG, JPG ou WEBP (max 5 Mo)</div>
                                    <input type="file" id="images" name="images[]" multiple accept="image/*" hidden>
                                </div>
                                <div class="preview-container" id="imagePreview"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fichiers du projet (source, docs)</label>
                                <div class="upload-box" id="fileDrop">
                                    <i class='bx bx-file'></i>
                                    <div class="fw-bold">Cliquez ou glissez vos fichiers</div>
                                    <div class="small opacity-75">ZIP, PDF ou autre (max 100 Mo)</div>
                                    <input type="file" id="files" name="files[]" multiple hidden>
                                </div>
                                <div class="file-list mt-3" id="fileList"></div>
                            </div>

                            <div class="col-12 text-center mt-3 pt-3" style="border-top:1px solid var(--ds-border)">
                                <button type="submit" class="btn-publish"><i class='bx bx-rocket'></i> Publier maintenant</button>
                                <p class="small text-muted mt-3 mb-0">Votre projet sera soumis à validation avant d'être visible publiquement.</p>
                            </div>
                        </div>
                    </form>
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
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
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

                    imageFilesList.items.add(file);
                    imageInput.files = imageFilesList.files;

                    div.querySelector('.remove-btn').onclick = () => {
                        const dt = new DataTransfer();
                        Array.from(imageFilesList.files).forEach((f) => {
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
                div.innerHTML = `<span><i class='bx bx-file me-2'></i>${f.name}</span><button type="button">×</button>`;
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
                box.style.borderColor = "var(--ds-brand-400)";
                box.style.background = "var(--ds-brand-50)";
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            box.addEventListener(e, ev => {
                ev.preventDefault();
                box.style.borderColor = "";
                box.style.background = "";
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
        counter.style.color = this.value.length > 2000 ? "var(--ds-danger)" : "var(--ds-muted)";
    };
})();
</script>
</body>
</html>
