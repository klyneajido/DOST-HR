<?php
include("PHP_Connections/fetch_homepage.php");
include("PHP_Connections/recent_activities.php");
include("PHP_Connections/upcoming_interview.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<title>HRMO Admin Dashboard</title>

	<link rel="shortcut icon" href="assets/img/dost_logo.png" type="image/png">

	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="assets/css/dashboard.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


</head>

<body class="scrollbar" id="style-5">
	<?php include("modal_logout.php") ?>
	<div class="main-wrapper">
		<?php include("navbar.php") ?>
		<div class="page-wrapper">
			<div class="content container-fluid">
				<div class="page-name 	mb-4">
					<h4 class="m-0"><img src="<?php echo htmlspecialchars($profile_image_path); ?>" class="mr-1" alt="profile" /> Welcome <span><?php echo htmlspecialchars($user_name); ?></span></h4>
					<label id="current-date"></label>
				</div>
				<div class="row mb-4">
					<div class="col-xl-12 col-sm-12 col-12">
						<div class="breadcrumb-path ">
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php"><img src="assets/img/dash.png" class="mr-3" alt="breadcrumb" />Home</a>
								</li>
								<li class="breadcrumb-item active">Dashboard</li>
							</ul>
							<h3>Admin Dashboard</h3>
						</div>
					</div>
				</div>
				<div class="row mb-4" id="ap">

					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill1 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Announcements</label>
									<h4><?php echo $announcement_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/announcement.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<!-- CODING LIKE A MADMAN YOWWWWW -->
					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill2 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Job Listed</label>
									<h4><?php echo $job_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/job.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-sm-6 col-12">
						<div class="card board1 fill3 ">
							<div class="card-body">
								<div class="card_widget_header">
									<label>Applicants</label>
									<h4><?php echo $applicant_count; ?></h4>
								</div>
								<div class="card_widget_img">
									<img src="assets/img/applicants.png" alt="card-img" />
								</div>
							</div>
						</div>
					</div>
				</div>


				<!-- Applicant filter -->
				<div class="row">
					<div class="col-xl-12 col-sm-12 col-12">
						<div class="card card-list py-3">
							<div class="card-header d-flex">
								<h4 class="col-md-8 pt-2"> <strong>
										Applicant by Job Title and Position</strong>
								</h4>
								<div class="col-md-4 user-menu justify-content-end align-items-center z-4">
									<a href="?filter=title#ap" class="sort-btn">Sort</a>
									<a href="index.php#ap">
									<button id="reset-filters" class="button ">
                                    <svg class="svg-icon" fill="none" height="20" viewBox="0 0 20 20" width="20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g stroke="#ff342b" stroke-linecap="round" stroke-width="1.5">
                                            <path
                                                d="m3.33337 10.8333c0 3.6819 2.98477 6.6667 6.66663 6.6667 3.682 0 6.6667-2.9848 6.6667-6.6667 0-3.68188-2.9847-6.66664-6.6667-6.66664-1.29938 0-2.51191.37174-3.5371 1.01468">
                                            </path>
                                            <path
                                                d="m7.69867 1.58163-1.44987 3.28435c-.18587.42104.00478.91303.42582 1.0989l3.28438 1.44986">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
									</a>
								</div>
							</div>

							<div class="card-body overflow-auto">
								<?php foreach ($positions_to_display as $general) : ?>
									<div>
										<div class="team-list ">
											<div class="team-view justify-content-between shadow-sm">
												<div class="row col-md-auto">
													<div class="team-img px-2 btn-info rounded-circle mx-2 my-2 disabled">
														<h4 class="text-light"><?php echo $general['total_count']; ?></h4>
													</div>
													<div class="team-content my-auto">
														<label><?php echo $general['general_title']; ?></label>
													</div>
												</div>
												<div class="mx-3">
												<a href="#" class="toggle-positions" data-id="<?php echo $general['general_title']; ?>"><i data-feather="chevron-down"></i></a>
												</div>
											</div>
										</div>

										<div>
											<div class="specific-positions" id="positions-<?php echo $general['general_title']; ?>" style="display: none; margin-left: 5%;">
												<?php foreach ($general['specific_positions'] as $specific) : ?>
													<div class="mb-3 filter-content-indiv">
														<div class="team-view shadow-sm">
															<div class="team-img px-2 btn-light rounded-circle mx-2 disabled" style="background:lightblue;">
																<h4 class="text-body-tertiary"><?php echo $specific['specific_position']; ?></h4>
															</div>
															<div class="team-content my-auto">
																<label><?php echo $specific['specific_count']; ?></label>
															</div>
														</div>
													</div>
												<?php endforeach; ?>
											</div>
										</div>

									</div>
								<?php endforeach; ?>

							</div>

							<!-- Pagination Links -->
							<nav aria-label="Page navigation example">
								<ul class="pagination justify-content-center">
									<?php if ($page > 1) : ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
												<span aria-hidden="true">&laquo;</span>
											</a>
										</li>
									<?php endif; ?>

									<?php for ($i = 1; $i <= $total_pages; $i++) : ?>
										<li class="page-item <?php if ($i == $page) {
										    echo 'active';
										} ?>">
											<a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
										</li>
									<?php endfor; ?>

									<?php if ($page < $total_pages) : ?>
										<li class="page-item">
											<a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
												<span aria-hidden="true">&raquo;</span>
											</a>
										</li>
									<?php endif; ?>
								</ul>
							</nav>

						</div>
					</div>
				</div>

				<script>
					document.addEventListener('DOMContentLoaded', function() {
						document.querySelectorAll('.toggle-positions').forEach(function(toggle) {
							toggle.addEventListener('click', function(event) {
								event.preventDefault();
								var id = toggle.getAttribute('data-id');
								var positionsDiv = document.getElementById('positions-' + id);
								if (positionsDiv.style.display === 'none') {
									positionsDiv.style.display = 'block';
									toggle.querySelector('i').setAttribute('data-feather', 'chevron-up');
								} else {
									positionsDiv.style.display = 'none';
									toggle.querySelector('i').setAttribute('data-feather', 'chevron-down');
								}
								feather.replace();
							});
						});
					});
				</script>

				<!-- history and interview cards -->
				<div class="row">
