The development of the system took two nights and was created by a single person. 
The database design was based on the design of another project (when I find the project, I will put its reference here). All the names of the tables and attributes of the database are in Spanish.
The system consists of two CRUD operations that allow creating a user, displaying their name, profile picture, and status; it allows updating the account status (online, offline) and deleting the user’s account. It also allows sending a message, displaying it, updating its status (read, unread), and deleting it. 
Each message is associated with two user accounts (sender and receiver), and only the sender can delete their message. 
The system also has several validations to prevent receivers from deleting messages, or users from accessing messages that do not belong to their accounts. 
The system does not allow updating the user’s information (email, password, profile picture, username) and every time an account is deleted, the messages associated with it are also deleted (both receiver and sender).
