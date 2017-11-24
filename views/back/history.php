<?php ob_start() ; ?>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<?php $historycss = ob_get_clean() ; ?>

<?php ob_start() ; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<table>
    <tr>
        <th>Name</th>
        <th>Price</th> 
        <th>Date</th>
    </tr>
    <?php if( $depenses != false ) : ?>
    <?php foreach ($depenses as $data) : ?>
    <tr>
        <td><?php echo htmlentities($data['name']); ?></td>
        <td><?php echo htmlentities($data['price']); ?></td>
        <td><?php echo htmlentities($data['pay_date']); ?></td>
    </tr>
    <?php endforeach; ?>
    <ul style="list-style-type: none">
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <li style="display: inline-block;">
            <a href="/history/?page=<?php echo intval($i); ?>">
                <?php echo intval($i); ?>
            </a>
        </li>
         <?php endfor; ?>
    </ul>
    <?php else : ?>
        <p>Pas de dépenses pour l'instant </p>
    <?php endif; ?>
</table>
<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>