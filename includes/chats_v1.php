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

<!--begin::Javascript-->
  <script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/11.7.1/firebase-app.js";
    import { getDatabase, ref, query, push, limitToLast, onChildAdded, onValue, orderByChild, equalTo, startAt, endAt } from "https://www.gstatic.com/firebasejs/11.7.1/firebase-database.js";

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
      apiKey: "AIzaSyCPHi8_VXowSdP1K_yNwAo8swK5zJ1tT4s",
      authDomain: "billing-crm-f3045.firebaseapp.com",
      databaseURL: "https://billing-crm-f3045-default-rtdb.asia-southeast1.firebasedatabase.app",
      projectId: "billing-crm-f3045",
      storageBucket: "billing-crm-f3045.firebasestorage.app",
      messagingSenderId: "232964342132",
      appId: "1:232964342132:web:bd588224afebcf3b756c21",
      measurementId: "G-FWQ4BHTB5C"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const db = getDatabase(app);

    window.db = db;
    window.ref = ref;
    window.query = query;
    window.push = push;
    window.limitToLast = limitToLast;
    window.onChildAdded = onChildAdded;
    window.onValue = onValue;
    window.orderByChild = orderByChild;
    window.equalTo = equalTo;
    window.startAt = startAt;
    window.endAt = endAt;
  </script>

  <script>
    toggleUserList();
    
    const loggedInUserId = "<?php echo $_SESSION['sess_user_id']; ?>";
    let receiverId = null;
    let senderId = null;
    let chatId = null;
    let lastMessageId = null;
    const isAdmin = <?php echo ($_SESSION['sess_user_rights'] === 'admin' || $_SESSION['sess_user_rights'] === 'sub_admin') ? 'true' : 'false'; ?>;
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
          work: "listChats"
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
          work: "searchUsers",
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

      receiverId = e.dataset.receiverId;
      senderId = e.dataset.senderId;
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

          // if (chatId) {
          //   $('.chat-actions').html(`
          //     <button class="btn btn-sm btn-icon btn-light" onclick="deleteChat()"><i class="bi bi-trash me-2"></i></button>
          //     <button class="btn btn-sm btn-icon btn-light" onclick="closeChat()"><i class="bi bi-x-lg"></i></button>
          //   `);
          // } else {
          //   $('.chat-actions').html(`
          //     <button class="btn btn-sm btn-icon btn-light" onclick="closeChat()"><i class="bi bi-x-lg"></i></button>
          //   `);
          // }

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

    function handleFileAttach(event) {
      const file = event.target.files[0];

      sendMessage(file);
    }

    document.getElementById('chatInput').addEventListener('keydown', function(event) {
      if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();

        sendMessage();
      }
    });

    function sendMessage(file = null) {
      const formData = new FormData();
      formData.append('work', 'sendMessage');
      formData.append('receiver_id', receiverId);
      formData.append('sender_id', senderId);
      formData.append('chat_id', chatId);

      const input = document.getElementById('chatInput');
      const msg = input.value.trim();

      if (!msg && file == null) return;

      if (msg) {
        formData.append('message', msg);

        input.value = '';
      }

      const messageRef = ref(db, 'messages/');

      if (file != null) {
        formData.append('file', file);

        const filesInput = document.getElementById('chatFile');
        filesInput.value = '';
      }
      
      $.ajax({
        type: "POST",
        url: "ajax.php",
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          const parsed = typeof response === 'string' ? JSON.parse(response) : response;
          
          const data = parsed.data;

          chatId = data.chat_id;

          lastMessageId = data.message_id;

          $('#chatMessages').append(data.html);

          smoothScroll();

          if (!isAdmin && loggedInUserId == senderId) {
            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: receiverId,
              message: data.message,
              timestamp: Date.now()
            });
          } else if (!isAdmin && loggedInUserId == receiverId) {
            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: senderId,
              message: data.message,
              timestamp: Date.now()
            });
          } else if (isAdmin && loggedInUserId == senderId) {
            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: receiverId,
              message: data.message,
              timestamp: Date.now()
            });
          } else if (isAdmin && loggedInUserId == receiverId) {
            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: senderId,
              message: data.message,
              timestamp: Date.now()
            });
          } else {
            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: senderId,
              message: data.message,
              timestamp: Date.now()
            });

            push(messageRef, {
              chat_id: data.chat_id,
              message_id: data.message_id,
              sender: "<?php echo $_SESSION['sess_user_id']; ?>",
              receiver: receiverId,
              message: data.message,
              timestamp: Date.now()
            });
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);

          alert("An error occurred during search.");
        }
      });
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
      const messagesQuery = isAdmin
        ? query(
            ref(db, 'messages'),
            orderByChild('timestamp')
          )
        : query(
            ref(db, 'messages'),
            orderByChild('receiver'),
            equalTo(loggedInUserId)
          );

      onValue(messagesQuery, (snapshot) => {
        const messages = snapshot.val() || {};
      
        const currentTime = Date.now();

        Object.values(messages).forEach(msg => {
          if (!msg.chat_id || !msg.timestamp) return;
          
          const chatId = msg.chat_id.toString();
          
          if ((isAdmin && msg.sender === loggedInUserId) || (!isAdmin && msg.receiver !== loggedInUserId)) return;

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
          
          if ((isAdmin && msg.sender === loggedInUserId) || (!isAdmin && msg.receiver !== loggedInUserId)) return;

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

    function deleteChat() {
      if (confirm("Are you sure you want to delete this chat?")) {
        $.ajax({
          type: "POST",
          url: "ajax.php",
          data: {
            work: "deleteChat",
            sender_id: senderId,
            receiver_id: receiverId,
            chat_id: chatId
          },
          success: function(response) {
            const parsed = typeof response === 'string' ? JSON.parse(response) : response;

            const data = parsed.data;

            if (parsed.success) {
              $('#chatMessages').html(data.html);

              lastMessageId = data.message_id;

              const messageRef = ref(db, 'messages/');

              push(messageRef, {
                chat_id: data.chat_id,
                message_id: data.message_id,
                sender: loggedInUserId,
                receiver: receiverId,
                message: data.message,
                is_chat_deleted: true,
                timestamp: Date.now()
              });
            } else {
              alert("An error occurred while deleting the chat.");
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", error);

            alert("An error occurred during delete chat.");
          }
        });
      }
    }
  </script>
<!--end::Javascript-->