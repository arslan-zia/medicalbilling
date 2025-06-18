<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
  include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>Chats - <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="Chats - <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="Chats - <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Chats - <?php echo SITE_NAME; ?>" />
		<meta property="og:url" content="<?php echo SERVER; ?>" />
		<meta property="og:site_name" content="<?php echo SITE_NAME; ?> | Chats" />
		<link rel="canonical" href="<?php echo SERVER; ?>" />
		<link rel="shortcut icon" href="<?php echo ASSETS; ?>media/logos/favicon.ico" />
		<!--begin::Fonts(mandatory for all pages)-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		<!--end::Fonts-->

		<!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
		<link href="<?php echo ASSETS; ?>plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo ASSETS; ?>css/style.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Global Stylesheets Bundle-->
	</head>
	<!--end::Head-->

	<!--begin::Body-->
	<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
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
              <!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-xxl">
                
                <div class="messenger-wrapper d-flex bg-white rounded shadow-sm">
                  <!--begin::Chat Sidebar-->
                  <div class="chat-sidebar p-4 border-end">
                    <div class="mb-4">
                      <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="chatSearch" class="form-control border-start-0 search-input" placeholder="Search a conversation" onkeyup="searchChat()"/>
                      </div>
                    </div>

                    <!--begin::Chat List-->
                    <div class="chat-user-list" id="chatUsers"></div>
                    <!--end::Chat List-->
                  </div>
                  <!--end::Chat Sidebar-->

                  <!--begin::Chat Area-->
                  <div class="chat-area d-flex flex-column" id="chatWindow">
                    <!--begin::Chat Header-->
                    <div class="chat-header d-flex align-items-center justify-content-between px-4 py-3 border-bottom">
                      <div id="chatBox">
                        <div class="d-flex align-items-center">
                          <img src="" class="avatar me-2 chat-image avatar" id="chatWithImage"/>
                          <div>
                            <div class="chat-name fw-semibold" id="chatWithName"></div>
                          </div>
                        </div>
                      </div>
                      <div class="chat-actions d-flex gap-2">
                        <button class="btn btn-sm btn-icon btn-light" onclick="closeChat()"><i class="bi bi-x-lg"></i></button>
                      </div>
                    </div>
                    <!--end::Chat Header-->

                    <!--begin::Chat Body-->
                    <div class="chat-body flex-grow-1 px-4 py-3 overflow-auto" style="background: linear-gradient(to top right, #e6ebff, #f3f6ff);" id="chatMessages"></div>
                    <!--end::Chat Body-->

                    <!--begin::Chat Footer -->
                    <div class="chat-footer d-flex align-items-center px-4 py-3 border-top">
                      <input type="text" class="form-control me-2" id="chatInput" placeholder="Write a message"/>
                      <label class="btn btn-light me-2" for="chatFile">
                        <i class="bi bi-paperclip"></i>
                      </label>
                      <input type="file" id="chatFile" style="display: none;" onchange="handleFileAttach(event)">
                      <button class="btn btn-primary" onclick="sendMessage()">Send</button>
                    </div>
                    <!--end::Chat Footer -->
                  </div>
                  <!--end::Chat Area-->
                </div>

                </div>
                <!--end::Content container-->
              </div>
              <!--end::Content wrapper-->
            </div>
            <!--end::Content-->
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

		<!--begin::Javascript-->
		<script>var hostUrl = "<?php echo ASSETS; ?>";</script>
		<!--begin::Global Javascript Bundle(mandatory for all pages)-->
		<script src="<?php echo ASSETS; ?>plugins/global/plugins.bundle.js"></script>
		<script src="<?php echo ASSETS; ?>js/scripts.bundle.js"></script>
		<!--end::Global Javascript Bundle-->
		<!--end::Javascript-->
	</body>
	<!--end::Body-->

  <!--begin::Chat Module-->
  <?php include('../includes/chats_v1.php'); ?>
  <!--end::Chat Module-->
</html>