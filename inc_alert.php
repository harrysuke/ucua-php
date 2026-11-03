<?php
//alert-primary, alert-secondary, alert-success, alert-danger, alert-warning, alert-info, alert-light, alert-dark
	if(isset($_SESSION['status']) && isset($_SESSION['statusType'])){
		echo '<div class="alert alert-'.$_SESSION['statusType'].' alert-dissmissable fade show" role="alert">
		<strong>'.$_SESSION['statusType'].'</strong> '.$_SESSION['status'].'
			<button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>';
		unset($_SESSION['status'], $_SESSION['statusType']);
	}
?>