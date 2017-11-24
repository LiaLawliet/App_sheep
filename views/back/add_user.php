<?php ob_start() ; ?>
<link rel="stylesheet" href="/assets/css/add_user.css">
<?php $add_usercss = ob_get_clean() ; ?>

<?php ob_start() ; ?>

<section>
	<form action="/auth" method="POST">
	    <p><input type="text" name="name" placeholder="Nom"></p>
	    <p><input type="email" name="email" placeholder="Email"></p>
	    <p><input type="password" name="password" placeholder="Password"></p>
	    <p><input type="password" name="passcheck" placeholder="Check password" ></p>
	    <p><input type="submit" value="Ajouter"></p>
	</form>
</section>

<?php $content = ob_get_clean() ; ?>

<?php include __DIR__ . '/../layouts/master.php' ?>