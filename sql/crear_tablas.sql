create database if not exists chat_local_v1;
use chat_local_v1;

create table if not exists usuario (
usuario_ID int(11) not null auto_increment,
nombre_usuario varchar(255) not null,
correo_usuario varchar(255) not null,
contrasena varchar(255) not null,
avatar longblob not null,
sesion_actual int(11) not null,
enlinea int(11) not null,
primary key (usuario_ID)
);

create table if not exists mensaje (
mensaje_ID int(11) not null auto_increment,
mensaje text not null,
sender_usuario_ID int(11) not null,
reciever_usuario_ID int(11) not null,
tiempo timestamp not null default current_timestamp,
status int(1) not null,
primary key (mensaje_ID),
foreign key (sender_usuario_ID) references usuario (usuario_ID),
foreign key (reciever_usuario_ID) references usuario (usuario_ID)
);