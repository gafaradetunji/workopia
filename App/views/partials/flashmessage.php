<?php

use Framework\Session;

$successMessage = Session::getFlash('success_message');
$errorMessage = Session::getFlash('error_message');

?>

<?php if ($successMessage !== null) : ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold"><?= $successMessage; ?></strong>
    </div>
<?php endif; ?>

<?php if ($errorMessage !== null) : ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold"><?= $errorMessage; ?></strong>
    </div>
<?php endif; ?>