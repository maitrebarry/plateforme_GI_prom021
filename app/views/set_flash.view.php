<?php if (isset($_SESSION['notification']['message'])):

$icon_class = '';
$border_class = '';
$text_color_class = ''; 
switch ($_SESSION['notification']['type']) {
    case 'info':
    
        $icon_class = 'fa-solid fa-circle-info align-middle';
        $border_class = 'border-0 border-info bg-light-info'; // Light background for info
        $text_color_class = 'text-info'; // Text color for info
        break;
    case 'warning':
        $icon_class = 'fa-solid fa-triangle-exclamation label-icon';
        $border_class = 'border-0 border-warning bg-light-warning'; // Light background for warning
        $text_color_class = 'text-warning'; // Text color for warning
        break;
    case 'danger':
        $icon_class = 'fa-solid fa-circle-xmark label-icon';
        $border_class = 'border-0 border-danger bg-light-danger'; // Light background for danger
        $text_color_class = 'text-danger'; // Text color for danger
        break;
    case 'success':
        $icon_class = 'fa-solid fa-circle-check label-icon';
        $border_class = 'border-0 border-success bg-light-success'; // Light background for success
        $text_color_class = 'text-success'; // Text color for success
        break;
    default:
        // Par défaut, utiliser les styles de succès
        $icon_class = 'fa-solid fa-circle-check label-icon';
        $border_class = 'border-0 border-success bg-light-success';
        $text_color_class = 'text-success'; // Text color for default
        break;
}
?>
<div class="container">
    <div class="alert alert-<?= $_SESSION['notification']['type'] ?> <?= $border_class ?> border-start border-4 alert-dismissible fade show py-2" role="alert">
        <div class="d-flex align-items-center">
            <div class="fs-3 <?= $text_color_class ?>">
                <i class="<?= $icon_class ?>"></i>
            </div>
            <div class="ms-3 <?= $text_color_class ?>">
                <?= $_SESSION['notification']['message'] ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
<?php $_SESSION['notification'] = []; ?>
<?php endif; ?>
