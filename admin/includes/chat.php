<!-- Floating Chat Button -->
<div id="chatLauncher" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050;">
  <button class="btn btn-primary rounded-circle" style="width: 60px; height: 60px;" onclick="toggleUserList()"><span class="newMessagesCount"></span>💬</button>
</div>

<!-- User List Popup -->
<div id="userListPopup" class="card shadow" style="display: none; position: fixed; bottom: 90px; right: 20px; width: 250px; z-index: 1050;">
  <div class="card-header chat-card-header p-2 d-flex justify-content-between align-items-center">
    <span class="chat-header-title">Chats</span>
    <button type="button" class="btn-close" onclick="closeChat()" aria-label="Close"></button>
  </div>

  <!-- Search Bar with icon -->
  <div class="chat-search-wrapper p-2 pt-1 pb-1">
    <span class="search-icon"><i class="bi bi-search"></i></span>
    <input type="text" id="chatSearch" class="form-control chat-search-input" placeholder="Search..." onkeyup="searchChat()">
  </div>

  <ul class="list-group list-group-flush" id="chatUsers"></ul>
</div>

<!-- Chat Window -->
<div id="chatWindow" class="card shadow" style="position: fixed; bottom: 90px; right: 120px; width: 300px; display: none; z-index: 1060;">
  <div class="card-header p-2 d-flex justify-content-between align-items-center">
    <span id="chatWithName" class="chat-header-title">Chat</span>
    <div class="chat-header-controls">
      <button type="button" class="btn-close" title="Close Chat" onclick="closeChat()"></button>
    </div>
  </div>
  <div class="card-body p-2" id="chatMessages" style="height: 250px; overflow-y: auto;"></div>
  <div class="card-footer p-2">
    <div class="input-group align-items-center">
      <!-- Attachment Button -->
      <label class="btn btn-outline-secondary mb-0 px-2" for="chatFile">
        📎
      </label>
      <input type="file" id="chatFile" style="display: none;" onchange="handleFileAttach(event)">

      <!-- Message Input -->
      <input type="text" id="chatInput" class="form-control" placeholder="Type a message">

      <!-- Send Button -->
      <button class="btn btn-primary" onclick="sendMessage()">Send</button>
    </div>
  </div>
</div>

<!--begin::Chat Module-->
  <?php include(dirname(__DIR__, 2) . '/includes/chats_v2.php'); ?>
<!--end::Chat Module-->