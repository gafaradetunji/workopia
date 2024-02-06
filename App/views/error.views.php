<?php
loadPartial('head');
loadPartial('navbar');
loadPartial('top-banner');
?>

<section>
    <div class="container mx-auto p-4 mt-4">
        <div class="text-center text-3xl mb-4 font-bold border border-gray-300 p-3"><?= $status ?></div>
        <p class="text-center text-2xl mb-4">
            <?= $message ?>
        </p>
    </div>
</section>

<?php
loadPartial('bottom-banner');
loadPartial('footer');
?>