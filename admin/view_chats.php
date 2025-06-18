<?php include(dirname(dirname(__FILE__)) . '/class/class.php'); 
  include('includes/general-settings.php');
?>
<!DOCTYPE html>
<html lang="en">
	<!--begin::Head-->
	<head>
		<title>View Chats <?php echo SITE_NAME; ?></title>
		<meta charset="utf-8" />
		<meta name="description" content="View Chats <?php echo SITE_NAME; ?>" />
		<meta name="keywords" content="View Chats <?php echo SITE_NAME; ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="View Chats <?php echo SITE_NAME; ?>" />
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

    <!--begin::Custom Stylesheet-->
    <style>
      .messenger-wrapper {
        height: 600px;
        max-height: 80vh;
        border-radius: 12px;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
      }

      .chat-sidebar {
        width: 300px;
        min-width: 300px;
        background-color: #f9fafb;
        border-right: 1px solid #e5eaf0;
        overflow-y: auto;
      }

      .search-input {
        font-size: 14px;
        padding: 6px 12px;
      }

      .chat-user {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        position: relative;
      }

      .chat-user:hover,
      .chat-user.active {
        background-color: #e6f0ff;
      }

      .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
      }

      .chat-info {
        flex-grow: 1;
        margin-left: 10px;
      }

      .chat-info span {
        display: block;
      }

      .chat-name {
        font-weight: 600;
        font-size: 14px;
      }

      .chat-preview {
        font-size: 12px;
        color: #6c757d;
      }

      .chat-time {
        font-size: 12px;
        color: #6c757d;
      }

      .chat-area {
        flex-grow: 1;
        display: none !important;
        flex-direction: column;
        background-color: #f4f7fb;
      }

      .chat-body {
        overflow-y: auto;
      }

      .message {
        max-width: 70%;
        padding: 10px 14px;
        margin-bottom: 10px;
        border-radius: 12px;
        font-size: 14px;
        display: inline-block;
        position: relative;
        word-break: break-word;
      }

      .message.sent {
        align-self: flex-end;
        background-color: #3f78e0;
        color: white;
        border-bottom-right-radius: 0;
        text-align: right;
      }

      .message.received {
        align-self: flex-start;
        background-color: #ffffff;
        border-bottom-left-radius: 0;
        color: #333;
        text-align: left;
      }

      .message.deleted {
        align-self: center;
        background-color: red;
        border-bottom-left-radius: 0;
        color: #333;
        text-align: center;
      }

      .message .time {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.7;
        text-align: right;
      }

      .chat-footer input {
        flex-grow: 1;
      }

      .message .text {
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
      }

      .message a {
        color: inherit;
        text-decoration: underline;
        word-break: break-all;
      }

      .chat-body {
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        padding: 1rem;
      }

      .message .text i.bi-paperclip {
        margin-right: 6px;
      }

      .chat-user.new-message {
        display: flex;
        align-items: center;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease;
        position: relative;
        background-color: #d4edda !important;
      }
    </style>
    <!--end::Custom Stylesheet-->
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

  <!--begin::Javascript-->
  <script>
    toggleUserList();
    
    const loggedInUserId = "<?php echo $_SESSION['sess_user_id']; ?>";
    let chatId = null;
    let lastMessageId = null;
    const lastCheckedTimestamps = {};
    const chatsWithNewMessages = new Set();
    let isInitialLoad = true;
    let initialLoadComplete = false;
    
    function toggleUserList() {
      const searchTerm = document.getElementById('chatSearch').value.toLowerCase();

      if (!searchTerm) {
        listChats();
      } else {
        searchChat();
      }
    }

    function listChats() {
      $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
          work: "listAdminChats"
        },
        success: function(response) {
          $('#chatUsers').html(response);
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);

          alert("An error occurred during fetch chats.");
        }
      });
    }

    function searchChat() {
      $('#chatMessages').html('');

      const searchTerm = document.getElementById('chatSearch').value.toLowerCase();
      
      $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
          work: "searchAdminUsers",
          search: searchTerm
        },
        success: function(response) {
          $('#chatUsers').html(response);
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);

          alert("An error occurred during search.");
        }
      });
    }
    
    function smoothScroll() {
      const chatBox = document.getElementById('chatMessages');

      setTimeout(() => {
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
      }, 50);
    }

    function openChat(e) {
      emptyChat();

      chatId = e.dataset.chatId;

      $('.chat-user').removeClass('active');
      $(e).addClass('active');

      $('#chatWithName').text(e.dataset.userName);
      $('#chatWithImage').attr('src', e.querySelector('img.avatar').src);

      document.querySelector('.chat-area').style.setProperty('display', 'flex', 'important');

      $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
          work: "singleChat",
          chat_id: chatId
        },
        success: function(response) {
          const parsed = typeof response === 'string' ? JSON.parse(response) : response;

          lastMessageId = parsed.last_message_id;

          $('#chatMessages').html(parsed.html);

          smoothScroll();
        },
        error: function(xhr, status, error) {
          console.error("AJAX ERROR:", error);

          alert("An error occurred during fetch chat messages.");
        }
      });

      $.ajax({
        type: "GET",
        url: "ajax.php",
        data: {
          work: "markedAsRead",
          chat_id: chatId
        },
        success: function(response) {
          const parsed = typeof response == 'string' ? JSON.parse(response) : response;

          $(`.chat-user[data-chat-id="${chatId}"]`).removeClass('new-message');
        },
        error: function(xhr, status, error) {
          console.error("AJAX ERROR:", error);
        }
      });
    }
    
    function emptyChat() {
      resetChatCount(chatId);

      receiverId = null;
      senderId = null;
      chatId = null;
      lastMessageId = null;

      $('.chat-user').removeClass('active');

      $('#chatWithName').text('');
      $('#chatWithImage').attr('src', '');

      $('#chatMessages').html('');
    }

    function closeChat() {
      emptyChat();

      document.querySelector('.chat-area').style.setProperty('display', 'none', 'important');
    }

    function updateMessageCountsDisplay() {
      const totalChatsWithMessages = chatsWithNewMessages.size;

      $('.newMessagesCount').text(totalChatsWithMessages > 0 ? totalChatsWithMessages : '');
    }

    function resetChatCount(chatId) {
      chatsWithNewMessages.delete(toString.toString());

      const now = Date.now();
      
      Object.keys(lastCheckedTimestamps).forEach(chatId => {
        lastCheckedTimestamps[chatId] = now;
      });

      updateMessageCountsDisplay();
    }

    function initChatTracking() {
      const messagesQuery = query(
        ref(db, 'messages'),
        orderByChild('timestamp')
      );

      onValue(messagesQuery, (snapshot) => {
        const messages = snapshot.val() || {};
      
        const currentTime = Date.now();

        Object.values(messages).forEach(msg => {
          if (!msg.chat_id || !msg.timestamp) return;
          
          const chatId = msg.chat_id.toString();
          
          if (msg.sender == loggedInUserId || msg.receiver == loggedInUserId) return;

          if (!lastCheckedTimestamps[chatId] || msg.timestamp > lastCheckedTimestamps[chatId]) {
            lastCheckedTimestamps[chatId] = currentTime;
          }
        });

        initialLoadComplete = true;

        isInitialLoad = false;
      }, { onlyOnce: true });

      onValue(messagesQuery, (snapshot) => {
        if (!initialLoadComplete) return;
        
        const messages = snapshot.val() || {};

        const newMessageChats = new Set();

        Object.values(messages).forEach(msg => {
          if (!msg.chat_id || !msg.timestamp) return;
          
          const chatId = msg.chat_id.toString();
          
          if (msg.sender == loggedInUserId || msg.receiver == loggedInUserId) return;

          if (msg.timestamp > (lastCheckedTimestamps[chatId] || 0)) {
            newMessageChats.add(chatId);

            lastCheckedTimestamps[chatId] = msg.timestamp;
          }
        });
        
        newMessageChats.forEach(chatId => chatsWithNewMessages.add(chatId));
        
        updateMessageCountsDisplay();
      });
    }
    
    document.addEventListener("DOMContentLoaded", function () {
      initChatTracking();

      const messagesQuery = query(
        ref(db, 'messages/'),
        limitToLast(1)
      );

      onChildAdded(messagesQuery, (data) => {
        const msg = data.val();

        if (msg.message_id == lastMessageId) return;

        if (msg.is_chat_deleted) { 
          $('#chatMessages').html(`
            <div class="message deleted">
              <div class="text">${msg.message}</div>
            </div>
          `);
        } else {
          $.ajax({
            type: "GET",
            url: "ajax.php",
            data: {
              work: "lastChatMessage",
              chat_id: chatId
            },
            success: function(response) {
              const parsed = typeof response === 'string' ? JSON.parse(response) : response;

              $('#chatMessages').append(parsed.html);

              if (parsed.html != null) {
                $(`.chat-user[data-chat-id="${msg.chat_id}"]`).addClass('new-message');
              }

              smoothScroll();
            },
            error: function(xhr, status, error) {
              console.error("AJAX ERROR:", error);

              alert("An error occurred during fetch chat message.");
            }
          });
        }
      });
    });
  </script>
<!--end::Javascript-->
</html>