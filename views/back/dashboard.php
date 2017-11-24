<?php ob_start() ; ?>
<link rel="stylesheet" href="/assets/css/dashboard.css">
<?php $dashboardcss = ob_get_clean() ; ?>

<?php ob_start() ; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<section class="sheep__main dashboard">
    <section class="sheep__graph grid-2"> 
      <?php include  __DIR__ . '/../partials/graphic.php'; ?>
	</section>
    <section class="sheep__spending grid-1"> 
       <?php if( $res != false ) : ?>
       	<ul>
        	<?php while( $data = $res->fetch() ) : ?>
            	<li>Nom(s): <?php echo $data['names']; ?>, Prix : <?php echo $data['price']; ?>, date : <?php echo $data['pay_date']; ?></li>
            <?php endwhile ; ?>
        </ul>
       <?php else : ?>
       	<p>Pas de dépenses pour l'instant </p>
       <?php endif; ?>
    </section>
    <form action="/history" method="POST">
    	<input type="submit" name="History" value="History"/>
	</form>
</section>

<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>