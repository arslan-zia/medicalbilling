CREATE TABLE chats( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL, 
    receiver_id INT NOT NULL, 
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_users (sender_id , receiver_id),
    FOREIGN KEY (sender_id) REFERENCES inv_qne_users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES inv_qne_users(user_id) ON DELETE CASCADE
);

CREATE TABLE chat_messages ( 
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_id) REFERENCES chats(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES inv_qne_users(user_id) ON DELETE CASCADE
);

ALTER TABLE chats ADD COLUMN last_message TEXT DEFAULT NULL;