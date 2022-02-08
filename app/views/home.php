<?php $this->layout('master', ['title' => 'Listagem']) ?>

<h1>Lista de users</h1>

<ul id="list">
    <?php foreach ($users as $user): ?>
        <li><?php echo $user->firstName; ?></li>
    <?php endforeach; ?>
</ul>


<?php echo $links ?>