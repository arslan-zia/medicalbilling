<?php 
	include(dirname(dirname(__FILE__)) . '/class/class.php'); 
	include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Reporting - Leads & Sales - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Reporting - Leads & Sales - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Reporting - Leads & Sales - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Reporting - Leads & Sales - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Reporting - Leads & Sales" />
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
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
										<h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Leads & Sales Reports</h1>
										<!--end::Title-->
										<!--begin::Breadcrumb-->
										<ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">
												<a href="<?php echo ADMIN_URL; ?>" class="text-muted text-hover-primary">Home</a>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item">
												<span class="bullet bg-gray-400 w-5px h-2px"></span>
											</li>
											<!--end::Item-->
											<!--begin::Item-->
											<li class="breadcrumb-item text-muted">Reports</li>
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
									<!--begin::Row-->
									<div class="row gy-5 g-xl-10">
										<!--begin::Col-->
										<div class="col-sm-6 col-xl-3 mb-xl-10">
											<!--begin::Card widget 2-->
											<div class="card h-lg-100">
												<!--begin::Body-->
												<div class="card-body d-flex justify-content-between align-items-start flex-column">
													<!--begin::Icon-->
													<div class="m-0">
														<i class="ki-duotone ki-compass fs-2hx text-gray-600">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</div>
													<!--end::Icon-->
													<!--begin::Section-->
													<div class="d-flex flex-column my-7">
														<!--begin::Number-->
														<span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2"><?php echo $leadModel->getLeadCount(); ?></span>
														<!--end::Number-->
														<!--begin::Follower-->
														<div class="m-0">
															<span class="fw-semibold fs-6 text-gray-400">Leads Count</span>
														</div>
														<!--end::Follower-->
													</div>
													<!--end::Section-->
													<!--begin::Badge-->
													<?php /*
													<span class="badge badge-light-success fs-base">
														<i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>100%
													</span>
													*/ ?>
													<!--end::Badge-->
												</div>
												<!--end::Body-->
											</div>
											<!--end::Card widget 2-->
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col-sm-6 col-xl-3 mb-xl-10">
											<!--begin::Card widget 2-->
											<div class="card h-lg-100">
												<!--begin::Body-->
												<div class="card-body d-flex justify-content-between align-items-start flex-column">
													<!--begin::Icon-->
													<div class="m-0">
														<i class="ki-duotone ki-chart-simple fs-2hx text-gray-600">
															<span class="path1"></span>
															<span class="path2"></span>
															<span class="path3"></span>
															<span class="path4"></span>
														</i>
													</div>
													<!--end::Icon-->
													<!--begin::Section-->
													<div class="d-flex flex-column my-7">
														<!--begin::Number-->
														<span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">
															$<?php echo number_format($leadModel->getTotalSaleAmount(), 2); ?>
														</span>
														<!--end::Number-->
														<!--begin::Follower-->
														<div class="m-0">
															<span class="fw-semibold fs-6 text-gray-400">Total Sale</span>
														</div>
														<!--end::Follower-->
													</div>
													<!--end::Section-->
													
												</div>
												<!--end::Body-->
											</div>
											<!--end::Card widget 2-->
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
											<!--begin::Card widget 2-->
											<div class="card h-lg-100">
												<!--begin::Body-->
												<div class="card-body d-flex justify-content-between align-items-start flex-column">
													<!--begin::Icon-->
													<div class="m-0">
														<i class="ki-duotone ki-abstract-26 fs-2hx text-gray-600">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</div>
													<!--end::Icon-->
													<!--begin::Section-->
													<div class="d-flex flex-column my-7">
														<!--begin::Number-->
														<span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2">
															$<?php echo number_format($leadModel->getTotalDiscountAmount(), 2); ?>
														</span>
														<!--end::Number-->
														<!--begin::Follower-->
														<div class="m-0">
															<span class="fw-semibold fs-6 text-gray-400">Total Discounts Given</span>
														</div>
														<!--end::Follower-->
													</div>
													<!--end::Section-->
													
												</div>
												<!--end::Body-->
											</div>
											<!--end::Card widget 2-->
										</div>
										<!--end::Col-->
										<!--begin::Col-->
										<div class="col-sm-6 col-xl-3 mb-5 mb-xl-10">
											<!--begin::Card widget 2-->
											<div class="card h-lg-100">
												<!--begin::Body-->
												<div class="card-body d-flex justify-content-between align-items-start flex-column">
													<!--begin::Icon-->
													<div class="m-0">
														<i class="ki-duotone ki-abstract-35 fs-2hx text-gray-600">
															<span class="path1"></span>
															<span class="path2"></span>
														</i>
													</div>
													<!--end::Icon-->
													<!--begin::Section-->
													<div class="d-flex flex-column my-7">
														<!--begin::Number-->
														<span class="fw-semibold fs-2x text-gray-800 lh-1 ls-n2"><?php echo $loginModel->getTotalUsers(); ?></span>
														<!--end::Number-->
														<!--begin::Follower-->
														<div class="m-0">
															<span class="fw-semibold fs-6 text-gray-400">Total Users</span>
														</div>
														<!--end::Follower-->
													</div>
													<!--end::Section-->
													
												</div>
												<!--end::Body-->
											</div>
											<!--end::Card widget 2-->
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
									
									<!--begin::Row-->
									<div class="row g-12 g-xl-12">
										<!--begin::Col-->
										<div class="col-md-12 col-xl-12">
											<!--begin::Card-->
											<!--begin::Card header-->
											<div class="card-header border-0 pt-9">
												<!--begin::Card Title-->
												<div class="card-title m-0">
													<div class="fs-3 fw-bold text-dark"> &nbsp; Advanced Search</div>
												</div>
												<!--end::Car Title-->
											</div>
											<!--end:: Card header-->
											<!--begin:: Card body-->
											<div class="card-body p-9">
												<!--begin::Advanced Filters-->
												<form method="get" class="row g-12 mb-12">
													<div class="col-md-10">
														<select name="report_type" class="form-control">
															<option value="">Select Report Type</option>
															<option value="current_month_leads" <?php echo (isset($_GET['report_type']) && $_GET['report_type'] == 'current_month_leads') ? 'selected' : ''; ?>>Current Month Leads</option>
															<option value="leads" <?php echo (isset($_GET['report_type']) && $_GET['report_type'] == 'leads') ? 'selected' : ''; ?>>Leads</option>
															<option value="sales" <?php echo (isset($_GET['report_type']) && $_GET['report_type'] == 'sales') ? 'selected' : ''; ?>>Sales</option>
															<option value="users" <?php echo (isset($_GET['report_type']) && $_GET['report_type'] == 'users') ? 'selected' : ''; ?>>Users</option>
														</select>
													</div>
													<div class="col-md-2">
														<button type="submit" class="btn btn-primary w-100">Filter</button>
													</div>
												</form>
												<!--end::Advanced Filters-->
											</div>
											<!--end:: Card body-->
											<!--end::Card-->
										</div>
										<!--end::Col-->																	
									</div>
									<!--end::Row-->
									
									<!--begin::Row-->
									<div class="row g-12 g-xl-12">
										<!--begin::Col-->
										<div class="col-md-12 col-xl-12">
											<!--begin::Card-->
											<a href="javascript:void(0);" class="card border-hover-primary">
												<?php if ((isset($_GET['report_type']) && $_GET['report_type'] == 'current_month_leads') || !isset($_GET['report_type']) || empty($_GET['report_type'])): ?>
													<!--begin::Card header-->
													<div class="card-header border-0 pt-9">
														<!--begin::Card Title-->
														<div class="card-title m-0">
															<!--begin::Avatar-->
															<div class="symbol symbol-50px w-50px bg-light">
																<img src="<?php echo ASSETS; ?>media/svg/brand-logos/disqus.svg" alt="image" class="p-3" />
															</div>
															<!--end::Avatar-->
															<div class="fs-3 fw-bold text-dark"> &nbsp; Current Month Leads</div>
														</div>
														<!--end::Car Title-->
														<!--begin::Card toolbar-->
														<div class="card-toolbar">
															<span class="badge badge-light fw-bold me-auto px-4 py-3">Leads</span>
														</div>
														<!--end::Card toolbar-->
													</div>
													<!--end:: Card header-->
													<!--begin:: Card body-->
													<div class="card-body p-9">
														<!--begin::Name-->
														<!--end::Name-->
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
															<!--begin::Table head-->
															<thead>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																	<th class="min-w-100px">Client Name</th>
																	<th class="min-w-100px">Client Email</th>
																	<th class="min-w-100px">Client Contact No</th>
																	<th class="min-w-100px">Client Company Name</th>
																	<th class="min-w-100px">Amount</th>
																	<th class="min-w-100px">Discount</th>
																	<th class="min-w-100px">Dispute Date</th>
																	<th class="min-w-100px">Dispute Amount</th>
																	<th class="min-w-125px">Sales Person</th>
																</tr>
																<!--end::Table row-->
															</thead>
															<!--end::Table head-->
															<!--begin::Table body-->
															<tbody class="fw-bold text-gray-600">
																<?php 
																	$getCurrentMonthLeads = $leadModel->getCurrentMonthLeads();

																	$leads = $getCurrentMonthLeads['data'];

																	$totalPages = $getCurrentMonthLeads['total_pages'];

																	$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
																	
																	foreach($leads as $lead) { 
																?>
																	<tr>
																		<td>
																			<span class="text-gray-800 text-hover-primary"><?php echo $lead['customer_name']; ?></span>
																		</td>
																		<td class="text-start"><?php echo $lead['email']; ?></td>												
																		<td class="text-start"><?php echo $lead['contact_no']; ?></td>												
																		<td class="text-start"><?php echo $lead['company_name']; ?></td>												
																		<td class="text-start">$<?php echo $lead['sale_amount']; ?></td>												
																		<td class="text-start">$<?php echo $lead['discount']; ?></td>
																		<td class="text-start"><?php echo $lead['dispute_date']; ?></td>
																		<td class="text-start"><?php echo $lead['dispute_amount']; ?></td>
																		<td class="text-start"><?php echo $lead['sales_person_name']; ?></td>
																	</tr>
																<?php
																	}
																?>														
																</tbody>
															<!--end::Table body-->
														</table>
														<!--end::Table-->
													</div>
													<!--end:: Card body-->
												<?php endif; ?>

												<?php if ((isset($_GET['report_type']) && $_GET['report_type'] == 'leads')): ?>
													<!--begin::Card header-->
													<div class="card-header border-0 pt-9">
														<!--begin::Card Title-->
														<div class="card-title m-0">
															<!--begin::Avatar-->
															<div class="symbol symbol-50px w-50px bg-light">
																<img src="<?php echo ASSETS; ?>media/svg/brand-logos/disqus.svg" alt="image" class="p-3" />
															</div>
															<!--end::Avatar-->
															<div class="fs-3 fw-bold text-dark"> &nbsp; Leads Reports</div>
														</div>
														<!--end::Car Title-->
														<!--begin::Card toolbar-->
														<div class="card-toolbar">
															<span class="badge badge-light fw-bold me-auto px-4 py-3">Leads</span>
														</div>
														<!--end::Card toolbar-->
													</div>
													<!--end:: Card header-->
													<!--begin:: Card body-->
													<div class="card-body p-9">
														<!--begin::Filters-->
														<form method="get" class="row g-3 mb-5">
															<input type="hidden" name="report_type" value="<?=$_GET['report_type']?>">
															<div class="col-md-5">
																<input type="text" name="search" class="form-control" placeholder="Search by name or email or contact no or company name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<input type="number" name="min_amount" class="form-control" placeholder="By min amount" value="<?php echo isset($_GET['min_amount']) ? htmlspecialchars($_GET['min_amount']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<input type="number" name="max_amount" class="form-control" placeholder="By max amount" value="<?php echo isset($_GET['max_amount']) ? htmlspecialchars($_GET['max_amount']) : ''; ?>">
															</div>
															<div class="col-md-3">
																<input type="text" name="date_range" class="form-control date_range" placeholder="Select date range"
																	value="<?php echo isset($_GET['date_range']) ? htmlspecialchars($_GET['date_range']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<select name="sale_person_id" class="form-control">
																	<option value="">All Sales Persons</option>
																	<?php 
																		$salesPersons = $leadModel->getSalesPersons();
																		
																		foreach($salesPersons as $salesPerson):
																	?>
																		<option value="<?php echo $salesPerson['id']; ?>" <?php echo (isset($_GET['sale_person_id']) && $_GET['sale_person_id'] == $salesPerson['id']) ? 'selected' : ''; ?>>
																			<?php echo htmlspecialchars($salesPerson['name']); ?>
																		</option>
																	<?php endforeach; ?>
																</select>
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-primary w-100">Filter</button>
															</div>
														</form>
														<!--end::Filters-->
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
															<!--begin::Table head-->
															<thead>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																	<th class="min-w-100px">Client Name</th>
																	<th class="min-w-100px">Client Email</th>
																	<th class="min-w-100px">Client Contact No</th>
																	<th class="min-w-100px">Client Company Name</th>
																	<th class="min-w-100px">Amount</th>
																	<th class="min-w-100px">Discount</th>
																	<th class="min-w-100px">Sale Person</th>
																	<th class="min-w-100px">Dispute Date & Amount</th>
																	<th class="min-w-100px">Closed At</th>
																	<th class="min-w-100px">Created At</th>
																</tr>
																<!--end::Table row-->
															</thead>
															<!--end::Table head-->
															<!--begin::Table body-->
															<tbody class="fw-bold text-gray-600">
																<?php 
																	$getLeads = $leadModel->getLeads();

																	$leads = $getLeads['data'];

																	$totalPages = $getLeads['total_pages'];

																	$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

																	foreach($leads as $lead) { 
																?>
																	<tr>
																		<td>
																			<span class="text-gray-800 text-hover-primary"><?php echo $lead['customer_name']; ?></span>
																		</td>																				
																		<td class="text-start"><?php echo $lead['email']; ?></td>												
																		<td class="text-start"><?php echo $lead['contact_no']; ?></td>												
																		<td class="text-start"><?php echo $lead['company_name']; ?></td>												
																		<td class="text-start">$<?php echo $lead['sale_amount']; ?></td>
																		<td class="text-start">$<?php echo $lead['discount']; ?></td>
																		<td class="text-start"><?php echo $lead['sales_person_name']; ?></td>	
																		<td class="text-start"><?php echo $lead['dispute_date'] != '-' ? $lead['dispute_date'] . ' - ' . $lead['dispute_amount'] : $lead['dispute_date']; ?></td>
																		<td class="text-start"><?php echo date('Y-m-d', strtotime($lead['date'])); ?></td>	
																		<td class="text-start"><?php echo date('Y-m-d', strtotime($lead['added_date'])); ?></td>	
																	</tr>
																<?php
																	}
																?>														
																</tbody>
															<!--end::Table body-->
														</table>
														<!--end::Table-->
													</div>
													<!--end:: Card body-->
												<?php endif; ?>

												<?php if ((isset($_GET['report_type']) && $_GET['report_type'] == 'sales')): ?>
													<!--begin::Card header-->
													<div class="card-header border-0 pt-9">
														<!--begin::Card Title-->
														<div class="card-title m-0">
															<!--begin::Avatar-->
															<div class="symbol symbol-50px w-50px bg-light">
																<img src="<?php echo ASSETS; ?>media/svg/brand-logos/disqus.svg" alt="image" class="p-3" />
															</div>
															<!--end::Avatar-->
															<div class="fs-3 fw-bold text-dark"> &nbsp; Sales Reports</div>
														</div>
														<!--end::Car Title-->
														<!--begin::Card toolbar-->
														<div class="card-toolbar">
															<span class="badge badge-light fw-bold me-auto px-4 py-3">Sales</span>
														</div>
														<!--end::Card toolbar-->
													</div>
													<!--end:: Card header-->
													<!--begin:: Card body-->
													<div class="card-body p-9">
														<!--begin::Filters-->
														<form method="get" class="row g-3 mb-5">
															<input type="hidden" name="report_type" value="<?=$_GET['report_type']?>">
															<div class="col-md-5">
																<input type="text" name="search" class="form-control" placeholder="Search by name or email or contact no or company name" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<input type="number" name="min_amount" class="form-control" placeholder="By min amount" value="<?php echo isset($_GET['min_amount']) ? htmlspecialchars($_GET['min_amount']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<input type="number" name="max_amount" class="form-control" placeholder="By max amount" value="<?php echo isset($_GET['max_amount']) ? htmlspecialchars($_GET['max_amount']) : ''; ?>">
															</div>
															<div class="col-md-3">
																<input type="text" name="date_range" class="form-control date_range" placeholder="Select date range"
																	value="<?php echo isset($_GET['date_range']) ? htmlspecialchars($_GET['date_range']) : ''; ?>">
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-primary w-100">Filter</button>
															</div>
														</form>
														<!--end::Filters-->
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
															<!--begin::Table head-->
															<thead>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																	<th class="min-w-100px">Client Name</th>
																	<th class="min-w-100px">Client Email</th>
																	<th class="min-w-100px">Client Contact No</th>
																	<th class="min-w-100px">Client Company Name</th>
																	<th class="min-w-100px">Amount</th>
																	<th class="min-w-100px">Discount</th>
																	<th class="min-w-100px">Dispute Date</th>
																	<th class="min-w-100px">Dispute Amount</th>
																	<th class="min-w-100px">Closed At</th>
																	<th class="min-w-100px">Created At</th>
																</tr>
																<!--end::Table row-->
															</thead>
															<!--end::Table head-->
															<!--begin::Table body-->
															<tbody class="fw-bold text-gray-600">
																<?php 
																	$getLeads = $leadModel->getLeads();

																	$leads = $getLeads['data'];

																	$totalPages = $getLeads['total_pages'];

																	$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

																	foreach($leads as $lead) { 
																?>
																	<tr>
																		<td>
																			<span class="text-gray-800 text-hover-primary"><?php echo $lead['customer_name']; ?></span>
																		</td>																				
																		<td class="text-start"><?php echo $lead['email']; ?></td>												
																		<td class="text-start"><?php echo $lead['contact_no']; ?></td>												
																		<td class="text-start"><?php echo $lead['company_name']; ?></td>												
																		<td class="text-start">$<?php echo $lead['sale_amount']; ?></td>
																		<td class="text-start">$<?php echo $lead['discount']; ?></td>
																		<td class="text-start"><?php echo $lead['dispute_date']; ?></td>
																		<td class="text-start"><?php echo $lead['dispute_amount']; ?></td>
																		<td class="text-start"><?php echo date('Y-m-d', strtotime($lead['date'])); ?></td>	
																		<td class="text-start"><?php echo date('Y-m-d', strtotime($lead['added_date'])); ?></td>	
																	</tr>
																<?php
																	}
																?>														
																</tbody>
															<!--end::Table body-->
														</table>
														<!--end::Table-->
													</div>
													<!--end:: Card body-->
												<?php endif; ?>

												<?php if ((isset($_GET['report_type']) && $_GET['report_type'] == 'users')): ?>
													<!--begin::Card header-->
													<div class="card-header border-0 pt-9">
														<!--begin::Card Title-->
														<div class="card-title m-0">
															<!--begin::Avatar-->
															<div class="symbol symbol-50px w-50px bg-light">
																<img src="<?php echo ASSETS; ?>media/svg/brand-logos/disqus.svg" alt="image" class="p-3" />
															</div>
															<!--end::Avatar-->
															<div class="fs-3 fw-bold text-dark"> &nbsp; User Reports</div>
														</div>
														<!--end::Car Title-->
														<!--begin::Card toolbar-->
														<div class="card-toolbar">
															<span class="badge badge-light fw-bold me-auto px-4 py-3">Users</span>
														</div>
														<!--end::Card toolbar-->
													</div>
													<!--end:: Card header-->
													<!--begin:: Card body-->
													<div class="card-body p-9">
														<!--begin::Filters-->
														<form method="get" class="row g-3 mb-5">
															<input type="hidden" name="report_type" value="<?=$_GET['report_type']?>">
															<div class="col-md-4">
																<input type="text" name="search" class="form-control" placeholder="Search by user name or email" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
															</div>
															<div class="col-md-2">
																<select name="role" class="form-control">
																	<option value="">All Roles</option>
																	<?php 
																		$getRoles = $leadModel->getRoles();
																		
																		foreach($getRoles as $role):
																	?>
																		<option value="<?php echo $role['id']; ?>" <?php echo (isset($_GET['role']) && $_GET['role'] == $role['id']) ? 'selected' : ''; ?>>
																			<?php echo htmlspecialchars($role['name']); ?>
																		</option>
																	<?php endforeach; ?>
																</select>
															</div>
															<div class="col-md-2">
																<select name="status" class="form-control">
																	<option value="">All Statuses</option>
																	<option value="1" <?php echo (isset($_GET['status']) && $_GET['status'] == 1) ? 'selected' : ''; ?>>Active</option>
																	<option value="0" <?php echo (isset($_GET['status']) && $_GET['status'] == 0) ? 'selected' : ''; ?>>In-Active</option>
																</select>
															</div>
															<div class="col-md-3">
																<select name="manager_id" class="form-control">
																	<option value="">All Managers</option>
																	<?php 
																		$managers = $leadModel->getManagers();
																		
																		foreach($managers as $manager):
																	?>
																		<option value="<?php echo $manager['manager_id']; ?>" <?php echo (isset($_GET['manager_id']) && $_GET['manager_id'] == $manager['manager_id']) ? 'selected' : ''; ?>>
																			<?php echo htmlspecialchars($manager['manager_name']); ?>
																		</option>
																	<?php endforeach; ?>
																</select>
															</div>
															<div class="col-md-1">
																<button type="submit" class="btn btn-primary w-100">Filter</button>
															</div>
														</form>
														<!--end::Filters-->
														<!--begin::Table-->
														<table class="table align-middle table-row-dashed fs-6 gy-3" id="kt_table_widget_4_table">
															<!--begin::Table head-->
															<thead>
																<!--begin::Table row-->
																<tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
																	<th class="min-w-100px">User Name</th>
																	<th class="min-w-100px">User Email</th>
																	<th class="min-w-100px">User Role</th>
																	<th class="min-w-100px">User Status</th>
																	<th class="min-w-100px">User Manager</th>
																	<th class="min-w-100px">User Total Leads</th>
																	<th class="min-w-100px">User Leads Amount</th>
																</tr>
																<!--end::Table row-->
															</thead>
															<!--end::Table head-->
															<!--begin::Table body-->
															<tbody class="fw-bold text-gray-600">
																<?php 
																	$getUsers = $leadModel->getUsers();

																	$users = $getUsers['data'];

																	$totalPages = $getUsers['total_pages'];

																	$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

																	foreach($users as $user) { 
																?>
																	<tr>
																		<td>
																			<span class="text-gray-800 text-hover-primary"><?php echo $user['user_name']; ?></span>
																		</td>										
																		<td class="text-start"><?php echo $user['user_email']; ?></td>												
																		<td class="text-start"><?php echo $user['user_right']; ?></td>												
																		<td class="text-start"><?php echo $user['user_status']; ?></td>												
																		<td class="text-start"><?php echo $user['manager_name']; ?></td>												
																		<td class="text-start"><?php echo $user['total_sales']; ?></td>												
																		<td class="text-start">$<?php echo $user['total_sales_amount']; ?></td>
																	</tr>
																<?php
																	}
																?>														
																</tbody>
															<!--end::Table body-->
														</table>
														<!--end::Table-->
													</div>
													<!--end:: Card body-->
												<?php endif; ?>
											</a>
											<!--end::Card-->
										</div>
										<!--end::Col-->	
										<!--begin::Pagination-->
										<?php if ($totalPages > 1): ?>
											<nav aria-label="Page navigation">
												<ul class="pagination">
													<li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
														<a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])); ?>" tabindex="-1">Previous</a>
													</li>

													<?php for ($p = 1; $p <= $totalPages; $p++): ?>
														<li class="page-item <?php echo ($p == $page) ? 'active' : ''; ?>">
															<a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $p])); ?>">
																<?php echo $p; ?>
																<?php if ($p == $page): ?><span class="sr-only">(current)</span><?php endif; ?>
															</a>
														</li>
													<?php endfor; ?>

													<li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
														<a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])); ?>">Next</a>
													</li>
												</ul>
											</nav>
										<?php endif; ?>
										<!--end::Pagination-->																
									</div>
									<!--end::Row-->

									<!--begin::Chat-->
									<?php include('includes/chat.php'); ?>
									<!--end::Chat-->
								</div>
								<!--end::Content container-->
							</div>
							
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php include('includes/footer-bottom.php'); ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::App-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo ASSETS; ?>";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo ASSETS; ?>plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--begin::Vendors Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>plugins/custom/datatables/datatables.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>plugins/custom/vis-timeline/vis-timeline.bundle.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/map.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
		<script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
		<!--end::Vendors Javascript-->
		<!--begin::Custom Javascript(used for this page only)-->
		<script src="<?php echo ASSETS; ?>js/widgets.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/widgets.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/apps/chat/chat.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/new-target.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/new-address.js"></script>
		<script src="<?php echo ASSETS; ?>js/custom/utilities/modals/users-search.js"></script>
		<!--end::Custom Javascript-->
		<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<script>
			$('.date_range').daterangepicker({
				locale: {
					format: 'YYYY-MM-DD'
				},
				autoUpdateInput: false
			});

			$('.date_range').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
			});

			$('.date_range').on('cancel.daterangepicker', function(ev, picker) {
				$(this).val('');
			});

			let preselected = "<?php echo isset($_GET['date_range']) ? $_GET['date_range'] : ''; ?>";
			
			if (preselected) {
				let dates = preselected.split(' - ');
				
				$('.date_range').data('daterangepicker').setStartDate(dates[0]);
				
				$('.date_range').data('daterangepicker').setEndDate(dates[1]);
				
				$('.date_range').val(preselected);
			}
		</script>
		<!--end::Javascript-->
	</body>
	<!--end::Body-->
</html>