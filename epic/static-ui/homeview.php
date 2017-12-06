<!-- ------------------------------------------------ -->
<!-- This is the main template for the Home Page View -->
<!-- ------------------------------------------------ -->
<!DOCTYPE html>
<html>

<!-- inject head-utils -->
<?php require_once ("head-utils.php");?>

<body class="sfooter">
<div class="sfooter-content">

    <!-- inject navbar -->
    <?php require_once("navbar.php"); ?>

    <!-- begin Home Page layout -->
    <main class="my-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-lg-3">

                    <!-- inject admin panel -->
                    <?php require_once ("adminpanel.php");?>

                </div>
                <div class="col-md-6 col-lg-6">

                    <!-- inject content panel -->
                    <?php require_once ("content-panel.php");?>

                </div>
            </div>
        </div>
    </main>

</div>

	<!-- inject footer -->
	<?php require_once ("footer.php");?>

	<!-- inject modal window -->
	<?php require_once ("signin-modal.php");?>
	<?php require_once ("signup-modal.php");?>
	<?php require_once ("edit-profile-modal.php");?>
</body>
</html>