<div class="col-xl-8 col-sm-12 col-12 d-flex">
    <div class="card card-list flex-fill">
        <div class="card-header">
            <h4 class="card-title">Recent Activities</h4>
        </div>
        <div class="card-body dash-activity">
            <div class="slimscroll activity_scroll">
                <?php if (empty($recent_activities)) : ?>
                    <div class="d-flex flex-column align-items-center justify-content-center text-center min-height-200">
                        <i class="bi bi-exclamation-circle fs-2 text-muted mb-2"></i>
                        <p class="text-muted mb-0">No recent activities yet.</p>
                    </div>
                <?php else : ?>
                    <?php foreach ($recent_activities as $activity) : ?>
                        <div class="activity-set d-flex align-items-start py-3 px-4">
                            <div class="activity-img mr-3 align-self-center">
                                <img src="<?php echo htmlspecialchars($activity['profile_image']); ?>" alt="avatar" class="rounded-circle">
                            </div>
                            <div class="activity-content w-100">
                                <div class="d-flex justify-content-between w-100 mb-1">
                                    <span class="font-weight-bold"><?php echo htmlspecialchars($activity['activity']); ?></span>
                                    <span class="text-muted small"><?php echo htmlspecialchars($activity['formatted_timestamp']); ?></span>
                                </div>
                                <div><?php echo htmlspecialchars($activity['name']) . ' has ' . htmlspecialchars($activity['activity']); ?></div>
                                <div class="text-muted mt-1"><?php echo htmlspecialchars($activity['details']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="leave-viewall mt-3">
                <a href="view_history.php">View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow"></a>
            </div>
        </div>
    </div>
</div>


			           <div class="col-xl-4 col-sm-12 col-12 d-flex">
							<div class="card card-list flex-fill">
								<div class="card-header">
									<h4 class="card-title">Upcoming Interview</h4>
								</div>
								<div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
									<?php if (!empty($upcoming_interviews)): ?>
										<?php foreach ($upcoming_interviews as $interview): ?>
											<div class="leave-set d-flex align-items-center justify-content-between py-2 px-3">
												<div class="d-flex align-items-center">
													<span class="leave-active d-flex align-items-center justify-content-center rounded-circle shadow-sm bg-success text-white p-2 mr-2">
														<i class="fas fa-briefcase"></i>
													</span>
													<label class="mb-0"><?php echo htmlspecialchars($interview['interview_date']); ?> - <?php echo htmlspecialchars($interview['full_name']); ?></label>
												</div>
												<label class="mb-0"><?php echo htmlspecialchars($interview['job_title']); ?></label>
											</div>
										<?php endforeach; ?>
									<?php else: ?>
										<div class="leave-set d-flex align-items-center justify-content-between py-2 px-3">
											<div class="d-flex align-items-center">
												<span class="leave-inactive d-flex align-items-center justify-content-center rounded-circle shadow-sm bg-light text-muted p-2 mr-2">
													<i class="fas fa-briefcase"></i>
												</span>
												<label class="mb-0">No upcoming interviews</label>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<div class="leave-viewall text-center py-2">
									<a href="view_applicants.php">View all <img src="assets/img/right-arrow.png" class="ml-2" alt="arrow" /></a>
								</div>
							</div>
						</div>


				</div>
			</div>

		</div>
	</div>

	</div>
	<script src="assets/js/date.js"></script>
	<script src="assets/js/jquery-3.6.0.min.js"></script>
	<!-- <script>
		document.getElementById('logoutLink').addEventListener('click', function(event) {
			event.preventDefault();
			$('#logoutModal').modal('show');
		});

		document.getElementById('confirmLogout').addEventListener('click', function() {
			window.location.href = 'PHP_Connections/logout.php';
		});
	</script> -->
	
<script src="assets/js/date.js"></script>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/applicant.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>



</body>

</html>