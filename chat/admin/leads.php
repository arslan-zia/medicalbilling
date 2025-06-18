<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>All Leads - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Dashboard - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Dashboard" />
		<link rel="canonical" href="<?php echo SERVER; ?>" />
		<link rel="shortcut icon" href="<?php echo ASSETS; ?>media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Vendor Stylesheets(used for this page only)-->
		<link href="<?php echo ASSETS; ?>plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo ASSETS; ?>plugins/custom/vis-timeline/vis-timeline.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="<?php echo ASSETS; ?>plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo ASSETS; ?>css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
		<script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<?php include('includes/header.php'); ?>
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--begin::Sidebar-->
					<?php include('includes/sidebar.php'); ?>
					<!--end::Sidebar-->
					<!--begin::Main-->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Toolbar-->
							<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
								<!--begin::Toolbar container-->
								<div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
									<!--begin::Page title-->
									<div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
										<!--begin::Title-->
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">All Leads</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo SERVER; ?>" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Leads</li>
											<!--end::Item-->
										</ul>
										<!--end::Breadcrumb-->
									</div>
									<!--end::Page title-->
								</div>
								<!--end::Toolbar container-->
							</div>
							<!--end::Toolbar-->
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
									<!--begin::Card-->
									<div class="card">
										<!--begin::Card header-->
										<div class="card-header border-0 pt-6">
											<!--begin::Card title-->
											<div class="card-title">
												<!--begin::Search-->
												<div class="d-flex align-items-center position-relative my-1">
													<span class="svg-icon svg-icon-1 position-absolute ms-6">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
															<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
															<path d="M11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11C19 15.4183 15.4183 19 11 19ZM11 5C7.68629 5 5 7.68629 5 11C5 14.3137 7.68629 17 11 17C14.3137 17 17 14.3137 17 11C17 7.68629 14.3137 5 11 5Z" fill="currentColor"></path>
														</svg>
													</span>
													<script>
														document.addEventListener('DOMContentLoaded', function() {
															const searchInput = document.querySelector('input[data-kt-customer-table-filter="search"]');
															const tableRows = document.querySelectorAll('#kt_customers_table tbody tr');

															searchInput.addEventListener('keyup', function() {
																const searchTerm = searchInput.value.toLowerCase();

																tableRows.forEach(row => {
																	const customerName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
																	const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
																	const contactNo = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
																	const companyName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

																	if (customerName.includes(searchTerm) || email.includes(searchTerm) || contactNo.includes(searchTerm) || companyName.includes(searchTerm)) {
																		row.style.display = '';
																	} else {
																		row.style.display = 'none';
																	}
																});
															});
														});
													</script>
													<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Leads">
												</div>
												<!--end::Search-->
											</div>
											<!--end::Card title-->
											<!--begin::Card toolbar-->
											<div class="card-toolbar">
												<!--begin::Toolbar-->
												<div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
													<!--begin::Add lead-->
													<a href="add_lead.php" class="btn btn-primary">Add Lead</a>
													<!--end::Add lead-->
												</div>
												<!--end::Toolbar-->
											</div>
											<!--end::Card toolbar-->
										</div>
										<!--end::Card header-->
										<!--begin::Card body-->
										<div class="card-body pt-0">
											<!--begin::Table-->
											<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
												<!--begin::Table head-->
												<thead>
													<!--begin::Table row-->
													<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
														<th class="min-w-125px">Customer Name</th>
														<th class="min-w-125px">Email</th>
														<th class="min-w-125px">Contact No</th>
														<th class="min-w-125px">Company Name</th>
														<th class="min-w-125px">Sale Amount</th>
														<th class="min-w-125px">Status</th>
														<th class="text-end min-w-70px">Actions</th>
													</tr>
													<!--end::Table row-->
												</thead>
												<!--end::Table head-->
												<!--begin::Table body-->
												<tbody class="fw-semibold text-gray-600">
													<?php
														$leads = $leadModel->getAllLeads();
														foreach($leads as $lead) {
													?>
													<tr>
														<td><?php echo htmlspecialchars($lead['customer_name']); ?></td>
														<td><?php echo htmlspecialchars($lead['email']); ?></td>
														<td><?php echo htmlspecialchars($lead['contact_no']); ?></td>
														<td><?php echo htmlspecialchars($lead['company_name']); ?></td>
														<td><?php echo htmlspecialchars($lead['sale_amount']); ?></td>
														<td><?php echo $lead['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
														<td class="text-end">

															
															<a href="<?php echo ADMIN_URL; ?>view_lead.php?lead_id=<?php echo $lead['lead_id']; ?>" class="fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" title="View Lead">
																<i class="bi bi-eye fs-6 text-muted me-1"></i>
															</a>
															&nbsp;
															<a href="<?php echo ADMIN_URL; ?>edit_lead.php?lead_id=<?php echo $lead['lead_id']; ?>" class="fw-bold" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" title="Edit Lead">
																<i class="bi bi-pencil fs-6 text-muted me-1"></i>
															</a>
															&nbsp;
															<a href="javascript:void(0);" class="fw-bold delete-lead" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-lead-id="<?php echo $lead['lead_id']; ?>" title="Delete Lead">
																<i class="bi bi-trash fs-6 text-muted me-1"></i>
															</a>

														</td>
													</tr>
													<?php } ?>
												</tbody>
												<!--end::Table body-->
											</table>
											<!--end::Table-->
										</div>
										<!--end::Card body-->
									</div>
									<!--end::Card-->
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
					</div>
					<!--end::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo ASSETS; ?>";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo ASSETS; ?>plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>plugins/custom/datatables/datatables.bundle.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>js/custom/apps/customers/list/list.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/customers/add.js"></script>
		<!--end::Custom Javascript-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

	<script type="text/javascript">
		$(document).ready(function() {
			$('.delete-lead').click(function() {
				var leadId = $(this).data('lead-id');
				if (confirm('Are you sure you want to delete this lead?')) {
					deleteLead(leadId);
					//alert('Lead deleted successfully');
					//location.reload();
				}
			});
		});

		function deleteLead(leadId)
		{
			var result = $.ajax({
				type: "POST",
				url: "ajax.php",
				data: "work=deleteLead&leadId=" + leadId,
				async: false
				}).responseText.split('=====');	
				console.log(result);
				if(result[0] == 'success')
				{
					//$('#deleteIPSetting-' + ipSettingID).css('display', 'none');
					alert('Lead deleted successfully');
					location.reload();
				}
				else
				{
					alert("Error: Lead Deletion Failed");
				}
		}
	</script>

</html>